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
      $vehicule->setAttribute("date_ajout", $row['date_ajout']);
      $vehicule->setAttribute("date_modification", $row['date_modification']);
      $vehicule->setAttribute("date_suppression", $row['date_suppression']);
      if ($row['id_categorie'] ==  1) {
          $vehicule->setAttribute("nb_places", utf8_encode($row['nb_places']));
          $vehicule->setAttribute("type_vehicule", "voiture");
      }
      elseif ($row['id_categorie'] ==  2) {
          $vehicule->setAttribute("cylindree", utf8_encode($row['cylindree']));
          $vehicule->setAttribute("type_vehicule", "moto");
      }
      elseif ($row['id_categorie'] ==  3) {
          $vehicule->setAttribute("cylindree", utf8_encode($row['cylindree']));
          $vehicule->setAttribute("type_vehicule", "scooter");
      }
                $user = $dom->createElement("membre");
          $vehicule->appendChild($user);
          $rowUser = $mVehicule->getMemberByVehicule($row['id_vehicule']);
          $user->setAttribute("id", $rowUser['id_membre']);
          $user->setAttribute("adresse_mail", $rowUser['mail']);
          $user->setAttribute("nom", utf8_encode($rowUser['nom']));
          $user->setAttribute("ville", utf8_encode($rowUser['ville']));
          $user->setAttribute("code_postal", $rowUser['code_postal']);
          $user->setAttribute("telephone", $rowUser['telephone']);
          if ($rowUser['type'] == 1) {
              $user->setAttribute("type_compte", "administrateur");
          }
          else if ($rowUser['type'] == 2) {
              $user->setAttribute("type_compte", "Membre basique");
          }
          if ($rowUser['statut'] == 0) {
              $user->setAttribute("statut_compte", "Compte banni");
          }
          else if ($rowUser['statut'] == 1) {
              $user->setAttribute("statut_compte", "Compte validé");
          }
          else if ($rowUser['statut'] == 2) {
              $user->setAttribute("statut_compte", "En attente de validation");
          }
          header("Content-type: text/xml;charset=UTF-8");
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
  if (empty($_PUT['id_categorie'])) {
      $erreurs[] = "idCategorieRequise";
  }
  if (empty($_PUT["description"])) {
    $erreurs[] = "descriptionRequise";
  }
  if (empty($_PUT["prix"])) {
    $erreurs[] = "prixRequis";
  }
  if (empty($_PUT["annee"])) {
    $erreurs[] = "anneeRequise";
  }
  if (empty($_PUT["km"])) {
    $erreurs[] = "kmRequis";
  }
  if (empty($_PUT["energie"])) {
    $erreurs[] = "energieRequise";
  }
  if (!empty($_PUT['id_categorie'])) {
    if ($_PUT['id_categorie'] == 1) {
      if (empty($_PUT["boite_vitesse"])) {
        $erreurs[] = "boiteVitesseRequise";
      }
      if (empty($_PUT["nb_places"])) {
        $erreurs[] = "nbPlacesRequise";
      }
      $_PUT['cylindree'] = NULL;
    }
    if ($_PUT['id_categorie'] == 2 || $_PUT['id_categorie'] == 3 ) {
      if (empty($_PUT["cylindree"])) {
        $erreurs[] = "cylindreeRequise";
      }
      $_PUT['boite_vitesse'] = NULL;
      $_PUT['nb_places'] = NULL;
    }
  }
 
  if (count($erreurs) > 0) {
    exit_error(400, join(", ", $erreurs));
  }
  else {
    global $unVehicule;
    $vehicule = new Application_Model_Vehicule();
    $unVehicule = $vehicule->setVehicule($_PUT['titre'], $_PUT['description'], $_PUT['prix'], $_PUT['annee'], $_PUT['km'], $_PUT['energie'], $_PUT['boite_vitesse'], $_PUT['nb_places'], $_PUT['cylindree'], $_GET['id']);
    if ($unVehicule) {
        send_status(200);
    }
    else {
        send_status(404);
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
  $delete_vehicule = $vehicule->deleteVehicule($id);
  if ($delete_vehicule) {
      send_status(200);
  }
  else {
      send_status(404);
  }
  
}