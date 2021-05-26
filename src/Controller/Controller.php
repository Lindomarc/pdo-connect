<?php		
	namespace Lin\PhpClass\Controller;
	
	use Rain\Tpl;
	
	
	class Controller
	{
		private $tpl;
		
		private $options = [];
		
		private $defaults = [
			"header"=>true,
			"footer"=>true,
			"data"=>[]
		];
		
		public $pathTheme;
		
		public function __construct( array $options = [])
		{
			$this->options =  array_merge($this->defaults, $options);
			
			$this->configPathTheme();
			
			
			$this->tpl = new Tpl();
 			if ($this->options['data']) {
				$this->setData($this->options['data']);
			}
			if ($this->options['header']){
				$this->tpl->draw("header");	
			}
			
		}
		
		private function configPathTheme()
		{
			if ($this->pathTheme) {
				$config = array(
					"tpl_dir"       => VIEWS.$this->pathTheme.DIRECTORY_SEPARATOR,
					"cache_dir"     => VIEWSCACHE.$this->pathTheme.DIRECTORY_SEPARATOR,
					"debug"         => DEBUG
				);
			}else{
				$config = array(
					"tpl_dir"       => VIEWS,
					"cache_dir"     => VIEWSCACHE,
					"debug"         => DEBUG
				);
			}

			return Tpl::configure( $config );			
			//return $this->pathTheme;
		}
		
		
		public function setTpl($name, $data = [], $returnHtml = false)
		{
			$this->setData($data);
			return $this->tpl->draw($name, $returnHtml);
		}
		
		public function view($name, $data = [], $returnHtml = false)
		{
			$this->setTpl($name,$data , $returnHtml);
		}
		
		public function __destruct()
		{
			if ($this->options['footer'] === true) {
				$this->tpl->draw("footer", false);
			}
		}
		
		private function setData($data = [])
		{
			foreach ($data as $key => $value){
				$this->tpl->assign($key,$value);
			}
		}
		
	}