<?php 

namespace Lin\Model;

use Lin\DB\Sql;


class Model {

	private array $values = [];
	
	public string $table;
	
	public $joins;
	
	public function setData($data)
	{
 		foreach ($data as $key => $value)
		{
			$attribute = ucfirst($key);
			$this->{"set".$attribute}($value);
		}
	}
	
	/**
	 * @param $name
	 * @param $args
	 * @return mixed
	 */
	public function __call($name, $args)
	{
		$method = substr($name, 0, 3);
		$fieldName = strtolower(substr($name, 3, strlen($name)));

		if (in_array($fieldName, $this->fields))
		{			
			switch ($method)
			{
				case "get":
					return (isset($this->values[$fieldName]))?$this->values[$fieldName]:NULL;
				break;

				case "set":
					$this->values[$fieldName] = $args[0];
				break;
			}
		}
		return  true;
	}

	public function getValues(): array
	{
		return $this->values;
	}
	
	/**
	 * @return array
	 */
	public function listAll()
	{
		$sql = new Sql();
		$query = "SELECT * FROM $this->table";
		$query .= ' '.$this->getJoins(); 
		return $sql->select($query);
	}
	
	/**
	 * @return mixed
	 */
	public function getJoins()
	{
		return $this->joins;
	}
	
	
}