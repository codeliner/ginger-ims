<?php

/**
 * When a Class implements this Interface, it can recieve an AuthenticationService via setter
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package cl
 * @subpackage Authentication 
 * @version 1.0
 */
namespace Cl\Authentication;

use Zend\Authentication\AuthenticationService;

interface AuthenticationAware {
    /**
     * @param AuthenticationService $auth
     */
    public function setAuthService (AuthenticationService $authService);
}
