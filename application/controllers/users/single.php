<?php
require_once APPLICATION_PATH."models/Users.php";

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
  global $unMembre;
  $mMembre = new Application_Model_Users();
  $unMembre = $mMembre->getUser($_GET['id']);
  $row = $unMembre;
        $dom = new DOMDocument();
          $membre = $dom->createElement("membre");
          $dom->appendChild($membre);
          $membre->setAttribute("id", $row['id_membre']);
          $membre->setAttribute("mail", $row['mail']);
          $membre->setAttribute("nom", utf8_encode($row['nom']));
          $membre->setAttribute("code_postal", $row['code_postal']);
          $membre->setAttribute("telephone", $row['telephone']);
          header("Content-type: text/xml;charset=UTF-8");
          print $dom->saveXML();
  
}

function do_put() {
  $erreurs = array();
  // Les parametres passés en put
  parse_str(file_get_contents("php://input"), $_PUT);
    if (empty($_PUT["mail"])) {
            $erreurs[] = "AdresseEmailRequise";
    }
    if (empty($_PUT["nom"])) {
            $erreurs[] = "nomRequis";
    }
    if (empty($_PUT["code_postal"])) {
            $erreurs[] = "codePostalRequis";
    }
    if (empty($_PUT["telephone"])) {
            $erreurs[] = "telephoneRequis";
    }
 
  if (count($erreurs) > 0) {
    exit_error(400, join(", ", $erreurs));
  }
  else {
    global $editUser;
    $membre = new Application_Model_Users();
    $id = $_GET["id"];
    $editUser = $membre->setUser($_PUT["mail"], $_PUT["nom"], $_PUT["code_postal"], $_PUT["telephone"], $id);
    if ($editUser) {
        send_status(200);
    }
    else {
        send_status(404);
    }
  }
}

function do_delete() {
  global $id;
  if (empty($_GET["id"])) {
    exit_error(400, "idRequis"); 
  }
  $id = $_GET["id"];
  $membre = new Application_Model_Users;
  $delete_membre = $membre->deleteMembreById($id);
  if ($delete_membre) {
      send_status(200);
  }
  else {
      send_status(404);
  }
  
}
	?>