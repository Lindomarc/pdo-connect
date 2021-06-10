<?php
	
	
	namespace Lin;
	
	use Lin\Model\Category;
	use Lin\Model\Product;
	use Lin\Model\User;
	
	class CategoryPage extends AdminPage
	{
		
		public function __construct(array $options = [], string $tpl_sub_dir = "admin/")
		{
			parent::__construct($options, $tpl_sub_dir);
		}
		
		
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
 			    $category = new  Category();
				$category->setData($_POST);
				$category->save();
				
				header('Location: /admin/categories/'.$category->getIdcategory());
				exit;
			}
			$this->setTpl("categories/create");
		}
		
		public function edit($id)
		{
			$Category = new  Category();
			$Category->get($id);
			if ($_POST) {
				$Category->setData($_POST);
				$Category->save();
				 
			}
			$this->setTpl("categories/update",[
				'category' => $Category->getValues()
			]);
			
		}
		
		public function delete($id)
		{
 			$category = new  Category();
			$category->get($id);
			$category->delete();
			header('Location: /admin/categories');
			exit;
		}
		
		public function categoriesProducts($id)
		{
			$Category = new  Category();
			$Category->get($id);

			
			$this->setTpl("categories/products",[
				'category' => $Category->getValues(),
				'productsRelated' => $Category->getProducts(),
				'productsNotRelated' => $Category->getProducts(false)
				
			]);

		}
		
		public function addProduct($idcategory,$idproduct)
		{
			$Category = new  Category();
			$Category->get($idcategory);
			
			$Product = new  Product();
			$Product->get($idproduct);
			
			$Category->addProduct($Product);
			
			header("Location: /admin/categories/{$idcategory}/products");
			exit;
		}
		
		public function removeProduct($idcategory,$idproduct)
		{
			$Category = new  Category();
			$Category->get($idcategory);
			
			$Product = new  Product();
			$Product->get($idproduct);
			
			$Category->removeProduct($Product);
			
			header("Location: /admin/categories/{$idcategory}/products");
			exit;
		}
	}