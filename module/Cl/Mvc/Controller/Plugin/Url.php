<?php

/**
 * Action Plugin Url 
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Mvc
 * @version 1.0
 */
namespace Cl\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Url extends AbstractPlugin {
    public function __invoke ($routeName, array $params = null) {
        if (null === $params) {
            $params = array();
        }
        return $this->getController()->getLocator()->get('view')->url($routeName, $params);
    }
}
