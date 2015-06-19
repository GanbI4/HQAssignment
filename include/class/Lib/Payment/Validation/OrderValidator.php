<?php
namespace Payment\Validation;

use Payment\Order;

/**
 * Class OrderValidator 
 * 
 * @package Payment\Validation 
 */
class OrderValidator
{
    /**
     * ValidAmount 
     * 
     * @param string $amount 
     * @static
     * @access public
     * @return bool
     */
    public static function ValidAmount($amount)
    {
        if (!is_string($amount)) {
            return false;
        }
        /* Positive characteristic w/o leading zeros & 2 digit mantissa only */
        return preg_match('/^(?:0\.(?:0[1-9]|[1-9]\d)|[1-9]+\d*\.\d{2})$/', $amount);
    }

    /**
     * ValidCurrency 
     * 
     * @param string $currency 
     * @static
     * @access public
     * @return bool
     */
    public static function ValidCurrency($currency)
    {
        return preg_match('/^[A-Z]{3}$/', $currency);
    }

    /**
     * ValidOrder 
     * 
     * @param Order $order 
     * @static
     * @access public
     * @return bool
     */
    public static function ValidOrder(Order $order)
    {
        return self::ValidAmount($order->price) && self::ValidCurrency($order->currency);
    }
}
