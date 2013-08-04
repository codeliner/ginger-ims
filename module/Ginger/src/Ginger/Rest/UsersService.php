<?php
namespace Ginger\Rest;

use Cl\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Ginger\Model\User\UserLoaderInterface;
/**
 * Description of UsersService
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class UsersService extends AbstractRestfulController
{
    /**
     *
     * @var UserLoaderInterface
     */
    protected $userLoader;
    
    /**
     * 
     * @param \Ginger\Model\User\UserLoaderInterface $userLoader
     */
    public function setUserLoader(UserLoaderInterface $userLoader)
    {
        $this->userLoader = $userLoader;
    }

        
    public function create($data)
    {
        $id = $this->userLoader->saveUser($data);
        
        $data['id'] = $id;
        
        return new JsonModel($this->cleanPublicData($data));
    }

    public function delete($id)
    {
        $this->userLoader->deleteUser($id);
        
        return new JsonModel(array('success' => true));
    }

    public function get($id)
    {
        $data = $this->userLoader->loadUser($id);
        
        if ($data) {
            return new JsonModel($this->cleanPublicData($data));
        } else {
            return $this->getResponse()->setStatusCode(404)->setContent('User not found');
        }
    }

    public function getList()
    {
        $users = $this->userLoader->getUsers();
        
        foreach ($users as $i => $data) {
            $users[$i] = $this->cleanPublicData($data);
        }
        
        return new JsonModel($users);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        
        $this->userLoader->saveUser($data);
        
        return new JsonModel($this->cleanPublicData($data));
    }
    
    protected function cleanPublicData(array $data)
    {
        unset($data['apiKey']);
        unset($data['secretKey']);
        
        return $data;
    }
}
