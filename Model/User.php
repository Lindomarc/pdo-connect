<?php 

namespace Lin\Model;

use Lin\DB\Sql;
use Lin\Mailer;
use mysql_xdevapi\Exception;

class User extends Model {
	
	const SECRET = "0123456789123456";
	const SECRET_IV = "6543219876543210";

	
	const SESSION = "User";

	protected array $fields = [
		"iduser", 
		"desperson",
		"deslogin",
		"despassword",
		"desemail",
		"nrphone",
		"inadmin",
		"dtergister"
	];
	
	public string $table = 'tb_users';
	
	private function beforeSave()
	{		
		if ($this->getDespassword()) {
			$passorword = password_hash($this->getDespassword(), PASSWORD_DEFAULT, [
				
				"cost"=>12
			
			]);
			
			$this->setDespassword($passorword);
		}

		$this->setInadmin($this->getInadmin() === 'on' ?1:0); 
	}
	
	public static function login($login, $password):User
	{

		$db = new Sql();

		$results = $db->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));
		
		if (!count($results)) {
			throw new \Exception("Não foi possível fazer login.");
		}
 
		$data = $results[0];

		if (!!password_verify($password, $data["despassword"])) {

			$user = new User();
			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getValues();
 
			return $user;

		} else {

			throw new \Exception("Não foi possível fazer login.");

		}

	}

	public static function logout()
	{

//		session_destroy();
		$_SESSION[User::SESSION] = NULL;

	}

	public static function verifyLogin($inadmin = true): bool
	{
 		if (
			!isset($_SESSION[User::SESSION])
			|| 
			!$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0
			||
			(bool)$_SESSION[User::SESSION]["iduser"] !== $inadmin
		) {
		   
		    header('Location: /auth/login');
		    exit;
	    } 
 		return true;
	}
	
	public function listAll(): array
	{
		$this->joins = 'INNER JOIN tb_persons USING(idperson) ORDER BY desperson';
		return parent::listAll(); 
	}
	
	public function save()
	{
		$this->beforeSave();
		
		$sql = new Sql();
		
 		$results = $sql->select('CALL sp_users_save(
			:desperson,
			:deslogin,
			:despassword,
			:desemail,
			:nrphone,
			:inadmin
		)',[
			':desperson' => $this->getDesperson(),
			':deslogin' => $this->getDeslogin(),
			':despassword'  => $this->getDespassword(),
			':desemail' => $this->getDesemail(),
			':nrphone' => $this->getNrphone(),
			':inadmin' => $this->getInadmin()
		]);
		$this->setData($results[0]);
	}
	
	public function update()
	{
		$this->beforeSave();
		
		$sql = new Sql();
		
 		$results = $sql->select('CALL sp_usersupdate_save(
 		    :iduser,
			:desperson,
			:deslogin,
			:despassword,
			:desemail,
			:nrphone,
			:inadmin
		)',[
			':iduser' => $this->getIduser(),
			':desperson' => $this->getDesperson(),
			':deslogin' => $this->getDeslogin(),
			':despassword'  => $this->getDespassword(),
			':desemail' => $this->getDesemail(),
			':nrphone' => $this->getNrphone(),
			':inadmin' => $this->getInadmin()
		]);
		$this->setData($results[0]);
	}
	
	
	public function get($iduser)
	{
		$sql = new Sql();
		
		$results = $sql->select('SELECT * 
			FROM tb_users a 
    		INNER JOIN tb_persons b USING(idperson) 
			where a.iduser = :iduser',[
			':iduser' => $iduser 
		]);
		
		$this->setData($results[0]);
	}
	
	public function delete()
	{
		$sql = new Sql();
		
		$sql->query('CALL sp_users_delete(:iduser)',[
			':iduser' => $this->getIduser()
		]);
		
	}
	
	/**
	 * @throws \Exception
	 */
	public static function getForgot()
	{ 
		$sql = new Sql();
		$results = $sql->select("
			SELECT * 
			FROM tb_persons  a
			INNER JOIN tb_users b USING(idperson)
			WHERE a.desemail = :email
		",[
			':email' => trim($_POST['email'])
		]);

		if (!count($results)) {
		   return false;
		} else {
			
			$data = $results[0];
			
			$results2 = $sql->select("CALL sp_userspasswordsrecoveries_create(
				:iduser,
				:desip
			)",[
				':iduser' => $data['iduser'],
				':desip' => $_SERVER['REMOTE_ADDR']
			]);
			if (!count($results2)) {
				return false; 
			} else {
				$dataRecovery = $results2[0];
			}
			
			$code = openssl_encrypt($dataRecovery['idrecovery'], 
				'AES-128-CBC', 
				pack("a16", User::SECRET), 
				0, 
				pack("a16", User::SECRET_IV)
			);
			
			$code = base64_encode($code);
			
			if ($inadmin === true) {
				
				$link = HTTP_HOST."/auth/forgot/reset?code={$code}";
				
			} else {
				
				$link = HTTP_HOST."/forgot/reset?code={$code}";
				
			}
 			
			return (new Mailer($data['desemail'],$data['desperson'],'Redefining Password','forgot',[
				'name' => $data['desperson'],
				'link' => $link
			]))->send();
			
		}
	}
	
	/**
	 * @throws \Exception
	 */
	public static  function validForgotDecrypt($code)
	{
		
		$code = (base64_decode($code));
		
		$idrecovery = openssl_decrypt($code, 'AES-128-CBC', 
			pack("a16", User::SECRET), 
			0, 
			pack("a16", User::SECRET_IV)
		);
		$sql = new Sql();
		
		$result = $sql->select("
			SELECT *
			FROM tb_userspasswordsrecoveries a
			INNER JOIN tb_users b USING(iduser)
			INNER JOIN tb_persons c USING(idperson)
			WHERE 
			      a.idrecovery = {$idrecovery}
			  AND 
			      a.dtrecovery IS NULL
			  AND
		        DATE_ADD(a.dtregister, INTERVAL  1 HOUR) >= now();

		");
		

		
		if (!count($result)) {
		    throw new Exception('Not Unable to retrieve password');
		} else {
			return $result[0];
		}
		
	}
	
	public static function setForgotUsed($idrecovery)
	{
		$sql = new Sql();
		$sql->query('
			UPDATE tb_userspasswordsrecoveries
			SET dtrecovery = NOW()
			WHERE idrecovery= :idrecovery
		', [
			':idrecovery' => $idrecovery
		]);
	}
	
	public function setPassword($password)
	{
		$password = password_hash($password, PASSWORD_DEFAULT, [
			"cost"=>12
		]);
		
		$sql = new Sql();
		

 		$sql->query('
			UPDATE tb_users
			SET despassword = :password
			WHERE iduser = :iduser 
		', [
			':password' => $password,
			':iduser' => $this->getIduser()
		]);
		
	}
	
}