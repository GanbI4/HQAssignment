<?php
use Payment\CreditCard;
use Payment\Order;
use Payment\Gateway\GatewayFactory;
use Payment\Gateway\IPaymentGateway;

class GatewayFactoryTest extends PHPUnit_Framework_TestCase
{   
    
    
    /**
     * @dataProvider FactoryReturnsProperGatewayData
     */
    public function testFactoryReturnsProperGatewayOrString($cardType, $currency, $expect)
    {
        $card = new CreditCard();
        $card->setType($cardType);

        $order = new Order();
        $order->setCurrency($currency);

        $rule = "HQAssignment";

        $gw = GatewayFactory::ApplyRule($rule, $card, $order);

        if ($gw instanceof IPaymentGateway) {
            $actual = $gw->getName();
        } else {
            $actual = gettype($gw);
        }

        $this->assertEquals($expect, $actual);
    }

    public function FactoryReturnsProperGatewayData()
    {
        return array(
            array("amex", "USD", "PayPal"),
            array("visa", "USD", "PayPal"),
            array("mastercard", "EUR", "PayPal"),
            array("mastercard", "AUD", "PayPal"),
            array("discover", "USD", "PayPal"),
            array("unknown",  "SGD", "Braintree"),
            array("discover", "THB", "Braintree"),
            array("visa", "GBP", "string"),
            array("amex", "AUD", "string"),
            array("unknown",  "AUD", "string"),
            array("discover", "GBP", "string"),
            array("amex", "THB", "string")
        );
    }

}
