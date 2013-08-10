<?php

/**
 * ActionController
 *
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Mvc
 * @version 1.0
 */
namespace Cl\Mvc\Controller;

use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController,
    Zend\Mvc\MvcEvent;

class AbstractActionController extends ZendAbstractActionController {
    public function getParam ($name, $default = null) {
        $val = $this->getEvent()->getRouteMatch()->getParam($name, $default);

        if ($val === $default) {
            $val = $this->getRequest()->getPost()->get($name, $default);

            if ($val === $default) {
                $val = $this->getRequest()->getQuery()->get($name, $default);
            }
        }
        
        return $val;
    }
}
