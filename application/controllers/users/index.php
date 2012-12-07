<?php 
init();
switch ($_SERVER['REQUEST_METHOD']) {
	case "GET": 
	do_get();
	break;
	case "POST":
	do_post();
	break;
	default:
	send_status(405);
	die();
}
?>
<?php

function init() {
	require APPLICATION_PATH."models/Users.php";
}

function do_get() {
	$Users = new Application_Model_Users();
	$liste_users = $Users->getAllUsers();
	if (empty($_GET["vehicule"])) {
		$erreurs[] = "typeVehiculeRequis";
		$_GET['vehicule'] = "";       
	}
	 foreach ($liste_users as $user) {
		echo 'Pseudo : '.$user['pseudo'].'<br />';
		echo 'Adresse mail : '.$user['mail'].'<br />';
		echo 'Nom : '.utf8_decode($user['nom']).'<br />';
		echo 'Prénom : '.$user['prenom'].'<br />';
		echo 'Ville : '.$user['ville'].' '.$user['code_postal'].'<br />';
		echo 'Téléphone : '.$user['telephone'].'<br />';
		if ($user['type'] == 1) {
			echo 'Type de membre : Administrateur <br />';
		}
		else {
			echo 'Type de membre : Membre basique <br />';
		}
		if ($user['statut'] == 1) {
			echo 'Statut du compte : Actif <br />';
		}
		else if ($user['statut'] == 2) {
			echo 'Statut du compte : En attente de validation <br />';
		}
		else {
			echo 'Statut du compte : Banni <br />';
		}
		echo '<a href="'.$user['id_membre'].'-'.str_replace(" ", "-",strtolower($user['pseudo'])).'">Afficher les informations du véhicule</a><br />';
	}
}

// FONCTION POST
function do_post() {
	if (!is_admin()) {
		exit_error(401, "mustBeAdmin");
	}
	$erreurs = array();

	parse_str(file_get_contents("php://input"), $_POST);
	if (empty($_POST["id_categorie"])) {
		$erreurs[] = "categorieRequise";
	}
	if (empty($_POST["titre"])) {
		$erreurs[] = "titreRequis";
	}
	if (empty($_POST["description"])) {
		$erreurs[] = "descriptionRequise";
	}
	if (empty($_POST["prix"])) {
		$erreurs[] = "prixRequis";
	}
	if (empty($_POST["annee"])) {
		$erreurs[] = "anneeRequise";
	}
	if (empty($_POST["km"])) {
		$erreurs[] = "kmRequis";
	}
	if (empty($_POST["energie"])) {
		$erreurs[] = "energieRequise";
	}

	if (count($erreurs) > 0) {
		exit_error(400, join(", ", $erreurs));
	}
	else {
		extract($_POST);
		$id_membre = $_SESSION['id_membre'];
		$vehicule = new Application_Model_Vehicule;
		$addVehicule = $vehicule->addVehicule($titre, $description, $prix, $annee, $km, $energie, $boite_vitesse, $nb_places, $cylindree, $id_membre, $id_categorie);
	}
}