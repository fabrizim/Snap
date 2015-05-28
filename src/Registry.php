<?php

class Snap_Registry implements Serializable
{
    
    private $data = array();
    
    private $_infer = false;
    
    public function __construct($infer = false)
    {
        $this->_infer = $infer;
    }
    
    public function set($name, $value)
    {
        $current =& $this->data;
        $name = explode('.', $name);
        
        // should we guess what this value is?
        if( $this->_infer ) $value = $this->infer($value);
        
        while( ($c = count($name)) ){
            $part = array_shift($name);
            if($c===1){
                $current[$part] = $value;
            }
            else{
                if( !isset($current[$part]) ){
                    $current[$part] = array();
                }
                $current =& $current[$part];
            }
        }
    }
    
    public function get($name, $default=null)
    {
        $parts = explode('.', $name);
        $current =& $this->data;
        while( ($c = count($parts)) ){
            $part = array_shift($parts);
            if( !isset($current[$part]) ) return $default;
            if( $c === 1 ){
                return $current[$part];
            }
            $current =& $current[$part];
        }
        return $default;
    }
    
    public function infer($value)
    {
        $inferred = $value;
        if( gettype($value) == 'string' ){
            $inferred = json_decode($value, true);
            if( $inferred === NULL ){
                $inferred = $value; 
            }
        }
        return $inferred;
    }
    
    public function export()
    {
        return $this->data;
    }
    
    public function import($data=array(), $prefix=null)
    {
        if( !is_array($data) ) throw new Exception("First parmeter expected to be an array");
        $current =& $this->data;
        if( $prefix ){
            $parts = explode('.', $prefix);
            while(count($parts)){
                $part = array_shift($parts);
                if( !isset($current[$part]) || !is_array($current[$part]) ){
                    $current[$part] = array();
                }
                $current =& $current[$part];
            }
        }
        foreach( $data as $key => $value ){
            if( is_array( $value ) ){
                
                $p = $prefix;
                if( !$prefix )  $p = $key;
                else            $p .=".$key";
                
                $this->import( $value, $p );
            }
            else {
                $current[$key] = $value;
            }
        }
    }
    
    public function serialize()
    {
        return serialize($this->data);
    }
    public function unserialize( $data )
    {
        $this->data = unserialize($data);
    }
}