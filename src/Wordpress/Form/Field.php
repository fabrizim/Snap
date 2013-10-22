<?php

class Snap_Wordpress_Form_Field
{
    
    static $id=0;
    
    protected $name;
    protected $value;
    protected $lastValue;
    protected $config;
    protected $form;
    protected $errors=array();
    protected $options;
    
    public function __construct( $name, $config = false )
    {
        $this->name = $name;
        $this->config = new Snap_Registry();
        $this->config->import( $config );
        if( ($options=$this->cfg('options', false ) ) ){
            $this->options = $options;
        }
        if( $this->cfg('id') ){
            $this->id = $this->cfg('id');
        }
        else {
            $this->id = self::_id();
        }
    }
    
    protected static function _id()
    {
        return 'snap_field_'.(++self::$id);
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId( $id )
    {
        $this->id = $id;
    }
    
    public function setName( $name )
    {
        $this->name = $name;
    }
    
    public function getInputName()
    {
        return $this->cfg('inputName', $this->getName() );
    }
    
    public function cfg( $name, $default=false )
    {
        return $this->config->get( $name, $default );
    }
    
    public function setCfg( $name, $value ){
        return $this->config->set( $name, $value );
    }
    
    public function setForm( $form )
    {
        $this->form = $form;
    }
    
    public function getForm()
    {
        return $this->form;
    }
    
    public function getType()
    {
        return $this->config->get( 'type', 'text' );
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getLabel()
    {
        return $this->config->get('label', $this->name);
    }
    
    public function getDescription()
    {
        return $this->config->get('description', '');
    }
    
    public function setValue( $value, $source=array() )
    {
        if( isset($this->value) ) $this->lastValue = $this->value;
        $this->value = $value;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function getValueFormatted()
    {
        return $this->format()->getValue();
    }
    
    public function isModified()
    {
        return $this->lastValue == $this->value;
    }
    
    public function isRequired()
    {
        return $this->cfg('validator.notEmpty', false);
    }
    
    public function validate()
    {
        $validators = $this->cfg('validator', array());
        if( $this->getValue() == '' && !$this->isRequired() ) return true; 
        foreach( $validators as $key => $message ){
            if( !$message ) continue;
            $validator = Snap_Wordpress_Form_Validator_Factory::get($key);
            $validator->setField( $this );
            if( is_string( $message ) ){
                $validator->setMessage( $message );
            }
            if( !$validator->isValid() ){
                $this->errors[] = $validator->getError();
            }
        }
        return !count( $this->errors );
    }
    
    public function filter()
    {
        $validators = $this->cfg('validator', array());
        foreach( $validators as $key => $message ){
            $validator = Snap_Wordpress_Form_Validator_Factory::get($key);
            $validator->setField( $this );
            $this->value = $validator->filter();
        }
        return $this;
    }
    
    public function format()
    {
        $validators = $this->cfg('validator', array());
        foreach( $validators as $key => $message ){
            $validator = Snap_Wordpress_Form_Validator_Factory::get($key);
            $validator->setField( $this );
            $this->value = $validator->format();
        }
        return $this;
    }
    
    public function getOptions()
    {
        if( isset( $this->options ) ) return apply_filters('snap_form_field_options', $this->options, $this);
        if( !$this->form ){
            $this->options = array();
        }
        else{
            $this->options = $this->form->getOptions( $this->getName() );
        }
        return apply_filters('snap_form_field_options', $this->options, $this);
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getError()
    {
        return count($this->errors) ? $this->errors[0] : '';
    }
    
    public function hasError()
    {
        return !!count($this->errors);
    }
    
    public function render( $options=array() )
    {
        $defaults = array(
            'renderer'      => 'Snap_Form_Field_Renderer_Default'
        );
        $options = array_merge( $defaults, $options );
        extract( $options );
        
        $renderer = Snap::singleton( $renderer );
        
        return $renderer->renderField( $this );
    }
}