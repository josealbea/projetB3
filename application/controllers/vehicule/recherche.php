<?php 
init();
switch ($_SERVER['REQUEST_METHOD']) {
	case "GET":
	do_get();
	break;
	default:
	send_status(405);
	die();
}
 
function init() {
	require APPLICATION_PATH."models/Vehicule.php";
}

function do_get() {
	$Vehicule = new Application_Model_Vehicule;
	if (empty($_GET['id_categorie'])) {
		$_GET['id_categorie'] = '';
	}
	if (empty($_GET['recherche'])) {
		$_GET['recherche'] = '';
	}
	if (empty($_GET['annee'])) {
		$_GET['annee'] = '';
	}
	if (empty($_GET['cp'])) {
		$_GET['cp'] = '';
	}
	if (empty($_GET['km'])) {
		$_GET['km'] = '';
	}
	if (empty($_GET['prix_min'])) {
		$_GET['prix_min'] = '';
	}
	if (empty($_GET['prix_max'])) {
		$_GET['prix_max'] = '';
	}
	if (empty($_GET['energie'])) {
		$_GET['energie'] = '';
	}
	if (empty($_GET['boite_vitesse'])) {
		$_GET['boite_vitesse'] = '';
	}
	$rows = $Vehicule->searchVehicule($_GET['id_categorie'], $_GET['recherche'], $_GET['annee'], $_GET['cp'], $_GET['km'], $_GET['prix_min'], $_GET['prix_max'], $_GET['energie'], $_GET['boite_vitesse']);
  if (!$rows) {
    echo "0";
  }
  else {
    $dom = new DOMDocument();
    $vehicules = $dom->createElement("vehicules");
    $dom->appendChild($vehicules);
    foreach($rows as $row){
      $vehicule = $dom->createElement("vehicule");
      $vehicules->appendChild($vehicule);
      $vehicule->setAttribute("id", $row['id_vehicule']);
      $vehicule->setAttribute("titre", utf8_encode($row['titre']));
      $vehicule->setAttribute("description", utf8_encode($row['description']));
      $vehicule->setAttribute("prix", utf8_encode($row['prix']));
      $vehicule->setAttribute("km", utf8_encode($row['km']));
      $vehicule->setAttribute("annee", utf8_encode($row['annee']));
      $vehicule->setAttribute("energie", utf8_encode($row['energie']));
      $vehicule->setAttribute("date_ajout", $row['date_ajout']);
      $vehicule->setAttribute("date_modification", $row['date_modification']);
      $vehicule->setAttribute("date_suppression", $row['date_suppression']);
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
      $user = $dom->createElement("membre");
      $vehicule->appendChild($user);
      $rowUser = $Vehicule->getMemberByVehicule($row['id_vehicule']);
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
      }
      header("Content-type: text/xml;charset=UTF-8");
      print $dom->saveXML();
    }
}