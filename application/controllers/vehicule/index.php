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
	require APPLICATION_PATH."models/Vehicule.php";
}
 
function do_get() {
  $Vehicule = new Application_Model_Vehicule();
		if (!isset($_GET['page'])) {
			$_GET['page'] = 1;	
		}
	$limit_min = ($_GET['page'] - 1) * 10;
  $limit_max = 10;
  global $liste_vehicule;

    $dom = new DOMDocument();
    $vehicules = $dom->createElement("vehicules");
  if (empty($_GET['type'])) {
    $type_vehicule = "";
  }
  else {
    $type_vehicule = $_GET['type'];
  }
  if (isset($_GET['id_membre'])) {
    $id_membre = $_GET['id_membre'];
    $liste_vehicule = $Vehicule->getAllVehiculesByMember($limit_min, $limit_max, $id_membre);
    $vehicules->setAttribute("nb_vehicules", $Vehicule->countNbVehicules('membre', $id_membre));
  }
  elseif (isset($_GET['type'])) {
    $id_type = $_GET['type'];
    $liste_vehicule = $Vehicule->getAllVehiculesByType($limit_min, $limit_max, $id_type);
    $vehicules->setAttribute("nb_vehicules", $Vehicule->countNbVehicules('type', $id_type));
  }
  else {
    $liste_vehicule = $Vehicule->getAllVehicules($limit_min, $limit_max);
    $vehicules->setAttribute("nb_vehicules", $Vehicule->countNbVehicules());
  }
    $dom->appendChild($vehicules);
    foreach($liste_vehicule as $row){
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
 
// FONCTION POST
function do_post() {
	$erreurs = array();
	parse_str(file_get_contents("php://input"), $_POST);
	if (empty($_POST["id_categorie"])) {
		$erreurs[] = "categorieRequise";
	}
	if (empty($_POST["titre"])) {
		$erreurs[] = "titreRequis";
	}
	if (empty($_POST["description"])) {
		$erreurs[] = "descriptionRequise";
	}
	if (empty($_POST["prix"])) {
		$erreurs[] = "prixRequis";
	}
	if (empty($_POST["annee"])) {
		$erreurs[] = "anneeRequise";
	}
	if (empty($_POST["km"])) {
		$erreurs[] = "kmRequis";
	}
	if (empty($_POST["energie"])) {
		$erreurs[] = "energieRequise";
	}
	if (!empty($_POST['id_categorie'])) {
		if ($_POST['id_categorie'] == 1) {
			if (empty($_POST["boite_vitesse"])) {
				$erreurs[] = "boiteVitesseRequise";
			}
			if (empty($_POST["nb_places"])) {
				$erreurs[] = "nbPlacesRequise";
			}
			$_POST['cylindree'] = "";
		}
		if ($_POST['id_categorie'] == 2 || $_POST['id_categorie'] == 3 ) {
			if (empty($_POST["cylindree"])) {
				$erreurs[] = "cylindreeRequise";
			}
			$_POST['boite_vitesse'] = "";
			$_POST['nb_places'] = "";
		}
	}
	if (count($erreurs) > 0) {
		exit_error(400, join(", ", $erreurs));
	}
	else {
    if (empty($_POST['id_membre'])) {
      $_POST['id_membre'] = 1;
    }
		$vehicule = new Application_Model_Vehicule;
    //$file_name = $_POST['nom_image'];
    //$file_array = explode ('.',$file_name);
    //$extension = count ($file_array) - 1;
    //$new = substr ($file_name,0,strlen($file_name) -strlen ($file_array[$extension])-1);
    //$nom_image = uniqid($new);
    //$nom_image = $nom_image.'.'.$_POST['ext'];
		$result = $vehicule->addVehicule($_POST['titre'], $_POST['description'], $_POST['prix'], $_POST['annee'], $_POST['km'], $_POST['energie'], $_POST['boite_vitesse'], $_POST['nb_places'], $_POST['cylindree'], $_POST['id_membre'], $_POST['id_categorie']);
    //if ($result) {
      //addImage($_POST['image'], $nom_image, $_POST['ext']);
    //}
  }
}
 
function check_extension($ext) {
    $ext_aut = array('jpg','jpeg','png','gif');
    if(in_array($ext,$ext_aut))
    {
        return true;
    }
}
 
function addImage($image, $nom_image, $ext) {
    $valid = false;
    if (!check_extension($ext)) {
        $valid = false;
        $erreur = 'Veuillez charger une image';
        send_status(404);
    }
    else {
        $valid = true;
        $erreur = '';
    }
    
    if($valid)
    {
        $path_to_image = 'uploads/';
        $path_to_min = 'uploads/min/';
        $source = $image;
        $target = $path_to_image . $nom_image;
        echo $source."<br />";
        echo $target."<br />";
        
        $move = move_uploaded_file($source,$target);
        
        
        if($ext == 'jpg' || $ext == 'jpeg') {$im = imagecreatefromjpeg($path_to_image.$nom_image.'.'.$ext);}
        if($ext == 'png') {$im = imagecreatefrompng($path_to_image.$nom_image.'.'.$ext);}
        if($ext == 'gif') { $im = imagecreatefromgif($path_to_image.$nom_image.'.'.$ext);}
        
        $ox = imagesx($im);
        $oy = imagesy($im);
        $nx = 300;
        $ny = floor($oy *($nx/$ox));
        $nm = imagecreatetruecolor($nx,$ny);
        imagecopyresized($nm, $im, 0,0,0,0, $nx,$ny,$ox,$oy);
        imagejpeg($nm, $path_to_min.$nom_image.'.'.$ext);
    }
}