<?php

abstract class Snap_Wordpress_Form2_Validator_Field_Abstract extends Snap_Wordpress_Form2_Validator_Abstract
{
  protected $value;
  
  public function __construct( $config=array(), $field=null )
  {
    parent::__construct( $config );
    if( $field ) $this->set_field( $field );
    $this->name = Snap::get($this)->klass('validator_field.name');
  }
  
  public function set_field( $field )
  {
    $this->field = $field;
    $this->variables['label'] = $field->get_label();
    $this->variables['name'] = $field->get_name();
  }
  
  public function get_field()
  {
    return $this->field;
  }
  
  public function set_value($value)
  {
    $this->value = $value;
  }
  
  public function get_value()
  {
    return $this->value;
  }

}
