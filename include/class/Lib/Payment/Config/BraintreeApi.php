<?php

namespace Payment\Config;

/**
 * Class BraintreeApi 
 * 
 * Represents Braintree API Config 
 * 
 * @package Payment\BraintreeApi 
 */
class BraintreeApi 
{

    static $accounts = array(
        "EUR" => "euro_hqTest",
        "AUD" => "aud_hqTest",
        "USD" => "usd_hqTest",
        "THB" => "thb_hqTest",
        "HKD" => "hkd_hqTest",
        "SGD" => "sgd_hqTest"
    );

    /**
     * getCredential 
     * 
     * @param string $credentialName 
     * @static
     * @access public
     * @return $string
     */
    public static function getCredential($credentialName) 
    {
        switch ($credentialName) {
            case 'test':
                return array(
                    'environment' => 'sandbox', 
                    'merchantId'  => '9ydhbpzd52xsxnk8',
                    'publicKey'   => 'dh9273dzs9b56gbn',
                    'privateKey'  => '4ba3abef49037e9d880c90bd164d75db'
                );
                break;

            default:
                throw new \Exception("Unknown credential config name ". $credentialName);
                break;
        }
    }

    /**
     * Return Merchant Account id for currency
     * 
     * @param mixed $currencyCode 
     * @static
     * @access public
     * @return string
     */
    public static function getAccountId($currencyCode)
    {
        if (array_key_exists($currencyCode, self::$accounts)) {
            return self::$accounts[$currencyCode];
        }

        throw new \Exception("Can't obtain account id for ".$currencyCode.". Are you sure it exists in Config\BraintreeApi?");
    }
}
