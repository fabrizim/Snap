<?php

abstract class Snap_Wordpress_Form2_Validator_Field_Abstract extends Snap_Wordpress_Form2_Validator_Abstract
{
  
  public function __construct( $config=array(), $field=null )
  {
    parent::__construct( $config );
    if( $field ) $this->field = $field;
  }
  
  public function set_field( $field )
  {
    $this->field = $field;
  }
  
  public function get_field()
  {
    return $this->field;
  }

}
