<?php
namespace Ginger\Model\User;

use Ginger\Job\Job;
use Ginger\Service\Acl\PermissionProviderInterface;

/**
 * Description of UserManager
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class UserManager implements PermissionProviderInterface
{
    const DUMMY_API_KEY = 'dummy';
    
    /**
     *
     * @var UserLoaderInterface 
     */
    protected $userLoader;
    
    /**
     *
     * @var PermissionsLoaderInterface
     */
    protected $permissionsLoader;

    /**
     *
     * @var User
     */
    protected $activeUser;
    
    /**
     *
     * @var bool
     */
    protected $hasUsers;


    /**
     * If "dummy" is provided as apiKey in method @see setActiveUser, this
     * data is used to initialize an user object.
     * 
     * The dummy user is treated as an admin and can only be used, when no
     * users are registered in the system (@see hasUsers returns false)
     * 
     * @var array
     */
    protected $dummyData = array(
        'id' => -1,
        'apiKey' => 'dummy',
        'secretKey' => 'dummy',
        'lastname' => 'dummy',
        'firstname' => 'dummy',
        'email' => 'dummy',
        'isAdmin' => true
    );


    public function setUserLoader(UserLoaderInterface $userLoader)
    {
        $this->userLoader = $userLoader;
    }

    public function setPermissionsLoader(PermissionsLoaderInterface $permissionsLoader)
    {
        $this->permissionsLoader = $permissionsLoader;
    }
    
    /**
     * Is at least one user registered in the system?
     * 
     * @return bool
     */
    public function hasUsers()
    {
        if (is_null($this->hasUsers)) {
            $this->hasUsers = $this->userLoader->hasUsers();
        }
        
        return $this->hasUsers;
    }
        
    /**
     * Get a User by id
     * 
     * @param int $id
     * @return User
     * @throws Exception\InvalidArgumentException
     */
    public function getUser($id)
    {
        $userData = $this->userLoader->loadUser($id);
        
        if (!$userData) {
            throw new Exception\InvalidArgumentException(
                sprintf('User with id: "%d" can not be found.', $id)
                );
        }
        
        return $this->hydrateUserdata($id, $userData);
    }
    
    /**
     * The user identified by it's apiKey is internally set as active user
     * and can be requestd via {@method getActiveUser} 
     * 
     * @param string $apiKey
     * 
     * @throws Exception\InvalidArgumentException
     * @throws Exception\UnexpectedValueException
     */
    public function setActiveUser($apiKey)
    {
        $userdata = array();
        
        if (!$this->hasUsers() && $apiKey == static::DUMMY_API_KEY) {
            $userdata = $this->dummyData;
        } else {
            $userdata = $this->userLoader->loadUserByApiKey($apiKey);
        }
        
        
        if (!$userdata) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'An user with the provided api key: "%s" can not be found',
                    $apiKey
                    )
                );
        }
        
        if (!isset($userdata['id'])) {
            throw new Exception\UnexpectedValueException(
                sprintf(
                    'The UserLoader "%s" does not return valid user data. User-Id is missing.',
                    get_class($this->userLoader)
                    )
                );
        }
        
        $this->activeUser = $this->hydrateUserdata($userdata['id'], $userdata);
    }
    
    /**
     * Get the user, how is requesting a resource
     * 
     * @return User|null
     */
    public function getActiveUser()
    {
        return $this->activeUser;
    }
    
    /**
     * Persist the data of a user
     * 
     * You can pass the data as an array or an User object, that shoud be updated
     * 
     * @param User|array $dataOrUser
     * 
     * @return User New or updated user object
     */
    public function saveUser($dataOrUser)
    {
        $userData = array();
        
        if ($dataOrUser instanceof User) {
            $userData = array(
                'id' => $dataOrUser->getId(),
                'apiKey' => $dataOrUser->getApiKey(),
                'secretKey' => $dataOrUser->getSecretKey(),
                'lastname' => $dataOrUser->getLastname(),
                'firstname' => $dataOrUser->getFirstname(),
                'email' => $dataOrUser->getEmail(),
            );
        } else {
            $userData = $dataOrUser;
            unset($dataOrUser);
        }
        
        $id = $this->userLoader->saveUser($userData);
        
        if (is_null($id)) {
            throw new Exception\UnexpectedValueException(
                sprintf(
                    'Failed to save user data "lastname:%s", "firstname:%s". UserLoader returned no user id.',
                    $userData['lastname'],
                    $userData['firstname']
                    )
                );
        }
        
        return $this->hydrateUserdata($id, $userData);
    }
    
    /**
     * Delete an user and it's permissions
     * 
     * @param \Ginger\Model\User\User $user
     */
    public function deleteUser(User $user)
    {   
        $this->permissionsLoader->deletePermissions($user->getId());
        $this->userLoader->deleteUser($user->getId());
        unset($user);
    }
    
    /**
     * Save or replace permissions of a user for a job
     * 
     * @param \Ginger\Model\User\User $user
     * @param \Ginger\Job\Job $job
     * @param bool $read
     * @param bool $write
     * @param bool $execute
     * 
     * @return void
     */
    public function updateUserPermissions(User $user, Job $job, 
        $read = false, $write = false, $execute = false)
    {
        $this->permissionsLoader->savePermissions($user->getId(), $job->getName(), $read, $write, $execute);
    }
    
    /**
     * Get permissions of an user
     * 
     * If a job is provided, permissions of the user for this job are returned
     * 
     * @param \Ginger\Model\User\User $user
     * @param \Ginger\Job\Job $job
     * @return array|null
     */
    public function getUserPermissions(User $user, Job $job = null)
    {
        $jobsPermissions = $this->permissionsLoader->loadPermissions($user->getId());
        
        if (!is_null($job)) {
            if (isset($jobsPermissions[$job->getName()])) {
                return $jobsPermissions[$job->getName()];
            } else {
                return null;
            }
        }
        
        return $jobsPermissions;
    }
    
    /**
     * @inherit doc
     * @see \Ginger\Service\Acl\PermissionProviderInterface
     */
    public function getPermissions()
    {
        $users = $this->userLoader->getUsers();
        
        $permissions = array();
        
        foreach ($users as $userData) {
            
            $userPermissions = $this->permissionsLoader->loadPermissions($userData['id']);
            
            if (is_null($userPermissions)) {
                $userPermissions = array();
            }
            
            $permissions[$userData['id']] = array(
                'isAdmin' => $userData['isAdmin'],
                'permissions' => $userPermissions
            );
        }
        
        return $permissions;
    }
    
    /**
     * Create a new user object and populate data
     * 
     * @param int $id
     * @param array $data
     * 
     * @return User User object with populated data
     */
    protected function hydrateUserdata($id, array $data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }
        
        $user = new User($id);
        
        $user->exchangeArray($data);
        
        return $user;
    }
}
