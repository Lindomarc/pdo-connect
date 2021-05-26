<?php
	
	namespace Lin\PhpClass\Controller;
	
	use Lin\PhpClass\Model\User;
	use Lin\PhpClass\Controller\Auth;
	
	class AdminController extends Controller
	{
		public $pathTheme = 'admin';
		
		public function __construct( array $options = [])
		{
			User::verifyLogin();
			
			parent::__construct(  $options);
		}
		
	}