<?php

/**
 * Common Chars Validator 
 * 
 * allow more than Zend's Alnum Validator like point, minus, etc.
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Validator 
 * @version 1.0
 */
namespace Cl\Validator;

use Zend\Validator\AbstractValidator,
    Zend\Validator\Regex;

class CommonNameChars extends AbstractValidator {
    const CONTAINS_NO_COMMON_CHARS = 'contains_no_common_chars';
    
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::CONTAINS_NO_COMMON_CHARS => '"%value%" contains not allowed chars.',
    );
    
    public function isValid ($value) {
        $regex = new Regex('/^[a-zA-Z0-9äÄüÜöÖß \.\-\_]+$/');
        
        if (!$regex->isValid($value)) {
            $this->error(self::CONTAINS_NO_COMMON_CHARS);
            return false;
        }
        
        return true;
    }
}
