<?php 

namespace Lin\PhpClass\Db;


class Sql {
	
	private $conn;
	
	private $config;
	
	public function __construct()
	{
		$this->loadConfig();
		
		$dsn = "mysql:dbname={$this->config->DBNAME};host={$this->config->HOSTNAME}";
		
		$username = $this->config->USERNAME;
		$password = $this->config->PASSWORD;
		
  		$this->conn = new \PDO($dsn, $username, $password);
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
		
		$baseDir = dirname(dirname(dirname(dirname($vendorDir))));
		
		$file = $baseDir.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config_database.json';

		if(file_exists($file)) {
			
			$this->config = json_decode(file_get_contents($file));
			
		} else {            			
			
			echo "<p>Not Found config file:</p>";			
			echo '<p>/config/config_database.json</p>';			
			die();
			
		}
	}
	
}