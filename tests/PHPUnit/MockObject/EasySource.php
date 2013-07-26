<?php
namespace MockObject;

use Ginger\Model\Source\AbstractSource;
use Ginger\Model\Connector\ConnectorEvent;
use Zend\EventManager\EventManagerInterface;

/**
 * Description of Source
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class EasySource extends AbstractSource
{
    protected $options = array();

    public function attach(EventManagerInterface $events)
    {
        //do nothing
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

    public function getDataStructure()
    {
        return null;
    }

    public function getDataType()
    {
        return static::DATA_TYPE_NOT_DEFINED;
    }
}