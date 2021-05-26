<?php
	
	namespace Lin\PhpClass\Model;
	
	use Lin\PhpClass\Helper\Utils;
	
	class Model
	{
		private $values = [];
		
		protected function beforeSave()
		{
			
		}
		
		public function __call($name, $arguments)
		{
			$method = substr($name,0,3);
 			$name =  Utils::snakeToCamel($name);
			$fieldName = strtolower(substr($name, 3, strlen($name)));
			
			
			$attribute =substr($name, strpos($name,'Attribute'),strlen($fieldName));
			$fieldName = rtrim($fieldName,strtolower($attribute));

			switch ($attribute){
				case 'Attribute':
					switch ($method)
					{
						case "get":
							if (isset($this->{$fieldName})) {
								return $this->{$fieldName};    
							}else {
								$this->set{$fieldName} = $arguments[0];
							}
							
							break;
						
						case "set":
							$this->{$fieldName} = $arguments[0];
							break;
					}
				break;
					
				default:
					if (in_array($fieldName, $this->fields))
					{
						switch ($method)
						{
							case "get":
								return $this->values[$fieldName];
								break;
							
							case "set":
								$this->values = $arguments[0];
								break;
						}
						
					}
				break;
			}
		}
		
		public function setData($data)
		{
			foreach ($data as $key => $value)
			{
				$this->{"set".$key}($value);
			}
		}
		
		public function setAttributes($data)
		{
			foreach ($data as $key => $value)
			{
				$this->{"set".$key.'Attribute'}(trim($value));
			}
		}
		
		public function getValues()
		{
			return $this->values;
		}
		
	}