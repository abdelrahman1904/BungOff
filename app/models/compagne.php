<?php
class Compagne {
    private $conn;
    private $compagne = "compagne";

    public $idC;
    public $titreC;
    public $descriptionC;
    public $date_debutC;
    public $date_finC;
    public $id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->compagne . " ORDER BY idC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->compagne . " WHERE idC = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->idC);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->idC = $row['idC'];
            $this->titreC = $row['titreC'];
            $this->descriptionC = $row['descriptionC'];
            $this->date_debutC = $row['date_debutC'];
            $this->date_finC = $row['date_finC'];
            $this->id = $row['id'];
            return true;
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->compagne . " 
                SET titreC=:titreC, descriptionC=:descriptionC, 
                    date_debutC=:date_debutC, date_finC=:date_finC, id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->titreC = htmlspecialchars(strip_tags($this->titreC));
        $this->descriptionC = htmlspecialchars(strip_tags($this->descriptionC));
        $this->date_debutC = htmlspecialchars(strip_tags($this->date_debutC));
        $this->date_finC = htmlspecialchars(strip_tags($this->date_finC));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":titreC", $this->titreC);
        $stmt->bindParam(":descriptionC", $this->descriptionC);
        $stmt->bindParam(":date_debutC", $this->date_debutC);
        $stmt->bindParam(":date_finC", $this->date_finC);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->compagne . " 
                SET titreC=:titreC, descriptionC=:descriptionC, 
                    date_debutC=:date_debutC, date_finC=:date_finC, id=:id
                WHERE idC=:idC";
        
        $stmt = $this->conn->prepare($query);
        
        $this->titreC = htmlspecialchars(strip_tags($this->titreC));
        $this->descriptionC = htmlspecialchars(strip_tags($this->descriptionC));
        $this->date_debutC = htmlspecialchars(strip_tags($this->date_debutC));
        $this->date_finC = htmlspecialchars(strip_tags($this->date_finC));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->idC = htmlspecialchars(strip_tags($this->idC));
        
        $stmt->bindParam(':titreC', $this->titreC);
        $stmt->bindParam(':descriptionC', $this->descriptionC);
        $stmt->bindParam(':date_debutC', $this->date_debutC);
        $stmt->bindParam(':date_finC', $this->date_finC);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':idC', $this->idC);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->compagne . " WHERE idC = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->idC);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>