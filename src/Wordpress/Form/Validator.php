<?php

class Snap_Wordpress_Form_Validator
{
    protected $error;
    
    protected $message;
    
    protected $value;
    
    public function __construct()
    {
        
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
}