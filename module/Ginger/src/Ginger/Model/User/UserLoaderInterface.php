<?php
namespace Ginger\Model\User;
/**
 * Description of UserLoaderInterface
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
interface UserLoaderInterface
{
    /**
     * Return userdata as array
     * 
     * @param int $id
     * @return array|null The userdata
     */
    public function loadUser($id);
    
    /**
     * Return userdata as array
     * 
     * @param string $apiKey
     * @return array The userdata
     */
    public function loadUserByApiKey($apiKey);
    
    /**
     * Return a list of userdata arrays
     * 
     * @return array
     */
    public function getUsers();
    
    /**
     * If data contains the userId, then the data should be updated for the user
     * otherwise a new user should be created
     * 
     * @param  array $data
     * @return int|null The userId  
     */
    public function saveUser(array $data);
    
    /**
     * Delete userdata for given userId
     * 
     * @param int $id
     * @return bool Success state
     */
    public function deleteUser($id);
    
    /**
     * Is at least one user registered in the system?
     * 
     * @return bool
     */
    public function hasUsers();
}
