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
	require APPLICATION_PATH."models/Vehicule.php";
}

function do_get() {
        global $liste_vehicule;
	$Vehicule = new Application_Model_Vehicule();
	$liste_vehicule = $Vehicule->getAllVehicules();
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
	if (!empty($_POST['id_categorie'])) {
		if ($_POST['id_categorie'] == 1) {
			if (empty($_POST["boite_vitesse"])) {
				$erreurs[] = "boiteVitesseRequise";
			}
			if (empty($_POST["nb_places"])) {
				$erreurs[] = "nbPlacesRequise";
			}
			$_POST['cylindree'] = "";
		}
		if ($_POST['id_categorie'] == 2 || $_POST['id_categorie'] == 3 ) {
			if (empty($_POST["cylindree"])) {
				$erreurs[] = "cylindreeRequise";
			}
			$_POST['boite_vitesse'] = "";
			$_POST['nb_places'] = "";
		}
	}
	if (count($erreurs) > 0) {
		exit_error(400, join(", ", $erreurs));
	}
	else {
		extract($_POST);
		$id_membre = $_SESSION['id_membre'];
		$vehicule = new Application_Model_Vehicule;
		$vehicule->addVehicule($titre, $description, $prix, $annee, $km, $energie, $boite_vitesse, $nb_places, $cylindree, $id_membre, $id_categorie);
	}
}