<?php

namespace Payment;

/**
 * CardPayment 
 *
 * Represent Payment with credit card
 *
 * @package Payment 
 */
class CardPayment 
{
    public $paymentGateway;
    public $card;
    public $order;
    public $responce;
    public $logger;
    protected $error = false;
    protected $errorMsg;


    /**
     * addOrder 
     * 
     * @param Order $order 
     * @access public
     * @return $this
     */
    public function addOrder(Order $order) 
    {
        $this->order = $order;
        return $this;
    }

    /**
     * addCard 
     * 
     * @param CreditCard $card 
     * @access public
     * @return $this
     */
    public function addCard(CreditCard $card)
    {
        $this->card = $card;
        return $this;
    }
    
    /**
     * Set Gateway for Payment
     *
     * @param Gateway\IPaymentGateway $payGW 
     * @access public
     * @return bool
     */
    public function setPaymentGateway(Gateway\IPaymentGateway $payGW) 
    {
        $this->paymentGateway = $payGW;
        return $this;
    }

    /**
     * Set In/Out Source to write order data and responce from GW.
     *
     * @param IOSource\IOSourceInterface $logger 
     * @access public
     * @return $this
     */
    public function setLogger(IOSource\IOSourceInterface $logger) 
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * hasError 
     * 
     * @access public
     * @return $this
     */
    public function hasError() 
    {
        return $this->error;
    }

    /**
     * getErrorMsg
     *
     * Error message. Contents only user friendly message
     * 
     * @access public
     * @return $this
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * JSON formatted responce from Gateway
     *
     * @access public
     * @return $this
     */
    public function getResponce() 
    {
        return $this->responce;
    }

    /**
     * Set payment gateway, via GatewayFactory, using rule, or set error, if Factory fails 
     * 
     * @param mixed $rulename rule
     * @access public
     * @return $this
     */
    public function obtainPaymentGateway($rulename) 
    {
        $payGW = Gateway\GatewayFactory::ApplyRule($rulename, $this->card, $this->order);

        if ($payGW instanceof Gateway\IPaymentGateway) {
            $this->setPaymentGateway($payGW);
        } else {
            $this->error = true;
            $this->errorMsg = $payGW;
        }

        return $this;
    }

    /**
     * Request Gateway. If Gateway failed set error
     * 
     * @access public
     * @return $this 
     */
    public function execute() 
    {
        if (!$this->card || !$this->order) {
            throw new \Exception("You must add card and order first.");
        }

        if (!$this->paymentGateway) {
            $this->obtainPaymentGateway($rule = 'HQAssignment');
        }

        if ($this->hasError()) {
            return $this;
        }

        $this->paymentGateway->prepareRequest($card = $this->card, $order = $this->order);
        if (!$this->paymentGateway->makeRequest()) {
            $this->error = true;
            $this->errorMsg = $this->paymentGateway->error;
        }
        $this->responce = $this->paymentGateway->getResponce();

        return $this;
    }

    /**
     * save order data and responce, if gateway return smth 
     * 
     * @access public
     * @return $this 
     */
    public function writeLog() 
    {
        if (!$this->logger) {
            $this->setLogger(IOSource\MySQL::init());
        }

        $this->logger->save($this);

        return $this;
    }
}
