<?php
	
	
	namespace Lin;
	
	use Lin\Model\User;
	
	class AdminPage extends Page
	{	
		public function __construct(array $options = [], string $tpl_sub_dir = "admin/")
		{
			User::verifyLogin();
			
			parent::__construct($options, $tpl_sub_dir);
			
		}
	}