<?php

/*
    This file was modified from the mobble file.
 
	mobble
	------
	
	Plugin Name: mobble
	Plugin URI: http://www.toggle.uk.com/journal/mobble
	Description: Conditional functions for detecting a variety of mobile devices and tablets. For example is_android(), is_ios(), is_iphone().
	Author: Scott Evans
	Version: 1.1
	Author URI: http://www.toggle.uk.com/

	Copyright (c) 2011 toggle labs ltd <http://www.toggle.uk.com>

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	THIS SOFTWARE AND DOCUMENTATION IS PROVIDED "AS IS," AND COPYRIGHT
	HOLDERS MAKE NO REPRESENTATIONS OR WARRANTIES, EXPRESS OR IMPLIED,
	INCLUDING BUT NOT LIMITED TO, WARRANTIES OF MERCHANTABILITY OR
	FITNESS FOR ANY PARTICULAR PURPOSE OR THAT THE USE OF THE SOFTWARE
	OR DOCUMENTATION WILL NOT INFRINGE ANY THIRD PARTY PATENTS,
	COPYRIGHTS, TRADEMARKS OR OTHER RIGHTS.COPYRIGHT HOLDERS WILL NOT
	BE LIABLE FOR ANY DIRECT, INDIRECT, SPECIAL OR CONSEQUENTIAL
	DAMAGES ARISING OUT OF ANY USE OF THE SOFTWARE OR DOCUMENTATION.

	You should have received a copy of the GNU General Public License
	along with this program. If not, see <http://gnu.org/licenses/>.

*/

class Snap_Util_Device
{
    
    public static function useragent()
    {
        static $useragent;
        if( !isset( $useragent ) ){
            $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
			//$useragent = 'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A543a Safari/419.3';
        }
        return $useragent;
    }


    /***************************************************************
    * Function is_iphone
    * Detect the iPhone
    ***************************************************************/
    
    public static function is_iphone()
    {
        return(preg_match('/iphone/i',self::useragent()));
    }

    /***************************************************************
    * Function is_ipad
    * Detect the iPad
    ***************************************************************/
    public static function is_ipad()
    {
        return(preg_match('/ipad/i',self::useragent()));
    }
    
    /***************************************************************
    * Function is_ipod
    * Detect the iPod, most likely the iPod touch
    ***************************************************************/
    public static function is_ipod()
    {
        return(preg_match('/ipod/i',self::useragent()));
    }

    /***************************************************************
    * Function is_android
    * Detect an android device. They *SHOULD* all behave the same
    ***************************************************************/
    public static function is_android()
    {
        return(preg_match('/android/i',self::useragent()));
    }

    /***************************************************************
    * Function is_blackberry
    * Detect a blackberry device 
    ***************************************************************/
    public static function is_blackberry()
    {
        return(preg_match('/blackberry/i',self::useragent()));
    }
    
    /***************************************************************
    * Function is_opera_mobile
    * Detect both Opera Mini and hopfully Opera Mobile as well
    ***************************************************************/
    public static function is_opera_mobile()
    {
        return(preg_match('/opera mini/i',self::useragent()));
    }
    
    /***************************************************************
    * Function is_palm
    * Detect a webOS device such as Pre and Pixi
    ***************************************************************/
    public static function is_palm()
    {
        return(preg_match('/webOS/i', self::useragent()));
    }
    
    /***************************************************************
    * Function is_symbian
    * Detect a symbian device, most likely a nokia smartphone
    ***************************************************************/
    public static function is_symbian()
    {
        return(preg_match('/Series60/i', self::useragent()) || preg_match('/Symbian/i', self::useragent()));
    }
    
    /***************************************************************
    * Function is_windows_mobile
    * Detect a windows smartphone
    ***************************************************************/
    public static function is_windows_mobile()
    {
        return(preg_match('/WM5/i', self::useragent()) || preg_match('/WindowsMobile/i', self::useragent()));
    }
    
    /***************************************************************
    * Function is_lg
    * Detect an LG phone
    ***************************************************************/
    public static function is_lg()
    {
        return(preg_match('/LG/i', self::useragent()));
    }
    
    /***************************************************************
    * Function is_motorola
    * Detect a Motorola phone
    ***************************************************************/
    public static function is_motorola()
    {
        return(preg_match('/\ Droid/i', self::useragent()) || preg_match('/XT720/i', self::useragent()) || preg_match('/MOT-/i', self::useragent()) || preg_match('/MIB/i', self::useragent()));
    }
    
    /***************************************************************
    * Function is_nokia
    * Detect a Nokia phone
    ***************************************************************/
    public static function is_nokia()
    {
        return(preg_match('/Series60/i', self::useragent()) || preg_match('/Symbian/i', self::useragent()) || preg_match('/Nokia/i', self::useragent()));
    }
    
    /***************************************************************
    * Function is_samsung
    * Detect a Samsung phone
    ***************************************************************/
    public static function is_samsung()
    {
        return(preg_match('/Samsung/i', self::useragent()));
    }
    
