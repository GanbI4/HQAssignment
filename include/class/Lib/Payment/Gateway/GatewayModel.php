<?php
namespace Payment\Gateway;

/**
 * Class GatewayModel 
 * 
 * Parent class for IPaymentGateway classes
 *
 * @package Payment\Gateway 
 * @property string $error Error message if Gateway cant process request
 */
class GatewayModel 
{
    const BASE_ERROR_MSG = "Sorry! Something goes wrong. Please check your card data, or try again later.";

    public $error;
    public $responce;

    /**
     * Responce from gateway (JSON)
     *
     * @access public
     * @return string JSON answer from Gateway
     */
    public function getResponce()
    {
        return $this->responce;
    }

    /**
     * getName
     * 
     * Name of Gateway
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Error message
     *
     * @access public
     * @return string 
     */
    public function getError()
    {
        return $this->error;
    }

}
