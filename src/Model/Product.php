<?php 

namespace Lin\Model;

use Lin\DB\Sql;


class Product extends Model 
{
	public string $table = 'tb_categories';
	
	protected array $fields = [
		'idproduct',
		'desproduct',
		'vlprice',
		'vlwidth',
		'vlheight',
		'vllength',
		'vlweight',
		'desurl',
		'desphoto'
	];
	
	public function listAll(): array
	{
		$sql = new Sql();
		$resuts =  $sql->select('SELECT * FROM tb_products');

		return $this->checkList($resuts);
	}
	
	public static function checkList($list)
	{
		foreach ($list as &$row){
			$product = new Product();
			$product->setData($row);
			$product->checkPhoto();
			$row = $product->getValues();
			
		}
		
		return $list;
		
	}
	
	public function save()
	{
		$sql = new Sql();
		
		$results =  $sql->select('CALL sp_products_save(
			:idproduct,
			:desproduct,
			:vlprice,
			:vlwidth,
			:vlheight,
			:vllength,
			:vlweight,
			:desurl
		)',[
			'idproduct' => $this->getIdproduct(),
			'desproduct' => $this->getDesproduct(),
			'vlprice' => $this->getVlprice(),
			'vlwidth' => $this->getVlwidth(),
			'vlheight' => $this->getVlheight(),
			'vllength' => $this->getVllength(),
			'vlweight' => $this->getVlweight(),
			'desurl' => $this->getDesurl()
		]);
		
		$this->setData($results[0]);
		
	}
	
	public function get($id)
	{

 		$sql = new Sql();
		$results = $sql->select('SELECT * FROM tb_products WHERE idproduct = :idproduct',[
			'idproduct' => $id
		]);
		
 		$this->setData($results[0]);
	}
	
	public function delete()
	{
		$sql = new Sql();

		$sql->query('DELETE FROM  tb_products WHERE idproduct = :idproduct',[
			':idproduct' => $this->getIdproduct()
		]);
		$this->deletePhoto();
		
	}
	
	
	public function checkPhoto()
	{
		$dist = 
			ROOT.
			'public' . DIRECTORY_SEPARATOR .
			'assets'.DIRECTORY_SEPARATOR.
			'img'.DIRECTORY_SEPARATOR.
			'products'.DIRECTORY_SEPARATOR.
			$this->getIdproduct().'.jpg';
			

		
		if (file_exists($dist)) {
			$url = '/assets/img/products/'.$this->getIdproduct().'.jpg';				
		} else {
			$url = '/assets/img/products/product.jpg';				
		}
		
		$this->setDesphoto($url);
	}
	
	public function deletePhoto()
	{
		$dist =
			ROOT.
			'public' . DIRECTORY_SEPARATOR .
			'assets'.DIRECTORY_SEPARATOR.
			'img'.DIRECTORY_SEPARATOR.
			'products'.DIRECTORY_SEPARATOR.
			$this->getIdproduct() . '.jpg';
		
		if (file_exists($dist)) {
			unlink($dist);
		}
	}
	
	
	public function setPhoto($file)
	{
		if ($file) {
			
			switch ($file['type']) {
				case 'image/jpeg':
					$image = imagecreatefromjpeg($file['tmp_name']);
					break;
				case 'image/gif':
					$image = imagecreatefromgif($file['tmp_name']);
					break;
				case 'image/png':
					$image = imagecreatefrompng($file['tmp_name']);
					break;
			}
			
			if (isset($image)) {
				   
			    $dist = ROOT .
				    'public' . DIRECTORY_SEPARATOR .
				    'assets' . DIRECTORY_SEPARATOR .
				    'img' . DIRECTORY_SEPARATOR .
				    'products' . DIRECTORY_SEPARATOR .
				    $this->getIdproduct() . '.jpg';
 				
				imagejpeg($image, $dist);
			    imagedestroy($image);
		    }
 		}
	}
	
	public function getFromUrl($desUrl)
	{
		$query = "
			SELECT *
			FROM tb_products
			WHERE desurl = :desurl
			LIMIT 1;
		";
		
		$sql = new Sql();
		
		$result = $sql->select($query,[
			':desurl' => $desUrl
		]);
		
		$this->setData($result);
		$result = Product::checkList($result);
		
		return $result[0] ?? array();
		
	}
	
	public function getCategories()
	{
		$sql = new Sql();
		$query = "
			SELECT *
			FROM tb_categories a
		         INNER JOIN  tb_productscategories b
		                     ON a.idcategory = b.idcategory
			WHERE b.idproduct = :idproduct		
		";
		
		$results =  $sql->select($query,[
			':idproduct' => $this->getIdproduct() 
		]);

		return $results ?? array();
	}
	
	
}