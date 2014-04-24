<?php

abstract class Snap_Wordpress_Form2_Field_Abstract
{
  protected static $id=0;
  protected $name;
  protected $config;
  protected $value;
  protected $form;
  protected $messages;
  protected $description;
  protected $classes = array();
  protected $validators = array();
  protected $required = false;
  
  public function __construct( $name='', $config=array() )
  {
    $this->id = 'sfid_'.(++self::$id);
    $this->name = $name;
    $this->type = Snap::get($this)->klass('field.name');
    $this->config = new Snap_Registry(false);
    if( $config ) $this->config->import( (array)$config );
    $this->init();
  }
  
  protected function init()
  {
    
  }
  
  public function add_validator( $validator )
  {
    $this->validators = array_merge( $this->validators, array($validator) );
    if( $validator instanceof Snap_Wordpress_Form2_Validator_Required ){
      $this->required = true;
    }
  }
  
  public function set_required( $message=null )
  {
    $this->add_validator(new Snap_Wordpress_Form2_Validator_Required(array(
      'message' => $message
    )));
  }
  
  public function validate()
  {
    $valid = true;
    foreach( $this->validators as $validator ){
      if( $this->required || $this->get_value() !== ''){
        if( !$validator->validate( $this ) ){
          $valid = false;
        }
      }
    }
    return $valid;
  }
  
  public function set_form( $form )
  {
    $this->form = $form;
  }
  
  public function get_form()
  {
    return $this->form;
  }
  
  public function get_type()
  {
    return $this->type;
  }
  
  public function get_id()
  {
    return $this->id;
  }
  
  public function set_id( $id )
  {
    $this->id = $id;
  }
  
  public function get_name()
  {
    return $this->name;
  }
  
  public function set_name( $name )
  {
    $this->name = $name;
  }
  
  public function get_label()
  {
    return $this->label;
  }
  
  public function set_label( $label )
  {
    $this->label = $label;
  }
  
  public function get_value()
  {
    return $this->value;
  }
  
  public function set_value( $value )
  {
    $this->value = $value;
  }
  
  public function get_config( $key=null )
  {
    if( $key ){
      return $this->apply_filters('config', $this->config->get( $key ));
    }
    return $this->config;
  }
  
  public function set_config( $config, $value=null )
  {
    if( isset($value) ){
      $this->config->set($config, $value );
    }
    else {
      $this->config = $config;
    }
  }
  
  public function add_class()
  {
    $this->classes = array_merge( func_get_args(), $this->classes );
  }
  
  public function get_classes()
  {
    return (array) $this->apply_filters( 'classes', $this->classes );
  }
  
  public function get_html()
  {
    $attrs = array(
        'name'  => $this->get_name()
      , 'type'  => $this->get_type()
      , 'value' => $this->get_value()
      , 'id'    => $this->get_id()
      , 'class' => $this->get_classes()
      , 'title' => $this->get_config('label')
    );
    
    $attrs = $this->apply_filters('attributes', $attrs);
    
    $html = Snap_Util_Html::tag('input', $attrs);
    return $this->apply_filters('html', $html);
    
  }
  
  protected function apply_filters( $filter, $value )
  {
    $filter = 'snap/form/field/'.$filter;
    $value = apply_filters($filter, $value, $this);
    $value = apply_filters($filter.'?type='.$this->get_type(), $value, $this);
    $value = apply_filters($filter.'?id='.$this->get_id(), $value, $this);
    return $value;
  }
  
  protected function to_attributes( $array )
  {
    $atts = array();
    foreach( $array as $name => $val ){
      if( is_array($val) ) $val = implode(' ', $val);
      if( $val ) $atts[] = $name.'="'.esc_attr($val).'"';
    }
    return implode(' ', $atts);
  }
}