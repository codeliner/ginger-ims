<?php
/**
 * Description of Cl Module class
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
namespace Cl;


class Module
{
    public function getConfig()
    {
        return array(
            'service_manager' => array(
                'factories' => array(
                    'sys_mail' => 'Cl\Mail\Service\SysMailFactory'
                )
            ),
            'controller_plugins' => array(
                'invokables' => array(
                    'PageNotFound' => 'Cl\Mvc\Controller\Plugin\PageNotFound',
                    'SendDownload' => 'Cl\Mvc\Controller\Plugin\SendDownload',
                )
            ),
//@example sys mail config
//            'sys_mail' => array(
//                'from' => 'info@ginger-ims.local',
//                'transport_type' => 'Smtp',
//                'transport_options' => array(
//                    'host'              => 'smtp-host',
//                    'name'              => 'smtp-name',
//                    'port'              => '465',
//                    'connection_class'  => 'plain',
//                    'connection_config' => array(
//                        'username' => 'username',
//                        'password' => 'password',
//                        'ssl' => 'ssl',
//                    ),
//                )
//            )
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ ,
                ),
            ),
        );
    }
}