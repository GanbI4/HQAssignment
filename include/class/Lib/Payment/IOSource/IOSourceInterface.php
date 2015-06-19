<?php 
namespace Payment\IOSource;

use Payment\CardPayment;

/**
 * Interface IOSourceInterface 
 * 
 * @package Payment\IOSourceInterface 
 */
interface IOSourceInterface 
{
    public function save(CardPayment $payment);
}
