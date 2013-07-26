<?php
namespace MockObject;

use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * Description of ServiceLocatorMock
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ServiceLocatorMock implements ServiceLocatorInterface
{
    protected $registry = array();

    public function register($name, $object)
    {
        $this->registry[$name] = $object;
    }

    public function get($name)
    {
        return $this->registry[$name];
    }

    public function has($name)
    {
        return isset($this->registry[$name]);
    }
}