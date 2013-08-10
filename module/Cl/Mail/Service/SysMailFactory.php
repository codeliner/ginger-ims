<?php
namespace Cl\Mail\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Cl\Mail\Sysmail;
use Cl\Mail\Exception\ConfigNotFoundException;
/**
 * Description of SysMailFactory
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class SysMailFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get("configuration");

        if (!isset($config['sys_mail'])) {
            throw new ConfigNotFoundException("Config key sys_mail could not be found in app config.");
        }

        $config = $config['sys_mail'];

        if (!isset($config['from'])) {
            throw new ConfigNotFoundException("Missing config key 'from' in sys_mail config.");
        }

        if (!isset($config['to'])) {
            throw new ConfigNotFoundException("Missing config key 'to' in sys_mail config.");
        }

        if ($config['transport_type'] == "Smtp") {
            if (isset($config['transport_options'])) {
                $smtpOptions = new SmtpOptions($config['transport_options']);
            } else {
                $smtpOptions = new SmtpOptions();
            }

            $transport = new Smtp();
            $transport->setOptions($smtpOptions);
        }

        $sysMail = new Sysmail();
        $sysMail->setTransport($transport);

        $sysMail->setFrom($config['from']);
        $sysMail->addTo($config['to']);

        return $sysMail;
    }
}