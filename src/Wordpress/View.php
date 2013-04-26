<?php

class Snap_Wordpress_View
{
  public function __construct($type, $tmpl)
  {
    $this->locals = array();
    $this->tmpl = Snap_Wordpress_Template::load($type, $tmpl, false);
  }
  
  public function set($name, $value=null)
  {
    if( $value === null && is_array($name) ){
      $this->locals = array_merge( $this->locals, $name);
    }
    else if( isset($value) ){
      $this->locals[$name] = $value;
    }
  }
  
  public function render()
  {
    extract( $this->locals );
    include( $this->tmpl );
  }
}