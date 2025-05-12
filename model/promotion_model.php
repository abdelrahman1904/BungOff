<?php
require_once '../../../config.php';

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
    public function readAll($search = '', $sort = 'idP', $order = 'ASC') {
        $sql = "SELECT p.*, c.nom AS campaign_name 
                FROM promotionsP p 
                LEFT JOIN compagne c ON p.idC = c.id";
        
        // Add search conditions
        $params = [];
        if ($search) {
            $sql .= " WHERE p.titreP LIKE :search OR p.codePromo LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        // Add sorting
        $allowedSorts = ['idP', 'titreP', 'pourcentage', 'date_debutP', 'date_finP'];
        $sort = in_array($sort, $allowedSorts) ? $sort : 'idP';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        $sql .= " ORDER BY p.$sort $order";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
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