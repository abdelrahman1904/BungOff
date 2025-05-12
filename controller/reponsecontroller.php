<?php
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../model/reponse.php';
require_once __DIR__.'/../model/avis.php';

class ReponseController
{
    // Récupère toutes les réponses avec les infos de l'avis associé
    public function getAllWithAvis() {
        $sql = "SELECT r.*, a.Nom, a.Commentaire 
                FROM reponse r
                JOIN avis a ON r.IDUtilisateur = a.IDUtilisateur";
        
        $db = Config::getConnexion();
        try {
            $query = $db->query($sql);
            return $query->fetchAll();
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    // Crée une nouvelle réponse (avec jointure implicite via IDUtilisateur)
    public function create($reponseData) {
        $sql = "INSERT INTO reponse 
                (poste_admin, reponse_admin, date_reponse, IDUtilisateur) 
                VALUES (:poste, :reponse, :date, :id_utilisateur)";
        
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                ':poste' => $reponseData['poste_admin'],
                ':reponse' => $reponseData['reponse_admin'],
                ':date' => $reponseData['date_reponse'],
                ':id_utilisateur' => $reponseData['IDUtilisateur']
            ]);
            return $db->lastInsertId();
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    // Récupère une réponse avec les infos de l'avis associé
    public function getOneWithAvis($id_reponse) {
        $sql = "SELECT r.*, a.Nom, a.Commentaire 
                FROM reponse r
                JOIN avis a ON r.IDUtilisateur = a.IDUtilisateur
                WHERE r.id_reponse = :id";
        
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([':id' => $id_reponse]);
            return $query->fetch();
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    public function update($id_reponse, $reponseData) {
        $sql = "UPDATE reponse SET
                poste_admin = :poste,
                reponse_admin = :reponse,
                date_reponse = :date
                WHERE id_reponse = :id";
        
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                ':poste' => $reponseData['poste_admin'],
                ':reponse' => $reponseData['reponse_admin'],
                ':date' => $reponseData['date_reponse'],
                ':id' => $id_reponse
            ]);
            
            // Retourne le nombre de lignes affectées
            return $query->rowCount() > 0;
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    // Supprime une réponse
    public function delete($id_reponse) {
        $sql = "DELETE FROM reponse WHERE id_reponse = :id";
        
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            return $query->execute([':id' => $id_reponse]);
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }

    // Récupère toutes les réponses pour un utilisateur spécifique
    public function getByUser($IDUtilisateur) {
        $sql = "SELECT r.*, a.Nom, a.Commentaire 
                FROM reponse r
                JOIN avis a ON r.IDUtilisateur = a.IDUtilisateur
                WHERE r.IDUtilisateur = :id_utilisateur";
        
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([':id_utilisateur' => $IDUtilisateur]);
            return $query->fetchAll();
        } catch(Exception $e) {
            die('Erreur: '.$e->getMessage());
        }
    }
}
?>