<?php

/**
 * @field.name                        checkbox
 * @field.label                       Checkbox
 *
 * @field.extra.boxlabel.input        textarea
 * @field.extra.boxlabel.label        Box Label
 * @field.extra.boxlabel.class        short
 * 
 * @field.extra.inputvalue.input      text
 * @field.extra.inputvalue.label      Input Value
 * 
 */
class Snap_Wordpress_Form2_Field_Checkbox extends Snap_Wordpress_Form2_Field_Abstract
{
  public function get_html()
  {
    $v = $this->get_config('extra.inputvalue', '1');
    if( !$v ) $v = 1;
    
    $attrs = array(
        'name'      => $this->get_name()
      , 'id'        => $this->get_id()
      , 'type'      => $this->get_type()
      , 'class'     => $this->get_classes()
      , 'title'     => $this->get_config('label')
      , 'value'     => $this->get_config('extra.inputvalue', '1')
      , 'checked'   => $this->get_value() == $v
    );
    
    $attrs = $this->apply_filters('attributes', $attrs);
    
    $html = '<label for="'.$this->get_id().'"><input '.$this->to_attributes( $attrs ).' /> '.
      $this->get_config('extra.boxlabel', $this->get_config('label')).'</label>';
    return $this->apply_filters('html', $html);
    
  }
}
