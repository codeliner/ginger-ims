<?php
namespace Ginger\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\AbstractQuery;
use Ginger\Model\User\PermissionsLoaderInterface;
use Ginger\Model\User\Exception;
use Ginger\Entity\Permission;

/**
 * Description of PermissionRepository
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class PermissionRepository extends EntityRepository implements PermissionsLoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function loadPermissions($userId)
    {
        $permissions = $this->loadPermissionsEntities($userId);
        
        $permissionsData = array();
        
        foreach ($permissions as $permission) {
            $permissionsData[$permission->getJob()->getName()] 
                = $this->extractPermissionData($permission);
        }
        
        return $permissionsData;
    }

    /**
     * {@inheritdoc}
     */
    public function savePermissions($userId, $jobname, $read = false, $write = false, $execute = false)
    {
        $user = $this->getEntityManager()->getRepository('Ginger\Entity\User')
            ->find($userId);
        
        if (!$user) {
            throw new Exception\UnexpectedValueException(
                sprintf(
                    'User with id: "%s" can not be found.',
                    $userId
                    )
                );
        }
        
        $job = $this->getEntityManager()->getRepository('Ginger\Entity\Job')
            ->findOneByName($jobname);
        
        if (!$job) {
            throw new Exception\UnexpectedValueException(
                sprintf(
                    'Job with name: "%s" can not be found.',
                    $jobname
                    )
                );
        }
        
        $permission = new Permission($user, $job);
        $permission->setRead($read);
        $permission->setWrite($write);
        $permission->setExecute($execute);
        
        $this->getEntityManager()->persist($permission);
        $this->getEntityManager()->flush($permission);
    }    
    
    /**
     * {@inheritdoc}
     */
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

    /**
     * 
     * @param int $userId
     * @return Permission[]
     */
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
    
    /**
     * 
     * @param \Ginger\Entity\Permission $permission
     * @return array
     */
    private function extractPermissionData(Permission $permission)
    {
        return array(
                'read' => $permission->getRead(),
                'write' => $permission->getWrite(),
                'execute' => $permission->getExecute()
            );
    }
}
