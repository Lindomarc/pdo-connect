<?php
	
	namespace Lin;
	


	use Lin\Model\Category;
	use Lin\Model\Product;
	use Rain\Tpl;
	
	class Page
	{
		private Tpl $tpl;
		
		private array $options;
		
		private array $defaults  = [
			'header' => true,
			'footer' => true,
			'data' => []
		];
 		
		public function __construct(array $options = [], $tpl_sub_dir = "")
		{
			$this->options = array_merge($this->defaults, $options);

			// config
			$config = array(
				"base_url"      => null,
				"tpl_dir"       => VIEW.$tpl_sub_dir,
				"cache_dir"     => VIEW_CACHE,
				"debug"         => true
			);

			Tpl::configure( $config );
			
			$this->tpl = new Tpl;
 			
			if (!!$this->options['data']) $this->setData($this->options['data']);
			
			if (!!$this->options['header']) $this->tpl->draw("header", false);
		}
		
		public function setTpl($name, $data = [], $returnHTML = false)
		{
			$this->setData($data);
			return $this->tpl->draw($name, $returnHTML);			
		}
		
		public function setData($data)
		{
			if ($data) {				
				foreach ($data as $key => $val) {
					$this->tpl->assign($key, $val);
				}
			}
		}
		
		public function category($id)
		{
			$Category = new  Category();
			$Category->get($id);

			
			$this->setTpl('category', [
				'category' => $Category->getValues(),
				'products' => []
			]);
		}
		
		public function __destruct()
		{
			if (!!$this->options['footer']) $this->tpl->draw("footer", false);
		}
		
		public  function index()
		{
			$Product = new Product();
			
			$products = $Product->listAll();

			$this->setTpl("index",[
				'products' => $Product->checkList($products)
			]);
		}
		
	}