<?php

class Snap_Loader
{
    protected static $libs=array();
    
    protected static $registered = false;
    
    public static function register( $prefix, $dir )
    {
        self::$libs[$prefix] = $dir;
        if( !self::$registered ){
            spl_autoload_register( array('Snap_Loader', 'load') );
            self::$registered = true;
        }
    }
    
    public static function load( $className )
    {
        $parts = explode( '_', $className );
        $prefix = array_shift( $parts );
        if( isset( self::$libs[$prefix] ) ){
            if( !count( $parts ) ) $parts[] = $prefix;
            $file = self::$libs[$prefix] . DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $parts ) . '.php';
            if( file_exists( $file ) ){
                require( self::$libs[$prefix] . DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $parts ) . '.php' );
            }
        }
    }
}