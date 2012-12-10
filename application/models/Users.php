<?php 
class Application_Model_Users {
	
	function getDataUser($id_user) {
		global $bdd;
		try {
			$sql = $bdd->prepare("SELECT * FROM membre WHERE id_membre = :id_user");
			$sql->bindValue(":id_user", $id_user);
			$sql->execute();
		}
		catch (PDOEXCEPTION $e) {
			die('Erreur : '.$e->getMessage());
		}
	}

	function getAllUsers() {
		global $bdd;
		try {
			$sql = $bdd->prepare("SELECT * FROM membre");
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
                            echo "Désolé mais un compte avec le même pseudo ou même adresse mail est déjà présent dans la base";
                            die;
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
                            echo "Le compte vient d'être créé. Toutefois, il est nécéssaire de le valider via le mail qui vous a été envoyé.";
                        }
		}
		catch (PDOEXCEPTION $e) {
			die('Erreur : '.$e->getMessage());
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
                $sql = $bdd->prepare("SELECT * FROM membre WHERE hash = :hash");
                $sql->bindValue(":hash", $hash);
                $result = $sql->execute();
                $fetchUser = $sql->fetch();
                if ($result) {
                    $rowUser = $sql->fetchColumn();
                    if ($rowUser > 0) {
                        switch($fetchUser['statut']) {
                            case "0":
                                return $validate = 0;
                            break;
                            case "1":
                                return $validate = 1;
                            break;
                            case "2":
                                $update = $bdd->prepare("UPDATE membre SET statut = '1' WHERE hash = :hash");
                                $update->bindValue(":hash", $hash);
                                $result = $update->execute();
                                if ($result) {
                                    return $validate = 2;
                                }
                            
                        }
                    }
                    
                }
            }
            catch(PDOException $e) {
                die('Erreur : '.$e->getMessage());
            }
        }

}