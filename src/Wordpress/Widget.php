<?php

class Snap_Wordpress_Widget extends WP_Widget
{
  
  protected $_form;
  protected $_field_map=array();
  
  public function __construct()
  {
    $this->snap = Snap::get($this);
    $options = $this->snap->klass('wp.widget.args', array());
    $name = $this->snap->klass('wp.widget.name', 'base_widget_name');
    $label = $this->snap->klass('wp.widget.label', 'Base Widget Label');
		parent::__construct($name, $label, $options);
	}
  
  public function widget( $args, $instance )
  {
    // this needs to be implemented by the extending class
  }
  
  public function form( $instance )
  {
    $form = $this->_get_form();
    if( !$form->processed() ) $form->setValues( $instance );
    $form->render();
  }
  
  public function update( $new_instance, $old_instance )
  {
    $form = $this->_get_form();
    if( $form->process( $new_instance ) ){
      return $form->getValues();
    }
    return $old_instance;
  }
  
  protected function _get_form()
  {
    $this->_form = new Snap_Wordpress_Form();
    // get the properties marked as form fields
    $properties = $this->snap->getRegistry()->get('property');
    foreach( array_keys($properties) as $name ){
      if( ($config = $this->snap->property($name, 'wp.widget.field')) ){
        if( $config === true ) $config = array();
        $this->_form->addField($name, $config);
				$this->_form->field($name)->setCfg( 'inputName', $this->get_field_name( $name ) );
        //  $this->_form->field($name)->setValue( $this->$key );
      $this->_form->field($name)->setId( $this->get_field_id( $name ) );
      }
    }
    return $this->_form;
  }
  
}
