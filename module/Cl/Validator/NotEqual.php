<?php

/**
 * Not Equal Validator
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Validator 
 * @version 1.0
 */
namespace Cl\Validator;

use Zend\Validator\AbstractValidator,
    Zend\Validator\StringLength;

class NotEqual extends AbstractValidator {
    const IS_EQUAL = 'is_equal';
    const CHECK    = 'check_value';
    
    protected $check = '';


    public function __construct($options = null) {
        if (is_array($options) && array_key_exists('check_value', $options)) {
            $this->check = $options['check_value'];
        }
        
        parent::__construct($options);
    }
    
    /**
     *
     * @param mixed $checkValue 
     */
    public function setCheck ($checkValue) {
        $this->check = $checkValue;
    }
    
     /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::IS_EQUAL      => 'The value must not be "%value%"',
        );
    
    protected $_messageVariables = array(
        'name' => 'name'
        );
    
    public function isValid ($value) {
        if ($this->check === $value) {
            $this->error(self::IS_EQUAL, (string)$value);
            return false;
        }
        
        return true;
    }
}
