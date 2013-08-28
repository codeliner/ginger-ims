<?php
namespace GingerORM\Service\PermissionsLoader;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
/**
 * Description of OrmPermissionsLoaderFactory
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class OrmPermissionsLoaderFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('entitymanager')->getRepository('GingerORM\Entity\Permission');
    }    
}
