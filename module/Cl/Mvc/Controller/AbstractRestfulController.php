<?php
namespace Cl\Mvc\Controller;

use Zend\Mvc\Controller\AbstractRestfulController as ZendAbstractRestfulController;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Exception;
use Zend\Mvc\MvcEvent;
/**
 * Description of AbstractRestfulController
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
abstract class AbstractRestfulController extends ZendAbstractRestfulController
{
    /**
     * Process post data and call create
     *
     * @param Request $request
     * @return mixed
     */
    public function processPostData(Request $request)
    {
        return $this->create(json_decode($request->getContent(), true));
    }

    /**
     * Process put data and call update
     *
     * @param Request $request
     * @param $routeMatch
     * @return mixed
     * @throws Exception\DomainException
     */
    public function processPutData(Request $request, $routeMatch)
    {
        if (null === $id = $routeMatch->getParam('id')) {
            if (!($id = $request->getQuery()->get('id', false))) {
                throw new Exception\DomainException('Missing identifier');
            }
        }
        $content = json_decode($request->getContent(), true);

        return $this->update($id, $content);
    }

    public function getParam ($name, $default = null) {
        $val = $this->getEvent()->getRouteMatch()->getParam($name, $default);

        if ($val === $default) {
            $val = $this->getRequest()->getPost()->get($name, $default);

            if ($val === $default) {
                $val = $this->getRequest()->getQuery()->get($name, $default);
            }
        }
        return $val;
    }

    /**
     * Handle the request
     *
     * @param  MvcEvent $e
     * @return mixed
     * @throws Exception\DomainException if no route matches in event or invalid HTTP method
     */
    public function onDispatch(MvcEvent $e)
    {
        try {
            parent::onDispatch($e);
        } catch (\Exception $e) {
            return $this->getResponse()->setStatusCode(500)->setContent($e->__toString());
        }
    }
}