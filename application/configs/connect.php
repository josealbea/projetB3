<?php
	try {
		$bdd = new PDO('mysql:host=db441597014.db.1and1.com;dbname=db441597014', 'dbo441597014', 'f4qSY2dE'); //PROD
		//$bdd = new PDO('mysql:host=localhost;dbname=vehicule', 'root', 'root'); //TEST
	}
	catch (PDOException $e) {
		die('Erreur : '.$e->getMessage());
	}
?>