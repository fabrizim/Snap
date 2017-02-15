<?php

class Snap_Wordpress_Theme_Assets
{
  
  protected $dir;
  protected $dist_dir;
  protected $manifest = [];
  
  public function __construct( $dir=null, $path=null, $dist='dist', $manifest='assets.json' )
  {
    if( !$dir ) $dir = get_template_directory();
    if( !$path ) $path = get_template_directory_uri();
    $this->dir = $dir;
    $this->path = $path;
    $this->dist_dir = $this->dir.'/'.$dist;
    $this->dist_path = $this->path.'/'.$dist;
    
    $manifest = $this->dist_dir.'/'.$manifest;
    if( file_exists($manifest) ){
      $this->manifest = json_decode( file_get_contents( $manifest ), true );
    }
  }
  
  public function script($file, $name=null, $deps=null, $ver=null, $footer=true)
  {
    if( !$name ) $name = sanitize_title( basename( $file, '.js' ) );
    wp_enqueue_script($name, $this->get_path($file), $deps, $ver, $footer );
  }
  
  public function style($file, $name=null, $deps=null, $ver=null)
  {
    if( !$name ) $name = sanitize_title( basename( $file, '.css' ) );
    wp_enqueue_style($name, $this->get_path($file), $deps, $ver );
  }
  
  public function get_path($file)
  {
    $key = $file;
    $directory = '';
    if( !preg_match('#/#', $file) ){
      $key = basename( $file );
      $directory = dirname( $file );
    }
    return $this->dist_path.'/'.
      (isset( $this->manifest[$key] ) ?
        ($directory . $this->manifest[$key]) : $file);
  }
}
