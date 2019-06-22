<?php
function getDB() {
	try {
	$dbhost="q7cxv1zwcdlw7699.chr7pe7iynqr.eu-west-1.rds.amazonaws.com	";
	$dbuser="a5zoekzahyqy5nhy";
	$dbpass="gtxh55xgasjwei22";
	$dbname="m0y5tmhsdczc8dck";

	$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbConnection;
	} catch (Exception $e) {

		 die('Erreur : '.$e->getMessage());
		
	}
	
}
?>