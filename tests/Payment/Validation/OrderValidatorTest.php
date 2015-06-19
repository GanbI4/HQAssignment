<?php
use Payment\Validation\OrderValidator;
use Payment\Order;

class OrderValidatorTest extends PHPUnit_Framework_TestCase
{   

    /**
     * @dataProvider amountData
     */
    public function testValidAmount($amount, $expect) 
    {
        $actual = OrderValidator::ValidAmount($amount);
        $this->assertEquals($expect, $actual);
    }

    /**
     * @dataProvider currencyData
     */
    public function testValidCurrency($currency, $expect) 
    {
        $actual = OrderValidator::ValidCurrency($currency);
        $this->assertEquals($expect, $actual);
    }

    /**
     * @dataProvider orderData
     */
    public function testValidOrder($amount, $currency, $expect) 
    {
        $order = new Order();
        $order->setPrice($amount)
            ->setCurrency($currency);

        $actual = OrderValidator::ValidOrder($order);
        $this->assertEquals($expect, $actual);
    }

    public function amountData() {
        return array(
            array( "-0.2",  false),
            array(  -0.2,   false),
            array( 01.20,   false),
            array(  0.00,   false),
            array(  0.01,   false),
            array( "0.01",  true),
            array( "1.00",  true),
            array( "1.99",  true),
            array( "0.99",  true),
            array(  5.80,   false)
        );
    }   

    public function currencyData()
    {
        return array(
            array("USD",  true),
            array("GBP",  true),
            array("gbp",  false),
            array("euro", false),
        );
    }

    public function orderData()
    {
        return array(
            array( 2.30 , "USD", false),
            array("2.99", "AUD", true),
            array( ".33", "THB", false),
            array( 5.33 , "dollars", false),
        );
    }
}
