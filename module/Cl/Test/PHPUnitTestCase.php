<?php
namespace Cl\Test;

use Zend\Mvc\Application;
/**
 * Description of PHPUnitTestCase
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class PHPUnitTestCase extends \PHPUnit_Framework_TestCase
{
    static protected $application;

    static public function setApplication($application)
    {
        self::$application = $application;
    }

    /**
     *
     * @return Application
     */
    static public function getApplication()
    {
        return self::$application;
    }
}