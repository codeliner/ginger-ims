<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'router' => array(
        'routes' => array(
            'rest_modules' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rest/modules[/:id]',
                    'defaults' => array(
                        'controller' => 'Ginger\Rest\Modules',
                    ),
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9 %_\-]+'
                    )
                ),
            ),
            'rest_jobs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rest/jobs[/:id]',
                    'defaults' => array(
                        'controller' => 'Ginger\Rest\Jobs',
                    ),
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9 %_\-]+'
                    )
                ),
            ),
            'rest_job_runs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rest/jobruns/:jobname[/:id]',
                    'defaults' => array(
                        'controller' => 'Ginger\Rest\JobRuns',
                    ),
                    'constraints' => array(
                        'jobname' => '[a-zA-Z0-9 %_\-]+',
                        'id' => '[a-zA-Z0-9]+'
                    )
                ),
            ),
            'rest_job_runs_list_pagination' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rest/jobruns/:jobname[/:id]',
                    'defaults' => array(
                        'controller' => 'Ginger\Rest\JobRuns',
                    ),
                    'constraints' => array(
                        'jobname' => '[a-zA-Z0-9 %_\-]+',
                        'id' => '[a-zA-Z0-9]+'
                    )
                ),
            ),
            'rest_job_runs_latest' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rest/latest-jobruns[/:id]',
                    'defaults' => array(
                        'controller' => 'Ginger\Rest\LatestJobRuns',
                    ),
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9]+'
                    )
                ),
            ),
            'rest_sources' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rest/sources[/:id]',
                    'defaults' => array(
                        'controller' => 'Ginger\Rest\Sources',
                    ),
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9]+'
                    )
                ),
            ),
            'rest_targets' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rest/targets[/:id]',
                    'defaults' => array(
                        'controller' => 'Ginger\Rest\Targets',
                    ),
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9]+'
                    )
                ),
            ),
            'rest_mappers' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rest/mappers[/:id]',
                    'defaults' => array(
                        'controller' => 'Ginger\Rest\Mappers',
                    ),
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9]+'
                    )
                ),
            ),
            'rest_features' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rest/features[/:id]',
                    'defaults' => array(
                        'controller' => 'Ginger\Rest\Features',
                    ),
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9]+'
                    )
                ),
            ),
            'rest_sourceinfo' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rest/sourceinfo[/:id[/:jobname[/:configid]]]',
                    'defaults' => array(
                        'controller' => 'Ginger\Rest\SourceInfo',
                        'jobname' => '-',
                        'configid' => '-1',
                    ),
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9]+',
                        'jobname' => '[a-zA-Z0-9 %_\-]+',
                        'configid' => '[a-zA-Z0-9]+',
                    )
                ),
            ),
            'rest_targetinfo' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rest/targetinfo[/:id[/:jobname[/:configid]]]',
                    'defaults' => array(
                        'controller' => 'Ginger\Rest\TargetInfo',
                        'jobname' => '-',
                        'configid' => '-1',
                    ),
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9]+',
                        'jobname' => '[a-zA-Z0-9 %_\-]+',
                        'configid' => '[a-zA-Z0-9]+',
                    )
                ),
            ),
            'rest_configurations' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rest/configurations/:jobname[/:id]',
                    'defaults' => array(
                        'controller' => 'Ginger\Rest\Configurations',
                    ),
                    'constraints' => array(
                        'jobname' => '[a-zA-Z0-9 %_\-]+',
                        'id' => '[a-zA-Z0-9]+'
                    )
                ),
            ),
            'rest_users' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/rest/users[/:id]',
                    'defaults' => array(
                        'controller' => 'Ginger\Rest\Users',
                    ),
                    'constraints' => array(
                        'id' => '[a-zA-Z0-9-]+'
                    )
                ),
            ),
            'configuration_export' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/export/configuration/:jobname/:id',
                    'defaults' => array(
                        'controller' => 'Ginger\Controller\Jobdata',
                        'action' => 'exportconfig'
                    ),
                    'constraints' => array(
                        'id'      => '[a-zA-Z0-9 %_\-]+',
                        'jobname' => '[a-zA-Z0-9 %_\-]+'
                    )
                ),
            ),
            'configuration_import' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/import/configuration',
                    'defaults' => array(
                        'controller' => 'Ginger\Controller\Jobdata',
                        'action' => 'importconfig'
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ValidatorPluginManager' => 'Zend\Validator\ValidatorPluginManager',
        ),
        'factories' => array(
            'translator' => 'Cl\Translator\TranslatorServiceFactory',
            'js_translation_parser' => 'Codelinerjs\Javascript\TranslationParser\ParserFactory',
            'job_loader' => 'Ginger\Service\JobLoader\OrmLoaderFactory',
            'jobrun_logger' => 'Ginger\Service\Logger\OrmLoggerFactory',
            'element_loader' => function($sl) {
                return $sl->get('entitymanager')->getRepository('Ginger\Entity\ConnectorElement');
            },
            'module_loader' => function($sl) {
                return $sl->get('entitymanager')->getRepository('Ginger\Entity\ModuleConfiguration');
            },
            'user_loader' => 'Ginger\Service\UserLoader\OrmUserLoaderFactory',
            'permissions_loader' => 'Ginger\Service\PermissionsLoader\OrmPermissionsLoaderFactory',
            'usermanager' => function($sl) {
                $usermanager = new \Ginger\Model\User\UserManager();
                $usermanager->setUserLoader($sl->get('user_loader'));
                $usermanager->setPermissionsLoader($sl->get('permissions_loader'));
                return $usermanager;
            },
            'api_key_auth_adapter' => function($sl) {
                return new \Ginger\Service\Auth\ApiKeyAdapter($sl->get('user_loader'));
            },
            'check_active_user_listener' => function($sl) {
                $listener = new \Ginger\Service\ActiveUser\CheckActiveUserListener();
                $listener->setUserManager($sl->get('usermanager'));
                $listener->setAuthAdapter($sl->get('api_key_auth_adapter'));
                $listener->setJsLoader($sl->get('codelinerjs.js_loader'));
                return $listener;
            },
            'FilterPluginManager' => function($sl) {
                $config = $sl->get('configuration');

                $fP = new \Zend\Filter\FilterPluginManager();

                $config = $config['filter_plugins'];

                if (isset($config['invokables'])) {
                    foreach ($config['invokables'] as $alias => $class) {
                        $fP->setInvokableClass($alias, $class);
                    }
                }

                if (isset($config['factories'])) {
                    foreach($config['factories'] as $alias => $factory) {
                        $fP->setFactory($alias, $factory);
                    }
                }

                return $fP;
            },
        ),
        'aliases' => array(
            'source_loader'  => 'element_loader',
            'target_loader'  => 'element_loader',
            'mapper_loader'  => 'element_loader',
            'feature_loader' => 'element_loader',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Ginger\Controller\Index' => 'Ginger\Controller\IndexController',
        ),
        'factories' => array(
            'Ginger\Controller\Jobdata' => function($cl) {
                $c = new \Ginger\Controller\JobdataController();
                $c->setJobLoader($cl->getServiceLocator()->get('job_loader'));
                return $c;
            },
            'Ginger\Rest\Modules' => function($cl) {
                $c = new \Ginger\Rest\ModulesService();
                $c->setModuleLoader($cl->getServiceLocator()->get('module_loader'));
                return $c;
            },
            'Ginger\Rest\Jobs' => function($cl) {
                $c = new \Ginger\Rest\JobsService();
                $c->setJobLoader($cl->getServiceLocator()->get('job_loader'));
                return $c;
            },
            'Ginger\Rest\JobRuns' => function($cl) {
                $c = new \Ginger\Rest\JobRunsService();
                $c->setJobLoader($cl->getServiceLocator()->get('job_loader'));
                return $c;
            },
            'Ginger\Rest\LatestJobRuns' => function($cl) {
                $c = new \Ginger\Rest\LatestJobRunsService();
                $c->setLogger($cl->getServiceLocator()->get('jobrun_logger'));
                return $c;
            },
            'Ginger\Rest\Sources' => function($cl) {
                $c = new \Ginger\Rest\SourcesService();
                $c->setSourceLoader($cl->getServiceLocator()->get('source_loader'));
                return $c;
            },
            'Ginger\Rest\SourceInfo' => function($cl) {
                $c = new \Ginger\Rest\SourceInfoService();
                $c->setSourceLoader($cl->getServiceLocator()->get('source_loader'));
                $c->setJobLoader($cl->getServiceLocator()->get('job_loader'));
                return $c;
            },
            'Ginger\Rest\Targets' => function($cl) {
                $c = new \Ginger\Rest\TargetsService();
                $c->setTargetLoader($cl->getServiceLocator()->get('target_loader'));
                return $c;
            },
            'Ginger\Rest\TargetInfo' => function($cl) {
                $c = new \Ginger\Rest\TargetInfoService();
                $c->setTargetLoader($cl->getServiceLocator()->get('target_loader'));
                $c->setJobLoader($cl->getServiceLocator()->get('job_loader'));
                return $c;
            },
            'Ginger\Rest\Mappers' => function($cl) {
                $c = new \Ginger\Rest\MappersService();
                $c->setMapperLoader($cl->getServiceLocator()->get('mapper_loader'));
                return $c;
            },
            'Ginger\Rest\Configurations' => function($cl) {
                $c = new \Ginger\Rest\ConfigurationsService();
                $c->setJobLoader($cl->getServiceLocator()->get('job_loader'));
                $c->setSourceLoader($cl->getServiceLocator()->get('source_loader'));
                $c->setTargetLoader($cl->getServiceLocator()->get('target_loader'));
                $c->setMapperLoader($cl->getServiceLocator()->get('mapper_loader'));
                $c->setFeatureLoader($cl->getServiceLocator()->get('feature_loader'));
                return $c;
            },
            'Ginger\Rest\Features' => function($cl) {
                $c = new \Ginger\Rest\FeaturesService();
                $c->setFeatureLoader($cl->getServiceLocator()->get('feature_loader'));
                return $c;
            },
            'Ginger\Rest\Users' => function($cl) {
                $c = new \Ginger\Rest\UsersService();
                $c->setUserLoader($cl->getServiceLocator()->get('user_loader'));
                $c->setUserManager($cl->getServiceLocator()->get('usermanager'));
                return $c;
            },
        ),
    ),
    'translator' => array(
        'locale' => 'de_DE',
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
            'public_path' => __DIR__ . '/../../../public/js/jsload',
            'public_link' => '/js/jsload',
            'core_script_name' => 'jobs_app',
            'cache_loaded_classes' => true,
            'cache_filename' => 'js_jobs_app',
            'preInitLoadStack' => array(
                'Cl.Application.Application',
                'Ginger.AppInitializer',
                'Ginger.Application.Module',
                'Ginger.Dashboard.Module',
                'Ginger.Jobs.Module',
                'Ginger.Users.Module',
            ),
            'postInitLoadStack' => array(
                'appInitializer' => 'Ginger.AppInitializer',
            ),
            'includePaths' => array(
                'Ginger' =>  __DIR__ . '/../src/Ginger/Javascript',
            ),
            'appVars' => array(
                '$APPLICATION_MODULES' => array(
                    'Ginger.Application.Module',
                    'Ginger.Dashboard.Module',
                    'Ginger.Jobs.Module',
                    'Ginger.Users.Module',
                ),
            ),
            'use_client_translation' => true,
            'templates' => array(
                'dashboard_main'                             => 'ginger/dashboard/main',
                'dashboard_module'                           => 'ginger/dashboard/partial/module',
                'dashboard_latest_jobruns'                   => 'ginger/dashboard/partial/latest-jobruns',
                'application_breadcrumbs'                    => 'ginger/helpers/breadcrumbs',
                'application_structure_mapper_options'       => 'ginger/application/partial/structure-mapper-options',
                'application_sourcefile_options'             => 'ginger/application/partial/source-file-options',
                'application_sourcedirectory_options'        => 'ginger/application/partial/source-directory-options',
                'application_targetdirectory_options'        => 'ginger/application/partial/target-directory-options',
                'application_sourcescript_options'           => 'ginger/application/partial/source-script-options',
                'application_devnull_options'                => 'ginger/application/partial/dev-null-options',
                'application_edit_remove'                    => 'ginger/application/partial/edit-remove-icons',
                'application_popup_bar'                      => 'ginger/application/partial/popup-bar-icons',
                'application_form_submit'                    => 'ginger/application/partial/form-submit-buttons',
                'application_abstract_feature_options'       => 'ginger/application/partial/abstract-feature-options',
                'application_validator_feature_options'      => 'ginger/application/partial/validator-feature-options',
                'application_attributemap_feature_options'   => 'ginger/application/partial/attributemap-feature-options',
                'application_staticvalue_feature_options'    => 'ginger/application/partial/staticvalue-feature-options',
                'jobs_index'              => 'ginger/jobs/index/index',
                'jobs_job'                => 'ginger/jobs/index/job',
                'jobs_add'                => 'ginger/jobs/index/add-form',
                'jobs_job_edit'           => 'ginger/jobs/index/job-edit',
                'jobs_job_edit_sidebar'   => 'ginger/jobs/index/partial/edit-sidebar',
                'jobs_job_sidebar'        => 'ginger/jobs/index/partial/job-sidebar',
                'jobs_config_edit'        => 'ginger/jobs/configuration/edit',
                'jobs_config_sidebar'     => 'ginger/jobs/configuration/sidebar',
                'jobs_config_footer'      => 'ginger/jobs/configuration/footer',
                'jobs_jobrun_show'        => 'ginger/jobs/jobrun/show',
                'jobs_jobrun_entry'       => 'ginger/jobs/jobrun/entry',
                'users_form_user'         => 'ginger/users/form/user',
                'users_auth_login'        => 'ginger/users/auth/login',
                'users_nav_active_user'   => 'ginger/users/partial/nav-active-user',
                'users_user_show'         => 'ginger/users/user/show',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/ginger/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'ginger_module_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/Ginger/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Ginger' => 'ginger_module_driver',
                )
            )
        )
    ),
    'connect_modules' => array(
        /*
         * 'module_name' => array(
         *      'icon' => '/path/to/icon.png',
         *      'route' => 'js_route_to_module_index'
         * ),
         */
    ),
    'filter_plugins' => array(
        'invokables' => array(
            'Url' => 'Cl\Filter\Url',
        )
    )
);
