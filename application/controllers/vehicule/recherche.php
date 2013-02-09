<?php 
init();
switch ($_SERVER['REQUEST_METHOD']) {
	case "POST":
	do_post();
	break;
	default:
	send_status(405);
	die();
}
 
function init() {
	require APPLICATION_PATH."models/Vehicule.php";
}

function do_post() {
	parse_str(file_get_contents("php://input"), $_POST);
	$vehicule = new Application_Model_Vehicule;
	$rows = $vehicule->searchVehicule($_POST['id_categorie'], $_POST['titre'], $_POST['description'], $_POST['prix'], $_POST['annee'], $_POST['cp'], $_POST['km'], $_POST['energie'], $_POST['boite_vitesse']);
	self::do_get($rows);
}

function do_get($rows) {
	$Vehicule = new Application_Model_Vehicule();
		if (!isset($_GET['page'])) {
			$_GET['page'] = 1;	
		}
		$limit_min = ($_GET['page'] - 1) * 10;
  $limit_max = ($_GET['page'] * 10);
  global $rows;
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
      }
      header("Content-type: text/xml;charset=UTF-8");
      print $dom->saveXML();
}