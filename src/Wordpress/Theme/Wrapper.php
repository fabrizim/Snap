<?php

class Snap_Wordpress_Theme_Wrapper extends Snap_Wordpress_Plugin
{
  
  protected $main_template;
  protected $base;
  
  /**
   * @wp.filter             template_include
   * @wp.priority           100
   */
  public function template_include( $main )
  {
    if( !is_string( $main ) ) return $main;
    
    $this->main_template = $main;
    $this->base = basename( $this->main_template, '.php');
    
    if( $this->base === 'index'){
      $this->base = false;
    }
    
    $this->init();
    return $this->locate_template();
  }
  
  public function init( $template = 'base.php' )
  {
    $this->slug = basename( $template, '.php');
    $this->templates = [$template];
    
    if( $this->base ){
      $str = substr($template, 0, -4);
      array_unshift( $this->templates, sprintf( $str.'-%s.php', $this->base) );
    }
  }
  
  public function locate_template()
  {
    $this->templates = apply_filters('snap/theme/wrap_'.$this->slug,
      apply_filters('snap/theme/wrap', $this->templates)
    );
    return locate_template( $this->templates );
  }
  
  public function content()
  {
    include $this->main_template;
  }
}
