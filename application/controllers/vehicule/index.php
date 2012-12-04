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
	global $liste_vehicule;
	require APPLICATION_PATH."models/Vehicule.php";
	$Vehicule = new Application_Model_Vehicule();
	$liste_vehicule = $Vehicule->getAllVehicules();
}

function do_get() {
	global $liste_vehicule;
	if (empty($_GET["vehicule"])) {
		$erreurs[] = "typeVehiculeRequis";
		$_GET['vehicule'] = "";       
	}
	 foreach ($liste_vehicule as $vehicule) {
		echo 'Titre : '.$vehicule['titre'].'<br />';
		echo 'Prix : '.$vehicule['prix'].'<br />';
		if ($vehicule['id_categorie'] == 1) {
			echo 'Type de vehicule : voiture<br />';
		}
		elseif ($vehicule['id_categorie'] == 2) {
			echo 'Type de véhicule : moto<br />';
		}
		else {
			echo 'Type de véhicule : scooter<br />';
		}
		
		echo 'Ajouté le  : '.date("j/m/Y", strtotime($vehicule['date_ajout'])).'<br />';
		if ($vehicule['date_modification'] != "0000-00-00") {
			echo 'Dernière modification le  : '.date("j/m/Y", strtotime($vehicule['date_modification'])).'<br />';
		}
		if ($vehicule['date_suppression'] != "0000-00-00") {
			echo 'Supprimé le  : '.date("j/m/Y", strtotime($vehicule['date_suppression'])).'<br />';
		}
		echo '<a href="?controller=vehicule&action=single&id='.$vehicule['id_vehicule'].'">Afficher les informations du véhicule</a><br />';
	}
}

// FONCTION POST
function do_post() {
	if (!is_admin()) {
		exit_error(401, "mustBeAdmin");
	}
	$erreurs = array();

	parse_str(file_get_contents("php://input"), $_POST);
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
	if (empty($_POST["id_categorie"])) {
		$erreur[] = "typeVehiculeRequis";
	}
	if (empty($_POST["boite_vitesse"])) {
		$_POST["boite_vitesse"] = "";
	}
	if (empty($_POST["nb_places"])) {
		$_POST["nb_places"] = "";
	}
	if (empty($_POST["cylindree"])) {
		$_POST["cylindree"] = "";
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