<?php

class Snap_Wordpress_Form_Validator
{
    protected $error;
    protected $message;
    protected $value;
    protected $field;
    
    public function __construct()
    {
        
    }
    
    public function getValidationClasses()
    {
        return array();
    }
    
    public function setField( $field )
    {
        $this->field = $field;
        $this->value = $field->getValue();
    }
    
    public function setValue( $value )
    {
        $this->value = $value;
    }
    
    public function isValid()
    {
        return true;
    }
    
    public function getError()
    {
        return $this->message;
    }
    
    public function setMessage( $message )
    {
        $this->message = $message;
    }
    
    public function filter()
    {
        return $this->value;
    }
    
    public function format()
    {
        return $this->value;
    }
}