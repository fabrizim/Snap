<?php

/**
 * @field.name                    select
 * @field.label                   Select
 *
 * @field.extra.options.input         textarea
 * @field.extra.options.label         Options
 * @field.extra.options.description   Enter the options for this box, one on each line. Can also be in the format of "value : Text"
 */
class Snap_Wordpress_Form2_Field_Select extends Snap_Wordpress_Form2_Field_Abstract
{
  public function get_html()
  {
    $attrs = array(
        'name'  => $this->get_name()
      , 'type'  => $this->get_type()
      , 'id'    => $this->get_id()
      , 'class' => $this->get_classes()
      , 'title' => $this->get_config('label')
    );
    
    $attrs = $this->apply_filters('attributes', $attrs);
    
    $select = array('tag'=>'select','attributes' => $attrs);
    $options = array();
    
    foreach( $this->get_options() as $value => $text ){
      $options[] = array(
        'tag'           => 'option',
        'attributes'    => array(
            'value'         => $value
          , 'selected'      => $this->get_value() === $value ? 'selected' : false
        ),
        'content'       => $text
      );
    }
    $select['children'] = $options;
    $html = Snap_Util_Html::tag( $select );
    return $this->apply_filters('html', $html);
  }
  
  public function get_options()
  {
    $options = $this->get_config('options');
    if( !$options ){
      // check extra
      $text = $this->get_config('extra.options');
      if( !$text ){
        return array();
      }
      $options = array();
      foreach( explode("\n", $text) as $option ){
        if( strpos( $option,' : ' ) !== false ){
          list( $val, $text ) = explode(' : ', $option, 2);
          $options[$val] = $text;
        }
        else {
          $options[$option] = $option;
        }
      }
      $this->set_config( 'options', $options );
    }
    return $options;
  }
}
