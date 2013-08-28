<?php
namespace GingerORM\Service\UserLoader;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
/**
 * Description of OrmUserLoaderFactory
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class OrmUserLoaderFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('entitymanager')->getRepository('GingerORM\Entity\User');
    }    
}
