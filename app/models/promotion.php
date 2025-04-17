<?php
class Promotion {
    private $conn;
    private $promotion = "promotion";

    public $idP;
    public $titreP;
    public $descriptionP;
    public $pourcentage;
    public $codePromo;
    public $date_debutP;
    public $date_finP;
    public $idC;
    public $category_title;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT p.*, c.titreC as category_title 
                FROM " . $this->promotion . " p
                LEFT JOIN compagne c ON p.idC = c.idC
                ORDER BY p.idP";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT p.*, c.titreC as category_title 
                FROM " . $this->promotion . " p
                LEFT JOIN compagne c ON p.idC = c.idC
                WHERE p.idP = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->idP);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_OBJ); // Changed to FETCH_OBJ
        
        if($row) {
            // Assign properties to the current object
            $this->idP = $row->idP;
            $this->titreP = $row->titreP;
            $this->descriptionP = $row->descriptionP;
            $this->pourcentage = $row->pourcentage;
            $this->codePromo = $row->codePromo;
            $this->date_debutP = $row->date_debutP;
            $this->date_finP = $row->date_finP;
            $this->idC = $row->idC;
            $this->category_title = $row->category_title;
            
            return $row; // Return the object instead of true
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->promotion . " 
                SET titreP=:titreP, descriptionP=:descriptionP, 
                    pourcentage=:pourcentage, codePromo=:codePromo, 
                    date_debutP=:date_debutP, date_finP=:date_finP, idC=:idC";
        
        $stmt = $this->conn->prepare($query);
        
        $this->titreP = htmlspecialchars(strip_tags($this->titreP));
        $this->descriptionP = htmlspecialchars(strip_tags($this->descriptionP));
        $this->pourcentage = htmlspecialchars(strip_tags($this->pourcentage));
        $this->codePromo = htmlspecialchars(strip_tags($this->codePromo));
        $this->date_debutP = htmlspecialchars(strip_tags($this->date_debutP));
        $this->date_finP = htmlspecialchars(strip_tags($this->date_finP));
        $this->idC = htmlspecialchars(strip_tags($this->idC));
        
        $stmt->bindParam(":titreP", $this->titreP);
        $stmt->bindParam(":descriptionP", $this->descriptionP);
        $stmt->bindParam(":pourcentage", $this->pourcentage);
        $stmt->bindParam(":codePromo", $this->codePromo);
        $stmt->bindParam(":date_debutP", $this->date_debutP);
        $stmt->bindParam(":date_finP", $this->date_finP);
        $stmt->bindParam(":idC", $this->idC);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->promotion . " 
                SET titreP=:titreP, descriptionP=:descriptionP, 
                    pourcentage=:pourcentage, codePromo=:codePromo, 
                    date_debutP=:date_debutP, date_finP=:date_finP, idC=:idC
                WHERE idP=:idP";
        
        $stmt = $this->conn->prepare($query);
        
        $this->idP = htmlspecialchars(strip_tags($this->idP));
        $this->titreP = htmlspecialchars(strip_tags($this->titreP));
        $this->descriptionP = htmlspecialchars(strip_tags($this->descriptionP));
        $this->pourcentage = htmlspecialchars(strip_tags($this->pourcentage));
        $this->codePromo = htmlspecialchars(strip_tags($this->codePromo));
        $this->date_debutP = htmlspecialchars(strip_tags($this->date_debutP));
        $this->date_finP = htmlspecialchars(strip_tags($this->date_finP));
        $this->idC = htmlspecialchars(strip_tags($this->idC));
        
        $stmt->bindParam(':idP', $this->idP);
        $stmt->bindParam(':titreP', $this->titreP);
        $stmt->bindParam(':descriptionP', $this->descriptionP);
        $stmt->bindParam(':pourcentage', $this->pourcentage);
        $stmt->bindParam(':codePromo', $this->codePromo);
        $stmt->bindParam(':date_debutP', $this->date_debutP);
        $stmt->bindParam(':date_finP', $this->date_finP);
        $stmt->bindParam(':idC', $this->idC);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->promotion . " WHERE idP = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->idP);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getPromotionsByCompagne($idC) {
        $query = "SELECT p.*, c.titreC as category_title 
                FROM " . $this->promotion . " p
                LEFT JOIN compagne c ON p.idC = c.idC
                WHERE p.idC = ?
                ORDER BY p.idP";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $idC);
        $stmt->execute();
        return $stmt;
    }
}
?>