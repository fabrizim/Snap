<?php

class Snap_Wordpress_Mixin
{
    protected $target;
    protected $snap;
    protected $_wp_meta_boxes = array();
    
    public function __construct( &$target )
    {
        $this->target =& $target;
        $this->snap =& Snap::get( $target );
        $this->_registerMethods();
    }
    
    protected function _registerMethods()
    {
        $reflectionClass = new ReflectionClass( $this->target );
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
            if( is_admin() && $this->snap->method($name, 'wp.ajax', false) ){
                $this->_wp_add_ajax($name);
            }
        }
    }
}