<?php

class authentification {

    private $pdo;

    public function __construct() {
		$config = parse_ini_file("config.ini");
		
		try {
			$this->pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

    public function connexion($login, $mdp){

        $sql = "SELECT mdpPatient FROM patient WHERE loginPatient = :login";

        $req = $this->pdo->prepare($sql);
        $req->bindParam(':login', $login, PDO::PARAM_STR);
        $req->execute();

        $result = $req->fetch();
        $hash = $result[0];

        $token = bin2hex(random_bytes(32));
        $ip = $_SERVER['REMOTE_ADDR'];

        $sql2 = "INSERT INTO authentification VALUES (:letoken,(SELECT idPatient FROM patient WHERE loginPatient = :login), :Ip)";

        $req2 = $this->pdo->prepare($sql2);
        $req2->bindParam(':letoken', $token, PDO::PARAM_STR);
        $req2->bindParam(':Ip', $ip, PDO::PARAM_STR);
        $req2->bindParam('login', $login, PDO::PARAM_STR);
        $req2->execute();

        $correctPassword = password_verify($mdp, $hash);

        if (!$correctPassword) {
            return false;
        }
    
        return true;
        
    }

    public function getIdPatient(){
        $sql = "SELECT idPatient FROM authentification where ipAppareil = :ip";

        $req = $this->pdo->prepare($sql);
        $req->bindParam(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $req->execute();

        $result = $req->fetch();
        $id = $result[0];

        return $id;
    }

    public function verifIP($ip){

        if ($_SERVER['REMOTE_ADDR'] != $ip) {
            return false;
        }
        else {
            return true;
        }
    }
}

?>