<?php 

namespace Lin\Model;

use Lin\DB\Sql;
 

class Category extends Model 
{
	
	public string $table = 'tb_categories';
	 
	
	public function listAll(): array
	{
		$sql = new Sql();
		return $sql->select('SELECT * FROM tb_categories ORDER BY descategory');
	}	
	
}