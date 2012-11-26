<?php 
class Application_Model_Vehicule {
	
	function getVehicule($id_vehicule) {
		try {
			$bdd = PDO2::getInstance();
			$sql = $bdd->prepare("SELECT * FROM vehicule WHERE id_vehicule = :id_vehicule");
			$sql->bindValue(':id_vehicule', $id_vehicule);
			$sql->execute();
		}
		catch (PDOException $e) {
		    //erreur a mettre
		}
	}

	function getAllVehicules() {
		try {
			$bdd = PDO2::getInstance();
			$sql = $bdd->prepare("SELECT * FROM vehicule");
			$sql->execute();
		}
		catch (PDOException $e) {
		    //erreur a mettre
		}
	}

	function getAllVehiculesByMember($id_member) {
		try {
			$bdd = PDO2::getInstance();
			$sql = $bdd->prepare("SELECT * FROM vehicule WHERE id_membre = :id_member");
			$sql->bindValue(':id_member', $id_member);
			$sql->execute();
		}
		catch (PDOException $e) {
		    //erreur a mettre
		}
	}

	function getAllVehiculesByCategory($id_category) {
		try {
			$bdd = PDO2::getInstance();
			$sql->bdd->prepare("SELECT * FROM vehicule WHERE id_categorie = :id_category");
			$sql->bindValue(':id_category', $id_category);
			$sql->execute();
		}
		catch(PDOException $e) {
			//erreur a mettre
		}
	}

	function deleteVehicule($id_vehicule) {
		try {
			$bdd = PDO2::getInstance();
			$sql = $bdd->prepare("DELETE FROM annonce WHERE id_vehicule = :id_vehicule");
			$sql->bindValue(":id_vehicule", $id_vehicule);
			$sql->execute();
		}
		catch(PDOException $e) {
			// erreur a mettre
		}
	}

	function setVehicule($id_vehicule) {
		//a faire
	}
}