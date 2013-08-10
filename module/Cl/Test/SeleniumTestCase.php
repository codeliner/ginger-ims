<?php

/**
 * SeleniumTestCase Base Class
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Test 
 * @version 1.0
 */
namespace Cl\Test;

class SeleniumTestCase extends \PHPUnit_Extensions_SeleniumTestCase {
    
    protected $bootstrap;
    private $application;
    
    protected function setUp()
    {
        $this->setBrowser("*firefox");
        $this->setBrowserUrl("http://dogs.notebook/");
        
        $this->bootstrap = \Zend\Registry::get('App_Bootstrap');
        $this->application = new \Zend\Mvc\Application;
    }
    
    protected function bootstrap() {
        $this->bootstrap->bootstrap($this->application);
    }

    protected function getApplication() {
        return $this->application;
    }
    
    protected function login ($username = 'kontakt@codeliner.ws', $password = 'wO19tAn%') {
        $this->open('/');
        $this->click("css=#login_link");
        $this->waitForVisible('id=login_form');
        $this->type("name=login_key", $username);
        $this->type("name=login_pass", $password);        
        $this->click("id=login_link");
    }
    
    protected function logout () {
        $this->open('/');
        $this->click("css=#login_link");
    }
}
