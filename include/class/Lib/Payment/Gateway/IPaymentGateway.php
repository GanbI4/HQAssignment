<?php
namespace Payment\Gateway;

use Payment\CreditCard;
use Payment\Order;

/**
 * Interface IPaymentGateway 
 * 
 * @package Payment\Gateway 
 */
interface IPaymentGateway 
{
    public function getResponce();
    public function prepareRequest(CreditCard $card, Order $order); 
    public function makeRequest();
}
