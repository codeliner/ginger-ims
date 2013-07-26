<?php
namespace Ginger\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\AbstractQuery;
use Ginger\Entity\User;
use Ginger\Model\User\UserLoaderInterface;
use Ginger\Model\User\Exception\UnexpectedValueException;
/**
 * Description of UserRepository
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{
    /**
     * @inherit doc
     * @see     UserLoaderInterface
     */
    public function getUsers()
    {
        $users = $this->findAll();
        
        $usersData = array();
        
        foreach ($users as $user) {
            $usersData[] = $this->extractUserData($user);
        }
        
        return $usersData;
    }

    /**
     * @inherit doc
     * @see     UserLoaderInterface
     */
    public function hasUsers()
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('count(u.id)')
            ->from('Ginger\Entity\User', 'u')
            ->getQuery();
        return (bool)$query->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }

    /**
     * @inherit doc
     * @see     UserLoaderInterface
     */
    public function loadUser($id)
    {
        $user = $this->find($id);
        
        if ($user) {
            return $this->extractUserData($user);
        }
        
        return null;
    }

    /**
     * @inherit doc
     * @see     UserLoaderInterface
     */
    public function loadUserByApiKey($apiKey)
    {
        $user = $this->findOneBy(array('apiKey' => $apiKey));
        
        if ($user) {
            return $this->extractUserData($user);
        }
        
        return null;
    }

    /**
     * @inherit doc
     * @see     UserLoaderInterface
     */
    public function saveUser(array $data)
    {        
        if (isset($data['id'])) {
            $user = $this->find($data['id']);
            
            if ($user) {
                $this->updateUser($user, $data);
            } else {
                throw new UnexpectedValueException(
                    sprintf(
                        'User with id: "%d" can not be updated. User was not found.',
                        $data['id']
                        )
                    );
            }
        } else {
            $user = new User();
            $this->updateUser($user, $data);
            $this->getEntityManager()->persist($user);
        }
        
        $this->getEntityManager()->flush($user);
        
        return $user->getId();
    }    
    
    /**
     * @inherit doc
     * @see     UserLoaderInterface
     */
    public function deleteUser($id)
    {
        $user = $this->find($id);
        
        $this->getEntityManager()->remove($user);
        
        $this->getEntityManager()->flush();
    }
    
    private function extractUserData(User $user)
    {
        return array(
            'id' => $user->getId(),
            'apiKey' => $user->getApiKey(),
            'secretKey' => $user->getSecretKey(),
            'lastname' => $user->getLastname(),
            'firstname' => $user->getFirstname(),
            'email' => $user->getEmail(),
            'isAdmin' => $user->getIsAdmin()
        );
    }
    
    private function updateUser(User $user, $newData)
    {
        $user->setApiKey($newData['apiKey']);
        $user->setSecretKey($newData['secretKey']);
        $user->setLastname($newData['lastname']);
        $user->setFirstname($newData['firstname']);
        $user->setEmail($newData['email']);
        $user->setIsAdmin($newData['isAdmin']);
    }
}
