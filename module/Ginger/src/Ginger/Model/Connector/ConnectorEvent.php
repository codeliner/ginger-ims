<?php
namespace Ginger\Model\Connector;

use Zend\EventManager\Event;
use Ginger\Model\Source\AbstractSource;
use Ginger\Model\Target\AbstractTarget;
/**
 * Description of ConnectorEvent
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ConnectorEvent extends Event
{
    const EVENT_START_INSERT           = "start_insert";
    const EVENT_MAP_ITEM               = "map_item";
    const EVENT_POST_WRITE_TARGET_ITEM = "post_write_target_item";
    const EVENT_FINISH_INSERT          = "finish_insert";
    const EVENT_ROLEBACK               = "roleback";

    protected $skipItem = false;

    /**
     * Set the source
     *
     * @param AbstractSource $source
     */
    public function setSource(AbstractSource $source)
    {
        $this->setParam('source', $source);
    }

    /**
     * Get the source
     *
     * @return AbstractSource
     */
    public function getSource()
    {
        return $this->getParam('source');
    }

    /**
     * Get the target
     *
     * @return AbstractTarget
     */
    public function getTarget()
    {
        return parent::getTarget();
    }

    /**
     * Set current item
     *
     * @param mixed $item
     */
    public function setItem($item)
    {
        $this->setParam('item', $item);
    }

    /**
     * Get current item
     *
     * @return mixed
     */
    public function getItem()
    {
        return $this->getParam('item');
    }

    public function skipItem()
    {
        $this->skipItem = true;
    }

    public function isSkipItem()
    {
        return $this->skipItem;
    }

    public function resetSkipItem()
    {
        $this->skipItem = false;
    }
}