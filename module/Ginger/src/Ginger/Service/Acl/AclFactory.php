<?php
namespace Ginger\Service\Acl;

use Zend\Permissions\Acl;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Ginger\Job\JobLoaderInterface;
use Ginger\Model\User\User;

class AclFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        /* @var $permissionProvider PermissionProviderInterface */
        $permissionProvider = $serviceLocator->get('acl_permission_provider');
        
        /* @var $jobLoader JobLoaderInterface */
        $jobLoader = $serviceLocator->get('job_loader');
        
        $acl = new Acl\Acl();
        
        //Create a resource for each job
        foreach ($jobLoader->getJobNames() as $jobname) {
            $resource = new Acl\Resource\GenericResource($jobname);
            
            $acl->addResource($resource);
        }
        
        $adminRole = new Acl\Role\GenericRole('administrator');
        
        $acl->addRole($adminRole);
        
        //Assign default rules:
        //Admins have full access to all resources
        //Users have no access by default
        $acl->allow($adminRole);
                
        foreach ($permissionProvider->getPermissions() as $userId => $config) {
            
            $userRole = new Acl\Role\GenericRole(User::ACL_ROLE_PREFIX . $userId);
            
            if ($config['is_admin']) {
                $acl->addRole($userRole, $adminRole);
                continue;
            } else {
                $acl->addRole($userRole);
                
                foreach ($config['permissions'] as $jobname => $privileges) {
                    $acl->allow($userRole, $jobname, $privileges);
                }
            }
        }
        
        return $acl;
    }    
}