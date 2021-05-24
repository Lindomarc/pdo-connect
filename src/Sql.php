<?php 

namespace PdoConnect\DB;

class Sql {
	
	private $conn;
	private $config;
	
	public function __construct()
	{
		$this->loadConfig();
		
 		$this->conn = new \PDO('mysql:host='.$this->config->HOSTNAME.';dbname='.$this->config->DBNAME, 
			$this->config->USERNAME,
			$this->config->PASSWORD
		);

	}

	private function setParams($statement, $parameters = array())
	{

		foreach ($parameters as $key => $value) {
			
			$this->bindParam($statement, $key, $value);

		}

	}

	private function bindParam($statement, $key, $value)
	{

		$statement->bindParam($key, $value);

	}

	public function query($rawQuery, $params = array())
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

	}

	public function select($rawQuery, $params = array()):array
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}
	
	
	private function loadConfig()
	{
		$vendorDir = dirname(dirname(__FILE__));
		$baseDir = dirname(dirname(dirname($vendorDir)));
		$file = $baseDir.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config_database.json';

		if(file_exists($file)) {
			$this->config = json_decode(file_put_contents($file));
		} else {
			echo "<p>Not Found config file:</p>";
			echo '<p>/config/config_database.json</p>';
			die();
		}
	}

}