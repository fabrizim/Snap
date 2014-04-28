<?php

abstract class Snap_Wordpress_Form2_Decorator_Abstract extends Snap_Wordpress_Plugin
{
  /**
   * @wp.filter       snap/form/field/html
   */
  public function decorate( $html, $field )
  {
    return $html;
  }
}