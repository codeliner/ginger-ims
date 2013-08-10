<?php
/**
 * von Weth Online Shop
 *
 * @link      http://vonwerth.de/
 * @copyright Copyright (c) 2012 von Werth GmbH
 */
namespace Cl\Stdlib;

use Zend\Stdlib\ArrayUtils;
use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractObjectList extends ArrayCollection
{ 
    public function __construct(array $elements = array())
    {
        parent::__construct(array());
        
        if (count($elements)) {
            foreach ($elements as $i => $element) {
                $this->offsetSet($i, $element);
            }
        } 
    }
    
    public function offsetSet($offset, $value) 
    {   
        $requiredType = $this->getRequiredType();
        if (!$value instanceof $requiredType) {
            throw new Exception\InvalidArgumentException(
                "Value must be instance of $requiredType"
                . " but type of "
                . ((is_object($value))? get_class($value) : gettype($value))
                . " given"
            );
        }
        
        parent::offsetSet($offset, $value);
    }
    
    abstract protected function getRequiredType();
}