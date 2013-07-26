<?php
namespace MockObject;

use Ginger\Model\Source\AbstractSource;
use Ginger\Model\Connector\ConnectorEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of Source
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Source extends AbstractSource implements ServiceLocatorAwareInterface
{
    private $startTriggered = false;

    private $options = array();

    private $serviceLocator;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(ConnectorEvent::EVENT_START_INSERT, array($this, 'onStartInsert'));
    }

    public function getData()
    {
        $data = array(
            'item1',
            'item2',
            'item3'
        );

        return $data;
    }

    public function onStartInsert(ConnectorEvent $e)
    {
        $this->startTriggered = true;
        return "insert is started";
    }

    public function isStartTriggered()
    {
        return $this->startTriggered;
    }

    public function getItemCount()
    {
        return 3;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getDataStructure()
    {
        return array(
            'itemname' => array(
                'type' => 'string'
            )
        );
    }

    public function getDataType()
    {
        return static::DATA_TYPE_TABLE_STRUCTURE;
    }
}