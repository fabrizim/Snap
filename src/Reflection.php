<?php

class Snap_Reflection
{
    protected $registry;
    
    public function __construct($className)
    {
        $this->registry = new Snap_Registry();
        
        $reflectionClass = new ReflectionClass( $className );
        $this->_reflect( $reflectionClass );
        
    }
    
    protected function _reflect($reflectionClass)
    {
        // get parent classes
        $parent = $reflectionClass->getParentClass();
        if( $parent ) $this->_reflect( $parent );
        
        // add any implemented interfaces
        $interfaces = $reflectionClass->getInterfaces();
        if( $interfaces && count( $interfaces ) ) foreach( $interfaces as $key => $interface ) {
            $this->_reflect( $interface );
        }
        
        // now lets look at ourself
        $this->registry->import( Snap_Parser::parse( $reflectionClass->getDocComment() )->export(), 'klass' );
        
        
        foreach( $reflectionClass->getProperties() as $property ){
            $n = 'property.'.$property->getName();
            $this->registry->import( Snap_Parser::parse( $property->getDocComment() )->export(), $n );
            $this->registry->set("$n.snap.public", $property->isPublic());
            $this->registry->set("$n.snap.private", $property->isPrivate());
            $this->registry->set("$n.snap.protected", $property->isProtected());
            $this->registry->set("$n.snap.static", $property->isStatic());
        }
        
        foreach( $reflectionClass->getMethods() as $method ){
            $n = 'method.'.$method->getName();
            $this->registry->import( Snap_Parser::parse( $method->getDocComment() )->export(), $n );
            $this->registry->set("$n.snap.public", $method->isPublic());
            $this->registry->set("$n.snap.private", $method->isPrivate());
            $this->registry->set("$n.snap.protected", $method->isProtected());
            $this->registry->set("$n.snap.static", $method->isStatic());
            $this->registry->set("$n.snap.arguments", $method->getNumberOfParameters());
        }
        
    }
    
    public function method($name, $prop, $default=null)
    {
        return $this->registry->get("method.$name.$prop", $default);
    }
    
    public function property($name, $prop, $default=null)
    {
        return $this->registry->get("property.$name.$prop", $default);
    }
    
    public function klass($prop, $default=null)
    {
        return $this->registry->get("klass.$prop", $default);
    }
    
}