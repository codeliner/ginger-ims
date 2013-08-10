<?php
namespace Ginger\Service\ActiveUser;

use Ginger\Model\User\UserManager;
use Ginger\Service\Auth\ApiKeyAdapter;
use Codelinerjs\Javascript\Loader\AbstractLoader as JsLoader;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
/**
 * Description of CheckActiveUserListener
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class CheckActiveUserListener implements ListenerAggregateInterface
{
    /**
     *
     * @var UserManager
     */
    protected $userManager;
    
    /**
     *
     * @var ApiKeyAdapter
     */
    protected $authAdapter;
    
    /**
     *
     * @var JsLoader 
     */
    protected $jsLoader;

    /**
     *
     * @var array
     */
    protected $listeners = array();


    /**
     * 
     * @param \Ginger\Model\User\UserManager $userManager
     */
    public function setUserManager(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }
    
    /**
     * 
     * @param \Ginger\Service\Auth\ApiKeyAdapter $authAdapter
     */
    public function setAuthAdapter(ApiKeyAdapter $authAdapter)
    {
        $this->authAdapter = $authAdapter;
    }
    
    /**
     * 
     * @param \Codelinerjs\Javascript\Loader\AbstractLoader $jsLoader
     */
    public function setJsLoader(JsLoader $jsLoader)
    {
        $this->jsLoader = $jsLoader;
    }
        
    /**
     * 
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH, 
            array($this, 'onDispatch'), 
            100);
    }

    /**
     * 
     * {@inheritdoc}
     */
    public function detach(EventManagerInterface $events)
    {
        foreach($this->listeners as $i => $listener) {
            $events->detach($listener);
            unset($this->listeners[$i]);
        }
    }

    /**
     * Check wether user informations are provided via HTTP Header params or not
     * 
     * If no params are present and the UserManager can find users in the system,
     * then the request is answered with a 401 HTTP statuscode (Unauthorized).
     * 
     * If no user is registered in the system, then a dummy
     * user is set to active in the UserManager that have all priviliges.
     * 
     * If an api_key header and a request_hash is given, the listener tries to 
     * authenticate the request. If authentication failed, a 401 HTTP statuscode 
     * is returned, otherwise the user associated with the api_key is set as
     * active in the UserManager.
     * 
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function onDispatch(MvcEvent $e)
    {
        $apiKey = $e->getRequest()->getHeader('Api-Key', null);
        $requestHash = $e->getRequest()->getHeader('Request-Hash', null);
        
        //Check lazy loading of js modules. Nothing to do in this case.
        if ($e->getRouteMatch()->getParam('controller') == "Codelinerjs\Controller\LazyModule") {
            return;
        }        
                
        //Set dummy user as active and return early when no user is registered
        if (!$this->userManager->hasUsers()) {
            $this->userManager->setActiveUser(UserManager::DUMMY_API_KEY);
            return;
        }
        
        
        
        if (is_null($apiKey) || is_null($requestHash)) {
            if ($e->getRequest()->isXmlHttpRequest()) {
                return $e->getResponse()->setStatusCode(401)->setContent(
                    'Credentials missing. Please provide an Api-Key and a Request-Hash header parameter!'
                    );
            } else {
                
                //This flag is used in the Javascript\AppInitializer
                $this->jsLoader->addUserVars(array(
                    '$LOGIN_REQUIRED' => true
                ));
                return;
            }
            
        }
        
        $this->authAdapter->setApiKey($apiKey->getFieldValue());
        $this->authAdapter->setRequestHash($requestHash->getFieldValue());
        $this->authAdapter->setRequestUri($e->getRequest()->getUri()->getPath());
        
        $authResult = $this->authAdapter->authenticate();
        
        if (!$authResult->isValid()) {
            return $e->getResponse()->setStatusCode(401)->setContent(
                'Invalid credentials provided'
                );
        }
        
        $this->userManager->setActiveUser($apiKey->getFieldValue());
    }
}
