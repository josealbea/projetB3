<?php 
function init() {
	require APPLICATION_PATH."models/Users.php";
}
init();
validerCompte($_GET['hash']);

function validerCompte($hash) {
	$user = new Application_Model_Users;
	$verif = $user->validateAccount($hash);
	if ($verif == 0) {
		echo "Ce compte a été banni";
	}
	elseif ($verif == 1) {
		echo "Ce compte est déjà validé";
	}
	else if ($verif == 2) {
		echo "Le compte a bien été activé";
	}
	else if ($verif == 3) {
		echo "Nous n'avons trouvé aucun compte a valider.";
	}
}
