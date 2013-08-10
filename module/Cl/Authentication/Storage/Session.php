<?php

/**
 * Class Authentication Storage Session Adapter
 * 
 * Provides events when saving or returning an Instance
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Authentication
 * @version 1.0
 */
namespace Cl\Authentication\Storage;

use Zend\Authentication\Storage\Session as ZendSession,
    Zend\EventManager\EventCollection,
    Zend\EventManager\EventManager,
    Zend\EventManager\Event;

class Session extends ZendSession {
    
    /**
     *
     * @var EventCollection
     */
    protected $events;


    public function setEventManager(EventCollection $events) {
        $this->events = $events;

        return $this;
    }

    public function events() {
        if (!$this->events instanceof EventCollection) {
            $this->setEventManager(new EventManager(array(__CLASS__, get_class($this), 'authentication_storage')));
        }
        return $this->events;
    }
    
    public function read () {
        $contents = parent::read();
        
        $event = new Event('read.post', $this, array('contents' => $contents));
        
        $this->events()->trigger($event);
        
        return $event->getParam('contents');
    }
    
    public function write ($contents) {
        $event = new Event('write.pre', $this, array('contents' => $contents));
        
        $this->events()->trigger($event);
        
        parent::write($event->getParam('contents'));
    }
}
