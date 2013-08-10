<?php

/**
 * Jsloader ViewHelper
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Jsloader
 * @subpackage View
 * @version 1.0
 */
namespace Css\View\Helper;

use Css\Manager;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class CssFolder extends AbstractHelper implements ServiceManagerAwareInterface {
    
    /**
     *
     * @var ServiceManager
     */
    protected $serviceManager;


    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager->getServiceLocator();        
    }
        
    public function __invoke () {
        try{
            return $this->serviceManager->get("css_manager")->getPublicFolder();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
