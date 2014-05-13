<?php 
require 'DB.php';

$db = new DB;
$db->find("SELECT * FROM test");
$db->save("test", array(
	'nom'=>'BOUAB',
	'prenom'=>'abiyyyyayay',
	'age'=>50) ,false);

$db->update("test",array('prenom' => '\'yooooooooo\'' ), "age=2");
$db->delete("test","age=2");
?>