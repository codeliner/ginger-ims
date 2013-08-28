<?php
return array(
    'service_manager' => array(        
        'factories' => array(            
            'job_loader' => 'GingerORM\Service\JobLoader\OrmLoaderFactory',
            'jobrun_logger' => 'GingerORM\Service\Logger\OrmLoggerFactory',
            'element_loader' => function($sl) {
                return $sl->get('entitymanager')->getRepository('GingerORM\Entity\ConnectorElement');
            },
            'module_loader' => function($sl) {
                return $sl->get('entitymanager')->getRepository('GingerORM\Entity\ModuleConfiguration');
            },
            'user_loader' => 'GingerORM\Service\UserLoader\OrmUserLoaderFactory',
            'permissions_loader' => 'GingerORM\Service\PermissionsLoader\OrmPermissionsLoaderFactory',            
        ),
        'aliases' => array(
            'source_loader'  => 'element_loader',
            'target_loader'  => 'element_loader',
            'mapper_loader'  => 'element_loader',
            'feature_loader' => 'element_loader',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'gingerorm_module_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/GingerORM/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'GingerORM' => 'gingerorm_module_driver',
                )
            )
        )
    ),
);