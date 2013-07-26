<?php
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Ginger\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'ie_advise' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ie-advise',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'ieAdvise',
                    ),
                ),
            ),
        ),
    ),
);