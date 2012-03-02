<?php

class Snap_Wordpress_Util
{
    public static function datepicker( )
    {
        static $included;
        if( !isset( $included ) ){
            
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style( 'jquery-ui-daatepicker', self::snapUrl( '/../resources/jquery-ui/datepicker.css' ) );
            
            $included=true;
        }
    }
    
    public static function snapUrl( $path='' )
    {
        return WP_PLUGIN_URL . substr( SNAP_DIR , strlen( WP_PLUGIN_DIR ) ) . $path;
    }
}