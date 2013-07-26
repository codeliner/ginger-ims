<?php
namespace MockObject;

use Ginger\Service\Acl\PermissionProviderInterface;
/**
 * Description of PermissionLoaderMock
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class PermissionProviderMock implements PermissionProviderInterface
{
    protected $permissions = array();
    
    public function setPermissions($perms) 
    {
        $this->permissions = $perms;
    }
    
    public function getPermissions()
    {
        return $this->permissions;
    }    
}
