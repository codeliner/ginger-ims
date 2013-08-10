<?php
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));

if (APPLICATION_ENV === 'development') {
    ini_set('display_errors', true);
}

define('TEST_PATH', realpath(__DIR__));

chdir(realpath(TEST_PATH . '/../../'));

include 'init_autoloader.php';

if (isset($loader)) {
    $loader->add('MockObject', TEST_PATH);
    $loader->add('TestHelper', TEST_PATH);
}

//Regiser zf2 autoloader as fallback to autoload PHPUnit_* classes from include path
$zf2Autoloader = new Zend\Loader\StandardAutoloader();
$zf2Autoloader->setFallbackAutoloader(true);
$zf2Autoloader->register();

include 'module/Cl/Test/PHPUnitTestCase.php';

Cl\Test\PHPUnitTestCase::setApplication(
    Zend\Mvc\Application::init(include 'config/application.config.php')
    );