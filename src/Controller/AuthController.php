<?php
	
	
	namespace Lin\PhpClass\Controller;
	
	use http\Env\Request;
	use http\Header;
	use Lin\PhpClass\Model\User;
	use Lin\PhpClass\Controller\Controller;
	use Lin\PhpClass\Helper\Utils;
	
	class AuthController  
	{
		 
		public function login()
		{
			$options = [
				"header"=>false,
				"footer"=>false,
			];
			$page = new Controller($options);
 			return $page->view('admin/login');
		}
		
		public function pass()
		{
			if (User::login($_POST['username'],$_POST['password'])) {				
				$url = '/admin';
			} else {
				$url = '/admin/login';
			}
			Utils::redirect($url);
		}
		 
		public function logout()
		{
			User::logout();		
			Utils::redirect('/admin/login');
		}
		
	}