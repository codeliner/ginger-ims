<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overridding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return array(
    'service_manager' => array(
        'factories' => array(
            'db' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Zend\View\Strategy\JsonStrategy' => 'Zend\Mvc\Service\ViewJsonStrategyFactory',
            'data_cache' => function($sl) {
                $cache = \Zend\Cache\StorageFactory::factory(array(
                    'adapter' => 'filesystem',
                    'plugins' => array(
                        'exception_handler' => array(
                            'throw_exceptions' => false,
                        ),
                        'serializer'
                    )
                ));

                $cache->setOptions(array(
                    'cache_dir' => './data/cache'
                ));

                return $cache;
            },
        ),
        'invokables' => array(
            'Doctrine\ORM\Mapping\UnderscoreNamingStrategy' => 'Doctrine\ORM\Mapping\UnderscoreNamingStrategy',
        ),
        'aliases' => array(
            'entitymanager' => 'doctrine.entitymanager.orm_default',
        ),
    ),
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                'naming_strategy' => 'Doctrine\ORM\Mapping\UnderscoreNamingStrategy'
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'Zend\View\Strategy\JsonStrategy'
        ),
    ),
    'css_manager' => array(
        'less' => array(
            'style.css' => __DIR__ . '/../../public/less/style.less',
        ),
    ),
);
