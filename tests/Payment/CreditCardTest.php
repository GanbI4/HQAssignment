<?php

use Payment\CreditCard;

class CreditCardTest extends PHPUnit_Framework_TestCase
{
    public function cardDataProvider()
    {
        return array(
            array('4556712188622443', 'visa'),
            array('4485345662050082', 'visa'),
            array('4556502009386397', 'visa'),
            array('4024007152721212', 'visa'),
            array('4556174211875078', 'visa'),
            array('5109588022866407', 'mastercard'),
            array('5570886780385044', 'mastercard'),
            array('5104937225207932', 'mastercard'),
            array('5258412640842162', 'mastercard'),
            array('5471586036550918', 'mastercard'),
            array('6011931037455276', 'discover'),
            array('6011360580964916', 'discover'),
            array('6011005323468259', 'discover'),
            array('6011886747863187', 'discover'),
            array('6011060627621772', 'discover'),
            array('379983475904827' , 'amex'),
            array('372166819282657' , 'amex'),
            array('348756354515262' , 'amex'),
            array('347169578615903' , 'amex'),
            array('375166061300196' , 'amex'),
            array('3530111333300000', 'unknown'),
            array('3566002020360505', 'unknown'),
            array('5610591081018250', 'unknown'),
            array('30569309025904',   'unknown'),  
            array('38520000023237',   'unknown')
        );


    }

    /**
     * testCardTypeObtaining 
     * @dataProvider cardDataProvider
     */
    public function testCardTypeObtaining($number, $expectedType) 
    {
        $type = CreditCard::obtainCardType($number);
    
        $this->assertEquals($expectedType, $type); 
    }
}
