<?php
namespace MockObject;

use Ginger\Model\Target\AbstractTarget;
use Zend\EventManager\EventManagerInterface;
use Ginger\Model\Connector\ConnectorEvent;
/**
 * Description of Target
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Target extends AbstractTarget
{
    protected $items = array();

    protected $options = array();

    private $finishInsertTriggered = false;

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function resetItems()
    {
        $this->items = array();
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(ConnectorEvent::EVENT_FINISH_INSERT, array($this, 'onFinishInsert'));
        $this->listeners[] = $events->attach(ConnectorEvent::EVENT_POST_WRITE_TARGET_ITEM, array($this, 'onPostWriteTargetItem'));
    }

    public function onFinishInsert(ConnectorEvent $e)
    {
        $this->finishInsertTriggered = true;
        return "insert is finished";
    }

    public function isFinishInsertTriggered()
    {
        return $this->finishInsertTriggered;
    }

    public function onPostWriteTargetItem(ConnectorEvent $e)
    {
        $item = $e->getItem();

        $itemPos = array_search($item, $this->getItems());

        $this->items[$itemPos] .= 'AndWritten';
        return "item: " . $item . " is written";
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