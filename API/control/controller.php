<?php
class controller {

    public function erreur404() {
		http_response_code(404);
		(new vue)->erreur404();
	}

	public function verifierAttributsJson($objetJson, $listeDesAttributs) {
		$verifier = true;
		foreach($listeDesAttributs as $unAttribut) {
			if(!isset($objetJson->$unAttribut)) {
				$verifier = false;
			}
		}
		return $verifier;
	}

    public function getPatients() {
        $donnees = null;

		if(isset($_GET["id"])) {
			if((new patient)->exists($_GET["id"])) {
				http_response_code(200);
				$donnees = (new patient)->getPatient($_GET["id"]);
			}
			else {
				http_response_code(404);
				$donnees = array("message" => "Patient introuvable");
			}
		}
		else {
			http_response_code(200);
			$donnees = (new patient)->getAll();
		}
		
		(new vue)->transformerJson($donnees);

    }

    public function ajouterPatient() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
        if($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        }
        else {
            $attributsRequis = array("nom", "prenom", "rue", "cp", "ville", "tel", "login", "mdp");
            if($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if(!empty($donnees->nom) && !empty($donnees->prenom) && !empty($donnees->rue) && !empty($donnees->cp) && !empty($donnees->ville) && !empty($donnees->login) && !empty($donnees->login) && !empty($donnees->mdp)) {
                    $resultat = (new patient)->insert($donnees->nom, $donnees->prenom, $donnees->rue, $donnees->cp, $donnees->ville, $donnees->tel, $donnees->login, $donnees->mdp);
    
                    if($resultat !== false) {
                        http_response_code(201);
                        $renvoi = array("message" => "Ajout effectué avec succès", "idPatient" => $resultat);
                    }
                    else {
                        http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue",
                    "dump" => "".var_dump($resultat)."");
                    }
                }
                else {
                    http_response_code(400);
                    $renvoi = array("message" => "Données manquantes");
                }
            }
            else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }
    
        (new vue)->transformerJson($renvoi);
    }

    public function modifierPatient() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
        if($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        }
        else {
            $attributsRequis = array("id", "nom", "prenom", "rue", "cp", "ville", "tel", "login", "mdp");
                
            if($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if((new patient)->exists($donnees->id)) {
                    $resultat = (new patient)->update($donnees->id, $donnees->nom, $donnees->prenom, $donnees->rue, $donnees->cp, $donnees->ville, $donnees->tel, $donnees->login, $donnees->mdp);

                    if($resultat != false) {
                        http_response_code(200);
                        $renvoi = array("message" => "Modification effectuée avec succès");
                    }
                    else {
                        http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue");
                    }
                }
                else {
                    http_response_code(400);
                    $renvoi = array("message" => "Le patient spécifié n'existe pas");
                }
            } 
            else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }

        (new vue)->transformerJson($renvoi);
    }


    public function supprimerPatient() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
        if ($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        } else {
            $attributsRequis = array("id");
            if ($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if ((new patient)->exists($donnees->id)) {
                    $resultat = (new patient)->delete($donnees->id);
    
                    if ($resultat != false) {
                        http_response_code(200);
                        $renvoi = array("message" => "Suppression effectuée avec succès");
                    } else {
                        http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue");
                    }
                } else {
                    http_response_code(400);
                    $renvoi = array(
                        "message" => "Le patient spécifié n'existe pas"
                    );
                }
            } else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }
    
        (new vue)->transformerJson($renvoi);
    }
    

    public function getRdvs()
    {
        $donnees = null;

        if(isset($_GET["id"])) {
            if((new rdv)->exists($_GET["id"])) {
                http_response_code(200);
                $donnees = (new rdv)->getRdv($_GET["id"]);
            }
            else {
                http_response_code(404);
                $donnees = array("message" => "Rdv introuvable");
            }
        }
        else {
            http_response_code(200);
            $donnees = (new rdv)->getAll();
        }
        
        (new vue)->transformerJson($donnees);
    }


    public function ajouterRdv()
    {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
        if($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        }
        else {
            $attributsRequis = array("dateHeureRdv", "idPatient", "idMedecin");
            if($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if(!empty($donnees->dateHeureRdv) && !empty($donnees->idPatient) && !empty($donnees->idMedecin)){
                    if (!(new rdv)->alreadyExists($donnees->dateHeureRdv, $donnees->idMedecin)) {
                        $resultat = (new rdv)->setRdv($donnees->dateHeureRdv, $donnees->idPatient, $donnees->idMedecin);
    
                        if($resultat !== false) {
                            http_response_code(201);
                            $renvoi = array("message" => "Ajout effectué avec succès", "idRdv" => $resultat);
                        }
                        else {
                            http_response_code(500);
                            $renvoi = array("message" => "Une erreur interne est survenue");
                        }
                    } else {
                        http_response_code(409);
                        $renvoi = array("message" => "L'heure de rdv est déjà prise");
                    }
                    
                }
                else {
                    http_response_code(400);
                    $renvoi = array("message" => "Données manquantes");
                }
            }
            else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }
    
        (new vue)->transformerJson($renvoi);
    }


    public function modifierRdv() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
    
        if($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        }
        else {
            $attributsRequis = array("id", "dateHeureRdv", "idPatient", "idMedecin");
    
            if($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if((new rdv)->exists($donnees->id)) {
                    $resultat = (new rdv)->modifierRdv($donnees->id, $donnees->dateHeureRdv, $donnees->idPatient, $donnees->idMedecin);
    
                    if($resultat != false) {
                        http_response_code(200);
                        $renvoi = array("message" => "Modification effectuée avec succès");
                    }
                    else {
                        http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue");
                    }
                }
                else {
                    http_response_code(400);
                    $renvoi = array("message" => "Le RDV spécifié n'existe pas");
                }
            } 
            else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }
    
        (new vue)->transformerJson($renvoi);
    }
    

    public function supprimerRdv() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
        
        if ($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        } else {
            $attributsRequis = array("id");
            
            if ($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if ((new rdv)->exists($donnees->id)) {
                    $resultat = (new rdv)->delete($donnees->id);
        
                    if ($resultat != false) {
                        http_response_code(200);
                        $renvoi = array("message" => "Suppression effectuée avec succès");
                    } else {
                        http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue");
                    }
                } else {
                    http_response_code(400);
                    $renvoi = array(
                        "message" => "Le rendez-vous spécifié n'existe pas"
                    );
                }
            } else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }
    
        (new vue)->transformerJson($renvoi);
    }
    
    public function connexion() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
    
        if ($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        } else {
            $attributsRequis = array("login", "mdp");

            if($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if ((new patient)->exists($donnees->login)) {
                    $resultat = (new authentification)->connexion($donnees->login, $donnees->mdp);
                    if ($resultat != false) {
                        http_response_code(200);
                        $renvoi = array("message" => "Connexion effectuée avec succès");
                    } else {
                        http_response_code(500);
                            $renvoi = array("message" => "Une erreur interne est survenue");
                    }
                } else {
                    http_response_code(400);
                        $renvoi = array(
                            "message" => "Mauvais nom d'utilisateur ou mot de passe"
                        );
                }       
            } else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }

        (new Vue)->transformerJson($renvoi);
    }

    public function cookieConnexion() {
        $renvoi = null;
        if(isset($_GET['token'])) {
            $token = $_GET['token'];
            if ((new cookie)->exists($token)) {
                $resultat = (new cookie)->getAuth($token);
                if ($resultat != false) {
                    http_response_code(200);
                    $renvoi = array("message" => "Connexion effectuée avec succès", "nomPatient" => $resultat[0]['nomPatient'], "prenomPatient" => $resultat[0]['prenomPatient'], "loginPatient" => $resultat[0]['loginPatient']);
                } else {
                    http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue");
                }
            } else {
                http_response_code(400);
                $renvoi = array(
                    "message" => "Pas de token"
                );
            }
        }
        (new Vue)->transformerJson($renvoi);
    }

    public function cookieCreate() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
    
        if ($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        } else {
            $attributsRequis = array("token", "idPatient");

            if($this->verifierAttributsJson($donnees, $attributsRequis)) {
                $resultat = (new cookie)->createAuth($donnees->token, $donnees->idPatient);
                
                if ($resultat != false) {
                    http_response_code(200);
                    $renvoi = array("message" => "Connexion effectuée avec succès");
                } else {
                    http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue");
                }
            } else {
                http_response_code(400);
                    $renvoi = array(
                        "message" => "Données manquantes"
                    );
            }
        }
        (new Vue)->transformerJson($renvoi);
    }

    public function cookieDelete() {
        $donnees = json_decode(file_get_contents("php://input"));
        $renvoi = null;
        
        if ($donnees === null) {
            http_response_code(400);
            $renvoi = array("message" => "JSON envoyé incorrect");
        } else {
            $attributsRequis = array("idPatient", "token");
            
            if ($this->verifierAttributsJson($donnees, $attributsRequis)) {
                if ((new cookie)->exists($donnees->token)) {
                    $resultat = (new cookie)->deleteAuth($donnees->token, $donnees->idPatient);
        
                    if ($resultat != false) {
                        http_response_code(200);
                        $renvoi = array("message" => "Suppression effectuée avec succès");
                    } else {
                        http_response_code(500);
                        $renvoi = array("message" => "Une erreur interne est survenue");
                    }
                } else {
                    http_response_code(400);
                    $renvoi = array(
                        "message" => "Le cookie spécifié n'existe pas"
                    );
                }
            } else {
                http_response_code(400);
                $renvoi = array("message" => "Données manquantes");
            }
        }
    
        (new vue)->transformerJson($renvoi);
    }
}

?>