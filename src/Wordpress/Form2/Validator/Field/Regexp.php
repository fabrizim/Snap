<?php

/**
 * @validator_field.name                  regex
 * @validator_field.label                 Regular Expression
 *
 * @validator_field.args.exp.label        Expression
 * @validator_field.args.exp.input        text
 * @validator_field.args.exp.description  The expression will use "/" as delimiters
 *
 */
class Snap_Wordpress_Form2_Validator_Field_Regexp extends Snap_Wordpress_Form2_Validator_Field_Abstract
{
  
  const MISMATCH = 'Mismatch';
  
  public static $message_templates = array(
    self::MISMATCH  => 'This field is not in the right format'
  );
  
  public function validate()
  {
    $expression = $this->config->get('arg.exp');
    if( !$expression ) return true;
    if( !preg_match( '/'.$expression.'/', $this->get_field()->get_value() ) ){
      $this->add_message( self::MISMATCH );
      return false;
    }
    return true;
  }
}