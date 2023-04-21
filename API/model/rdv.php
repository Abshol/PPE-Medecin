<?php

class rdv {

    private $pdo;

    public function __construct() {

		$config = parse_ini_file("config.ini");
		
		try {
			$this->pdo = new \PDO("mysql:host=".$config["host"].";dbname=".$config["database"].";charset=utf8", $config["user"], $config["password"]);
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	}

    public function getAll() {

        $sql = "SELECT * FROM rdv";

        $req = $this->pdo->prepare($sql);
        $req->execute();

        return $req->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getRdv($id){

        $sql = "SELECT * FROM rdv where idRdv = :id";

        $req = $this->pdo->prepare($sql);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();

        return $req->fetch(\PDO::FETCH_ASSOC);
    }

    public function exists($id){

        $sql = "SELECT COUNT(*) AS nb FROM rdv where idRdv = :id";

        $req = $this->pdo->prepare($sql);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();

        $nb = $req->fetch(\PDO::FETCH_ASSOC)["nb"];
        if ($nb == 1) {
            return true;
        }
        else {
            return false;
        }
    }

    public function setRdv($dateRdv, $idPatient, $idMedecin){
    
        $sql = "INSERT INTO rdv (dateHeureRdv, idPatient, idMedecin) VALUES (:dateRdv, :idPatient, :idMedecin)";
        
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':dateRdv', $dateRdv, PDO::PARAM_STR);
        $req->bindParam(':idPatient', $idPatient, PDO::PARAM_INT);
        $req->bindParam(':idMedecin', $idMedecin, PDO::PARAM_STR);
    
        if ($req->execute()) {
            return true;
        }
        else {
            return false;
        }
    }

    public function modifierRdv($id, $dateRdv, $idPatient, $idMedecin) {
        if (!$this->exists($id)) {
            return false;
        }
       
        $sql = "UPDATE rdv SET dateHeureRdv=:dateRdv, idPatient=:idPatient, idMedecin=:idMedecin WHERE idRdv=:id";
        $req = $this->pdo->prepare($sql);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->bindParam(':dateRdv', $dateRdv, PDO::PARAM_STR);
        $req->bindParam(':idPatient', $idPatient, PDO::PARAM_INT);
        $req->bindParam(':idMedecin', $idMedecin, PDO::PARAM_INT);
        $req->execute();
    
        return true;
    }
    
    public function delete($id) {
		$sql = "DELETE FROM rdv WHERE idRdv = :id";
		
		$req = $this->pdo->prepare($sql);
		$req->bindParam(':id', $id, PDO::PARAM_INT);
		return $req->execute();
	}
    
}

?>