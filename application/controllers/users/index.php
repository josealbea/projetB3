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
	if (!isset($_GET['page'])) {
		$_GET['page'] = 1;	
	}
	$limit_min = ($_GET['page'] - 1) * 10;
    $limit_max = ($_GET['page'] * 10);
	global $liste_users;
	$Users = new Application_Model_Users();
	$liste_users = $Users->getAllUsers($limit_min, $limit_max);
        $dom = new DOMDocument();
        $users = $dom->createElement("utilisateurs");
        $dom->appendChild($users);
        foreach($liste_users as $row){
          $user = $dom->createElement("utilisateur");
          $users->appendChild($user);
          $user->setAttribute("id", $row['id_membre']);
          $user->setAttribute("adresse_mail", $row['mail']);
          $user->setAttribute("nom", utf8_decode($row['nom']));
          $user->setAttribute("ville", utf8_decode($row['ville']));
          $user->setAttribute("code_postal", $row['code_postal']);
          $user->setAttribute("telephone", $row['telephone']);
          if ($row['type'] == 1) {
              $user->setAttribute("type_compte", "administrateur");
          }
          else if ($row['type'] == 2) {
              $user->setAttribute("type_compte", "Membre basique");
          }
          if ($row['statut'] == 0) {
              $user->setAttribute("statut_compte", "Compte banni");
          }
          else if ($row['statut'] == 1) {
              $user->setAttribute("statut_compte", "Compte validé");
          }
          else if ($row['statut'] == 2) {
              $user->setAttribute("statut_compte", "En attente de validation");
          }
        }
        header("Content-type: text/xml;charset=UTF-8");
        print $dom->saveXML();
}

// FONCTION POST
function do_post() {
	$erreurs = array();

	parse_str(file_get_contents("php://input"), $_POST);
	if (empty($_POST["password"])) {
		$erreurs[] = "motDePasseRequis";
	}
	if (empty($_POST["mail"])) {
		$erreurs[] = "AdresseEmailRequise";
	}
	if (empty($_POST["nom"])) {
		$erreurs[] = "nomRequis";
	}
	if (empty($_POST["ville"])) {
		$erreurs[] = "villeRequise";
	}
	if (empty($_POST["code_postal"])) {
		$erreurs[] = "codePostalRequis";
	}
        if (empty($_POST["telephone"])) {
		$_POST['telephone'] = "";
	}

	if (count($erreurs) > 0) {
		exit_error(400, join(", ", $erreurs));
	}
	else {
		extract($_POST);
		$password = sha1($password);
		$membre = new Application_Model_Users();
		$addUser = $membre->addUser($password, $mail, $nom, $ville, $code_postal, $telephone);
	}
}