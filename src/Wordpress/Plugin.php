<?php

class Snap_Wordpress_Plugin
{
    protected $snap;
    protected $_wp_meta_boxes = array();
    
    public function __construct()
    {
        $this->snap =& Snap::get($this);
        $this->_registerMethods();
    }
    
    protected function _registerMethods()
    {
        $reflectionClass = new ReflectionClass( $this );
        foreach( $reflectionClass->getMethods( ReflectionMethod::IS_PUBLIC ) as $method ){
            $name = $method->getName();
            if( $this->snap->method($name, 'wp.filter', false) ){
                $this->_wp_add('filter', $name);
            }
            if( $this->snap->method($name, 'wp.action', false) ){
                $this->_wp_add('action', $name);
            }
            if( ($shortcode = $this->snap->method('wp.shortcode', false)) !== false ){
                $this->_wp_add('shortcode', $name);
            }
            if( $this->snap->method($name, 'wp.meta_box', false) || $this->snap->method($name, 'wp.metabox', false) ){
                $this->_wp_meta_boxes[] = $name;
                if( count( $this->_wp_meta_boxes ) === 1 ){
                    add_action( 'add_meta_boxes', array( &$this, '_wp_add_meta_boxes' ) );
                }
            }
            if( $this->snap->method($name, 'wp.ajax', false) ){
                $this->_wp_add_ajax($name);
            }
        }
    }
    
    protected function _wp_add( $type, $name )
    {
        $fn = "add_$type";
        $_name = $this->snap->method($name, "wp.$type", false);
        $priority = $this->snap->method( $name, 'wp.priority');
        $args = $this->snap->method( $name, 'wp.args', $this->snap->method( $name, 'snap.arguments', 1 ) );
        $callback = array( &$this, $name );
        
        $arguments = array( is_string( $_name ) ? $_name : $name, $callback );
        
        switch( $type ){
            case 'filter':
            case 'action':
                $arguments[] = $priority;
                $arguments[] = $args;
                break;
            
            default:
                break;
        }
        
        return call_user_func_array( $fn, $arguments );
        
    }
    
    protected function _wp_add_ajax( $method )
    {
        $name = $this->snap->method( $method, 'wp.ajax' );
        
        if( $name === true ) $name = $method;
        $admin = $this->snap->method( $method, 'wp.ajax_admin', false);
        $nopriv = $this->snap->method( $method, 'wp.ajax_nopriv', false);
        $prefix = 'wp_ajax';
        
        if( $admin || (!$admin && !$nopriv ) ){
            add_action( $prefix.'_'.$name, array( &$this, $method ) );
        }
        if( $nopriv ){
            $action.= '_nopriv';
            add_action( $prefix.'_'.$name, array( &$this, $method ) );
        }
    }
    
    public function _wp_add_meta_boxes()
    {
        foreach( $this->_wp_meta_boxes as $name ) {
            $callback       = array( &$this, $name );
            $id             = $this->snap->method( $name, 'wp.id', $name );
            $title          = $this->snap->method( $name, 'wp.title', $name );
            $post_type      = $this->snap->method( $name, 'wp.post_type', null );
            $context        = $this->snap->method( $name, 'wp.context', 'normal' );
            $priority       = $this->snap->method( $name, 'wp.priority', 'high' );
            
            $this->_wp_add_meta_box( $id, $title, $callback, $post_type, $context, $priority );
        }
    }
    
    public function _wp_add_meta_box( $id, $title, $callback, $post_type, $context, $priority ){
        add_meta_box( $id, $title, $callback, $post_type, $context, $priority );
    }
}