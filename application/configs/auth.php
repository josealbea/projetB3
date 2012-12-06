<?php 
function is_admin() {
	$result = false;
	if (isset($_SERVER["PHP_AUTH_USER"])) {
		$result = $_SERVER["PHP_AUTH_USER"] == "admin" && $_SERVER["PHP_AUTH_PW"] == "admin";
		$_SESSION['id_membre'] = 1;
	}
	return $result;
}
