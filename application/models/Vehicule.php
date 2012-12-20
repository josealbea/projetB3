<?php 
class Application_Model_Vehicule {
        
        function ifVehiculeExist($id_vehicule) {
            global $bdd;
            try {
                $count = $bdd->prepare("SELECT * FROM vehicule WHERE id_vehicule = :id_vehicule");
                $count->bindValue(":id_vehicule", $id_vehicule);
                $count->execute();
                if ($count->fetchColumn() < 1) {
                    return false;
                }
                else {
                    return true;
                }
            }
            catch(PDOException $e) {
                die("Erreur :". $e->getMessage());
            }
        }
	
	function getVehicule($id_vehicule) {
		global $bdd;
		try {
                    if (!self::ifVehiculeExist($id_vehicule)) {
                        send_status(404);
                    }
                    else {
                        $sql = $bdd->prepare("SELECT * FROM vehicule WHERE id_vehicule = :id_vehicule");
                        $sql->bindValue(':id_vehicule', $id_vehicule);
                        $result = $sql->execute();
                        if ($result) {
                                $row = $sql->fetch(PDO::FETCH_ASSOC);
                                return $row;
                        }
                    }
		}
		catch (PDOException $e) {
		    die('Erreur : '.$e->getMessage());
		}
	}

	function getAllVehicules($limit_min, $limit_max) {
		global $bdd;
		try {
            $sql = $bdd->prepare("SELECT * FROM vehicule LIMIT :limit_min, :limit_max");
            $sql->bindValue(":limit_min", $limit_min,  PDO::PARAM_INT);
            $sql->bindValue(":limit_max", $limit_max,  PDO::PARAM_INT);
            $result = $sql->execute();
            if ($result) {
                    $rows = $sql->fetchAll();
                    return $rows;
            }
		}
		catch (PDOException $e) {
		    die('Erreur : '.$e->getMessage());
		}
	}

	function getAllVehiculesByMember($limit_min, $limit_max) {
		global $bdd;
		try {
                    $sql = $bdd->prepare("SELECT * FROM vehicule WHERE id_membre = :id_member LIMIT :limit_min , :limit_max");
                    $sql->bindValue(':id_member', $id_member);
                    $sql->bindValue(':limit_min', $limit_min);
                    $sql->bindValue(':limit_max', $limit_max);
                    $result = $sql->execute();
                    if ($result) {
                            $rows = $sql->fetchAll();
                            return $rows;
                    }
		}
		catch (PDOException $e) {
		    die('Erreur : '.$e->getMessage());
		}
	}

