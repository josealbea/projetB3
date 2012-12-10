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
	$vehicule = new Application_Model_Vehicule();
	$unVehicule = $vehicule->getVehicule($_GET['id']);
}

function do_put() {
  if (!is_admin()) {
    exit_error(401, "mustBeAdmin");
  }
  $erreurs = array();
  // Les parametres passés en put
  parse_str(file_get_contents("php://input"), $_PUT);
  if (empty($_PUT["id"])) {
    $erreurs[] = "idRequis";
  }
 
  if (count($erreurs) > 0) {
    exit_error(400, join(", ", $erreurs));
  }
  else {
    global $unVehicule;
    $vehicule = new Application_Model_Vehicule();
    $unVehicule = $vehicule->setVehicule($_PUT['id']);
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