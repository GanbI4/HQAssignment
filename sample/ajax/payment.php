<?php
    require_once '../../vendor/autoload.php';

    $amount     = $_REQUEST['amount'];
    $currency   = $_REQUEST['currency'];
    $customerFN = $_REQUEST['customer_fn'];
    $customerLN = $_REQUEST['customer_ln'];
    $number     = $_REQUEST['number'];
    $holderFN   = $_REQUEST['holder_fn'];
    $holderLN   = $_REQUEST['holder_ln'];
    $expDate    = $_REQUEST['expire'];
    $ccv        = $_REQUEST['ccv'];



    $card = new Payment\CreditCard();
    $card->setNumber($number)
        ->setHolder($holderFN . " " . $holderLN)
        ->setExpirationDate($expDate)
        ->setCCV($ccv);

    if (! Payment\Validation\CardValidator::ValidCard($card)) {
        $json['success'] = false;
        $json['msg'] = "Please, check your credit card data";
        echo json_encode($json);
        die();
    }
       
    $order = new Payment\Order();
    $order->setPrice($amount)
        ->setCurrency($currency)
        ->setCustomerName($customerFN . " " . $customerLN);
  
    if (! Payment\Validation\OrderValidator::ValidOrder($order)) {
        $json['success'] = false;
        $json['msg'] = "Please, check your order data";
        echo json_encode($json);
        die();
    }
    
    $payment = new Payment\CardPayment();
    $payment->addOrder($order)
        ->addCard($card)
        ->execute()
        ->writeLog();

    $json = array();

    if ($payment->hasError()) {
        $json['success'] = false;
        $json['msg'] =  $payment->getErrorMsg();
    }  else {
        $json['success'] = true;
        $json['msg'] = "Thank you!";
    }
    echo json_encode($json);
