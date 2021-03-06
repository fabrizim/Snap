<?php

class Snap_Wordpress_Form_Validator_Email extends Snap_Wordpress_Form_Validator
{
    
    protected $message = "Please enter a valid email address.";
    
    public function isValid()
    {
        return $this->validEmail( $this->value );
    }
    
    /**
     * Email Validation Code from:
     * http://www.linuxjournal.com/article/9585?page=0,3
     */
    function validEmail($email)
    {
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex)
        {
            $isValid = false;
        }
        else
        {
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64)
            {
                // local part length exceeded
                $isValid = false;
            }
            else if ($domainLen < 1 || $domainLen > 255)
            {
                // domain part length exceeded
                $isValid = false;
            }
            else if ($local[0] == '.' || $local[$localLen-1] == '.')
            {
               // local part starts or ends with '.'
               $isValid = false;
            }
            else if (preg_match('/\\.\\./', $local))
            {
               // local part has two consecutive dots
               $isValid = false;
            }
            else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
            {
               // character not valid in domain part
               $isValid = false;
            }
            else if (preg_match('/\\.\\./', $domain))
            {
               // domain part has two consecutive dots
               $isValid = false;
            }
            else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                     str_replace("\\\\","",$local)))
            {
                // character not valid in local part unless 
                // local part is quoted
                if (!preg_match('/^"(\\\\"|[^"])+"$/',
                    str_replace("\\\\","",$local)))
                {
                   $isValid = false;
                }
            }
            // disable DNS checks for now -- too much overhead
            if (false && $isValid && !(checkdnsrr($domain,"MX") ||  checkdnsrr($domain,"A")))
            {
                // domain not found in DNS
                $isValid = false;
            }
        }
        return $isValid;
    }
    
    public function getValidationClasses()
    {
        return array('email');
    }
}