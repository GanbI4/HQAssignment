<?php
namespace Payment\Gateway;

use Payment\CreditCard;
use Payment\Order;
use Payment\Config\BraintreeApi;

/**
 * Class Braintree 
 * 
 * Represent Braintree Gateway. Wrap over Braintree php sdk
 *
 * @uses GatewayModel
 * @uses IPaymentGateway
 * @package Payment\Gateway
 * @property array $transactionAttrs
 */
class Braintree extends GatewayModel implements IPaymentGateway 
{
    protected $name = "Braintree";

    public $transactionAttrs;

    /**
     * __construct 
     * 
     * @param string $credName credential config name (See Config\BraintreeApi)
     * @access public
     */
    public function __construct($credName='test') 
    {
        $credential = BraintreeApi::getCredential($credName);

        \Braintree_Configuration::environment($credential['environment']);
        \Braintree_Configuration::merchantId( $credential['merchantId']);
        \Braintree_Configuration::publicKey(  $credential['publicKey']);
        \Braintree_Configuration::privateKey( $credential['privateKey']);
    }

    /**
     * Build Transaction array for Braintree Api 
     *
     * @param CreditCard $card 
     * @param Order $order 
     * @access public
     * @return $this
     */
    public function prepareRequest(CreditCard $card, Order $order) 
    {
        $merchantAccountId = BraintreeApi::getAccountId($order->currency);

        $this->transactionAttrs = array(
            'amount' => $order->price,
            'merchantAccountId' => $merchantAccountId,
            'creditCard' => array(
                'cardholderName' => $card->holderFirstName . " " . $card->holderLastName,
                'cvv' => $card->ccv,
                'expirationMonth' => (int) $card->expirationMonth,
                'expirationYear'  => (int) $card->expirationYear,
                'number' => $card->number,
            ),
            'customer' => array(
                'firstName' => $order->customerFirstName,
                'lastName'  => $order->customerLastName
            )
        );

        return $this;
    }

    /**
     * Execute request to Braintree API, return true if success
     * 
     * @access public
     * @return bool 
     */
    public function makeRequest() 
    {
        $result = \Braintree_Transaction::sale($this->transactionAttrs);
        if (!$result->success) {
            $errorsArray = array();
            foreach ($result->errors->deepAll() as $error) {
                $errorData['code'] = $error->code;
                $errorData['attribute'] = $error->attribute;
                $errorData['message'] = $error->message;
                $errorsArray[] = $errorData;
            }

            $this->responce = json_encode($errorsArray);
            $this->error = parent::BASE_ERROR_MSG;
            return false;
        }
        $this->responce = json_encode($result->transaction);
        return true;
    }
}
