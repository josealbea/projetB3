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
                    if($sql->fetchColumn() > 0) {
                        if ($result) {
                                $rows = $sql->fetchAll();
                                return $rows;

                        }
                    }
                    else {
                        send_status(404);
                    }
		}
		catch (PDOEXCEPTION $e) {
			die('Erreur : '.$e->getMessage());
		}
	}

	function addUser($password, $mail, $nom, $code_postal, $telephone) {
            global $bdd;
		try {
                        $ifuserexist = $bdd->prepare("SELECT * FROM membre WHERE mail = :mail");
                        $ifuserexist->bindValue(":mail", $mail);
                        $ifuserexist->execute();
                        if ($ifuserexist->fetchColumn() > 0) {
                            return false;
                        }
                        $hash = uniqid(sha1($nom));
			$sql = $bdd->prepare("INSERT INTO membre (id_membre, password, mail, nom, code_postal, telephone, type, statut, hash) VALUES (NULL, :password, :mail, :nom, :code_postal, :telephone, '2', '2', :hash)");
            $sql->bindValue(":password", $password);
            $sql->bindValue(":mail", $mail);
            $sql->bindValue(":nom", $nom);
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


    function setUser($password, $mail, $nom, $ville, $code_postal, $telephone, $id_membre) {
        global $bdd;
        
        try {
            if (!self::ifUserExist($id_membre)) {
                send_status(404);
            }
            else {
                $sql = $bdd->prepare("UPDATE membre SET password = :password, mail = :mail, nom = :nom, ville = :ville, code_postal = :code_postal, telephone = :telephone WHERE id_membre = :id_membre");
                $sql->bindValue(":password", $password);
                $sql->bindValue(":mail", $mail);
                $sql->bindValue(":nom", $nom);
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
                  <p>Bonjour '.$rowUser['nom'].' </p>
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

    function checkUser($mail, $password) {
        global $bdd;
        try {
            $password = sha1($password);
            $sql = $bdd->prepare("SELECT * FROM membre WHERE mail = :mail AND password = :password");
            $sql->bindValue(":mail", $mail);
            $sql->bindValue(":password", $password);
            $result = $sql->execute();
            $membre = array();
            if ($result) {
                $row = $sql->fetch();
                $membre['code_postal'] = $row['code_postal'];
                $membre['type'] = $row['type'];
                $membre['mail'] = $row['mail'];
                if($sql->rowCount() > 0) {
                    if ($row['statut'] == 2) {
                        $membre['statut'] = "2";
                        send_status(204);
                    }
                    else if ($row['statut'] == 3) {
                        $membre['statut'] = "3";
                        send_status(204);
                    }
                    else {
                        $membre['statut'] = "1";
                        $membre['id_membre'] = $row['id_membre'];
                        send_status(200);
                        print json_encode($membre);exit;
                        return $membre;
                    }
                }
                else {
                    echo "0";
                }
            }
        }
        catch (PDOException $e) {
            die('Erreur : '.$e->getMessage());
        }
    }

}