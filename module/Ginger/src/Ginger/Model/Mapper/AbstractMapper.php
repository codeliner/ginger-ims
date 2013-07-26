<?php
namespace Ginger\Model\Mapper;

use Zend\EventManager\EventManagerInterface;
use Ginger\Model\Connector\ConnectorEvent;
use Ginger\Model\Connector\AbstractElement;
use Ginger\Job\Run\Message;
/**
 * Description of AbstractMapper
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
abstract class AbstractMapper extends AbstractElement
{
    protected $mapping = array();
    protected $disabledKeys = array();

    abstract public function mapItem($item, ConnectorEvent $e);

    public function onMapItem(ConnectorEvent $e)
    {
        $item = $e->getItem();

        try {
            $e->setItem($this->mapItem($item, $e));
        } catch (Exception\Exception $e) {
            $message = new Message(Message::TYPE_ERROR);
            $message->setText($e->__toString());
            $e->stopPropagation();
            return $message;
        }

        return $this->getMessage();
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(ConnectorEvent::EVENT_MAP_ITEM, array($this, 'onMapItem'));
    }

    public function getOptions()
    {
        return array(
            'mapping' => $this->mapping,
            'disabled_keys' => $this->disabledKeys,
        );
    }

    public function setOptions(array $options)
    {
        $this->mapping = $options['mapping'];
        $this->disabledKeys = $options['disabled_keys'];
    }

    /**
     * Hookpoint: Message is returned by onMapItm()
     *
     * Returns null by default, meaning no message will be logged
     *
     * @return null
     */
    public function getMessage()
    {
        return null;
    }


}