	function getAllVehiculesByCategory($id_category, $limit_min, $limit_max) {
		global $bdd;
		try {
                    $sql = $bdd->prepare("SELECT * FROM vehicule WHERE id_categorie = :id_category LIMIT :limit_min , :limit_max");
                    $sql->bindValue(':id_category', $id_category);
                    $sql->bindValue(':limit_min', $limit_min);
                    $sql->bindValue(':limit_max', $limit_max);
                    $result = $sql->execute();
                    if ($result) {
                            $rows = $sql->fetchAll();
                            return $rows;
                    }
		}
		catch(PDOException $e) {
			die('Erreur : '.$e->getMessage());
		}
	}
	function addVehicule($titre, $description, $prix, $annee, $km, $energie, $boite_vitesse, $nb_places, $cylindree, $id_membre, $id_categorie) {
		global $bdd;
		try {
                    $sql = $bdd->prepare("INSERT INTO vehicule (id_vehicule, titre, description, prix, annee, km, energie, date_ajout, date_modification, date_suppression, statut, boite_vitesse, nb_places, cylindree, id_membre, id_categorie) 
                    VALUES (NULL, :titre, :description, :prix, :annee, :km, :energie, curdate(), '0000-00-00', '0000-00-00', '0', :boite_vitesse, :nb_places, :cylindree, :id_membre, :id_categorie)");
                    $sql->bindValue(":titre", ucwords(trim($titre)));
                    $sql->bindValue(":description", trim($description));
                    $sql->bindValue(":prix", $prix);
                    $sql->bindValue(":annee", $annee);
                    $sql->bindValue(":km", $km);
                    $sql->bindValue(":energie", ucwords(trim($energie)));
                    $sql->bindValue(":boite_vitesse", ucwords(trim($boite_vitesse)));
                    $sql->bindValue(":nb_places", $nb_places);
                    $sql->bindValue(":cylindree", $cylindree);
                    $sql->bindValue(":id_membre", $id_membre);
                    $sql->bindValue(":id_categorie", $id_categorie);
                    $result = $sql->execute();
                    if ($result) {
                        return true;
                    }
		}
		catch (PDOException $e) {
			die ('Erreur : '.$e->getMessage());
		}
	}

	function searchVehicule($type, $recherche, $annee, $km, $prix_min, $prix_max, $energie, $boite_vitesse, $nb_places) {
		global $bdd;
		try {
                    $sql = $bdd->prepare("SELECT * FROM vehicule WHERE titre LIKE '%:recherche%' AND annee >= ':annee' AND km <= ':km' AND energie = ':energie' AND boite_vitesse = ':boite_vitesse' AND nb_places = ':nb_places' AND id_categorie = ':type' AND prix BETWEEN ':prix_min' AND ':prix_max' ");
                    $sql->bindValue(":recherche", $recherche);
                    $sql->bindValue(":prix_min", $prix_min);
                    $sql->bindValue(":prix_max", $prix_max);
                    $sql->bindValue(":annee", $annee);
                    $sql->bindValue(":km", $km);
                    $sql->bindValue(":energie", $energie);
                    $sql->bindValue(":boite_vitesse", $boite_vitesse);
                    $sql->bindValue(":nb_places", $nb_places);
                    //$sql->bindValue(":cylindree", $cylindree);
                    $sql->bindValue(":id_categorie", $type);
                    $result = $sql->execute();
                    if ($result) {
                            $rows = $sql->fetchAll();
                            return $rows;
                    }
		}
		catch (PDOException $e) {
		    die('Erreur : '.$e->getMessage());
		}
		}

	function setVehicule($titre, $description, $prix, $annee, $km, $energie, $boite_vitesse, $nb_places, $cylindree, $id_vehicule) {
		global $bdd;
		try {
                    if (!self::ifVehiculeExist($id_vehicule)) {
                        send_status(404);
                    }
                    else {
                        $date = date("Y-m-d");
                        $sql = $bdd->prepare("UPDATE vehicule SET titre = :titre, description = :description, prix = :prix, annee = :annee, km = :km, energie = :energie, date_modification = :date_modification, boite_vitesse = :boite_vitesse, nb_places = :nb_places, cylindree = :cylindree WHERE id_vehicule = :id_vehicule");
                        $sql->bindValue(":titre", $titre);
                        $sql->bindValue(":description", $description);
                        $sql->bindValue(":prix", $prix);
                        $sql->bindValue(":annee", $annee);
                        $sql->bindValue(":km", $km);
                        $sql->bindValue(":energie", $energie);
                        $sql->bindValue(":date_modification", $date);
                        $sql->bindValue(":boite_vitesse", $boite_vitesse);
                        $sql->bindValue(":nb_places", $nb_places);
                        $sql->bindValue(":cylindree", $cylindree);
                        $sql->bindValue(":id_vehicule", $id_vehicule);
                        $result = $sql->execute();
                        if ($result) {
                            return true;
                        }
                        else {
                            return false;
                        }
                    }
                }
		catch (PDOException $e) {
		    die('Erreur : '.$e->getMessage());
		}
	}

	function deleteVehicule($id_vehicule) {
		global $bdd;
		try {
                    if (!self::ifVehiculeExist($id_vehicule)) {
                        send_status(404);
                    }
                    else {
                        $sql = $bdd->prepare("DELETE FROM vehicule WHERE id_vehicule = :id_vehicule");
                        $sql->bindValue(":id_vehicule", $id_vehicule);
                        $result = $sql->execute();
                        if ($result) {
                            return true;
                        }
                    }
		}
		catch (PDOException $e) {
			die ('Erreur : '. $e->getMessage());
		}
	}
        
        function uploadImage($url_image, $nom_image, $id_vehicule) {
            global $bdd;
		try {
                    if (!self::ifVehiculeExist($id_vehicule)) {
                        send_status(404);
                        return false;
                    }
                    else {
                        $sql = $bdd->prepare("INSERT INTO photo (id_photo, titre, url, id_vehicule) VALUES (NULL, ':nom_photo', ':nom_image', ':id_vehicule')");
                        $sql->bindValue(":id_vehicule", $id_vehicule);
                        $sql->bindValue(":url_image", $url_image);
                        $sql->bindValue(":nom_image", $nom_image);
                        $result = $sql->execute();
                        if ($result) {
                            return true;
                        }
                    }
		}
		catch (PDOException $e) {
			die ('Erreur : '. $e->getMessage());
		}
        }

}