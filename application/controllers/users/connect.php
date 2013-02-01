<?php
init();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  do_post();
}
else {
  send_status(405);
  die();
}

function init() {
  require_once APPLICATION_PATH."models/Users.php";
}

function do_post() {
  $erreurs = array();

  parse_str(file_get_contents("php://input"), $_POST);
  if (empty($_POST["mail_connect"])) {
    $erreurs[] = "AdresseEmailRequise";
  }
  if (empty($_POST["password_connect"])) {
    $erreurs[] = "motDePasseRequis";
  }
  if (count($erreurs) > 0) {
    exit_error(400, join(", ", $erreurs));
  }
  else {
    extract($_POST);
    $membre = new Application_Model_Users();
    $check_membre = $membre->checkUser($_POST['mail_connect'], $_POST['password_connect']);
}



  
}