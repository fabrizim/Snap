<?php

class Snap_Wordpress_Form_Validator_Phone extends Snap_Wordpress_Form_Validator
{
    protected $re = '/^[\(]?(\d{0,3})[\)]?[\s]?[\-]?(\d{3})[\s]?[\-]?(\d{4})[\s]*?([x]?[\s]*(\d*))?$/';
    
    protected $message = "Please enter a valid phone number including area code";
    
    public function isValid()
    {
        $val = preg_replace('#\s#','',$this->value);
        return preg_match($this->re, $val);
    }
    
    public function filter()
    {
        preg_replace('#\s#','',$this->value);
        $valid = preg_match_all( $this->re, $this->value, $matches );
        return $valid ? $matches[1][0].$matches[2][0].$matches[3][0].$matches[4][0] : '';
    }
    
    public function format()
    {
        $number = $this->filter();
        
        if( !$number ) return '';
        
        $area = substr($number, 0, 3);
        $exchange = substr($number, 3, 3);
        $subscriber = substr($number,6,4);
        
        $formatted = "($area) $exchange-$subscriber";
        if( strlen( $number ) > 10 ){
            $formatted.= substr( $number, 10 );
        }
        return $formatted;
    }
    
}