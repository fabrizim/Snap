<?php

/**
 * @validator_field.name                required
 * @validator_field.label               Required Field
 *
 * @validator_field.messages.default    This field is required
 */
class Snap_Wordpress_Form2_Validator_Field_Required extends Snap_Wordpress_Form2_Validator_Field_Abstract
{
  
  const REQUIRED = 'Default';
  
  public static $message_templates = array(
    self::REQUIRED   => 'This field is required'
  );
  
  public function validate()
  {
    if( !$this->field->get_value() ){
      $this->set_message( self::REQUIRED );
      return false;
    }
    else return true;
  }
}