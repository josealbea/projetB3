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

	function addUser($pseudo, $password, $mail, $nom, $prenom, $ville, $code_postal, $telephone); {
            global $bdd;
		try {
			$sql = $bdd->prepare("INSERT INTO membre (id_membre, pseudo, password, mail, nom, prenom, ville, code_postal, telephone, type, statut) VALUES (NULL, ':pseudo', ':password', ':mail', ':nom', ':prenom', ':ville', ':code_postal', ':telephone', '2', '0')");
			$sql->bindValue(":pseudo", $pseudo);
                        $sql->bindValue(":password", $password);
                        $sql->bindValue(":mail", $mail);
                        $sql->bindValue(":nom", $nom);
                        $sql->bindValue(":prenom", $prenom);
                        $sql->bindValue(":ville", $ville);
                        $sql->bindValue(":code_postal", $code_postal);
                        $sql->bindValue(":telephone", $telephone);
			$result = $sql->execute();
                        if ($result) {
                            sendEmail($mail);
                            echo "Le compte vient d'être créé. Toutefois, il est nécéssaire de le valider via le mail qui vous a été envoyé."
                        }
		}
		catch (PDOEXCEPTION $e) {
			die('Erreur : '.$e->getMessage());
		}
	}
        
        function sendEmail($mail) {
            global $bdd;
            try {
                $sql = $bdd->prepare("SELECT * FROM membre WHERE mail = ':mail'");
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
                      <p>Bonjour '.$rowUser['prenom'].' '.$rowUser['prenom'].' </p>
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
                    $headers .= 'Reply-To: José de'.SITE_NAME.'<jose.albea@gmail.com>' ."\r\n";
                    $headers .= 'Service inscription '.SITE_NAME."\r\n";
                    mail($to, $subject, $message, $headers);
                }
            }
            catch(PDOException $e) {
                die('Erreur : '.$e->getMessage());
            }
        }

}