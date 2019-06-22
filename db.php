<?php
function getDB() {
	try {
	$dbhost="localhost";
	$dbuser="root";
	$dbpass="mysql";
	$dbname="smartHealth";

	$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbConnection;
	} catch (Exception $e) {

		 die('Erreur : '.$e->getMessage());
		
	}
	
}
?>