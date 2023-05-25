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

        return password_verify($mdp, $hash);
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
}

?>