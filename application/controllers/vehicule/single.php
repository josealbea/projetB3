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
  if (empty($_PUT["titre"])) {
    $erreurs[] = "titreRequis";
  }
  if (empty($_PUT["description"])) {
    $erreurs[] = "descriptionRequise";
  }
 
  if (count($erreurs) > 0) {
    exit_error(400, join(", ", $erreurs));
  }
  else {
    try {
      $db = getConnexion();
      $sql = "UPDATE annonce SET titre=:titre, description=:description, prix=:prix, km=:km, annee=:annee WHERE id_vehicule=:id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(":titre", ucwords(trim($_PUT["titre"])));
      $stmt->bindValue(":description", ucwords(trim($_PUT["description"])));
      $stmt->bindValue(":prix", ucwords(trim($_PUT["prix"])));
      $stmt->bindValue(":km", ucwords(trim($_PUT["km"])));
      $stmt->bindValue(":annee", ucwords(trim($_PUT["annee"])));
      $stmt->bindValue(":id", $_GET["id"]);
      $ok = $stmt->execute();
      if ($ok) {
        $nb = $stmt->rowCount();
        if ($nb == 0) {
          $sql = "SELECT id_film FROM film WHERE id_film=:id";
          $stmt = $db->prepare($sql);
          $stmt->bindValue(":id", $_GET["id"]);
          $ok = $stmt->execute();
          if ($stmt->fetch() == null) {
            send_status(404);
          }
          else {
            send_status(204);
          }
        }
        else {
          send_status(204);
        }
      }
      else {
        $erreur = $stmt->errorInfo();
        // si doublon
        if ($erreur[1] == 1062) {
          exit_error(409, "existeDeja");
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