<?php

/**
 * @validator_field.name                email
 * @validator_field.label               Valid Email Address
 *
 */
class Snap_Wordpress_Form2_Validator_Field_Email extends Snap_Wordpress_Form2_Validator_Field_Abstract
{
  
  const INVALID_EMAIL = 'Invalid Email';
  
  public static $message_templates = array(
    self::INVALID_EMAIL  => 'Please enter a valid email address'
  );
  
  public function validate()
  {
    if( false === filter_var( $this->field->get_value(), FILTER_VALIDATE_EMAIL) ){
      $this->add_message( self::INVALID_EMAIL );
      return false;
    }
    return true;
  }
  
  public function get_jquery_validate_config()
  {
    return array(
      'email' => array(
        'message' => $this->get_message( self::INVALID_EMAIL )
      )
    );
  }
}