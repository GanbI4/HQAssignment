<?php

namespace Payment\Config;

/**
 * Class Currency 
 * 
 * Currency configuration class
 *
 * @package Payment\Config  
 */
class Currency 
{
    /**
     * Returns array of allowed currencies for payment
     *
     * @static
     * @access public
     * @return array 
     */
    public static function getAllowed() 
    {
        return array('USD', 'EUR', 'HKD', 'SGD', 'AUD', 'THB');
    }
    
    /**
     * Returns array of currencies, which must be used via PayPal API
     *
     * @static
     * @access public
     * @return array 
     */
    public static function getPayPal()
    {
        return array('USD', 'AUD', 'EUR');
    }
}
