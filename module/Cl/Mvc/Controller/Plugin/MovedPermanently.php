<?php

/**
 * 
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package 
 * @subpackage 
 * @version 1.0
 */
namespace Cl\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class MovedPermanently extends AbstractPlugin {
    public function __invoke ($toUrl) {        
        $response = $this->getController()->redirect()->toUrl($toUrl);
        return $response->setStatusCode(301);
    }
}