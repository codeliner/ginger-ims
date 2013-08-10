<?php

/**
 * Cl Sysmail
 *
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Mail
 * @version 1.0
 */
namespace Cl\Mail;

use Zend\Mail\Message,
    Zend\Mail\Transport;

class Sysmail extends Message {

    const TRANSPORT_SMTP = 'Smtp';
    /**
     *
     * @var Transport
     */
    protected $transport;


    /**
     * Int Transport\Smtp with SmtpOptions via Locator rases circular dependency exeption
     * maybe a bug, which will be fixed in production version
     *
     * @var string
     */
    protected $transportType = 'Smtp';

    public function setTransport($transport)
    {
        $this->transport = $transport;
    }

    public function send () {
        return $this->transport->send($this);
    }
}
