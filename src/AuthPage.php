<?php
	
	
	namespace Lin;
	
	
	class AuthPage extends Page
	{	
		public function __construct(array $options = [], string $tpl_sub_dir = "admin/")
		{
			
			parent::__construct($options, $tpl_sub_dir);
			
		}
	}