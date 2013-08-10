<?php

/**
 * Password Validator
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Validator 
 * @version 1.0
 */
namespace Cl\Validator;

use Zend\Validator\AbstractValidator,
    Zend\Validator\StringLength;

class Password extends AbstractValidator {
    const PASSWORD_EMPTY = 'password_empty';
    const PASSWORD_NOT_SAFE = 'password_not_save';
    
     /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::PASSWORD_EMPTY      => "the password is empty",
        self::PASSWORD_NOT_SAFE   => "the password is not safe (min. 8 chars, with letters, digits and special chars)",
    );
    
    public function isValid ($value) {
        if (empty ($value) || $value === 'password' || $value === 'Passwort') {
            $this->error(self::PASSWORD_EMPTY);
            return false;
        }
        
        $strLength = new StringLength();
        $strLength->setMin(8);
        
        if (!$strLength->isValid($value)) {
            $this->error(self::PASSWORD_NOT_SAFE);
            return false;
        }
        
        if (preg_match('/[A-Za-zäüöÄÜÖß]/', $value) === 0) {
            $this->error(self::PASSWORD_NOT_SAFE);
            return false;
        }
        
        if (preg_match('/[0-9]/', $value) === 0) {
            $this->error(self::PASSWORD_NOT_SAFE);
            return false;
        }
        
        if (preg_match('/[^A-Za-z0-9äüöÄÜÖß]/', $value) === 0) {
            $this->error(self::PASSWORD_NOT_SAFE);
            return false;
        }
        
        
        
        return true;
    }
}
