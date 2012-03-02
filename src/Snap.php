<?php

if( !class_exists('Snap') ){

define('SNAP_DIR', dirname(__FILE__) );
require_once( SNAP_DIR . '/Loader.php' );

class Snap {
    
    private static $cache = array();
    
    private static $objects = array();
    
    public static function &get( $className )
    {
        if( is_object( $className) ) $className = get_class( $className );
        
        if( !isset( self::$cache[$className] ) ){
            self::$cache[$className] = new Snap_Reflection( $className );
        }
        return self::$cache[$className];
    }
    
    public static function factory( $obj )
    {
        return self::get($obj);
    }
    
    public static function create( $name )
    {
        return new $name;
    }
    
    public static function singleton( $className )
    {
        if( !isset( self::$objects[$className]) ){
            self::$objects[$className] = self::create( $className );
        }
        return self::$objects[$className];
    }
}

Snap_Loader::register( 'Snap', SNAP_DIR );

}