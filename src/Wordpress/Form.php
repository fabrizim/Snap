<?php

class Snap_Wordpress_Form
{
    protected $snap;
    
    protected $fields = array();
    
    protected $groups = array('default' => array());
    
    protected $success = false;
    
    protected $errors = array();
    
    protected $formErrors = array();
    
    protected $_processed = false;
    
    public function __construct()
    {
        $this->snap = Snap::get( $this );
        $this->initFields();
    }
    
    protected function initFields()
    {
        $reflectionClass = new ReflectionClass( $this );
        foreach( $reflectionClass->getProperties( ReflectionProperty::IS_PUBLIC ) as $property ){
            $name = $property->getName();
            $this->addField( $name, $this->snap->property( $name, 'form.field', array() ) );
        }
    }
    
    public function addField( $name, $config=array() )
    {
        $cls = 'Snap_Wordpress_Form_Field';
        $type = @$config['type'];
        if( $type ){
            $Type = strtoupper( substr( $type, 0, 1 ) ) . substr( $type, 1 );
            if( class_exists( $cls.'_'.$Type ) ) $cls.= '_'.$Type;
        }
        $field = $this->fields[$name] = new $cls( $name, $config );
        $field->setForm( $this );
        $group = $field->cfg( 'group', 'default' );
        if( !isset( $this->groups[ $group ] ) ) $this->groups[ $group ] = array();
        $this->groups[ $group ][] = $name;
    }
    
    public function addFormError( $error )
    {
        $this->formErrors[] = $error;
    }
    
    public function getFormErrors()
    {
        return $this->formErrors;
    }
    
    public function hasFormErrors()
    {
        return !!count($this->formErrors);
    }
    
    public function loadMeta( $post_id )
    {
        foreach( $this->fields as &$field ){
            $field->setValue( get_post_meta( $post_id, $field->getName(), true ) );
        }
        return $this;
    }
    
    public function updateMeta( $post_id )
    {
        foreach( $this->fields as &$field ){
            update_post_meta( $post_id, $field->getName(), $field->getValue() );
        }
    }
    
    public function getFieldNames()
    {
        return array_keys( $this->fields );
    }
    
    public function field( $name )
    {
        return @$this->fields[ $name ];
    }
    
    public function process( $from = array(), $all = false )
    {
        $this->success = true;
        $this->setValues( $from, $all );
        foreach( $this->fields as $name => &$field ){
            if( !$field->validate() ){
                $this->success = false;
                $this->errors = $this->errors + $field->getErrors();
            }
        }
        $this->_processed = true;
        return $this->success;
    }
    
    public function processed()
    {
        return $this->_processed;
    }
    
    public function isValid()
    {
        return !count($this->getErrors());
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getValues()
    {
        $values = array();
        foreach( $this->fields as $field ){
            $values[$field->getName()] = $field->getValue();
        }
        return $values;
    }
    
    public function setValues( $source = array(), $all=false )
    {
        foreach( $this->fields as $name => &$field ){
            if( $all || @$source[$name] ){
                $field->setValue( @$source[$name], $source );
            }
        }
    }
    
    public function render( $options = array() )
    {
        $defaults = array(
            'renderer'      => 'Snap_Wordpress_Form_Renderer_Default'
        );
        
        $options = array_merge( $defaults, $options );
        extract( $options );
        
        $renderer = Snap::singleton( $renderer );        
        
        if( @$action ){
            $renderer->renderOpenForm( $action );
        }
        if( (@$action && $this->hasFormErrors()) || @$formerrors ){
            $renderer->renderFormErrors( $this->getFormErrors() );
        }        
        
        $renderer->renderOpenGroup( $this );
        
        $fields = array();
        
        if( isset($group) ) $fields = $this->groups[$group];
        else $fields = array_keys( $this->fields );
        
        foreach( $fields as $name ){
            $this->field( $name )->render( $options );
        }
        $renderer->renderCloseGroup( $this );
        if( @$buttons ){
            $renderer->renderButtons( $buttons );
        }
        if( @$action  ){
            $renderer->renderCloseForm();
        }
    }
    
    public function getOptions( $name ){
        return array();
    }
}