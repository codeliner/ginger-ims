<?php
namespace Ginger\Install;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Ginger\Service\Installer\InstallInterface;
use Ginger\Model\Source\SourceLoaderInterface;

/**
 * Description of SourceProvider
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class SourceProvider implements InstallInterface, ServiceLocatorAwareInterface
{
    /**
     *
     * @var \MockObject\ServiceLocatorMock
     */
    protected $serviceLocator;

    public function install()
    {
        /* @var $sourceLoader SourceLoaderInterface */
        $sourceLoader = $this->serviceLocator->get('source_loader');

        $source = $sourceLoader->getSource(1);

        $this->serviceLocator->register('test_source_stub', $source);
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getLoadedSource()
    {
        return $this->source;
    }
}