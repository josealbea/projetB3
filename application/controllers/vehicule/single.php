<?php
require_once APPLICATION_PATH."models/Vehicule.php";

init();
switch ($_SERVER['REQUEST_METHOD']) {
  case "GET": 
    do_get();
    break;
  case "PUT":
    do_put();
    break;
  case "DELETE":
    do_delete();
    break;
  default:
    send_status(405);
    die();
}

function init() {
	global $id;
	if (empty($_GET['id'])) {
		exit_error(400, "idRequis"); 
	}
	else {
		$id = $_GET['id'];
		if (!is_numeric($id)) {
			exit_error(400, "idNonEntierPositif");
		}
		$id = 0 + $id;
		if (!is_int($id) || $id <= 0) {
			exit_error(400, "idNonEntierPositif");
		}  
	}
}
function do_get() {
    global $unVehicule;
    $mVehicule = new Application_Model_Vehicule();
    $unVehicule = $mVehicule->getVehicule($_GET['id']);
    $row = $unVehicule;
    $dom = new DOMDocument();
      $vehicule = $dom->createElement("vehicule");
      $dom->appendChild($vehicule);
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
      print $dom->saveXML();
    }

function do_put() {
  if (!is_admin()) {
    exit_error(401, "mustBeAdmin");
  }
  $erreurs = array();
  // Les parametres passés en put
  parse_str(file_get_contents("php://input"), $_PUT);
  if (empty($_GET["id"])) {
    $erreurs[] = "idRequis";
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
    global $unVehicule;
    $vehicule = new Application_Model_Vehicule();
    $unVehicule = $vehicule->setVehicule($titre, $description, $prix, $annee, $km, $energie, $boite_vitesse, $nb_places, $cylindree, $id_categorie, $id_vehicule);
    if ($unVehicule) {
        echo "L'annonce a bien été modifiée";
    }
    else {
        echo "L'id de l'annonce n'existe pas dans notre base";
    }
  }
}

function do_delete() {
  global $id;
  if (!is_admin()) {
    exit_error(401, "mustBeAdmin");
  }
  if (empty($_GET["id"])) {
    exit_error(400, "idRequis"); 
  }
  $id = $_GET["id"];
  $vehicule = new Application_Model_Vehicule;
  $delete_vehicule = $vehicule->deleteVehiculeById($id);
  if ($delete_vehicule) {
      echo "L'annonce a bien été supprimée.";
  }
  else {
      echo "L'id de l'annonce n'existe pas dans notre base";
  }
  
}
	?>