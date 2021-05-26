<?php
	namespace Lin\PhpClass\Helper;
	
	class Utils{
		
		public static function redirect($url)
		{
			
			header("Location: {$url}");
			exit;
			
 		}
		
		public static function snakeToCamel (string $str) {
			return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $str))));
		}
		
		
	}