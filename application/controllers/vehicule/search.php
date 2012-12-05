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
	global $searchVehicule;
	$vehicule = new Application_Model_Vehicule();
	extract($_GET);
	$searchVehicule = $vehicule->searchVehicule($type, $recherche, $annee, $km, $prix_min, $prix_max, $energie, $boite_vitesse, $nb_places);
}

function do_get() {
	global $searchVehicule;
	foreach($searchVehicule as $vehicule) {
		echo "waaaaaa"; exit;
		echo $vehicule['titre'];
		echo "<br />";
	}
}

function do_post() {

}