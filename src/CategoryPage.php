<?php
	
	
	namespace Lin;
	
	use Lin\Model\Category;
	
	class CategoryPage extends AdminPage
	{
 
		public  function index()
		{
			$Category = new Category;
			
			$categories = $Category::listAll();
			
			$this->setTpl("categories/index",[
				'categories' => $categories
			]);
		}
		
		public function create()
		{
			if ($_POST) {
			    $user = new  User();
				$user->setData($_POST);
			    $user->save();
				header('Location: /admin/users');
				exit;
			}
			$this->setTpl("users/create");
		}
		
		public function edit($iduser)
		{			    
			$user = new  User();
			
			$user->get($iduser);
			
			if ($_POST) {
				
				$user->setData($_POST);
				$user->update();
				
			}
			
			$this->setTpl("users/edit",[
				'user' => $user->getValues()
			]);
		}
		
		public function delete($iduser)
		{
			$user = new  User();
			$user->get((int)$iduser);
			$user->delete();
			
			header('Location: /admin/users');
			exit;
		}
		
		
	}