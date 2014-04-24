<?php

abstract class Snap_Wordpress_Form2_Validator_Abstract
{
  public static $message_templates = array();
  
  protected $name = 'abstract';
  protected $messages = array();
  protected $variables = array();
  
  public function __construct( $config=array() )
  {
    $this->config = new Snap_Registry();
    $this->config->import( (array) $config );
  }
  
  public function set_config( $config, $value=null )
  {
    if( isset($value) ) $this->config->set($config, $value);
    else $this->config = $config;
  }
  
  public function get_config( $key=null, $default=false )
  {
    return isset($key ) ? $this->config->get($key, $default) : $this->config;
  }
  
  public function get_name()
  {
    return $this->name;
  }
  
  public function add_message( $key )
  {
    $this->messages[$key] = $this->get_message( $key );
  }
  
  public function get_message( $key )
  {
    $message = $this->config->get( 'message.'.$key, isset(self::$message_templates[$key]) ? self::$message_templates[$key] : '' );
    // replace message variables
    foreach( $this->variables as $key => $val ){
      if( is_array( $val ) ) $val = '['.implode(', ',$val).']';
      $message = str_replace( "%$key%", $val );
    }
    return $message;
  }
  
  public function set_variable( $name, $value )
  {
    $this->variables[$name] = $value;
  }
  
  /**
   * This function must return true or false and set
   * the message if there is an error
   */
  public abstract function validate();
}
