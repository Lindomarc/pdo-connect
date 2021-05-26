<?php
	namespace Lin\PhpClass\Model;
	
	use http\Header;
	use Lin\PhpClass\Db\Sql;
	use Slim\Slim;
	
	class User extends Model
	{
		const SESSION = "User";
		
		private $table = 'tb_users';
		
		protected $fields = [
			"id",
			"person_id",
			"username",
			"password",
			"inadmin",
			"dtergister"
		];
		
 		protected function beforeSave()
	    {
	    	parent::beforeSave();
	    	
		    if (!!$this->getPasswordAttribute()) {
			    $password = password_hash($this->getPasswordAttribute(), PASSWORD_DEFAULT, [
				    "cost"=>12
			    ]);
			    $this->setPasswordAttribute($password);
		    }
	    }
		
		
		public static function login($login, $password)
		{
			$sql = new Sql();
			
			$results = $sql->select(
				'SELECT * FROM tb_users WHERE username = :LOGIN',[
				':LOGIN' => $login
			]);
			
			if (!!count($results)) {
				
				$data = $results[0];
				
				if (!!password_verify($password, $data['password'])) {
					unset($data['password']);
					$user = new User();
					
					$user->setId($data);

					$_SESSION[User::SESSION] = $user->getValues();
					return true;
				}
			}
			return false;
		}
		
		public static function verifyLogin( bool $inadmin = true)
		{
 			if (
				!isset($_SESSION[User::SESSION])
				||
				!$_SESSION[User::SESSION]
				||
				!(int)$_SESSION[User::SESSION]['id']
				||
				(bool)$_SESSION[User::SESSION]['inadmin'] !== $inadmin
			) {

			    header("Location: /admin/login");
			    exit;
			}
		    else {
			    return header("Location: /admin");
		    }
		}
		
		public static function logout()
		{
			session_destroy();
		}
		
		
		public static function listAll()
		{
			$sql = new Sql();
			return $sql->select("
				SELECT * 
				FROM tb_users a 
			    INNER JOIN tb_persons b  on a.person_id = b.id
				ORDER BY a.id
		    ");
		}
		
		public function save()
		{
			$this->beforeSave();
			
			$sql= new Sql();

			$data = [
				':desperson' => $this->getDespersonAttribute(),
				':username' => $this->getUsernameAttribute(),
				':password' => $this->getPasswordAttribute(),
				':desemail' => $this->getDesemailAttribute(),
				':nrphone' => $this->getNrphoneAttribute(),
				':inadmin' => $this->getInadminAttribute()
			];
			
			$results = $sql->select(
				'CALL sp_users_save(:desperson, :username, :password, :desemail,:nrphone,:inadmin)',
				$data
			); 
			
 			if (!!$results[0]) {
				$this->setData($results[0]);
			}
			
		}
		
		
	}