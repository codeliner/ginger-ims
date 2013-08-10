<?php
namespace Cl\Mvc\Controller\Plugin;
/**
 * Page not found plugin
 *
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Mvc
 * @version 1.0
 */
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Application;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\Mvc\MvcEvent;

class PageNotFound extends AbstractPlugin {
    public function __invoke () {
        $controller = $this->getController();

        if ($controller instanceof InjectApplicationEventInterface) {
            $event = $controller->getEvent();
            $event->setError(Application::ERROR_ROUTER_NO_MATCH);
            $application = $event->getApplication();
            $events  = $application->getEventManager();
            $results = $events->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
            $return  = $results->last();
            if (! $return) {
                $return = $event->getResult();
            }

            return $return;
        }

        return;
    }
}
