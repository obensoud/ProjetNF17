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
			//echo $this->messages['db'];
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
			/* echo "La dernière requête est :".$this->messages['last_query'];
			   print_r ($datas); */
			return $datas;
		}
		return false;
	}

	/** 
	* execute une requête SQL de type INSERT sur la bdd
	* @param string $table le nom de la table dans laquelle on veut insérer
	* @param array $array le tableau des variables de la requete 'champs'=>valeur
	* @param bool $idnull sert à savoir s'il y a un champ id dans la table
	* @return boolean
	*/
	public function save($table = "", $array = null, $idnull = true){
		if ($table != "" && isset($array)) {
			if ($idnull) {
				$query = "INSERT INTO $table VALUES ( default,";
			}else{
				$query = "INSERT INTO $table VALUES ( ";
			}
			foreach ($array as $k => $v) {
				$query .= " :".$k.",";
			}
			$query = substr($query, 0, -1);
			$query .= ");";
			//echo "<br>".$query;
			$this->messages['last_query'] = $query;
			$req = $this->pdo->prepare($query);
			try {
				//echo"<br><br>";
				//print_r($array);
				$req->execute($array);
				$this->lastId = $this->pdo->lastInsertId();
				return true;
			} catch (PDOException $e) {
				$str = "SELECT * FROM $table WHERE ";
				foreach ($array as $k => $v) {
					$str .= " $k = :$k AND";
				}
				$str = substr($str, 0, -3);
				$str .= ";";
				$resultat = $this->find($str,$array);
				if(isset($resultat[0])){
					$this->messages['error'] = "Cette ligne existe déjà dans la base de donnée.";
				}else{
					$this->messages['error'] = $e->getMessage();
				}
				return false;
			}
		}
		return false;
	}
	/** 
	* execute une requête SQL de type UPDATE ... SET ... WHERE .. sur la bdd
	* @param string $table le nom de la table dans laquelle on veut mettre à jour
	* @param array $array le tableau des variables de la requete 'champs'=>valeur
	* @param string $condition est la condition après le where dans le UPDATE
	* @return boolean
	*/
	public function update($table, $array = null, $condition = null){
		if ($table != "" && $condition != "" && isset($array)) {
			$query = "UPDATE $table SET";

			foreach ($array as $k => $v) {
				$query .= " $k=:$k,";
			}
			
			$query = substr($query, 0, -1);
			$query .= " WHERE $condition ;";
			echo $query."<br>";
			$this->messages['last_query'] = $query;
			$req = $this->pdo->prepare($query);
			print_r($array);
			print"<br>";
			$req->execute($array);
			return true;
		}
		return false;
	}

	/** 
	* execute une requête SQL de type DELETE sur la bdd
	* @param string $table le nom de la table dans laquelle on veut supprimer un tuple
	* @param string $condition est la condition de selection des tuples à supprimer
	* @return boolean
	*/
	public function delete($table, $condition){
		if ($table != "" && $condition != "") {
			$query = "DELETE FROM $table WHERE $condition";
			$this->messages['last_query'] = $query;
			$req = $this->pdo->exec($query);
			return true;
		}
		return false;
	}

	public function lastInsertId(){
		return $this->lastId;
	}

	public function lastError(){
		return $this->messages['error'];
	}

	public function lastDBMessage(){
		return $this->messages['db'];
	}

	public function lastQuery(){
		return $this->messages['last_query'];
	}

}