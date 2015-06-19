<?php
namespace Payment\Config;

/**
 * Class MySQLConfig 
 * 
 * MySQL Configuration class
 *
 * @package Payment\Config 
 *  
 */
class MySQLConfig 
{
    /**
     * Return MySQL configuration array.
     *
     * @param string $name Name of config.
     * @static
     * @access public
     * @return array
     */
    public static function getConfig($name) 
    {
        $configMethod = 'get' . $name . "Cfg";

        if(!method_exists(__CLASS__, $configMethod)) {
            throw new \Exception("Unknown MySQL config: " . $name);
        }

        return self::$configMethod();
    }

    /**
     * Return MySQL Default configuration array.
     *
     * @static
     * @access public
     * @return array 
     */
    public static function getDefaultCfg() 
    {
        return array(
            'host'   => 'localhost',
            'user'   => 'hq_user',
            'pass'   => 'phpround1',
            'dbname' => 'hq',
            'table'  => 'order_history'
        );
    }
}
