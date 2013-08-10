<?php
namespace SqlConnect;

return array(
    'connect_modules' => array(
        'SqlConnect' => array(
            'icon' => 'sql-connect.png',
            'route' => 'sqlconnect_index',
        ),
    ),
    'router' => array(
        'routes' => array(
            'sql_connect' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/sqlconnect',
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'rest' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/rest',
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'sources' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route'    => '/sources/:connection[/:id]',
                                    'constraints' => array(
                                        'id' => '[a-zA-Z0-9_]+',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'SqlConnect\Rest\Sources',
                                    )
                                ),
                            ),
                            'targets' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route'    => '/targets/:connection[/:id]',
                                    'constraints' => array(
                                        'id' => '[a-zA-Z0-9_]+',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'SqlConnect\Rest\Targets',
                                    )
                                ),
                            ),
                            'connections' => array(
                                'type' => 'Zend\Mvc\Router\Http\Segment',
                                'options' => array(
                                    'route'    => '/connections[/:id]',
                                    'constraints' => array(
                                        'id' => '[a-zA-Z0-9 %_\-]+',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'SqlConnect\Rest\Connections',
                                    )
                                ),
                            ),
                            'connection_test' => array(
                                'type' => 'Zend\Mvc\Router\Http\Literal',
                                'options' => array(
                                    'route'    => '/connection/test',
                                    'defaults' => array(
                                        'controller' => 'SqlConnect\Rest\Connections',
                                        'action' => 'test',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'sqlconnect_adapter_factory' => function($sl) {
                $adapterFactory = new \SqlConnect\Service\Db\AdapterFactory();
                $adapterFactory->setModuleLoader($sl->get('module_loader'));
                return $adapterFactory;
            }
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'SqlConnect\Rest\Sources' => 'SqlConnect\Rest\SourcesService',
            'SqlConnect\Rest\Targets' => 'SqlConnect\Rest\TargetsService'
        ),
        'factories' => array(
            'SqlConnect\Rest\Connections' => function($cl) {
                $c = new \SqlConnect\Rest\ConnectionsService();
                $c->setAdapterFactory($cl->getServiceLocator()->get('sqlconnect_adapter_factory'));
                return $c;
            },
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'PhpArray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
            ),
        ),
    ),
    'codelinerjs.js_loader' => array(
        'default' => array(
            'lazy_modules' => array(
                'SqlConnect' => array(
                    'includePaths' => array(
                        'SqlConnect' =>  __DIR__ . '/../src/SqlConnect/Javascript',
                    ),
                    'preInitLoadStack' => array(
                        'SqlConnect.Module',
                    ),
                    'appVars' => array(
                        '$APPLICATION_MODULES' => array(
                            'SqlConnect.Module',
                        ),
                    ),
                    'templates' => array(
                        'sqlconnect/index/index'              => 'sqlconnect/index/index',
                        'sqlconnect/index/configuration'      => 'sqlconnect/index/configuration',
                        'sqlconnect/index/configurationedit'  => 'sqlconnect/index/configuration-edit',
                        'sqlconnect/sources/index'            => 'sqlconnect/tables/index',
                        'sqlconnect/sources/show'             => 'sqlconnect/tables/show',
                        'sqlconnect/sources/options'          => 'sqlconnect/sources/options',
                        'sqlconnect/targets/index'            => 'sqlconnect/tables/index',
                        'sqlconnect/targets/show'             => 'sqlconnect/tables/show',
                        'sqlconnect/targets/options'          => 'sqlconnect/targets/options'
                    ),
                ),
            ),
        ),
    ),
);