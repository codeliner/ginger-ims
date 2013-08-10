<?php
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));

if (APPLICATION_ENV === 'development') {
    ini_set('display_errors', true);
}

define('TEST_PATH', realpath(__DIR__));

//go back from tests/PHPUnit
chdir(realpath(TEST_PATH . '/../../'));

include 'vendor/ZF2/library/Zend/Loader/AutoloaderFactory.php';

Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'autoregister_zf' => true,
        'fallback_autoloader' => true,
        'namespaces' => array(
            'Cl' => TEST_PATH . '/../../vendor/Cl_Library/Cl',
            'Mock' => TEST_PATH . '/Mock',
        )
    )
));