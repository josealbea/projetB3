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
		if (!isset($_GET['page'])) {
			$_GET['page'] = 1;	
		}
		$limit_min = ($_GET['page'] - 1) * 10;
        $limit_max = ($_GET['page'] * 10);
        global $liste_vehicule;

	$Vehicule = new Application_Model_Vehicule();
	$liste_vehicule = $Vehicule->getAllVehicules($limit_min, $limit_max);
        $dom = new DOMDocument();
        $vehicules = $dom->createElement("vehicules");
        $dom->appendChild($vehicules);
        foreach($liste_vehicule as $row){
          $vehicule = $dom->createElement("vehicule");
          $vehicules->appendChild($vehicule);
          $vehicule->setAttribute("id", $row['id_vehicule']);
          $vehicule->setAttribute("titre", utf8_encode($row['titre']));
          $vehicule->setAttribute("description", utf8_encode($row['description']));
          $vehicule->setAttribute("prix", utf8_encode($row['prix']));
          $vehicule->setAttribute("km", utf8_encode($row['km']));
          $vehicule->setAttribute("annee", utf8_encode($row['annee']));
          $vehicule->setAttribute("energie", utf8_encode($row['energie']));
          $vehicule->setAttribute("boite_vitesse", utf8_encode($row['boite_vitesse']));
          if ($row['id_categorie'] ==  1) {
              $vehicule->setAttribute("type_vehicule", "voiture");
              $vehicule->setAttribute("nb_places", utf8_encode($row['nb_places']));
          }
          elseif ($row['id_categorie'] ==  2) {
              $vehicule->setAttribute("type_vehicule", "moto");
              $vehicule->setAttribute("cylindree", utf8_encode($row['cylindree']));
          }
          elseif ($row['id_categorie'] ==  3) {
              $vehicule->setAttribute("type_vehicule", "scooter");
              $vehicule->setAttribute("cylindree", utf8_encode($row['cylindree']));
          }
          }
          print $dom->saveXML();
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
		$vehicule->addVehicule($_POST['titre'], $_POST['description'], $_POST['prix'], $_POST['annee'], $_POST['km'], $_POST['energie'], $_POST['boite_vitesse'], $_POST['nb_places'], $_POST['cylindree'], $id_membre, $_POST['id_categorie']);
	}
}