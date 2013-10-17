<?php

class Snap_Wordpress_Shortcodes extends Snap_Wordpress_Plugin
{
  
  protected $fn_map = array();
  protected $_processed = array();
  protected $_log = array();
  protected $_shortcode_placeholders = array();
  protected $_html_placeholders = array();
  protected $_placeholder_shortcode ='[:(shortcode_placeholder):]';
  protected $_placeholder_html ='[:(html_placeholder):]';
  
  protected function log()
  {
    $args = func_get_args();
    $this->_log[] = print_r($args,1);
  }
  
  /**
   * We need to process content prior to the autop filter, but
   * without moving the default order of autop (otherwise it will
   * break shortcodes for other plugins, like Gravity Forms.)
   *
   * http://wpforce.com/prevent-wpautop-filter-shortcode/
   *
   * @wp.filter       ["acf_the_content","the_content"]
   * @wp.priority     7
   */
  public function process_content_before_autop( $content )
  {
    global $shortcode_tags;
    $orig_shortcode_tags = $shortcode_tags;
    remove_all_shortcodes();
    
    // lets add all of our own shortcode tags
    $reflectionClass = new ReflectionClass( $this );
    foreach( $reflectionClass->getMethods( ReflectionMethod::IS_PUBLIC ) as $method ){
      $name = $method->getName();
      if( ($shortcode = $this->snap->method($name, 'wp.shortcode', false)) !== false ){
        //$this->_wp_add('shortcode', $name);
        $shortcode = $shortcode === true ? $name : $shortcode;
        $this->fn_map[$shortcode] = $name;
        add_shortcode( $shortcode, array( &$this, 'shortcode') );
      }
    }
    
    $content = do_shortcode( $content );
    $shortcode_tags = $orig_shortcode_tags;
    // add our dummy shortcodes in.
    foreach( array_keys( $this->fn_map ) as $shortcode ) add_shortcode( $shortcode, array(&$this, 'dummy'));
    if( !count( $this->_processed ) ) return $content;
    return $content;
  }
  
  public function dummy($atts=array(), $content=array())
  {
    return $content;
  }
  
  protected function clean_markup( $markup )
  {
    // leave other shortcodes alone.
    $re = '/\[([a-zA-Z_-]+)\].*?\[\/\1\]/s';
    $markup = preg_replace_callback( $re, array(&$this, 'replace_with_shortcode_placeholders'), $markup);
    $markup = preg_replace_callback( '/\<(pre|textarea|code).*?\<\/\1\>/si', array(&$this, 'replace_with_html_placeholders'), $markup);
    $markup = preg_replace('/^ */m',"",$markup);
    $markup = preg_replace_callback('/'.preg_quote($this->_placeholder_shortcode).'/', array(&$this, 'replace_shortcode_placeholders'), $markup);
    $markup = preg_replace_callback('/'.preg_quote($this->_placeholder_html).'/', array(&$this, 'replace_html_placeholders'), $markup);
    return $markup;
  }
  
  public function replace_with_shortcode_placeholders($content)
  {
    $this->_shortcode_placeholders[] = $content[0];
    return $this->_placeholder_shortcode;
  }
  
  public function replace_with_html_placeholders($content)
  {
    $this->_html_placeholders[] = $content[0];
    return $this->_placeholder_html;
  }
  
  public function replace_shortcode_placeholders()
  {
    $val = array_shift( $this->_shortcode_placeholders );
    return $val;
  }
  
  public function replace_html_placeholders()
  {
    $val = array_shift( $this->_html_placeholders );
    return $val;
  }
  
  public function shortcode( $atts, $content='', $tag='')
  {
    
    $fn = $this->fn_map[preg_replace('/\d+$/', '', $tag)];
    $this->_processed[] = $tag;
    ob_start();
    $this->$fn( $atts, $content, $tag );
    return $this->clean_markup( ob_get_clean() );
  }
  
  
  /**
   * Return a string of html attributes from an associative array
   *
   * @param array Associative array of attributes
   * @return string HTML attribute string
   */
  protected function to_attrs( $ar )
  {
    $attrs = array();
    
    foreach( $ar as $key => $val ){
      if( !$key ) continue;
      if( strpos($key, 'data_') === 0 ){
        $key = 'data-'.substr($key, 5);
      }
      $val = esc_attr( $val );
      $attrs[] = "$key=\"$val\"";
    }
    return implode(' ', $attrs);
  }
  
}