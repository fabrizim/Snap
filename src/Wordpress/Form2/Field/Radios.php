<?php

/**
 * @field.name                    radios
 * @field.label                   Radios
 *
 * @field.extra.options.input         textarea
 * @field.extra.options.label         Options
 * @field.extra.options.description   Enter the options for this box, one on each line. Can also be in the format of "value : Text"
 */
class Snap_Wordpress_Form2_Field_Radios extends Snap_Wordpress_Form2_Field_Abstract
{
  public function get_html()
  {
    $attrs = array(
      'name'  => $this->get_name()
    );
    
    $attrs = $this->apply_filters('attributes', $attrs);
    
    $ul = array('tag'=>'ul','attributes' => array('class'=>'radio-list') );
    $items = array();
    
    foreach( $this->get_options() as $value => $text ){
      $id = !count($items) ? $this->get_id() : ($this->get_id().'_'.count($items));
      $inputAttrs = array(
        'name'          => $this->get_name(),
        'type'          => 'radio',
        'value'         => $value,
        'id'            => $id
      );
      
      if( $value === $this->get_value() ){
        $inputAttrs['checked'] = 'checked';
      }
      
      $items[] = array(
        'tag'           => 'li',
        'attributes'    => array(
          'class'         =>'radio'
        ),
        'content'       => $text,
        'children'      => array(
          array(
            'tag'           => 'label',
            'attributes'    => array(
              'for'           => $id
            ),
            'children'      => array(
              array(
                'tag'           => 'input',
                'attributes'    => $inputAttrs
              ),
              array(
                'tag'           => 'span',
                'content'       => $text
              )
            )
          )
        )
      );
    } 
    $ul['children'] = $items;
    $html = Snap_Util_Html::tag( $ul );
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
