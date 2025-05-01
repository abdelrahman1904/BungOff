<?php
require_once __DIR__.'/../config.php';

class Promotion {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    // Create a new promotion
    public function create($titreP, $descriptionP, $pourcentage, $codePromo, $date_debutP, $date_finP, $idC) {
        $sql = "INSERT INTO promotionsP (titreP, descriptionP, pourcentage, codePromo, date_debutP, date_finP, idC) 
                VALUES (:titreP, :descriptionP, :pourcentage, :codePromo, :date_debutP, :date_finP, :idC)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':titreP' => $titreP,
            ':descriptionP' => $descriptionP,
            ':pourcentage' => $pourcentage,
            ':codePromo' => $codePromo,
            ':date_debutP' => $date_debutP,
            ':date_finP' => $date_finP,
            ':idC' => $idC
        ]);
    }

    // Read all promotions with associated campaign name
    public function readAll() {
        $sql = "SELECT p.*, c.nom AS campaign_name 
                FROM promotionsP p 
                LEFT JOIN compagne c ON p.idC = c.id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Read a single promotion by ID
    public function read($idP) {
        $sql = "SELECT p.*, c.nom AS campaign_name 
                FROM promotionsP p 
                LEFT JOIN compagne c ON p.idC = c.id 
                WHERE p.idP = :idP";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idP' => $idP]);
        return $stmt->fetch();
    }

    // Update a promotion
    public function update($idP, $titreP, $descriptionP, $pourcentage, $codePromo, $date_debutP, $date_finP, $idC) {
        $sql = "UPDATE promotionsP 
                SET titreP = :titreP, descriptionP = :descriptionP, pourcentage = :pourcentage, 
                    codePromo = :codePromo, date_debutP = :date_debutP, date_finP = :date_finP, idC = :idC 
                WHERE idP = :idP";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':idP' => $idP,
            ':titreP' => $titreP,
            ':descriptionP' => $descriptionP,
            ':pourcentage' => $pourcentage,
            ':codePromo' => $codePromo,
            ':date_debutP' => $date_debutP,
            ':date_finP' => $date_finP,
            ':idC' => $idC
        ]);
    }

    // Delete a promotion
    public function delete($idP) {
        $sql = "DELETE FROM promotionsP WHERE idP = :idP";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':idP' => $idP]);
    }
}
?>