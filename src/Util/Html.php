<?php

class Snap_Util_Html
{
  public static $void_elements = array(
    'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img',
    'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'
  );
  
  /**
   * This function creates HTML tags programatically
   */
  public static function tag( $tag, $attributes=array(), $content='', $close = true )
  {
    
    if( is_string( $tag ) && strpos($tag, '<') !== false) return $tag;
    
    if( is_array( $tag ) || is_object( $tag ) ){
      $tag = (array)$tag;
      if( !isset($tag['tag']) ){
        throw new Exception('Invalid argument passed to Snap_Util_Html::tag '.print_r($tag,1));
      }
      $attributes = @$tag['attributes'];
      $content = @$tag['content'];
      
      // lets allow "children" in the definition as well
      if( isset( $tag['children'] ) ){
        $content = $tag['children'];
      }
      $tag = $tag['tag'];
    }
    
    $str = "<$tag ";
    if( $attributes && count($attributes) ) $str.= self::attributes( $attributes );
    if( in_array( $tag, self::$void_elements ) ){
      return $str.' />';
    }
    $str.= '>';
    
    if( $content ){
      
      if( is_array( $content ) ){
        // treat these as children
        foreach( $content as $child ){
          $str.= self::tag( $child );
        }
      }
      else {
        $str.= $content;
      }
    }
    if( $close ){
      $str.="</$tag>";
    }
    return $str;
  }
  
  public static function attributes( $array )
  {
    $atts = array();
    foreach( (array)$array as $name => $val ){
      if( is_array($val) ) $val = implode(' ', $val);
      if( $val !== false ) $atts[] = $name.'="'.esc_attr($val).'"';
    }
    return implode(' ', $atts);
  }
}
