<?php
	try {
		$bdd = new PDO('mysql:host=db441597014.db.1and1.com;dbname=db441597014', 'dbo441597014', 'f4qSY2dE');
	}
	catch (PDOException $e) {
		die('Erreur : '.$e->getMessage());
	}
?>