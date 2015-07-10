<?php

class Snap_Wordpress_Theme extends Snap_Wordpress_Plugin
{
  
  public static $sidebar_config = array(
    'description'   => '',
    'class'         => '',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => "<h3 class=\"widget-title\">",
    'after_title'   => "</h3>"
  );
  
  /**
   * Simple function to initialize controllers based on context
   */
  public function init( $name='Theme' )
  {
    //self::$configs[$name] = array_merge( $this->get_defaults(), $config );
    self::inst( $name.'_Base' );
    if( is_admin() ) self::inst( $name.'_Admin' );
    else self::inst( $name.'_Front' );
  }
  
  /**
   * Instantiate and return a class only if it exists.
   *
   * @param string classname
   * @return mixed Instantiated object or false if class does not exist
   */
  public static function inst( $name )
  {
    if( class_exists( $name ) ) return Snap::inst( $name );
    return false;
  }
  
  //static $configs=array();
  public function __construct()
  {
    parent::__construct();
  }
  
  /**
   * @wp.action
   */
  public function after_setup_theme()
  {
    $this->register_classes();
    $this->register_navs();
    $this->register_sidebars();
    $this->add_theme_support();
    
    if( !is_admin() ) Snap::inst('Snap_Wordpress_Theme_Wrapper');
  }
  
  protected function register_classes()
  {
    $post_types = apply_filters('snap/theme/post_types', []);
    $taxonomies = apply_filters('snap/theme/taxonomies', []);
    $plugins    = apply_filters('snap/theme/plugins', []);
    
    $all = array_merge( $post_types, $taxonomies, $plugins );
    foreach( $all as $className ){
      self::inst( $className );
    }
  }
  
  protected function register_navs()
  {
    $navs       = apply_filters('snap/theme/navs', []);
    foreach( $navs as $key => $description ){
      register_nav_menu( $key, $description );
    }
  }
  
  protected function register_sidebars()
  {
    $sidebars   = apply_filters('snap/theme/sidebars', []);
    foreach( $sidebars as $key => $config ){
      if( is_string($config) ){
        $config = ['name'=>$config];
      }
      $config = array_merge(self::$sidebar_config, $config, [
        'id'        => $key
      ]);
      register_sidebar($config);
    }
  }
  
  protected function add_theme_support()
  {
    $defaults = [
      'title-tag'         => true,
      'post-thumbnails'   => ['post'],
      'post-formats'      => ['gallery','image','quote'],
      'html5'             => true
    ];
    $support = apply_filters('snap/theme/add_theme_support', $defaults);
    foreach( $support as $key => $args ){
      if( $args === true || $args === null ){
        add_theme_support( $key );
      }
      else {
        add_theme_support( $key, $args );
      }
    }
  }
  
}
