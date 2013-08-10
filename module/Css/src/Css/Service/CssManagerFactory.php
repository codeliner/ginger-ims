<?php
/**
 * Css Manager Factory
 * 
 * @package Css
 * @author Alexander Miertsch <miertsch@codeliner.ws>
 * @copyright (c) 2012, Alexander Miertsch
 */
namespace Css\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Css\CssManager;

class CssManagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('configuration');
        
        $cssManager = new CssManager;
        
        if(isset($config['css_manager'])) {
            $config = $config['css_manager'];
            
            if(isset($config['override_files'])) {
                $cssManager->setOverrideFiles($config['override_files']);
            }
            
            if (isset($config['public_folder'])) {
                $cssManager->setPublicFolder($config['public_folder']);
            }
            
            if (isset($config['files']) && is_array($config['files'])) {
                foreach ($config['files'] as $fileName => $path) {
                    $cssManager->transferCssScript($fileName, $path);
                }
            }
            
            if (isset($config['less']) && is_array($config['less'])) {
                foreach ($config['less'] as $fileName => $path) {
                    $cssManager->transferLessScript($fileName, $path);
                }
            }
        }
        
        return $cssManager;
    }
}