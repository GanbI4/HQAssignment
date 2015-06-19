<?php

namespace Payment\Config;

/**
 * PayPalApi 
 *
 * PayPal Api Configuration class
 *
 * @package Payment\Config 
 */
class PayPalApi 
{
    /**
     * GetCredential 
     * 
     * @param string $credentialName 
     * @static
     * @access public
     * @return array
     */
    public static function GetCredential($credentialName) 
    {
        switch ($credentialName) {
            case 'test':
                return array(
                    'clientid' => 'AVm2wvYHsEizBURwTfONjTbW5EfMbMgM4HnYilfj8i-HOOBm4Lida44XrdE0dLxgKJBl1NZLAVYVTkYL', 
                    'secret' => 'EBwAUgMhl-57VVy2sMNLRDnC8KsYwoJIKjBr_UMPlNg1uj3pReW2QU0sDByRJk81ze-GwFDHT6bh2PLJ'  
                );
                break;

            default:
                throw new \Exception("Unknown credential config name ". $credentialName);
                break;


        }
    }
}
