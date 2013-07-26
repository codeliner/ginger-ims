<?php
namespace Ginger\Service\JobLoader;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Ginger\Model\Connector\Connector;
use Zend\EventManager\EventManager;
/**
 * Description of OrmLoaderFactory
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class OrmLoaderFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $repo = $serviceLocator->get('entitymanager')->getRepository('Ginger\Entity\Job');

        $connector = new Connector();
        $connector->setEventManager(new EventManager());

        $repo->setConnector($connector);
        $repo->setLogger($serviceLocator->get('jobrun_logger'));

        $repo->setSourceLoader($serviceLocator->get('source_loader'));
        $repo->setTargetLoader($serviceLocator->get('target_loader'));
        $repo->setMapperLoader($serviceLocator->get('mapper_loader'));
        $repo->setFeatureLoader($serviceLocator->get('feature_loader'));
        $repo->setTranslator($serviceLocator->get('translator'));

        return $repo;
    }
}