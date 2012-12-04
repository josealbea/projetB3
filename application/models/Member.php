<?php 
class Application_Model_Member {
	
	function getDataMember($id_member) {
		try {
			$bdd = PDO2::getInstance();
			$sql = $bdd->prepare("SELECT * FROM membre WHERE id_membre = :id_member");
			$sql->bindValue(":id_member", $id_member);
			$sql->execute();
		}
		catch (PDOEXCEPTION $e) {
			//erreur a mettre
		}
	}

	function addMember($data) { // a faire
		try {
			$bdd = PDO2::getInstance();
			$sql = $bdd->prepare("INSERT INTO membre VALUES :data");
			$sql->bindValue(":data", $data);
			$sql->execute();
		}
		catch(PDOEXCEPTION $e) {
			// erreur a mettre
		}
	}

}