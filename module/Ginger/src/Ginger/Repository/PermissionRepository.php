<?php
namespace Ginger\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\AbstractQuery;
use Ginger\Model\User\PermissionsLoaderInterface;
use Ginger\Entity\Permission;

/**
 * Description of PermissionRepository
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class PermissionRepository extends EntityRepository implements PermissionsLoaderInterface
{
    public function loadPermissions($userId)
    {
        $permissions = $this->loadPermissionsEntities($userId);
        
        $permissionsData = array();
        
        foreach ($permissions as $permission) {
            $permissionsData[] = $this->extractPermissionData($permission);
        }
        
        return $permissionsData;
    }

    public function savePermissions($userId, $jobname, $read = false, $write = false, $execute = false)
    {
        //@todo implement this
    }    
    
    public function deletePermissions($userId)
    {
        $user = $this->getEntityManager()->getRepository('Ginger\Entity\User')->find($userId);
        
        if ($user) {
            $this->getEntityManager()
                ->createQuery('DELETE Ginger\Entity\Permission p WHERE p.user = :user')
                ->setParameter('user', $user)
                ->execute();
        }
        
    }

    private function loadPermissionsEntities($userId)
    {
        $user = $this->getEntityManager()->getRepository('Ginger\Entity\User')
            ->find($userId);
        
        if (!$user) {
            return array();
        }
        
        $query = $this->getEntityManager()->createQueryBuilder();
        
        $query->select('p')->from('Ginger\Entity\Permission', 'p')
            ->where('p.user = :user')
            ->setParameter('user', $user);
        
        return $query->getQuery()->getResult();
    }
    
    private function extractPermissionData(Permission $permission)
    {
        return array(
            $permission->getJob()->getName() => array(
                'read' => $permission->getRead(),
                'write' => $permission->getWrite(),
                'execute' => $permission->getExecute()
            )
        );
    }
}
