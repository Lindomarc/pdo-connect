<?php
	
	
	namespace Lin;
	
	use Lin\Model\Product;
	
	class ProductsPage extends AdminPage
	{
		
		public function __construct(array $options = [], string $tpl_sub_dir = "admin/")
		{
			parent::__construct($options, $tpl_sub_dir);
		}
		
		
		public  function index()
		{
			$Product = new Product();
			
			$products = $Product->listAll();

			$this->setTpl("products/index",[
				'products' => $products
			]);
		}
		
		public function create()
		{
			if ($_POST) {
				$Product = new Product();
				$Product->setData($_POST);

				$Product->save();
				
				header('Location: /admin/products/'.$Product->getIdproduct());
				exit;
			}
			$this->setTpl("products/create");
		}
		
		public function edit($id)
		{
			$Product = new  Product();
			$Product->get($id);
			
			if ($_POST) {
				$Product->setData($_POST);
				$Product->save();
				$Product->setPhoto($_FILES['file']);
			}
			$Product->checkPhoto();
			
			
			$this->setTpl("products/update",[
				'product' => $Product->getValues()
			]);
		}
		
		public function delete($id)
		{
 			$category = new  Product();
			$category->get($id);
			$category->delete();
			header('Location: /admin/products');
			exit;
		}
		 
	}