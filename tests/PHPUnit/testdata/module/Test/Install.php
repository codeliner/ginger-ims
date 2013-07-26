<?php
namespace Test;

use Ginger\Service\Installer\InstallInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
/**
 * Description of Install
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Install implements InstallInterface, ServiceLocatorAwareInterface
{
    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $sl;

    public function install()
    {
        $sourceLoader = $this->getServiceLocator()->get('source_loader');

        //autoload Mock from src/Test namespace dir, to check that module is registered via Zend\ModuleManager
        $dataProvider = new DataProviderMock();

        $data = $dataProvider->provideTestData();

        $sourceLoader->registerSource(
            $data['module'],
            $data['name'],
            $data['class'],
            $data['link']
            );
    }

    public function getServiceLocator()
    {
        return $this->sl;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->sl = $serviceLocator;
    }
}