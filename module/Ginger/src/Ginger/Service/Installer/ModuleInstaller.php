<?php
namespace Ginger\Service\Installer;

use Ginger\Service\Registry\GingerRegistry;
use Cl\Filesystem\DirectoryManager;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Config\Writer\PhpArray as PhpArrayWriter;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
/**
 * Description of ModuleInstaller
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ModuleInstaller implements ServiceLocatorAwareInterface
{
    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    protected $installDirPath = 'install';

    /**
     *
     * @var ModuleManagerInterface
     */
    protected $moduleManager;

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function setModuleManager($moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    public function getInstallDirPath()
    {
        return $this->installDirPath;
    }

    public function setInstallDirPath($installDirPath)
    {
        $this->installDirPath = $installDirPath;
    }

    public function install()
    {
        $objects = scandir($this->installDirPath);

        foreach($objects as $installObject) {
            if (strpos($installObject, ".") !== 0) {
                if (is_dir($this->installDirPath . '/' . $installObject)) {
                    try {
                        $this->moveModule($installObject);
                        $this->installModule($installObject);
                        $this->registerModule($installObject);
                    } catch (\Exception $e) {
                        throw new Exception\ModuleInstallationFailedException(
                            sprintf("Installation of the module '%s' failed.", $installObject),
                            $e->getCode(),
                            $e->getPrevious()
                            );
                    }
                } else if (is_file($this->installDirPath . '/' . $installObject)) {
                    require_once $this->installDirPath . '/' . $installObject;

                    $class = 'Ginger\Install\\' . pathinfo($this->installDirPath . '/' . $installObject, PATHINFO_FILENAME);

                    $installer = new $class();

                    if (!$installer instanceof InstallInterface) {
                        throw new Exception\ClassInstallationFailedException(
                        sprintf(
                            "Installation class '%s' must implement Ginger\Service\Installer\InstallInterface",
                            $class
                            )
                        );
                    }

                    if ($installer instanceof ServiceLocatorAwareInterface) {
                        $installer->setServiceLocator($this->getServiceLocator());
                    }

                    $installer->install();

                    unset($installer);

                    unlink($this->installDirPath . '/' . $installObject);
                }
            }
        }
    }

    protected function moveModule($moduleName)
    {
        $success = rename($this->installDirPath . '/' . $moduleName, 'vendor/' . $moduleName);

        if (!$success) {
            throw new Exception\CopyModuleFailedException(
                sprintf(
                    "Can not move module dir '%s' from '%s' to 'vendor folder'. Please check the permissions!",
                    $moduleName,
                    $this->installDirPath
                    )
                );
        }
    }

    protected function installModule($moduleName)
    {
        $this->moduleManager->loadModule($moduleName);

        require_once 'vendor/' . $moduleName . '/Install.php';

        $instClass = $moduleName . '\Install';

        $installer = new $instClass();

        if ($installer instanceof ServiceLocatorAwareInterface) {
            $installer->setServiceLocator($this->getServiceLocator());
        }

        $installer->install();
    }

    protected function registerModule($moduleName)
    {
        $appConfig = include('config/application.config.php');

        $appConfig['modules'][] = $moduleName;

        $writer = new PhpArrayWriter();

        file_put_contents(
            'config/application.config.php',
            $writer->processConfig($appConfig)
        );
    }
}