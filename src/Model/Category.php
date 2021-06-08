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
		
	}
	
	
	
}