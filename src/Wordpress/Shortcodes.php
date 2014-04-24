<?php

class Snap_Wordpress_Shortcodes extends Snap_Wordpress_Plugin
{
  
  protected $fn_map = array();
  protected $_processed = array();
  protected $_log = array();
  protected $_blocklevel = array();
  
  protected function log()
  {
    $args = func_get_args();
    $this->_log[] = print_r($args,1);
  }
  
  /**
   * @wp.filter
   * @wp.priority 10
   */
  public function the_content( $content )
  {
    if( count( $this->_blocklevel ) === 0 ) return $content;
    
    $block = join("|", array_map( function($tag){
      return $tag.'\d*';
    }, $this->_blocklevel));
    
    // opening tag
    $content = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);
    
    // closing tag
    $content = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]", $content);
    
    return $content;
  }
  
  protected function _wp_add( $type, $name )
  {
    if( $type !== 'shortcode' ) return parent::_wp_add( $type, $name );
    
    $fn = "add_$type";
    $_name = $this->snap->method($name, "wp.$type", false);
    
    $callback = array( &$this, 'shortcode' );
    
    $arguments = array();
    
    if( is_array( $_name ) ) foreach( $_name as $n )
      $arguments[] = array( $n, $callback );
        
    else
      $arguments[] = array( is_string( $_name ) ? $_name : $name, $callback );
    
    
    foreach( $arguments as $a ){
      $this->fn_map[$a[0]] = $name;
      if( !$this->snap->method($name, "wp.inline", false) ){
        $this->_blocklevel[] = $a[0];
      }
      call_user_func_array( $fn, $a );
    }
    
  }
  
  public function shortcode( $atts, $content='', $tag='')
  {
    
    $fn = $this->fn_map[preg_replace('/\d+$/', '', $tag)];
    $this->_processed[] = $tag;
    ob_start();
    $this->$fn( $atts, $content, $tag );
    return ob_get_clean();
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