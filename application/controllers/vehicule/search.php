<?php 
extract($_POST);
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
	global $vehicule;
	$vehicule = new Application_Model_Vehicule();
	}

function do_get() {
	echo "blaaa";
	global $vehicule;
	if (!empty($_GET['recherche'])) {
		extract($_GET);
		$searchVehicule = $vehicule->searchVehicule($type, $recherche, $annee, $km, $prix_min, $prix_max, $energie, $boite_vitesse, $nb_places, $cylindree);
		foreach($searchVehicule as $vehicule) {
			echo "waaaaaa"; exit;
			echo $vehicule['titre'];
			echo "<br />";
		}
	}
}

function do_post() {
	global $vehicule;
	if (!empty($_GET['recherche'])) {
		extract($_GET);
		$searchVehicule = $vehicule->searchVehicule($recherche, $annee, $km, $prix_min, $prix_max, $energie1, $vehicule1);
		foreach($searchVehicule as $vehicule) {
			echo "waaaaaa"; exit;
			echo $vehicule['titre'];
			echo "<br />";
		}
	}


}




