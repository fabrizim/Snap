<?php

class Snap_Wordpress_Form_Validator_NotEmpty extends Snap_Wordpress_Form_Validator
{
    
    protected $message = "This field is required.";    
    
    public function isValid()
    {
        $valid = !empty( $this->value ) && $this->value !== '';
        return $valid;
    }
    
    public function getValidationClasses()
    {
        return array('required');
    }
    
}