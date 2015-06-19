<?php
namespace Payment\Gateway;

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\CreditCard as PayPalApiCard;

use Payment\Config\PayPalApi; 
use Payment\Order;
use Payment\CreditCard;

/**
 * Class PayPal 
 * 
 * Represent PayPal Gateway. Wrap over PayPal PHP SDK
 * 
 * @uses GatewayModel
 * @uses IPaymentGateway
 * @package Payment\Gateway
 */
class PayPal extends GatewayModel implements IPaymentGateway
{
    protected $name = "PayPal";

    public $apiContext;
    public $card;
    public $fundingInstrument;
    public $payer;
    public $payment;

    /**
     * __construct 
     * 
     * @param string $credName 
     * @param string $configName 
     * @access public
     * @return void
     */
    public function __construct($credName = 'test', $configName = false) 
    {
        $credential = PayPalApi::getCredential($credName);

        $this->apiContext = new ApiContext(
            new OAuthTokenCredential($credential['clientid'], $credential['secret'])
        );

        if ($configName) {
            $config = PayPalApi::getConfig($configName);
            $this->apiContext->setConfig($config);
        }

    }

    /**
     * Build Request for PayPal Api PHP SDK
     *
     * @param CreditCard $card 
     * @param Order $order 
     * @access public
     * @return $this 
     */
    public function prepareRequest(CreditCard $card, Order $order) 
    {
        $this->card = new PayPalApiCard();
        $this->card->setType($card->type)
            ->setNumber($card->number)
            ->setExpireMonth($card->expirationMonth)
            ->setExpireYear($card->expirationYear)
            ->setCvv2("$card->ccv")
            ->setFirstName($card->holderFirstName)
            ->setLastName($card->holderLastName);

        $this->fundingInstrument = new FundingInstrument();
        $this->fundingInstrument->setCreditCard($this->card);

        // A resource representing a Payer that funds a payment 
        $this->payer = new Payer();
        $this->payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($this->fundingInstrument));

        // Specify a payment amount. 
        $this->amount = new Amount();
        $this->amount->setCurrency($order->currency)
            ->setTotal($order->price);

        // Transaction defines the contract of a payment - what is the payment for and who is fulfilling it.
        $this->transaction = new Transaction();
        $this->transaction->setAmount($this->amount)
            ->setDescription("Assignment 1 Test Payment from ". $order->customerName)
            ->setInvoiceNumber(uniqid());

        //A Payment Resource
        $this->payment = new Payment();
        $this->payment->setIntent("sale")
            ->setPayer($this->payer)
            ->setTransactions(array($this->transaction));

        return $this;
    }

    /**
     * Execute request to PayPal API, return true if success
     *
     * @access public
     * @return bool 
     */
    public function makeRequest() 
    {
        $apiContext = $this->apiContext;

        try {
            $this->payment->create($apiContext);
        } catch (\Exception $e) {
            $this->responce = $e->getData();
            $this->error = parent::BASE_ERROR_MSG;
            return false;
        }
        $this->responce = $this->payment->toJSON();
        return true;
    }
}
