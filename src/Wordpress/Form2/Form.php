<?php

class Snap_Wordpress_Form2_Form
{
  protected $fields=array();
  protected $valid = true;
  protected $validated = false;
  protected $validators=array();
  protected $errors = array('fields'=>array(), 'form'=>array());
  protected $config;
  
  public function __construct( $config=array() )
  {
    $this->config = new Snap_Registry();
    if( $config ) $this->config->import( $config );
  }
  
  public function get_config()
  {
    return $this->config;
  }
  
  public function add_field($name, $type='text', $config=array())
  {
    $this->fields[$name] = Snap::inst('Snap_Wordpress_Form2')->create_field($type, $name, $config);
    $this->fields[$name]->set_form( $this );
  }
  
  public function get_field($name)
  {
    return $this->fields[$name];
  }
  
  public function get_fields()
  {
    return $this->fields;
  }
  
  public function get_data()
  {
    $data = array();
    foreach( $this->fields as $name => $field ){
      $data[$name] = $field->get_value();
    }
    return $data;
  }
  
  public function reset()
  {
    $this->valid = true;
    $this->validated = false;
    foreach( $this->fields as $field ){
      $field->reset();
    }
  }
  
  public function add_validator( $validator, $priority = 'default' )
  {
    $validator->set_form( $this );
    $this->validators[$validator->get_name()] = $validator;
  }
  
  public function validate( $data = null )
  {
    $this->valid = true;
    if( $data !== null ) $this->set_data( $data );
    
    // first run our field validators
    foreach( $this->fields as $field ){
      if( !$field->validate() ){
        $this->errors['fields'][$field->get_name()] = $field->get_errors();
        $this->valid = false;
      }
    }
    
    // then go through form level validators
    foreach( $this->validators as $validator ){
      if( !$validator->validate() ){
        $this->errors['form'][$validator->get_name()] = $validator->get_messages();
        $this->valid = false;
      }
    }
    $this->validated = true;
    return $this->valid;
  }
  
  public function has_validated()
  {
    return $this->validated;
  }
  
  public function is_valid()
  {
    return $this->valid;
  }
  
  public function get_field_errors()
  {
    return $this->errors['fields'];
  }
  
  public function get_form_errors()
  {
    return $this->errors['form'];
  }
  
  public function set_data( $data )
  {
    foreach( (array)$data as $key => $value ){
      if( isset($this->fields[$key] ) ){
        $this->get_field($key)->set_value($value);
      }
    }
  }
  
}
