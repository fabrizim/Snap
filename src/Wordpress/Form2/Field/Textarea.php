<?php

/**
 * @field.name      textarea
 * @field.label     Textarea
 */
class Snap_Wordpress_Form2_Field_Textarea extends Snap_Wordpress_Form2_Field_Abstract
{
  public function get_html()
  {
    $attrs = array(
        'name'  => $this->get_name()
      , 'id'    => $this->get_id()
      , 'class' => $this->get_classes()
      , 'title' => $this->get_config('label')
    );
    
    $attrs = $this->apply_filters('attributes', $attrs);
    
    $html = '<textarea '.$this->to_attributes( $attrs ).'>'
            .$this->get_value()
            .'</textarea>';
    return $this->apply_filters('html', $html);
    
  }
}
