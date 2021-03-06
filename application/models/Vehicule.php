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
 
    function getMemberByVehicule($id_vehicule) {
        global $bdd;
        try {
                    if (!self::ifVehiculeExist($id_vehicule)) {
                        send_status(404);
                    }
                    else {
                        $sql = $bdd->prepare("SELECT id_membre FROM vehicule WHERE id_vehicule = :id_vehicule");
                        $sql->bindValue(':id_vehicule', $id_vehicule);
                        $result = $sql->execute();
                        if ($result) {
                            $row = $sql->fetch(PDO::FETCH_ASSOC);
                            $member = $bdd->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
                            $member->bindValue('id_membre', $row['id_membre']);
                            $resultMember = $member->execute();
                            if ($resultMember) {
                                $user = $member->fetch(PDO::FETCH_ASSOC);
                                return $user;
                            }
                                
                        }
                    }
        }
        catch (PDOException $e) {
            die('Erreur : '.$e->getMessage());
        }
    }

    function countNbVehicules($type, $id) {
        global $bdd;
        try {
            //if ($type == "membre") {
              //  $where = " WHERE id_membre = ".$id;
            //}
            if ($type == "type") {
                $where = " WHERE id_categorie = ".$id;
            }
            else {
                $where = "";
            }
            $sql = $bdd->prepare("SELECT COUNT(*) as nb FROM vehicule ".$where);
            $result = $sql->execute();
            if ($result) {
                return $sql->fetchColumn();
            }
        }
        catch (PDOException $e) {
            die('Erreur : '.$e->getMessage());
        }
    }
 
	function getAllVehicules($limit_min, $limit_max) {
        global $bdd;
        try {
            $sql = $bdd->prepare("SELECT * FROM vehicule ORDER BY id_vehicule DESC LIMIT :limit_min, :limit_max");
            $sql->bindValue(":limit_min", $limit_min,  PDO::PARAM_INT);
            $sql->bindValue(":limit_max", $limit_max,  PDO::PARAM_INT);
            $result = $sql->execute();
            if($sql->rowCount() > 0) {
                if ($result) {
                        $rows = $sql->fetchAll();
                        return $rows;
                }
            }
            else {
                send_status(404);
            }
        }
        catch (PDOException $e) {
            die('Erreur : '.$e->getMessage());
        }
    }

    function getAllVehiculesByType($limit_min, $limit_max, $type_vehicule) {
        global $bdd;
        try {
            $sql = $bdd->prepare("SELECT * FROM vehicule WHERE id_categorie = :type_vehicule ORDER BY id_vehicule DESC LIMIT :limit_min, :limit_max");
            $sql->bindValue(":limit_min", $limit_min,  PDO::PARAM_INT);
            $sql->bindValue(":limit_max", $limit_max,  PDO::PARAM_INT);
            $sql->bindValue(":type_vehicule", $type_vehicule);
            $result = $sql->execute();
            if($sql->rowCount() > 0) {
                if ($result) {
                        $rows = $sql->fetchAll();
                        return $rows;
                }
            }
            else {
                send_status(404);
            }
        }
        catch (PDOException $e) {
            die('Erreur : '.$e->getMessage());
        }
    }
 
	function getAllVehiculesByMember($limit_min, $limit_max, $id_membre) {
		global $bdd;
		try {
                    $sql = $bdd->prepare("SELECT * FROM vehicule WHERE id_membre = :id_membre LIMIT :limit_min , :limit_max");
                    $sql->bindValue(':id_membre', $id_membre, PDO::PARAM_INT);
                    $sql->bindValue(':limit_min', $limit_min, PDO::PARAM_INT);
                    $sql->bindValue(':limit_max', $limit_max, PDO::PARAM_INT);
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
                    $sql = $bdd->prepare("INSERT INTO vehicule (id_vehicule, titre, description, prix, annee, km, energie, date_ajout, date_modification, date_suppression, boite_vitesse, nb_places, cylindree, id_membre, id_categorie) 
                    VALUES (NULL, :titre, :description, :prix, :annee, :km, :energie, curdate(), '0000-00-00', '0000-00-00', :boite_vitesse, :nb_places, :cylindree, :id_membre, :id_categorie)");
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
                        //$lastid =  $bdd->lastInsertId();
                        //self::uploadImage($lastid, $photo);
                        return true;
                    }
		}
		catch (PDOException $e) {
			die ('Erreur : '.$e->getMessage());
		}
	}
 
	function searchVehicule($type, $recherche, $annee, $cp, $km, $prix_min, $prix_max, $energie, $boite_vitesse) {
		global $bdd;
        try {
            $wheres = array();
            $between = '';
            $whereCond = '';
            if (!empty($type)) {
                $wheres[] = "id_categorie = ".$type;
            }

            if (!empty($recherche)) {
                $wheres[] = "titre LIKE '%".$recherche."%' OR description LIKE '%".$recherche."%'";
            }

            if (!empty($annee)) {
                $wheres[] = "annee >= ".$annee;
            }

            if (!empty($cp)) {
                $wheres[] = "membre.code_postal = ".$cp;
            }

            if (!empty($km)) {
                $wheres[] = "km <= ".$km;
            }

            if (!empty($energie)) {
                $wheres[] = "energie = '".$energie."'";
            }

            if (!empty($boite_vitesse)) {
                $wheres[] = "boite_vitesse = '".$boite_vitesse."'";
            }

            if (!empty($prix_min) AND !empty($prix_max) ) {
                if (!empty($wheres)) {
                    $between = "AND prix BETWEEN ".$prix_min." AND ".$prix_max;
                }
                else {
                    $between = "WHERE prix BETWEEN ".$prix_min." AND ".$prix_max;
                }
                
            }
            if (!empty($wheres)) {
                $whereCond = "WHERE ".join(" AND ", $wheres);
            }

            $sql = $bdd->prepare('SELECT * FROM vehicule LEFT JOIN membre ON vehicule.id_membre = membre.id_membre '.$whereCond.' '.$between);
            $result = $sql->execute();
            if($sql->rowCount() > 0) {
                if ($result) {
                        $rows = $sql->fetchAll();
                        return $rows;
                }
            }
            else {
                send_status(404);
                return false;
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
        
        function uploadImage($id_vehicule, $url_image) {
            global $bdd;
		try {
                    if (!self::ifVehiculeExist($id_vehicule)) {
                        send_status(404);
                        return false;
                    }
                    else {
                        $sql = $bdd->prepare("INSERT INTO photo (id_photo, url, id_vehicule) VALUES (NULL, :url_image, :id_vehicule)");
                        $sql->bindValue(":id_vehicule", $id_vehicule, PDO::PARAM_INT);
                        $sql->bindValue(":url_image", $url_image);
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