<?php

/**
 * Check if header x-requested-with exists
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Mvc
 * @version 1.0
 */
namespace Cl\Mvc\Controller\Plugin;

use Zend\Http\Request;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class IsAjaxRequest extends AbstractPlugin {
    public function __invoke () {
        return $this->check();
    }
    
    public function check () {
        $controller = $this->getController();
        $request = $controller->getRequest();
        
        
        if ($request->headers()->has('x-requested-with')) {
            return $request->headers()->get('X-Requested-With')->getFieldValue() === 'XMLHttpRequest';
        }
        
        return false;   
    }
}
