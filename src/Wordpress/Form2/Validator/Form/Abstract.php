<?php

abstract class Snap_Wordpress_Form2_Validator_Form_Abstract extends Snap_Wordpress_Form2_Validator_Abstract
{
  public function __construct( $config=array(), $form=null )
  {
    parent::__construct( $config );
    if( $form ) $this->form = $form;
  }
  
  public function set_form( $form )
  {
    $this->form = $form;
  }
  
  public function get_form()
  {
    return $this->form;
  }
}
