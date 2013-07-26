<?php
namespace Ginger\Model\Connector;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Ginger\Model\Source\AbstractSource;
use Ginger\Model\Target\AbstractTarget;
use Ginger\Model\Mapper\AbstractMapper;
use Ginger\Job\Run\Message;

/**
 * Description of Connector
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Connector implements EventManagerAwareInterface
{
    /**
     * @var EventManagerInterface Event Manager Instance
     */
    protected $events;

    /**
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->events;
    }

    /**
     * Set Event Manager Instance
     *
     * @param EventManagerInterface $eventManager
     *
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->addIdentifiers(array(
            __CLASS__,
            get_class($this)
        ));

        $this->events = $eventManager;
    }


    /**
     * Insert source items into target
     *
     * @param AbstractSource $source
     * @param AbstractTarget $target
     * @param AbstractMapper $mapper
     *
     * @return array
     */
    public function insert(AbstractSource $source, AbstractTarget $target, AbstractMapper $mapper = null)
    {
        //Initialize response values:
        //Count all successfully inserted items
        $count = 0;
        //Success state will be set at the end of the try block
        $success = false;
        //All listener messages are merged in a collection
        $messages = array();
        //We use the same event object for all events
        $event = new ConnectorEvent();
        
        //@todo: Register own error handler to catch non exception errors

        //Catch all kinds of exceptions, to log them as error messages
        try{
            
            //Give all ConnectorElements the possibility to listen on ConnectorEvents
            $source->attach($this->events);
            $target->attach($this->events);

            if (!is_null($mapper)) {
                $mapper->attach($this->events);
            }

            //Prepaire the start event
            $event->setSource($source);
            $event->setTarget($target);
            
            $event->setName(ConnectorEvent::EVENT_START_INSERT);

            $res = $this->events->trigger($event);
            
            
            $messages = $this->mergeResponse($messages, $res);

            //After each event we check, if a listener has stopped the process
            if ($event->propagationIsStopped()) {
                goto FINISH;
            }

            //Loop over each source dataset
            foreach ($source->getData() as $item) {
                
                //Prepaire a mapping event for each dataset
                $event->setItem($item);
                $event->setName(ConnectorEvent::EVENT_MAP_ITEM);

                //Break the event chain, if current dataset should be skipped
                $res = $this->events->triggerUntil($event, function($respone) use ($event) {
                    return $event->isSkipItem();
                });

                $messages = $this->mergeResponse($messages, $res);

                if ($event->propagationIsStopped()) {
                    goto FINISH;
                }

                if ($event->isSkipItem()) {
                    //Reset the skip item flag, befor continuing with the next item
                    $event->resetSkipItem();
                    continue;
                }

                //No error occured and item should be inserted, 
                //so we pass it to the target
                $target->addItem($event->getItem());
                
                //Increase the item counter
                $count++;
                
                //Prepaire event object for the post write event
                $event->setName(ConnectorEvent::EVENT_POST_WRITE_TARGET_ITEM);

                $res = $this->events->trigger($event);
                $messages = $this->mergeResponse($messages, $res);

                if ($event->propagationIsStopped()) {
                    goto FINISH;
                }
            }

            //After the loop, we trigger a finish event, to give the possibilty
            //to check data integrity and/or run postprocessing tasks
            $event->setName(ConnectorEvent::EVENT_FINISH_INSERT);
            $res = $this->events->trigger($event);
            $messages = $this->mergeResponse($messages, $res);

            //We are done, but if a finish event listener has detected an error
            //we go to the finish block, without setting the success state to true
            if ($event->propagationIsStopped()) {
                goto FINISH;
            }

            $success = true;
        } catch(\Exception $e) {
            error_log($e->__toString());
            
            $errorMsg = new Message();
            $errorMsg->setType(Message::TYPE_ERROR);
            $errorMsg->setText($e->getMessage() . "\n" . $e->getTraceAsString());
            $messages[] = $errorMsg;
        }

        FINISH:
            if (!$success) {
                $event->setName(ConnectorEvent::EVENT_ROLEBACK);
                $roleBackResponse = $this->events->trigger($event);
                $messages = $this->mergeResponse($messages, $roleBackResponse);
            }

            //Detach all listeners
            $this->detachEvents($source, $target, $mapper);
            
            return array(
                'success' => $success,
                'count' => $count,
                'messages' => $messages
            );
    }

    protected function detachEvents(AbstractSource $source, AbstractTarget $target, AbstractMapper $mapper = null)
    {
        $source->detach($this->events);
        $target->detach($this->events);

        if (!is_null($mapper)) {
            $mapper->detach($this->events);
        }
    }

    protected function mergeResponse($resp1, $resp2)
    {
        $messages = array();

        foreach($resp2 as $message) {
            $messages[] = $message;
        }

        $messages = array_reverse($messages);

        foreach ($messages as $message) {
            if (!is_null($message)) {
                if (!$message instanceof Message) {
                    $messageText = $message;
                    $message = new Message();
                    $message->setText($messageText);
                }
                $resp1[] = $message;
            }
        }

        return $resp1;
    }
}