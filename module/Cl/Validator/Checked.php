<?php

/**
 * Checked Validator
 * 
 * @author Alexander Miertsch kontakt@codeliner.ws
 * @package Cl
 * @subpackage Validator 
 * @version 1.0
 */
namespace Cl\Validator;

use Zend\Validator\AbstractValidator;

class Checked extends AbstractValidator {
    const NOT_CHECKED = 'not_checked';
    
    protected $condition = 'the conditions';


    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_CHECKED      => "please acknowledge %condition%",
    );
    
    protected $_messageVariables = array(
        'condition' => 'condition',
    );
    
    /**
     *
     * @param string $condition 
     * 
     * @return Checked
     */
    public function setCondition ($condition) {
        if ($translator = $this->getTranslator()) {
            $condition = $translator->translate($condition);
        }
        
        $this->condition = $condition;
    }
    
    public function isValid ($value) {
        if ((int)$value !== 1) {
            $this->error(self::NOT_CHECKED);
            return false;
        }
        
        return true;
    }
}