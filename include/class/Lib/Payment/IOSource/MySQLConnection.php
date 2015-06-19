<?php
namespace Payment\IOSource;

use Payment\Config\MySQLConfig;

/**
 * Class MySQLConnection 
 * 
 * @package Payment\IOSource 
 */
class MySQLConnection extends \mysqli
{
	public $query;
	public $result;
	public $query_list;

	private static $instances = array();
	protected static $configs = array();
	
    /**
     * __construct 
     * 
     * @param mixed $cname 
     * @access private
     * @return void
     */
	private function __construct($cname)
	{
		if (is_array($cname)) {
			$config = $cname;
		}
		else {
			$config = $this->load_config($cname);
		}

		parent::__construct($config['host'], $config['user'], $config['pass'], $config['dbname']);

		$this->set_charset("utf8");
	}

	/**
	 * @static
	 * @param string $name connection config name
	 * @return MySQLConnection
	 */
	public static function instance($name = "Default")
	{
		if (!isset(self::$instances[$name])) {
			self::$instances[$name] = new self($name);
		}
		return self::$instances[$name];
	}

	private function load_config($name)
	{
		if (empty(self::$configs)) {
			self::$configs[$name] = MySQLConfig::getConfig($name);
		}
		return self::$configs[$name];
	}

	/**
	* query
	*
	* @param string $query
	* @return mysqli_result
	*/
	public function query($query)
	{
    	$this->query = $query;

		$this->query_list[] = $this->query;

		$this->result = parent::query($this->query);

		return $this->result;
	}

	/**
	* insert
	*
	* @param array $data column => value
	* @param string $table target table
	* @return int insert_id
	*/
	public function insert(array $data, $table)
	{
		$keys = array();
		$values = array();

		foreach ($data as $key => $value) {
			if (is_null($value) || $value === "NULL") {
				$value = "NULL";
			} else if (!is_numeric($value)) {
				$value = "'".$this->escape($value)."'";
			}
			$keys[] = "`$key`";
			$values[] = $value;
		}

		$this->query("INSERT INTO `$table` (".implode(", ", $keys).") VALUES (".implode(", ", $values).")");

		return $this->insert_id;
	}

    /**
     * escape 
     * 
     * @param mixed $str 
     * @access public
     * @return string 
     */
	public function escape($str)
	{
		return $this->real_escape_string($str);
	}
}
