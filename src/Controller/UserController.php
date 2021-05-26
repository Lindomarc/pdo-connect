<?php
		
	namespace Lin\PhpClass\Controller;
		
	use Lin\PhpClass\Helper\Utils;
	use Lin\PhpClass\Model\User;
	
	class UserController extends AdminController
	{
		public function index(){
			
			$users = User::listAll();
 			$this->view('users/index', [
				'users' => $users
			]);
 			
		}
		
		public function create()
		{
			$this->view('users/create');
		}
		
		public function store()
		{
			$user = new User();
			
			$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
			
			$user->setAttributes($_POST);
			
			$user->save();
			
			Utils::redirect('/admin/users');
		}
	
	}