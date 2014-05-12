<?php

/**
 * @validator_field.name                match
 * @validator_field.label               Match another Field
 *
 * @validator_field.args.source.label   Source Field
 * @validator_field.args.source.input   field
 *
 */
class Snap_Wordpress_Form2_Validator_Field_Match extends Snap_Wordpress_Form2_Validator_Field_Abstract
{
  
  const MISMATCH = 'Mismatch';
  
  public static $message_templates = array(
    self::MISMATCH  => 'This field does not match %source%'
  );
  
  public function validate()
  {
    $source = $this->get_source();
    if( !$source ) return true;
    
    $this->variables['source'] = $source->get_config('label');
    
    if( $source->get_value() != $this->field->get_value() ){
      $this->add_message( self::MISMATCH );
      return false;
    }
    return true;
  }
  
  public function get_jquery_validate_config()
  {
    if( !$this->get_source() ) return false;
    
    return array(
      'equalTo' => array(
        'arg'     => '#'.$this->get_source()->get_id(),
        'message' => $this->get_message( self::MISMATCH )
      )
    );
  }
  
  protected function get_source()
  {
    return $this
      ->get_field()
      ->get_form()
      ->get_field( $this->get_config('arg.source') );
  }
}