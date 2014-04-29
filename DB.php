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

	/** 
	* execute une requête SQL de type SELECT sur la bdd
	* @param string $query la requete en langage SQL
	* @param array $array le tableau des variables de la requete
	* @param bool $assoc retour de type Assoc ou Num
	* @return boolean if failed / array of datas if success
	*/
	
	public function find($query = "", $array = null, $assoc = true){
		if ($query != "") {
			if (!isset($array)) {
				$req = $this->pdo->query($query); 
			}else{
				$req = $this->pdo->prepare($query);
				$req->execute($array);
			}
			$datas = ($assoc) ? $req->fetchAll(PDO::FETCH_ASSOC) : $req->fetchAll(PDO::FETCH_NUM);
			/* PDO::FETCH_ASSOC: retourne un tableau indexé par le nom de la colonne comme retourné dans le jeu de résultats . On met par défaut FETCH_ASSOC pour avoiir comme indice du tableau retourné par la requete
			les noms de nos tables*/

			/*PDO::FETCH_NUM : retourne un tableau indexé par le numéro de la colonne comme elle est retourné dans votre jeu de résultat, commençant à 0. */

   			$req->closeCursor();
			$this->messages['last_query'] = $query;
			//echo "La dernière requête est :".$this->messages['last_query'];
			//print_r ($datas);
			return $datas;
		}
		return false;
	}

}