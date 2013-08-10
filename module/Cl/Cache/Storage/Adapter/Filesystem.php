<?php

/**
 * Cl Cache Storage Filesystem Class
 * 
 * Extends the Zend Class, to provide a setter for Serializer Plugin
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Cache 
 * @version 1.0
 */
namespace Cl\Cache\Storage\Adapter;

use Zend\Cache\Storage\Adapter\Filesystem as ZendFilesystem,
    Zend\Cache\Storage\Plugin\Serializer;

class Filesystem extends ZendFilesystem {
    public function setSerializerPlugin (Serializer $serializer) {
        $this->addPlugin($serializer);
        return $this;
    }
}
