<?php

class Snap_Wordpress_Form_Validator_Factory
{
    protected static $registry = array();
    
    public static function get( $key )
    {
        if( isset(self::$registry[$key]) ){
            $class = self::$registry[$key];
            return new $class();
        }
        // lets see if can figure this out...
        $name = strtoupper( substr( $key, 0, 1 ) ) . substr( $key, 1 );
        $class = 'Snap_Wordpress_Form_Validator_'.$name;
        if( class_exists($class) ){
            self::register( $key, $class );
            return self::get( $key );
        }
        throw new Exception("Missing Validator: $key");
    }
    
    public static function register( $key, $className )
    {
        self::$registry[$key] = $className;
    }
}