<?php

class Snap_Wordpress_Form2_Form
{
  protected $fields=array();
  protected $validators=array();
  protected $errors = array('fields'=>array(), 'form'=>array());
  
  public function add_field($name, $type='text', $config=array())
  {
    $this->fields[$name] = Snap::inst('Snap_Wordpress_Form2')->create_field($type, $name, $config);
  }
  
  public function get_field($name)
  {
    return $this->fields[$name];
  }
  
  public function get_fields()
  {
    return $this->fields;
  }
  
  public function add_validator( $validators, $priority = 'default' )
  {
    if( !isset($this->validators[$priority]) ){
      $this->validators[$priority] = array();
    }
    
  }
  
  public function validate( $data )
  {
    $valid = true;
    $this->set_data( $data );
    
    // first run our field validators
    foreach( $this->fields as $field ){
      if( !$field->validate() ){
        $this->errors['fields'][$field->get_name()] = $field->get_messages();
        $valid = false;
      }
    }
    
    // then go through form level validators
    foreach( $this->validators as $validator ){
      if( !$validator->validate( $this ) ){
        $this->erorrs['form'][$validator->get_name()] = $validator->get_messages();
        $valid = false;
      }
    }
    
    return false;
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
    foreach( $data as $key => $value ){
      if( $this->get_field( $key ) ){
        $this->get_field($key)->set_value($value);
      }
    }
  }
  
}
