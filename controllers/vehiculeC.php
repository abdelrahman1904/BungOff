<?php
require_once __DIR__ . '/../models/config.php';
require_once __DIR__ . '/../models/vehicule.php';

class VehiculeC {
    // Méthode pour ajouter un véhicule
    public function ajouterVehicule($vehicule) {
        $sql = "INSERT INTO vehicule (type, model, matricule, capacite, dispo) 
                VALUES (:type, :model, :matricule, :capacite, :dispo)"; 

        $db = Config::getConnexion(); // Utilisation de la classe Config pour la connexion à la base
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'type' => $vehicule->gettype(),
                'model' => $vehicule->getModel(),
                'matricule' => $vehicule->getmatricule(),
                'capacite' => $vehicule->getCapacite(),
                'dispo' => $vehicule->getDispo(),
            ]);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage(); // Affichage des erreurs en cas de problème
        }
    } 

    public function afficherVehicules() {
        $sql = "SELECT * FROM vehicule";
        $db = Config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll();
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }

    public function modifierVehicule($vehicule, $id) {
        $sql = "UPDATE vehicule SET 
                    type = :type, 
                    model = :model, 
                    matricule = :matricule, 
                    capacite = :capacite, 
                    dispo = :dispo 
                WHERE id_vehicule = :id";

        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'type' => $vehicule->getType(),
                'model' => $vehicule->getModel(),
                'matricule' => $vehicule->getMatricule(),
                'capacite' => $vehicule->getCapacite(),
                'dispo' => $vehicule->getDispo(),
                'id' => $id
            ]);
        } catch (Exception $e) {
            die('Erreur lors de la modification : ' . $e->getMessage());
        }
    }


   // Méthode pour supprimer un véhicule
    public function supprimerVehicule($id) {
        $sql = "DELETE FROM vehicule WHERE id_vehicule = :id"; // Assurez-vous que 'id_vehicule' est bien le nom de la clé primaire
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    public function rechercherVehiculesParMatricule($search) {
        $pdo = config::getConnexion();
        $stmt = $pdo->prepare("SELECT * FROM vehicule WHERE matricule LIKE :search");
        $stmt->bindValue(':search', "%$search%");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function trierVehicules($sortBy) {
        $allowed = ['id_vehicule', 'matricule', 'capacite', 'dispo'];
        if (!in_array($sortBy, $allowed)) $sortBy = 'id_vehicule';
        $pdo = config::getConnexion();
        $stmt = $pdo->query("SELECT * FROM vehicule ORDER BY $sortBy");
        return $stmt->fetchAll();
    }
    
    public function filtrerVehiculesParDispo($dispo)
{
    // Filtrage par disponibilité
    $query = "SELECT * FROM vehicules WHERE dispo = :dispo";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':dispo', $dispo);
    $stmt->execute();
    return $stmt->fetchAll();
}
    
}
?>
