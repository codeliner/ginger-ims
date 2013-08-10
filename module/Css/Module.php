<?php
/**
 * Css Module 
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Jsloader
 * @copyright 2012 Fenske und Miertsch GbR
 */
namespace Css;

class Module
{
    public function onBootstrap($e)
    {   
        $e->getApplication()->getServiceManager()->get('css_manager');
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(          
            'factories' => array(
                'cssfolder' => 'Css\View\Helper\Service\CssFolderFactory',
            )            
        );
    }
}
