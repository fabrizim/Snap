<?php

if( !class_exists('Snap') ){

define('SNAP_DIR', dirname(__FILE__) );
require_once( SNAP_DIR . '/Loader.php' );

class Snap {
    
    private static $cache = array();
    
    private static $objects = array();
    
    private static $time = 0;
    
    public static function &get( $className )
    {
        if( is_object( $className) ) $className = get_class( $className );
        
        if( !isset( self::$cache[$className] ) ){
            $start = microtime(true);
            self::$cache[$className] = new Snap_Reflection( $className );
            self::$time += (microtime(true)-$start);
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
    /**
     * Alias for singleton
     */
    public static function inst( $className )
    {
        return self::singleton( $className );
    }
    
    public static function get_time()
    {
      return self::$time;
    }
}

Snap_Loader::register( 'Snap', SNAP_DIR );

}