<?php
namespace MockObject;

use Ginger\Model\User\UserLoaderInterface;

/**
 * Description of UserLoaderMock
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class UserLoaderMock implements UserLoaderInterface
{
    protected $usersData = array();
    
    public function setUsersData(array $usersData) 
    {
        $this->usersData = $usersData;
    }


    public function deleteUser($id)
    {
        if (isset($this->usersData[$id])) {
            unset($this->usersData[$id]);
        }
        
        return true;
    }

    public function getUsers()
    {
        return $this->usersData;
    }

    public function loadUser($id)
    {
        if (isset($this->usersData[$id])) {
            return $this->usersData[$id];
        }
        
        return null;
    }

    public function loadUserByApiKey($apiKey)
    {
        $filteredData = array_filter($this->usersData, function($entry) use ($apiKey) {
            return $entry['apiKey'] == $apiKey;
        });
        
        return array_shift($filteredData);
    }

    public function saveUser(array $data)
    {
        $id = null;
        
        if (isset($data['id'])) {
            $id = $data['id'];
        } else {
            $id = count($this->usersData);
        }
        
        $data['id'] = $id;
        
        $this->usersData[$id] = $data;
        
        return $id;
    }
    
    public function hasUsers()
    {
        return !empty($this->usersData);
    }
}
