<?php 

namespace Lin\Model;

use Lin\DB\Sql;
 

class Category extends Model 
{
	
	public string $table = 'tb_categories';
	
	protected array $fields = [
		'idcategory',
		'descategory'
	];
	
	public function listAll(): array
	{
		$sql = new Sql();
		return $sql->select('SELECT * FROM tb_categories ORDER BY descategory');
	}	
	
	public function save()
	{
		
		
		$sql = new Sql();

		$results =  $sql->select('CALL sp_categories_save(
			:idcategory,
			:descategory
		)',[
			'idcategory' => $this->getIdcategory(),
			'descategory' => $this->getDescategory()
		]);
		
		$this->setData($results[0]);
		
		$this->updateFile();
		
	}
	
	public function get($id)
	{

 		$sql = new Sql();
		$results = $sql->select('SELECT * FROM tb_categories WHERE idcategory = :idcategory',[
			'idcategory' => $id
		]);
		
 		$this->setData($results[0]);
	}
	
	public function delete()
	{
		$sql = new Sql();

		$sql->query('DELETE FROM  tb_categories WHERE idcategory = :idcategory',[
			':idcategory' => $this->getIdcategory()
		]);
		
		$this->updateFile();
		
	}
	
	
	public function updateFile()
	{
		$categories = (new Category())->listAll();
		
		$html = [];
		
		foreach ($categories as $row){
			array_push($html, '<li><a href="/category/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');
		}
		
		file_put_contents(VIEW.'categories-menu.html',implode('',$html));
	}
	
	public function getProducts($related = true): array
	{		
		$sql = new Sql();
		
		if ($related) {
		    $included = 'IN';
		}else{
			$included = 'NOT IN';			
		}
		
		$query = "SELECT * 
			FROM tb_products
			WHERE idproduct  {$included}(
			    SELECT a.idproduct
			    FROM tb_products a
			        INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
			    WHERE b.idcategory = :idcategory 
		);";
		
		$results = $sql->select($query,[
			':idcategory' => $this->getIdcategory()
		]);
		
		return $results??[];
		
	}
	
	public function addProduct(Product $product)
	{
		$sql = new Sql();
		
		$sql->select("
			INSERT INTO tb_productscategories (idcategory, idproduct)
			VALUES(:idcategory, :idproduct)
			",[
				':idcategory' => $this->getIdcategory(),
				':idproduct' => $product->getIdproduct()
			]
		);
		
	}
		
	public function removeProduct(Product $product)
	{
		$sql = new Sql();
		$sql->select("
			DELETE from tb_productscategories 
			WHERE idcategory = :idcategory 
		  	AND idproduct = :idproduct;",[
				':idcategory' => $this->getIdcategory(),
				':idproduct' => $product->getIdproduct()
			]
		);
	}
	
	
	public function getProductsPage($page = 1,  $itensPerPage = 4): array
	{
		$start = ($page - 1) * $itensPerPage;
		
		$query = "
			SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_products a
			INNER JOIN tb_productscategories b on a.idproduct = b.idproduct
			INNER JOIN tb_categories c ON c.idcategory = b.idcategory
			WHERE c.idcategory = :idcategory
			LIMIT {$start}, {$itensPerPage};
		";
		
		
		$sql = new Sql();
		
		$results = $sql->select($query,[
			':idcategory'=>$this->getIdcategory()
		]);
		
		$count = $sql->select("SELECT found_rows() as nrtotal;");
		
		return [
			'data' => Product::checkList($results),
			'total' => (int)$count[0]['nrtotal'],
			'pages' => ceil((int)$count[0]['nrtotal']/$itensPerPage)
		];
	}
	
}