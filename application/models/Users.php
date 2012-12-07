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
                    $to  = $mail;
                    $subject = SITE_NAME." - Validation de votre compte";
                    $message = '
                    <html>
                     <head>
                      <title>Calendrier des anniversaires pour Août</title>
                     </head>
                     <body>
                      <p>Voici les anniversaires à venir au mois d\'Août !</p>
                      <table>
                       <tr>
                        <th>Personne</th><th>Jour</th><th>Mois</th><th>Année</th>
                       </tr>
                       <tr>
                        <td>Josiane</td><td>3</td><td>Août</td><td>1970</td>
                       </tr>
                       <tr>
                        <td>Emma</td><td>26</td><td>Août</td><td>1973</td>
                       </tr>
                      </table>
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