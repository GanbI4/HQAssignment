<?php

namespace Payment\Gateway;

use Payment\Config\Currency;
use Payment\CreditCard;
use Payment\Order;

/**
 * GatewayFactory 
 * 
 * @package Payment\Gateway
 */
class GatewayFactory 
{
    /* PayPalApi Class name */
    const PAYPAL = 'PayPal';
    /* BraintreeApi Class name */
    const BRAINTREE = 'Braintree';
    
    /**
     * Call Rule for determining Payment Gateway.
     * Rule must be implement in this class as static method rulenameRule()
     *
     * @param string $rulename 
     * @param CreditCard $card 
     * @param Order $order 
     * @static
     * @access public
     * @return IPaymentGateway|string
     */
    public static function ApplyRule($rulename, CreditCard $card, Order $order) 
    {
        $rulemethod = $rulename . "Rule";
        if (!method_exists(__CLASS__, $rulemethod))
        {
            throw new \Exception ("Unknown rule: ".$rulename);
        }

        return self::$rulemethod($card, $order);
    }

    /**
     * Implementation of rule from assignment specs
     *
     * @param mixed $card 
     * @param mixed $order 
     * @static
     * @access public
     * @return IPaymentGateway|string
     */
    public static function HQAssignmentRule($card, $order)
    {

        $allowedCurrency = Currency::getAllowed();
        $paypalCurrency  = Currency::getPayPal();

        if ($card->type == 'amex' && $order->currency != 'USD') {
            return 'American Express is possible to use only for USD';
        }

        if (!in_array($order->currency, $allowedCurrency)) {
            return 'Sorry, you can not pay with '.$order->currency;
        }

        if (in_array($order->currency, $paypalCurrency)) {
            if ($card->type == 'unknown') {
                $errorMsg  = "Sorry, you can not pay " . $order->currency ." with your card type. ";
                $errorMsg .= "Try Visa, AmEx, MasterCard or Discover Card. Or choose another currency.";
                return $errorMsg;
            }
            $gateway = __NAMESPACE__ . '\\' . self::PAYPAL;
        } else { 
            $gateway = __NAMESPACE__ . '\\' . self::BRAINTREE;
        }
        return new $gateway();
    }
}
