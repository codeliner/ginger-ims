<?php
namespace Ginger\Service\Installer;

use Cl\Filesystem\DirectoryManager;
use Cl\Test\PHPUnitTestCase;
use MockObject\ServiceLocatorMock;
/**
 * Test class for ModuleInstaller.
 * Generated by PHPUnit on 2013-04-08 at 22:49:43.
 */
class ModuleInstallerTest extends PHPUnitTestCase
{

    /**
     * @var ModuleInstaller
     */
    protected $object;

    protected $sourceLoader;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new ModuleInstaller;
        $this->sourceLoader = new \MockObject\SourceLoader();
        $registry = new ServiceLocatorMock();
        $registry->register('source_loader', $this->sourceLoader);
        $this->object->setServiceLocator($registry);
        $this->object->setModuleManager(self::getApplication()->getServiceManager()->get('ModuleManager'));

        copy('config/application.config.php', 'config/application.config.php.bak');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unlink('config/application.config.php');
        copy('config/application.config.php.bak', 'config/application.config.php');
        unlink('config/application.config.php.bak');
    }

    /**
     * @covers Ginger\Service\Installer\ModuleInstaller::install
     */
    public function testInstall()
    {
        DirectoryManager::copyDir('tests/PHPUnit/testdata/module/Test', 'install/Test');

        $this->object->install();

        $config = include 'config/application.config.php';

        $this->assertTrue(in_array('Test', $config['modules']));

        $instSourceData = $this->sourceLoader->getSources()[0];

        //Test module registers a Source with name 'installsource' from a data provider class.
        //The data provider should be autoloaded during installation proccess, to verify that the
        //new module has full access to the environment during installation.
        $this->assertEquals('installsource', $instSourceData['name']);

        DirectoryManager::recursiveRemoveDir('install/Test');
        DirectoryManager::recursiveRemoveDir('vendor/Test');
    }

    public function testInstallClass()
    {
        copy('tests/PHPUnit/testdata/install/SourceProvider.php', 'install/SourceProvider.php');

        $this->object->install();

        $this->assertTrue($this->object->getServiceLocator()->has('test_source_stub'));
    }
}

?>
