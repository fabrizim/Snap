<?php

class Snap_Wordpress_Template
{
    protected static $paths;
    
    protected static $styles=array();
    
    protected static $scripts=array();
    
    public static function registerPath( $type, $path  )
    {
        if( !isset(self::$paths[$type]) ) self::$paths[$type] = array();
        array_unshift( self::$paths[$type], $path );
    }
    
    public static function load( $type, $template_names, $load = true, $require_once = true )
    {
        $located = '';
        foreach ( (array) $template_names as $template_name ) {
            
            if ( !$template_name )
                continue;
            
            if( ($located = locate_template( $type.'/'.$template_name, $load, $require_once )) ){
                break;
            }
            
            // else check our registry
            if( !isset( self::$paths[$type] ) )
                continue;
            
            foreach( self::$paths[$type] as $path ){
                if( file_exists( $path .'/'. $template_name ) ){
                    $located = $path .'/'. $template_name;
                    if ( $load )
                        load_template( $located, $require_once );
                    break;
                }
            }
            if( $located ){
                break;
            }
        }
        return $located;
    }
    
    public static function addStyle($url, $media='all')
    {
        self::$styles[] = array($url, $media);
    }
    
    public static function printStyles( $combine = false )
    {
        foreach( self::$styles as $style ){
            ?>
            <link rel="stylesheet" type="text/css" media="<?= $style[1] ?>" href="<?= $style[0] ?>" />
            <?php
        }
    }
}