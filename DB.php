<?php 

use \PDO;

class DB{

	public $pdo;
	private $default = array(
		'host' => 'localhost',
		'login' => 'nf17',
		'password' => '',
		'database' => 'nf17',
		'port'=>'5432',
		'user' => '',
	);
	private $messages = array();
	private $lastId;

	function __construct($default = null){
		if (!isset($default)) {
			$default = $this->default;
		}
		try{
		    $this->pdo = new PDO('pgsql:host='.$default['host'].';dbname='.$default['database']);
			$this->messages['db'] = "DB initialized";
			echo $this->messages['db'];
		}catch(PDOException $e){
	        echo '<br>Erreur : '.$e->getMessage().'<br />';
	       	echo '<br>N° : '.$e->getCode();
	       	die("<br>Erreur dans la construction de DB");
		}
	}
}