<?php 
class Application_Model_Users {
    
        function ifUserExist($id_user) {
            global $bdd;
            try {
                $count = $bdd->prepare("SELECT * FROM membre WHERE id_membre = :id_user");
                $count->bindValue(":id_user", $id_user);
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
	
	function getUser($id_user) {
		global $bdd;
		try {
                    if (!self::ifUserExist($id_user)) {
                        send_status(404);
                    }
                    else {
			$sql = $bdd->prepare("SELECT * FROM membre WHERE id_membre = :id_user");
			$sql->bindValue(":id_user", $id_user);
			$result = $sql->execute();
                        if ($result) {
                            $row = $sql->fetch(PDO::FETCH_ASSOC);
                            return $row;
                        }
                    }
		}
		catch (PDOEXCEPTION $e) {
			die('Erreur : '.$e->getMessage());
		}
	}

	function getAllUsers($limit_min, $limit_max) {
		global $bdd;          
		try {
                    $sql = $bdd->prepare("SELECT * FROM membre LIMIT $limit_min , $limit_max");
                    $sql->bindValue(":limit_min", $limit_min,  PDO::PARAM_INT);
                    $sql->bindValue(":limit_max", $limit_max,  PDO::PARAM_INT);
                    $result = $sql->execute();
                    if ($result) {
                            $rows = $sql->fetchAll();
                            return $rows;

                    }
		}
		catch (PDOEXCEPTION $e) {
			die('Erreur : '.$e->getMessage());
		}
	}

	function addUser($pseudo, $password, $mail, $nom, $prenom, $ville, $code_postal, $telephone) {
            global $bdd;
		try {
                        $ifuserexist = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo OR mail = :mail");
                        $ifuserexist->bindValue(":pseudo", $pseudo);
                        $ifuserexist->bindValue(":mail", $mail);
                        $ifuserexist->execute();
                        if ($ifuserexist->fetchColumn() > 0) {
                            return false;
                        }
                        $hash = uniqid(sha1($pseudo));
			$sql = $bdd->prepare("INSERT INTO membre (id_membre, pseudo, password, mail, nom, prenom, ville, code_postal, telephone, type, statut, hash) VALUES (NULL, :pseudo, :password, :mail, :nom, :prenom, :ville, :code_postal, :telephone, '2', '2', :hash)");
			$sql->bindValue(":pseudo", $pseudo);
                        $sql->bindValue(":password", $password);
                        $sql->bindValue(":mail", $mail);
                        $sql->bindValue(":nom", $nom);
                        $sql->bindValue(":prenom", $prenom);
                        $sql->bindValue(":ville", $ville);
                        $sql->bindValue(":code_postal", $code_postal);
                        $sql->bindValue(":telephone", $telephone);
                        $sql->bindValue(":hash", $hash);
			$result = $sql->execute();
                        if ($result) {
                            $this->sendEmail($mail);
                            return true;  
                        }
		}
		catch (PDOEXCEPTION $e) {
			die('Erreur : '.$e->getMessage());
		}
	}


    function setUser($pseudo, $password, $mail, $nom, $prenom, $ville, $code_postal, $telephone, $id_membre) {
        global $bdd;
        
        try {
            if (!self::ifUserExist($id_membre)) {
                send_status(404);
            }
            else {
                $sql = $bdd->prepare("UPDATE membre SET pseudo = :pseudo, password = :password, mail = :mail, nom = :nom, prenom = :prenom, ville = :ville, code_postal = :code_postal, telephone = :telephone WHERE id_membre = :id_membre");
                $sql->bindValue(":pseudo", $pseudo);
                $sql->bindValue(":password", $password);
                $sql->bindValue(":mail", $mail);
                $sql->bindValue(":nom", $nom);
                $sql->bindValue(":prenom", $prenom);
                $sql->bindValue(":ville", $ville);
                $sql->bindValue(":code_postal", $code_postal);
                $sql->bindValue(":telephone", $telephone);
                $sql->bindValue(":id_membre", $id_membre);
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

    function deleteMembreById($id_membre) {
        global $bdd;
        try {
                    if (!self::ifUserExist($id_membre)) {
                        send_status(404);
                    }
                    else {
                        $sql = $bdd->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
                        $sql->bindValue(":id_membre", $id_membre);
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
        
    function sendEmail($mail) {
        global $bdd;
        try {
            $sql = $bdd->prepare("SELECT * FROM membre WHERE mail = :mail");
            $sql->bindValue(":mail", $mail);
            $result = $sql->execute();
            if ($result) {
                $rowUser = $sql->fetch();
                $to  = $mail;
                $subject = SITE_NAME." - Validation de votre compte";
                $message = '
                <html>
                 <head>
                  <title>Validation de votre inscription</title>
                 </head>
                 <body>
                  <p>Bonjour '.$rowUser['prenom'].' '.$rowUser['nom'].' </p>
                  <p>Pour valider votre compte, vous devez cliquez sur le lien suivant : <br />
                    <a href="'.SITE_ROOT.'users/validation/'.$rowUser['hash'].'">'.SITE_ROOT.'users/validation/'.$rowUser['hash'].'</a><br />
                    Copiez-coller le lien si vous ne parvenez pas a l\'ouvrir
                  </p>
                  <p>L\'équipe '.SITE_NAME.'</p>
                 </body>
                </html>
                ';
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: José de '.SITE_NAME.'<jose.albea@gmail.com>' ."\r\n";
                $headers .= 'Service inscription '.SITE_NAME."\r\n";
                mail($to, $subject, $message, $headers);
            }
        }
        catch(PDOException $e) {
            die('Erreur : '.$e->getMessage());
        }
    }
    
    function validateAccount($hash) {
        global $bdd;
        try {
            $sql = $bdd->prepare("SELECT COUNT(*) AS nb FROM membre WHERE hash = :hash");
            $sql->bindValue(":hash", $hash);
            $result = $sql->execute();
            $countUser = $sql->fetchAll();
            if ($result) {
                if ($countUser > 0) {
                    $fetch = $bdd->prepare("SELECT * FROM membre WHERE hash = :hash");
                    $fetch->bindValue(":hash", $hash);
                    $resultCount = $fetch->execute();     
                    $fetchUser = $fetch->fetch();
                    switch($fetchUser['statut']) {
                        case "0":
                            return 0;
                        break;
                        case "1":
                            return 1;
                        break;
                        case "2":
                            $update = $bdd->prepare("UPDATE membre SET statut = '1' WHERE hash = :hash");
                            $update->bindValue(":hash", $hash);
                            $result2 = $update->execute();
                            if ($result2) {
                                return 2;
                            }
                        
                    }
                }
                else {
                    send_status(404);
                    return 3;
                }
            }
        }
        catch(PDOException $e) {
            die('Erreur : '.$e->getMessage());
        }
    }

}