<?php

class Snap_Wordpress_Form2_Decorator_Bootstrap3 extends Snap_Wordpress_Form2_Decorator_Abstract
{
  /**
   * @wp.filter       snap/form/field/classes
   */
  public function classes( $classes, $field )
  {
    if( $field->get_style() != 'checkbox' )
      $classes[] = 'form-control';
    return $classes;
  }
  
  /**
   * @wp.filter       snap/form/field/html
   */
  public function decorate( $html, $field )
  {
    
    $children = array();
    
    if( in_array( $field->get_style(), array('hidden', 'raw') ) ) return $html;
    
    if( $field->get_style() != 'checkbox' && ($label = $field->get_label()) ){
      // lets create a label and add description...
      $children[] = Snap_Util_Html::tag('label', array(
        'for'         => $field->get_id(),
        'class'       => 'control-label'
      ), $label );
    }
    
    $children[] = $html;
    
    if( ($description = $field->get_config('description')) ){
      $children[] = Snap_Util_Html::tag('span', array(
        'class'       => 'help-block'
      ), $description);
    }
    
    if( !$field->is_valid() ){
      // get field errors
      $errors = $field->get_errors();
      $values = array_values( $errors );
      $messages = array_values( $values[0] );
      $children[] = Snap_Util_Html::tag('span', array(
        'class'       => 'help-block'
      ), $messages[0]);
    }
    
    $classes = array('form-group');
    if( !$field->is_valid() ) $classes[] = 'has-error';
    if( ($style = $field->get_style()) ) $classes[] = $style;
    
    return Snap_Util_Html::tag('div', array(
      'class'   => $classes
    ), $children);
    
  }
}