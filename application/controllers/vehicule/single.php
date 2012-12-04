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
  global $id;
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
  }
}

function do_delete() {
  global $id;
  if (!is_admin()) {
    exit_error(401);
  }
  if (empty($_GET["id"])) {
    exit_error(400, "idRequis"); 
  }
  $id = $_GET["id"];
  try {
    $db = getConnexion();
    $sql = "DELETE FROM vehicule WHERE id_vehicule=:id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":id", $id);
    $ok = $stmt->execute();
    if ($ok) {
      $nb = $stmt->rowCount();
      if ($nb == 0) {
        send_status(404);
      }
      else {
        send_status(204);
      }
    }
    else {
      $erreur = $stmt->errorInfo();
      // si artiste reference par film (realisateur) ou personnage (acteur)
      if ($erreur[1] == 1451) { // Contrainte de cle etrangere
        exit_error(409, "filmReferenceParTitreOuGenre");
      }
      else {
        exit_error(409, $erreur[1]." : ".$erreur[2]);
      }
    }
  }
  catch (PDOException $e) {
    exit_error(500, $e->getMessage());
  }
}
	?>