    /***************************************************************
    * Function is_samsung_galaxy_tab
    * Detect the Galaxy tab
    ***************************************************************/
    public static function is_samsung_galaxy_tab()
    {
        return(preg_match('/SPH-P100/i', self::useragent()));
    }
    
    /***************************************************************
    * Function is_sony_ericsson
    * Detect a Sony Ericsson
    ***************************************************************/
    public static function is_sony_ericsson()
    {
        return(preg_match('/SonyEricsson/i', self::useragent()));
    }
    
    /***************************************************************
    * Function is_nintendo
    * Detect a Nintendo DS or DSi
    ***************************************************************/
    public static function is_nintendo()
    {
        return(preg_match('/Nintendo DSi/i', self::useragent()) || preg_match('/Nintendo DS/i', self::useragent()));
    }
    
    /***************************************************************
    * Function is_handheld
    * Wrapper function for detecting ANY handheld device
    ***************************************************************/
    public static function is_handheld()
    {
        return(self::is_iphone() ||
               self::is_ipad() ||
               self::is_ipod() ||
               self::is_android() ||
               self::is_blackberry() ||
               self::is_opera_mobile() ||
               self::is_palm() ||
               self::is_symbian() ||
               self::is_windows_mobile() ||
               self::is_lg() ||
               self::is_motorola() ||
               self::is_nokia() ||
               self::is_samsung() ||
               self::is_samsung_galaxy_tab() ||
               self::is_sony_ericsson() ||
               self::is_nintendo());
    }
    
    /***************************************************************
    * Function is_mobile
    * Wrapper function for detecting ANY mobile phone device
    ***************************************************************/
    public static function is_mobile()
    {
        if (self::is_tablet()) { return false; }  // this catches the problem where an Android device may also be a tablet device
        return(self::is_iphone() ||
               self::is_ipod() ||
               self::is_android() ||
               self::is_blackberry() ||
               self::is_opera_mobile() ||
               self::is_palm() ||
               self::is_symbian() ||
               self::is_windows_mobile() ||
               self::is_lg() ||
               self::is_motorola() ||
               self::is_nokia() ||
               self::is_samsung() ||
               self::is_sony_ericsson() ||
               self::is_nintendo());
    }
    
    /***************************************************************
    * Function is_ios
    * Wrapper function for detecting ANY iOS/Apple device
    ***************************************************************/
    public static function is_ios()
    {
        return(self::is_iphone() ||
               self::is_ipad() ||
               self::is_ipod());
    
    }
    
    /***************************************************************
    * Function is_tablet
    * Wrapper function for detecting tablet devices (needs work)
    ***************************************************************/
    public static function is_tablet()
    {
        return(self::is_ipad() ||
               self::is_samsung_galaxy_tab());
    }
	
	public static function is_desktop()
	{
		return !self::is_handheld();
	}
    
    public static function get_classes() 
    {
    
        // top level
        if (self::is_handheld()) { $classes[] = "handheld"; };
        if (self::is_mobile()) { $classes[] = "mobile"; };
        if (self::is_ios()) { $classes[] = "ios"; };
        if (self::is_tablet()) { $classes[] = "tablet"; };
        if (self::is_desktop()) { $classes[] = "desktop"; }
    
        // specific 
        if (self::is_iphone()) { $classes[] = "iphone"; };
        if (self::is_ipad()) { $classes[] = "ipad"; };
        if (self::is_ipod()) { $classes[] = "ipod"; };
        if (self::is_android()) { $classes[] = "android"; };
        if (self::is_blackberry()) { $classes[] = "blackberry"; };
        if (self::is_opera_mobile()) { $classes[] = "opera-mobile";}
        if (self::is_palm()) { $classes[] = "palm";}
        if (self::is_symbian()) { $classes[] = "symbian";}
        if (self::is_windows_mobile()) { $classes[] = "windows-mobile"; }
        if (self::is_lg()) { $classes[] = "lg"; }
        if (self::is_motorola()) { $classes[] = "motorola"; }
        if (self::is_nokia()) { $classes[] = "nokia"; }
        if (self::is_samsung()) { $classes[] = "samsung"; }
        if (self::is_samsung_galaxy_tab()) { $classes[] = "samsung-galaxy-tab"; }
        if (self::is_sony_ericsson()) { $classes[] = "sony-ericsson"; }
        if (self::is_nintendo()) { $classes[] = "nintendo"; }
		
		// bonus
		
		global $is_lynx, $is_gecko, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_IE;
		
		if ($is_lynx) { $classes[] = "lynx"; }
		if ($is_gecko) { $classes[] = "gecko"; }
		if ($is_opera) { $classes[] = "opera"; }
		if ($is_NS4) { $classes[] = "ns4"; }
		if ($is_safari) { $classes[] = "safari"; }
		if ($is_chrome) { $classes[] = "chrome"; }
		if ($is_IE) { $classes[] = "ie"; }
		
		if( preg_match('#webkit#i', self::useragent()) ) $classes[] = 'webkit';
        
        return $classes;
    }

}