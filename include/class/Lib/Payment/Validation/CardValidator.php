<?php 
namespace Payment\Validation;

use Payment\CreditCard;

/**
 * Class CardValidator 
 * 
 * @package Payment\Validation
 */
class CardValidator 
{
    /**
     * ValidExpiration 
     * 
     * @param string $month 2digit month
     * @param string $year  4digit year
     * @static
     * @access public
     * @return bool
     */
    public static function ValidExpiration($month, $year)
    {
        if (!preg_match('/^20\d{2}$/', $year)) {
            return false;
        }
        if (!preg_match('/^(?:0[1-9]|1[0-2])$/', $month)) {
            return false;
        }

        if ($year < date('Y') || ($year == date('Y') && $month < date('m'))) {
            return false;
        }

        return true;
    }
   
    /**
     * ValidCCV 
     * 
     * @param string $ccv 
     * @static
     * @access public
     * @return bool
     */
    public static function ValidCCV($ccv)
    {
        return preg_match('/^\d{3,4}$/', $ccv);
    }

    /**
     * ValidNumber 
     * 
     * @param string $number 
     * @static
     * @access public
     * @return bool
     */
    public static function ValidNumber($number)
    {
        return preg_match('/^\d{12,19}$/', $number);
    }

    /**
     * ValidHolder 
     * 
     * @param string $name 
     * @static
     * @access public
     * @return bool
     */
    public static function ValidHolder($name)
    {
        return preg_match('/^[A-Za-z]+$/', $name);
    }

    /**
     * ValidCard 
     * 
     * @param CreditCard $card 
     * @static
     * @access public
     * @return bool
     */
    public static function ValidCard(CreditCard $card)
    {
        $ret = self::ValidNumber($card->number) &&
               self::ValidExpiration($card->expirationMonth, $card->expirationYear) &&
               self::ValidCCV($card->ccv) &&
               self::ValidHolder($card->holderFirstName) &&
               self::ValidHolder($card->holderLastName);

        return $ret;
    }
}
