<?php

class Snap_Wordpress_Form2_Decorator_Default extends Snap_Wordpress_Form2_Decorator_Abstract
{
  /**
   * @wp.filter       snap/form/field/html
   */
  public function decorate( $html, $field )
  {
    
    if( ($label = $field->get_label()) ){
      // lets create a label and add description...
      $label = Snap_Util_Html::tag('label', array(
        'for'         => $field->get_id()
      ), $label );
      
      $html = $label.' '.$html;
    }
    
    if( ($description = $field->get_config('description')) ){
      $html.=Snap_Util_Html::tag('div', array(
        'class'       => 'description'
      ), $description);
    }
    
    return $html;
  }
}