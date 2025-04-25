<?php
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../model/avis.php';

class AvisController
{
    public function listAvis() {
        $sql = "SELECT * FROM avis";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch(Exception $e) {
            die('Error' .$e->getMessage());
        }
    }

    public function deleteAvis($IDUtilisateur) {
        $sql = "DELETE FROM avis WHERE IDUtilisateur = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $IDUtilisateur);
        try {
            $req->execute();
        } catch(Exception $e) {
            die('Error' .$e->getMessage());
        }
    }
    public function addAvis($data) {
        $db = Config::getConnexion();
        $sql = "INSERT INTO avis (Nom, LieuDuBungalow, ActivitéUtilisée, Note, Commentaire) 
                VALUES (:nom, :lieu, :activite, :note, :commentaire)";
        
        try {
            $req = $db->prepare($sql);
            
            // Utilisation de bindValue pour la sécurité
            $req->bindValue(':nom', $data['Nom'], PDO::PARAM_STR);
            $req->bindValue(':lieu', $data['LieuDuBungalow'], PDO::PARAM_STR);
            $req->bindValue(':activite', $data['ActivitéUtilisée'], PDO::PARAM_STR);
            $req->bindValue(':note', $data['Note'], PDO::PARAM_INT);
            $req->bindValue(':commentaire', $data['Commentaire'], PDO::PARAM_STR);
            
            $req->execute();
            
            // Retourne le dernier ID inséré
            return $db->lastInsertId();
            
        } catch(PDOException $e) {
            throw new Exception("Erreur base de données: ".$e->getMessage());
        }
    }
    public function updateAvis($data) {
        $db = Config::getConnexion();
        $req = $db->prepare("UPDATE avis SET 
            Nom = ?,
            LieuDuBungalow = ?,
            ActivitéUtilisée = ?,
            Note = ?,
            Commentaire = ?
            WHERE IDUtilisateur = ?");
        
        return $req->execute([
            $data['nom'],
            $data['lieu'],
            $data['activite'],
            $data['note'],
            $data['commentaire'],
            $data['id']
        ]);
    }
    
    public function getAvis($id) {
        $db = Config::getConnexion();
        $req = $db->prepare("SELECT * FROM avis WHERE IDUtilisateur = ?");
        $req->execute([$id]);
        return $req->fetch();
    }
}