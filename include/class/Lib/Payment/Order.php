<?php

namespace Payment;

/**
 * Class Order 
 * 
 * @package 
 */
class Order 
{
    public $price;
    public $customerName;
    public $currency;
    public $customerFirstName;
    public $customerLastName;

    /**
     * Set price, format "999.99" 
     * 
     * @param string $price 
     * @access public
     * @return void
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Set currency. 3 symbols 
     * 
     * @param mixed $currency 
     * @access public
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = strtoupper($currency);
        return $this;
    }

    /**
     * setCustomerName 
     * 
     * @param string $cname 
     * @access public
     * @return $this
     */
    public function setCustomerName($cname) 
    {
        $this->customerName = $cname;

        $cnameArray = preg_split("/\s+/", $cname);
        $this->customerFirstName = $cnameArray[0];
        $this->customerLastName  = $cnameArray[1];

        return $this;
    }
}
