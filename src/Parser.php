<?php

class Snap_Parser
{
    public static function parse($comment)
    {
        $meta = new Snap_Registry(true);
        if( !$comment ) return $meta;
        $lines = explode("\n", $comment);
        // go through each line
        foreach($lines as $line){
            
            // remove leading * and whitespace
            $line = trim( preg_replace('/^\s*(\/?\*\*?)?/', '', $line) );
            
            // check for @
            if( strpos($line, '@') !== 0 ){
                continue;
            }
            
            // grab the name
            $parts = preg_split('/[\s\t]/', $line, 2);
            $name = substr($parts[0],1);
            $value = true;
            
            // no name?
            if( !$name ){
                continue;
            }
            if( isset($parts[1]) ){
                $value = trim($parts[1]);
            }
            
            $meta->set($name, $value);
            
        }
        return $meta;
    }
}