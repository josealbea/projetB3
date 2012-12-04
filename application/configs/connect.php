<?php
	try {
		$bdd = new PDO('mysql:host=localhost;dbname=vehicule', 'root', 'root');
	}
	catch (PDOException $e) {
		die('Erreur : '.$e->getMessage());
	}
?>