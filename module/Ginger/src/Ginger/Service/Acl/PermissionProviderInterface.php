<?php
namespace Ginger\Service\Acl;
/**
 * Description of PermissionProviderInterface
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
interface PermissionProviderInterface 
{
    /**
     * Provide job based permissions for every user (role)
     * 
     * <code>
     *  //sample structure
     *  return array(
     *      'user_id' => array(
     *          'is_admin' => false,
     *          'permissions' => array(
     *              'jobname' => array('execute', 'write')
     *          )
     *      ) 
     *  )
     * </code>
     * 
     * @return array
     */
    public function getPermissions();
}
