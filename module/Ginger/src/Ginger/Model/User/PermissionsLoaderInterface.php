<?php
namespace Ginger\Model\User;
/**
 * Description of PermissionsLoaderInterface
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
interface PermissionsLoaderInterface
{
    /**
     * Get the permissions of an user
     * 
     * @param int $userId
     * 
     * @return array List of jobname => priviliges pairs
     */
    public function loadPermissions($userId);
    
    /**
     * Add or update permissions for an user and job combination
     * 
     * @param int $userId
     * @param string $jobname
     * @param bool $read
     * @param bool $write
     * @param bool $execute
     */
    public function savePermissions(
        $userId, 
        $jobname, 
        $read = false, 
        $write = false, 
        $execute = false);
    
    /**
     * Delete all permissions related to the given user id
     * 
     * @param int $userId
     */
    public function deletePermissions($userId);
}
