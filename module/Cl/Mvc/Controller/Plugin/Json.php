<?php

/**
 * Action Plugin Json
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Mvc
 * @version 1.0
 */
namespace Cl\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Json extends AbstractPlugin {
    
    public function __invoke ($data) {
        return $this->send($data);
    }
    
    public function send ($data) {
        $response = $this->getController()->getResponse();
        $locator = $this->getController()->getLocator();
        $view = $locator->get('view');
        $json = $view->plugin('json')->setResponse($response)->__invoke($data);
        $response->setContent($json);
        return $response;
    } 
}
