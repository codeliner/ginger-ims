<?php
namespace MockObject;

use Ginger\Model\Mapper\AbstractMapper;
use Zend\EventManager\EventManagerInterface;
use Ginger\Model\Connector\ConnectorEvent;
use Ginger\Job\Run\Message;
/**
 * Description of Mapper
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class MapperWithError extends AbstractMapper
{
    protected $options;

    public function mapItem($item, ConnectorEvent $e)
    {
        return $item .= "Mapped";
    }

    public function attach(EventManagerInterface $events)
    {
        parent::attach($events);

        $this->listeners[] = $events->attach(ConnectorEvent::EVENT_MAP_ITEM, function($e) {
            $item = $e->getItem();

            if ($item == "item2") {
                $e->stopPropagation();

                $message = new Message(Message::TYPE_ERROR);
                $message->setText("Can not map item 2.");
                return $message;
            }
        }, 100);
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }
}