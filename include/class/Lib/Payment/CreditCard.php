<?php

namespace Payment;

/**
 * CreditCard 
 * 
 * @package Payment 
 */
class CreditCard 
{
    public $type;
    public $holder;
    public $holderFirstName;
    public $holderLastName;
    public $expirationMonth;
    public $expirationYear;
    public $ccv;
    public $number;

    /**
     * setNumber 
     * 
     * @param string $number 
     * @access public
     * @return $this
     */
    public function setNumber($number)
    {
        if (!$this->type) {
            $this->setType(self::obtainCardType($number));
        }

        $this->number = $number;

        return $this;
    }

    /**
     * Space separated card holder Full Name 
     * 
     * @param mixed $name 
     * @access public
     * @return void
     */
    public function setHolder($name)
    {
        $this->holder = $name;

        $holderNameArray = preg_split("/\s+/", $name);

        $this->holderFirstName = $holderNameArray[0];
        $this->holderLastName = $holderNameArray[1];

        return $this;
    }

    /**
     * Expiration date (mm/yyyy) 
     * 
     * @param string $date 
     * @access public
     * @return void
     */
    public function setExpirationDate($date) 
    {
        $dateArray = explode('/', $date);
        $this->expirationMonth = $dateArray[0];
        $this->expirationYear  = $dateArray[1];
        return $this;
    }

    /**
     * setCCV 
     * 
     * @param string $ccv 
     * @access public
     * @return $this
     */
    public function setCCV($ccv) 
    {
        $this->ccv = $ccv;
        return $this;
    }

    /**
     * card type, 'visa', 'amex', 'mastercard', etc 
     * 
     * @param string $type 
     * @access public
     * @return $this
     */
    public function setType($type) 
    {
        $this->type = $type; 
        return $this;
    }

    /**
     * Return card type via number
     * 
     * @param string $number 
     * @static
     * @access public
     * @return string
     */
    public static function obtainCardType($number) 
    {
        $first_six = (int) substr($number, 0, 6); 

           /* Starts with 4 => visa */
        if (preg_match("/^4\d+$/", $number)) {
            return 'visa';

           /* Starts with 34 or 37 => American Express */
        } else if (preg_match("/^3[47]\d+$/", $number)) {
            return 'amex';

          /* Start with 51-55 => MasterCard (222100 - 272099 is not active) */
        } else if (preg_match("/^5[1-5]\d+$/", $number)) {
            return 'mastercard';

          /* 6011,  644-649, 65 is for Discover Card */             /* i find a regexp so ugly so check range 622126-622925 */
        } else if (preg_match("/^6((011|5)|(4[4-9]))\d+$/", $number) || (622126 <= $first_six && $first_six <= 622925 )) {
            return 'discover';
        } else {
            return 'unknown';
        }
    }
}
