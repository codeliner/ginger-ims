<?php
namespace Ginger\Service\Logger;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * Description of OrmLoaderFactory
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class OrmLoggerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $logger = new OrmLogger();
        $logger->setEntityManager($serviceLocator->get('entitymanager'));

        return $logger;
    }
}