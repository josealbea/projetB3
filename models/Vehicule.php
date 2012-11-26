<?php 
class Application_Model_Vehicule {

	function connectBdd() {
 		$config = parse_ini_file("../configs/application.ini");
 		$username = $config['resources.db.params.username'];
 		$password = $config['resources.db.params.password'];
 		$host = $config['resources.db.params.host'];
 		$dbname = $config['resources.db.params.dbname'];
 		try {
 			$bdd = new PDO('mysql:host='.$host.';dbname='.$dbname, $username, $password);
 		}
 		catch (PDOException $e) {
 			die('Erreur : '.$e->getMessage());
 		}
	}
	
	function getVehicule($id_vehicule) {
		try {
			$sql = $bdd->prepare("SELECT * FROM vehicule WHERE id_vehicule = :id_vehicule");
			$sql->bindValue(':id_vehicule', $id_vehicule);
			$sql->execute();
		}
		catch (PDOException $e) {
		    die('Erreur : '.$e->getMessage());
		}
	}

	function getAllVehicules() {
		try {
			$sql = $bdd->prepare("SELECT * FROM vehicule");
			$sql->execute();
		}
		catch (PDOException $e) {
		    die('Erreur : '.$e->getMessage());
		}
	}

	function getAllVehiculesByMember($id_member) {
		try {
			$sql = $bdd->prepare("SELECT * FROM vehicule WHERE id_membre = :id_member");
			$sql->bindValue(':id_member', $id_member);
			$sql->execute();
		}
		catch (PDOException $e) {
		    die('Erreur : '.$e->getMessage());
		}
	}

	function getAllVehiculesByCategory($id_category) {
		try {
			$sql->bdd->prepare("SELECT * FROM vehicule WHERE id_categorie = :id_category");
			$sql->bindValue(':id_category', $id_category);
			$sql->execute();
		}
		catch(PDOException $e) {
			die('Erreur : '.$e->getMessage());
		}
	}

	function deleteVehicule($id_vehicule) {
		try {
			$sql = $bdd->prepare("DELETE FROM annonce WHERE id_vehicule = :id_vehicule");
			$sql->bindValue(":id_vehicule", $id_vehicule);
			$sql->execute();
		}
		catch(PDOException $e) {
			die('Erreur : '.$e->getMessage());
		}
	}

	function setVehicule($id_vehicule) {
		//a faire
	}
}