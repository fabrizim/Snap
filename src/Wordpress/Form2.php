<?php

class Snap_Wordpress_Form2 extends Snap_Wordpress_Plugin
{
  //
  protected $field_validators;
  protected $form_validators;
  protected $fields;
  protected $decorators;
  
  public function get_field_validators()
  {
    if( !isset($this->field_validators) ){
      $this->field_validators = array();
      do_action_ref_array( 'snap/form/validator/field/register', array(&$this) );
    }
    return $this->field_validators;
  }
  
  public function get_form_validators()
  {
    if( !isset($this->form_validators) ){
      $this->form_validators = array();
      do_action_ref_array( 'snap/form/validator/form/register', array(&$this) );
    }
    return $this->form_validators;
  }
  
  public function get_fields()
  {
    if( !isset($this->fields) ){
      $this->fields = array();
      do_action_ref_array('snap/form/field/register', array(&$this) );
    }
    return $this->fields;
  }
  
  public function get_decorators()
  {
    if( !isset($this->decorators) ){
      $this->decorators = array();
      do_action_ref_array('snap/form/decorator/register', array(&$this) );
    }
    return $this->decorators;
  }
  
  public function register_validator_field($name, $config)
  {
    $this->field_validators[$name] = $config;
  }
  
  public function register_validator_form($name, $config)
  {
    $this->form_validators[$name] = $config;
  }
  
  public function register_field($name, $config)
  {
    $this->fields[$name] = $config;
  }
  
  public function register_decorator($name, $config)
  {
    $this->decorators[$name] = $config;
  }
  
  /**
   * This is a catch all - should have started with this :(
   */
  public function register($class)
  {
    $type = self::get_type( $class );
    $config = self::get_config( $class, $type );
    $name = $config['name'];
    
    if( strpos($type, 'validator_') === 0 )
      $type = implode('_', array_reverse( explode('_', $type) ) );
    
    $types = $type.'s';
    
    $this->{$types}[$name] = $config;
  }
  
  /**
   * @wp.action       snap/form/validator/field/register
   * @wp.priority     5
   */
  public function register_default_field_validators( $form )
  {
    $this->_register_defaults( $form, 'validator_field');
  }
  
  /**
   * @wp.action       snap/form/validator/form/register
   * @wp.priority     5
   */
  public function register_default_form_validators( $form )
  {
    $this->_register_defaults( $form, 'validator_form');
  }
  
  /**
   * @wp.action       snap/form/decorator/register
   * @wp.priority     5
   */
  public function register_default_decorators( $form )
  {
    $this->_register_defaults( $form, 'decorator');
  }
  
  /**
   * @wp.action       snap/form/field/register
   * @wp.priority     5
   */
  public function register_default_fields( $form )
  {
    $this->_register_defaults( $form, 'field');
  }
  
  protected function _register_defaults( $form, $type, $key=false )
  {
    if( !$key ) $key = "{$type}.name";
    $Type = implode('_', array_map('ucfirst', explode( '_', $type )));
    $path = str_replace('_','/',$Type);
    
    
    $defaults = array();
    
    $files = scandir(dirname(__FILE__).'/Form2/'.$path);
    $ignore = array('.','..','Abstract.php');
    
    $fn = 'register_'.$type;
    
    foreach( $files as $file ){
      if( in_array($file, $ignore) || !preg_match('#\.php$#', $file) ) continue;
      $name = basename($file,'.php');
      $class = __CLASS__."_{$Type}_{$name}";
      $config = self::get_config( $class, $type );
      $form->$fn( $config['name'], $config );
    }
  }
  
  public static function get_config( $class, $type=false )
  {
    $reflection = Snap::get( $class );
    
    if( $type === false ) $type = self::get_type( $class );
      
    $config = (array)$reflection->klass($type);
    $config['classname'] = $class;
    
    $config['name'] = $reflection->klass("{$type}.name", array_pop(explode('_',strtolower($class))));
    
    if( is_subclass_of( $class, 'Snap_Wordpress_Form2_Validator_Abstract') ){
      $config['messages'] = $class::$message_templates;
    }
    
    return $config;
  }
  
  public static function get_type( $class )
  {
    $base = __CLASS__;
    foreach(array('Field', 'Validator_Field', 'Validator_Form', 'Decorator') as $parent ){
      if( is_subclass_of( $class, "{$base}_{$parent}_Abstract") ){
        return strtolower( $parent );
      }
    }
    return false;
  }
  
  public function create_field( $type, $name, $options )
  {
    $fields = $this->get_fields();
    if( !isset( $fields[$type] ) ){
      throw new Exception('Field type ['.$type.'] does not exist');
    }
    $class = $fields[$type]['classname'];
    return new $class( $name, $options );
  }
  
}
