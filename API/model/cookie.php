<?php
class cookie
{
    private $pdo;

    public function __construct() {
		$config = parse_ini_file("config.ini");
		
		try {
			$this->pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

    public function exists($token, $ip = null) {
        if ($ip === null) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $sql = "SELECT count(*) as nb FROM authentification WHERE token = :token AND ipAppareil = :ip";
        
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':token', $token, PDO::PARAM_STR);
        $req->bindParam(':ip', $ip, PDO::PARAM_STR);
        $req->execute();

        $nb = $req->fetch(\PDO::FETCH_ASSOC)["nb"];

        if ($nb == 1) {
            return true;
        }
        return false;
    }

    public function getAuth($token, $ip = null) {
        if ($ip === null) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $sql = "SELECT * FROM patient INNER JOIN authentification ON patient.loginPatient = authentification.idPatient WHERE token = :token AND ipAppareil = :ip";
    
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':token', $token, PDO::PARAM_STR);
        $req->bindParam(':ip', $ip, PDO::PARAM_STR);
        $req->execute();
        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createAuth($token, $idPatient, $ip = null) {
        if ($ip === null) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $sql = "INSERT INTO authentification (token, idPatient, ipAppareil) VALUES (:token, :id, :ip)";

        $req = $this->pdo->prepare($sql);
        $req->bindParam(':token', $token, PDO::PARAM_STR);
        $req->bindParam(':ip', $ip, PDO::PARAM_STR);
        $req->bindParam(':id', $idPatient, PDO::PARAM_STR);
        return ($req->execute());
    }

    public function deleteAuth($token, $idPatient, $ip = null) {
        if ($ip === null) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $sql = "DELETE FROM authentification WHERE token = :token AND ipAppareil = :ip AND idPatient = :id";

        $req = $this->pdo->prepare($sql);
        $req->bindParam(':token', $token, PDO::PARAM_STR);
        $req->bindParam(':ip', $ip, PDO::PARAM_STR);
        $req->bindParam(':id', $idPatient, PDO::PARAM_STR);
        return ($req->execute());
    }
}
?>