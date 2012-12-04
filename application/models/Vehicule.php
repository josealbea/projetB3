<?php 
class Application_Model_Vehicule {
	
	function getVehicule($id_vehicule) {
		global $bdd;
		try {
			$sql = $bdd->prepare("SELECT * FROM vehicule WHERE id_vehicule = :id_vehicule");
			$sql->bindValue(':id_vehicule', $id_vehicule);
			$result = $sql->execute();
			if ($result) {
				$rows = $sql->fetchAll();
				return $rows;
			}
		}
		catch (PDOException $e) {
		    die('Erreur : '.$e->getMessage());
		}
	}

	function getAllVehicules() {
		global $bdd;
		try {
			$sql = $bdd->prepare("SELECT * FROM vehicule");
			$result = $sql->execute();
			if ($result) {
				$rows = $sql->fetchAll();
				return $rows;
			}
		}
		catch (PDOException $e) {
		    die('Erreur : '.$e->getMessage());
		}
	}

	function getAllVehiculesByMember($id_member) {
		global $bdd;
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
		global $bdd;
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
		global $bdd;
		try {
			$sql = $bdd->prepare("DELETE FROM annonce WHERE id_vehicule = :id_vehicule");
			$sql->bindValue(":id_vehicule", $id_vehicule);
			$sql->execute();
		}
		catch(PDOException $e) {
			die('Erreur : '.$e->getMessage());
		}
	}

	function addVehicule($titre, $description, $prix, $annee, $km, $energie, $boite_vitesse, $nb_places, $cylindree, $id_membre, $id_categorie) {
		global $bdd;
		try {
			$sql = $bdd->prepare("INSERT INTO vehicule (id_vehicule, titre, description, prix, annee, km, energie, date_ajout, date_modification, date_suppression, statut, boite_vitesse, nb_places, cylindree, id_membre, id_categorie) 
			VALUES (NULL, :titre, :description, :prix, :annee, :km, :energie, curdate(), '0000-00-00', '0000-00-00', '1', :boite_vitesse, :nb_places, :cylindree, :id_membre, :id_categorie)");
			$sql->bindValue(":titre", ucwords(trim($titre)));
			$sql->bindValue(":description", trim($description));
			$sql->bindValue(":prix", $prix);
			$sql->bindValue(":annee", $annee);
			$sql->bindValue(":km", $km);
			$sql->bindValue(":energie", ucwords(trim($energie)));
			$sql->bindValue(":boite_vitesse", ucwords(trim($boite_vitesse)));
			$sql->bindValue(":nb_places", $nb_places);
			$sql->bindValue(":cylindree", $cylindree);
			$sql->bindValue(":id_membre", $id_membre);
			$sql->bindValue(":id_categorie", $id_categorie);
			$result = $sql->execute();
			if ($result) {
				echo "Votre annonce a bien été ajoutée et sera validée dans les plus brefs délais.";
			}
		}
		catch (PDOException $e) {
			die ('Erreur : '.$e->getMessage());
		}
	}
}