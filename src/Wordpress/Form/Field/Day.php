<?php

class Snap_Wordpress_Form_Field_Day extends Snap_Wordpress_Form_Field
{
    protected $options;
    
    public function setValue( $value, $source=array() )
    {
        $name = $this->getName();
        
        $month  = @$source[$name.'_month'];
        $day    = @$source[$name.'_day'];
        $year   = @$source[$name.'_year'];
        
        if( $month && $day && $year ){
            return parent::setValue( "$year-$month-$day 00:00:00", $source );
        }
        
        return parent::setValue( $value, $source );
    }
}