<?php
namespace MockObject;

use Ginger\Model\User\PermissionsLoaderInterface;

/**
 * Description of UserPermissionsLoaderMock
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class UserPermissionsLoaderMock implements PermissionsLoaderInterface
{
    protected $permissions = array();
    
    public function setPermissions(array $permissions)
    {
        $this->permissions = $permissions;
    }


    public function deletePermissions($userId)
    {
        if (isset($this->permissions[$userId])) {
            unset($this->permissions[$userId]);
        }
    }

    public function loadPermissions($userId)
    {
        if (isset($this->permissions[$userId])) {
            return $this->permissions[$userId];
        }
        
        return null;
    }

    public function savePermissions($userId, $jobname, $read = false, $write = false, $execute = false)
    {
        if (!isset($this->permissions[$userId])) {
            $this->permissions[$userId] = array();
        }
        
        $this->permissions[$userId][$jobname] = array(
            'read' => $read,
            'write' => $write,
            'execute' => $execute
        );
    }    
}
