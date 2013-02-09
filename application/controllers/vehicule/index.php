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
  $limit_max = ($_GET['page'] * 10);
  global $liste_vehicule;
  if (empty($_GET['type'])) {
    $type_vehicule = "";
  }
  else {
    $type_vehicule = $_GET['type'];
  }
  if (isset($_GET['id_membre'])) {
    $id_membre = $_GET['id_membre'];
    $liste_vehicule = $Vehicule->getAllVehiculesByMember($limit_min, $limit_max, $id_membre);
    $dom = new DOMDocument();
    $vehicules = $dom->createElement("vehicules");
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
  else {
	$liste_vehicule = $Vehicule->getAllVehicules($limit_min, $limit_max, $type_vehicule);
        $dom = new DOMDocument();
        $vehicules = $dom->createElement("vehicules");
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
}
 
// FONCTION POST
function do_post() {
	$erreurs = array();
	parse_str(file_get_contents("php://input"), $_POST);
  var_dump($_POST);
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
		extract($_POST);
		$vehicule = new Application_Model_Vehicule;
    var_dump($_POST);exit;
		$vehicule->addVehicule($_POST['titre'], $_POST['description'], $_POST['prix'], $_POST['annee'], $_POST['km'], $_POST['energie'], $_POST['boite_vitesse'], $_POST['nb_places'], $_POST['cylindree'], $id_membre, $_POST['id_categorie']);
	}
}
 
function check_extension($ext) {
    $ext_aut = array('jpg','jpeg','png','gif');
    if(in_array($ext,$ext_aut))
    {
        return true;
    }
}
 
function addImage($image, $id_vehicule) {
    var_dump($image); 
    $valid = false;
    $nom_image = $image['name'];
    $ext = strtolower(substr(strrchr($nom_image,'.'),1));
    if (!self::check_extension($ext)) {
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
        $max_size = 2000000;
        if($image['size']>$max_size)
        {
            $valid = false;
            $erreur = 'Fichier trop gros';
        }
    }
    
    if($valid)
    {
        if($image['error']>0)
        {
            $valid = false;
            $erreur = 'Erreur lors du transfert';
        }
    }
    
    if($valid)
    {
        $path_to_image = '../public/uploads/';
        $path_to_min = '../public/uploads/min/';
        
        $filename = uniqid($nom_image);
        
        $source = $image['tmp_name'];
        $target = $path_to_image . $filename. '.'. $ext;
        
        move_uploaded_file($source,$target);
        
        if($ext == 'jpg' || $ext == 'jpeg') {$im = imagecreatefromjpeg($path_to_image.$filename.'.'.$ext);}
        if($ext == 'png') {$im = imagecreatefrompng($path_to_image.$filename.'.'.$ext);}
        if($ext == 'gif') { $im = imagecreatefromgif($path_to_image.$filename.'.'.$ext);}
        
        $ox = imagesx($im);
        $oy = imagesy($im);
        $nx = 300;
        $ny = floor($oy *($nx/$ox));
        $nm = imagecreatetruecolor($nx,$ny);
        imagecopyresized($nm, $im, 0,0,0,0, $nx,$ny,$ox,$oy);
        imagejpeg($nm, $path_to_min.$filename.'.'.$ext);
        
        $url_image = $filename.'.'.$ext;
        
        $uploadImage = new Application_Model_Vehicule;
        $upload = $uploadImage->uploadPicture($url_image, $nom_image, $id_vehicule);
        if ($upload) {
            send_status(200);
        }
        else {
            send_status(400);
        }
    }
}