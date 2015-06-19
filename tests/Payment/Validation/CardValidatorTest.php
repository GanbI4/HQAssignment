<?php
use Payment\Validation\CardValidator;
use Payment\CreditCard;

class CardValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider expirationData
     */
    public function testValidExpiration($month, $year, $expected)
    {
        $actual = CardValidator::ValidExpiration($month, $year);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider ccvData
     */
    public function testValidCCV($ccv, $expected) 
    {
        $actual = CardValidator::ValidCCV($ccv);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider cardNumberData
     */
    public function testValidNumber($number, $expected) 
    {
        $actual = CardValidator::ValidNumber($number);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider cardHolderData
     */
    public function testValidHolder($name, $expected) 
    {
        $actual = CardValidator::ValidHolder($name);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider cardData
     */
    public function testValidCard(
        $number, $holder, $expiration, $cvv, $expected) 
    {
        $card = new CreditCard();
        $card->setNumber($number)
            ->setHolder($holder)
            ->setExpirationDate($expiration)
            ->setCCV($cvv);

        $actual = CardValidator::ValidCard($card);
        $this->assertEquals($expected, $actual);
    }

    public function expirationData() 
    {
        return array(
            array(12,     15,   false),
            array("12",   99,   false),
            array(13,     233,  false),
            array('June', 2033, false),
            array(7,      2014, false),
            array("00",   2034, false),
            array("07",   2023, true),
            array(11,     2020, true),
            array(date("m"), date("Y"), true)
        );
    }

    public function ccvData()
    {
        return array(
            array(123, true),
            array(3452, true),
            array(0, false),
            array(12345, false)
        );
    }

    public function cardNumberData()
    {
        return array(
            array("123456789012",    true),
            array("123456789012345", true),
            array("123784358O123",   false),
            array("331231",          false),
            array("1234567890123456789",  true),
            array("12345678901234567890", false)
        );
    }

    public function cardHolderData()
    {
        return array(
            array("Joe1", false),
            array("",     false),
            array(" Foo", false),
            array("Bar ", false),
            array("T j" , false),
            array("BUYER", true),
            array("Onemore", true)
        );
    }

    public function cardData ()
    {
        return array(
            array('12345678', 'Foo Bar', "12/2020", '123', false),
            array('123456789012', 'Foo Bar', "12/20", '123', false),
            array('123456789012', 'Foo Bar', "12/2020", '12', false),
            array('123456789012', 'FooBar 2', "12/2020", '12', false),
            array('123456789012', 'Foo1 Bar', "12/2020", '1234', false),
            array('123456789012', 'Foo Bar', "12/2020", '1234', true)
        );
    }
}

