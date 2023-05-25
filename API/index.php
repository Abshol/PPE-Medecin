<?php
session_start();

// Test de connexion à la base
$config = parse_ini_file("config.ini");
try {
	$pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
} catch(Exception $e) {
	http_response_code(500);
	header('Content-Type: application/json');
	header("Access-Control-Allow-Origin: *");
	echo '{ "message":"Erreur de connexion à la base de données" }';
	exit;
}

// Chargement des fichiers MVC
require("control/controller.php");
require("view/vue.php");
require("model/authentification.php");
require("model/patient.php");
require("model/rdv.php");
require("model/cookie.php");

// Routes et méthodes HTTP associées
if(isset($_GET["action"])) {
	switch($_GET["action"]) {
		case "patient":
			switch($_SERVER["REQUEST_METHOD"]) {
				case "GET":
					(new controller)->getPatients();
					break;
				case "POST":
					(new controller)->ajouterPatient();
					break;
				case "PUT":
					(new controller)->modifierPatient();
					break;
				case "DELETE":
					(new controller)->supprimerPatient();
					break;
				default:
					(new controller)->erreur404();
					break;
			}
			break;

		case "rdv":
			switch($_SERVER["REQUEST_METHOD"]) {
				case "GET":
					(new controller)->getRdvs();
					break;
				case "POST":
					(new controller)->ajouterRdv();
					break;
				case "PUT":
					(new controller)->modifierRdv();
					break;
				case "DELETE":
					(new controller)->supprimerRdv();
					break;
				default:
					(new controller)->erreur404();
					break;
			}
			break;
		
		case "authentification":
			switch ($_SERVER["REQUEST_METHOD"]) {
				case 'POST':
					(new controller)->connexion();
					break;
				
				default:
					(new controller)->erreur404();
					break;
			}
			break;
        
        case "cookie":
            switch ($_SERVER["REQUEST_METHOD"]) {
                case 'GET':
                    (new controller)->cookieConnexion();
                    break;
                case 'POST':
                    (new controller)->cookieCreate();
                    break;
                case 'DELETE':
                    (new controller)->cookieDelete();
                    break;
                default:
                    (new controller)->erreur404();
                    break;
            }
        break;
		// Route par défaut : erreur 404
		default:
			(new controller)->erreur404();
			break;
	}
}
else {
	// Pas d'action précisée = erreur 404
	(new controller)->erreur404();
}