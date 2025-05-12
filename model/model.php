<?php
require_once '../../../config.php';

class Compagne {
    private $pdo;

    public function __construct() {
        $this->pdo = config::getConnexion();
    }

    // Create a new campaign
    public function create($nom, $description, $date_debut, $date_fin) {
        $sql = "INSERT INTO compagne (nom, description, date_debut, date_fin) VALUES (:nom, :description, :date_debut, :date_fin)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':date_debut' => $date_debut,
            ':date_fin' => $date_fin
        ]);
    }

    // Read all campaigns
    public function readAll($search = '', $sort = 'id', $order = 'ASC') {
        $sql = "SELECT * FROM compagne";
        
        // Add search conditions
        $params = [];
        if ($search) {
            $sql .= " WHERE nom LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        // Add sorting
        $allowedSorts = ['id', 'nom', 'date_debut', 'date_fin'];
        $sort = in_array($sort, $allowedSorts) ? $sort : 'id';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        $sql .= " ORDER BY $sort $order";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Read a single campaign by ID
    public function read($id) {
        $sql = "SELECT * FROM compagne WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Update a campaign
    public function update($id, $nom, $description, $date_debut, $date_fin) {
        $sql = "UPDATE compagne SET nom = :nom, description = :description, date_debut = :date_debut, date_fin = :date_fin WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':description' => $description,
            ':date_debut' => $date_debut,
            ':date_fin' => $date_fin
        ]);
    }

    // Delete a campaign
    public function delete($id) {
        $sql = "DELETE FROM compagne WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>