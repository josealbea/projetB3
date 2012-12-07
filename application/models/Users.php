<?php 
class Application_Model_Users {
	
	function getDataUser($id_user) {
		global $bdd;
		try {
			$sql = $bdd->prepare("SELECT * FROM membre WHERE id_membre = :id_user");
			$sql->bindValue(":id_user", $id_user);
			$sql->execute();
		}
		catch (PDOEXCEPTION $e) {
			die('Erreur : '.$e->getMessage());
		}
	}

	function getAllUsers() {
		global $bdd;
		try {
			$sql = $bdd->prepare("SELECT * FROM membre");
			$result = $sql->execute();
			if ($result) {
				$rows = $sql->fetchAll();
				return $rows;
			}
		}
		catch (PDOEXCEPTION $e) {
			die('Erreur : '.$e->getMessage());
		}
	}

	function addUser($pseudo, $password, $mail, $nom, $prenom, $ville, $code_postal, $telephone); { // a faire
		try {
			$sql = $bdd->prepare("INSERT INTO membre VALUES :data");
			$sql->bindValue(":data", $data);
			$sql->execute();
		}
		catch (PDOEXCEPTION $e) {
			die('Erreur : '.$e->getMessage());
		}
	}

}