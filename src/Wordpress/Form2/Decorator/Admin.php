<?php

class Snap_Wordpress_Form2_Decorator_Admin extends Snap_Wordpress_Form2_Decorator_Abstract
{
  /**
   * @wp.filter       snap/form/field/classes
   */
  public function classes( $classes, $field )
  {
    if( $field->get_style() != 'checkbox' )
      $classes[] = 'regular-text';
    return $classes;
  }
  
  /**
   * @wp.filter       snap/form/field/html
   */
  public function decorate( $html, $field )
  {
    
    if( in_array( $field->get_style(), array('hidden', 'raw') ) ){
      
      return '<tr style="display: none;"><td colspan="2">'.$html.'</td></tr>';
    }
    
    $row = '<tr><th scope="row">';
    
    if( ($label = $field->get_label()) ){
      // lets create a label and add description...
      $row.= Snap_Util_Html::tag('label', array(
        'for'         => $field->get_id(),
        'class'       => 'control-label'
      ), $label );
    }
    
    $row.='</th><td>'.$html.'</td></tr>';
    return $row;
    
  }
}