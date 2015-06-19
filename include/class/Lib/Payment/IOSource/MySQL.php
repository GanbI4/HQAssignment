<?php
namespace Payment\IOSource;

use Payment\CardPayment;
use Payment\Config\MySQLConfig;

/**
 * Class MySQL 
 * 
 * In\Out Source for saving order data and response from payment gateway to database table.
 *
 * @uses IOSourceInterface
 * @package Payment\IOSource 
 */
class MySQL implements IOSourceInterface 
{
    private static $instance;
    private $db;
    public $table;
    

    /**
     * __construct 
     * 
     * @access private
     * @return void
     */
    private function __construct() 
    {
        $this->db = MySQLConnection::instance();
        $config = MySQLConfig::getConfig($name = "Default");
        $this->table = $config['table'];
    }

    /**
     * init 
     * 
     * @static
     * @access public
     * @return MySQL
     */
    public static function init() 
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * buildQueryArray 
     * 
     * @param CardPayment $payment 
     * @access public
     * @return array
     */
    public function buildQueryArray(CardPayment $payment)
    {
        $order = $payment->order;
        $card  = $payment->card;

        $success = $payment->hasError() ? 0 : 1;

        $queryArr = array(
            /* Save amounts in cents */
            'price' => str_replace(".", "", $order->price),
            'currency'      => $order->currency,
            'customer_name' => $order->customerName,
            'card_holder' => $card->holder,
            'card_number' => $card->number,
            'card_type'   => $card->type,
            'card_expiration_month' => $card->expirationMonth,
            'card_expiration_year'  => $card->expirationYear,
            'gateway' =>  $payment->paymentGateway->getName(),
            'success' =>  $success,
            'responce' => $payment->getResponce()
        );

        return $queryArr;

    }

    /**
     * queryInsert 
     * 
     * @param array $queryArray 
     * @param MySQLConnection $dbConn 
     * @param string $table Name of MySQL table to save data
     * @access public
     * @return void
     */
    public function queryInsert($queryArray, MySQLConnection $dbConn, $table)
    {
        $dbConn->insert($queryArray, $table);
    }

    /**
     * Save order data and gateway responce. If there was no responce, do nothing.
     *
     * @param CardPayment $payment 
     * @access public
     * @return void
     */
    public function save(CardPayment $payment)
    {
        if(!$payment->getResponce()) {
            return;
        }

        $queryArr = $this->buildQueryArray($payment);

        $this->queryInsert($queryArr, $this->db, $this->table);
    }
}
