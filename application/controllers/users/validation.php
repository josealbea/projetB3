<?php 
function init() {
	require APPLICATION_PATH."models/Users.php";
}
init();
validerCompte($_GET['hash']);

function validerCompte($hash) {
	$user = new Application_Model_Users;
	$verif = $user->validateAccount($hash);
}
