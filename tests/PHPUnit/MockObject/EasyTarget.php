<?php
namespace MockObject;

use Ginger\Model\Target\AbstractTarget;
use Zend\EventManager\EventManagerInterface;
/**
 * Description of Target
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class EasyTarget extends AbstractTarget
{
    protected $items = array();

    protected $options = array();

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function attach(EventManagerInterface $events)
    {
        //do nothing
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