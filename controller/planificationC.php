<?php

require_once dirname(__DIR__) . '/model/planification.php';

class PlanificationC {
    public function ajouterPlanification($planification) {
        $sql = "INSERT INTO planification (lieu, date, heure_debut, heure_fin, capacite, nom_activite)
                VALUES (:lieu, :date, :heure_debut, :heure_fin, :capacite, :nom_activite)";
        
        $db = config::getConnexion(); // Assurez-vous que config::getConnexion() est correct
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'lieu' => $planification->getLieu(),
                'date' => $planification->getDate(),
                'heure_debut' => $planification->getHeureDebut(),
                'heure_fin' => $planification->getHeureFin(),
                'capacite' => $planification->getCapacite(),
                'nom_activite' => $planification->getNomActivite()
            ]);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    public function afficherPlanifications() {
        $sql = "SELECT * FROM planification"; // Exemple de requête pour récupérer toutes les planifications
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne toutes les lignes sous forme de tableau associatif
    }
    public function supprimerPlanification($id) {
        $sql = "DELETE FROM planification WHERE IDP = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    public function recupererPlanification($id) {
        $sql = "SELECT * FROM planification WHERE IDP = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC); // retourne une ligne
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    public function modifierPlanification($planification, $id) {
        var_dump($planification);
        $sql = "UPDATE planification 
                SET lieu = :lieu, date = :date, heure_debut = :heure_debut, 
                    heure_fin = :heure_fin, capacite = :capacite, nom_activite = :nom_activite 
                WHERE IDP = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'lieu' => $planification->getLieu(),
                'date' => $planification->getDate(),
                'heure_debut' => $planification->getHeureDebut(),
                'heure_fin' => $planification->getHeureFin(),
                'capacite' => $planification->getCapacite(),
                'nom_activite' => $planification->getNomActivite(),
                'id' => $id
            ]);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    public function inscrireParticipant($idActivite) {
        try {
            // Récupérer la capacité actuelle
            $query = "SELECT capacite FROM planification WHERE IDP = :idActivite";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':idActivite', $idActivite);
            $stmt->execute();
            $capacite = $stmt->fetchColumn();
    
            if ($capacite > 0) {
                // Décrémenter la capacité
                $query = "UPDATE planification SET capacite = capacite - 1 WHERE IDP = :idActivite";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':idActivite', $idActivite);
                $stmt->execute();
            }
        } catch (Exception $e) {
            die("Erreur lors de l'inscription : " . $e->getMessage());
        }
    }
    
    
    public function updateFileInscription($idPlanification, $pdfPath) {
        $db = config::getConnexion(); // Connexion à la base de données
        $query = "UPDATE planification SET file_inscription = :file_inscription WHERE IDP = :idPlanification";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':file_inscription', $pdfPath); // Utilisation de file_inscription pour le chemin du fichier
        $stmt->bindParam(':idPlanification', $idPlanification);
        $stmt->execute();
    }
    
    public function chercherParActivite($motCle) {
        $sql = "SELECT p.IDP, a.titre AS nom_activite, p.lieu, p.date, p.heure_debut, p.heure_fin, p.capacite
                FROM planification p
                JOIN activite a ON p.nom_activite = a.titre
                WHERE a.titre LIKE :motCle";
    
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'motCle' => '%' . $motCle . '%'
            ]);
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }
    
}
?>