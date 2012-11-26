<?php 
require('../models/Vehicule.php');
class Vehicule_Controller {

	public function indexAction() {
		$vehicule = new Application_Model_Vehicule();
		$liste_vehicule = $vehicule->getAllVehicules();
		echo $liste_vehicule;
	}
}























































































$class = new Vehicule_Controller();
echo $class->indexAction();
