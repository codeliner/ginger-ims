<?php

/**
 * CssFolder view helper factory
 *
 * @link      http://vonwerth.de/
 * @copyright Copyright (c) 2012 von Werth GmbH
 */
namespace Css\View\Helper\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Css\View\Helper\CssFolder;

class CssFolderFactory implements FactoryInterface
{
    
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {              
        $cssFolderHelper = new CssFolder;
        return $cssFolderHelper;
    }
}