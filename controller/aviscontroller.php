<?php
include dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/model/avis.php';

class AvisController
{
    private $badWords = ['chien', 'chat', 'singe', 'lion', 'tigre', 'éléphant', 'cheval', 'lapin', 'renard', 'ours'];

    private function containsBadWord($text) {
        foreach ($this->badWords as $word) {
            if (stripos($text, $word) !== false) {
                return true;
            }
        }
        return false;
    }

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
        // Debug: Log the input data
        error_log("Input data for addAvis: " . print_r($data, true));

        // Check for bad words in each field individually
        if ($this->containsBadWord($data['Nom'])) {
            error_log("Bad word detected in 'Nom': " . $data['Nom']);
            throw new Exception("Le champ 'Nom' contient des mots interdits.");
        }
        if ($this->containsBadWord($data['LieuDuBungalow'])) {
            error_log("Bad word detected in 'LieuDuBungalow': " . $data['LieuDuBungalow']);
            throw new Exception("Le champ 'Lieu du Bungalow' contient des mots interdits.");
        }
        if ($this->containsBadWord($data['ActivitéUtilisée'])) {
            error_log("Bad word detected in 'ActivitéUtilisée': " . $data['ActivitéUtilisée']);
            throw new Exception("Le champ 'Activité Utilisée' contient des mots interdits.");
        }
        if ($this->containsBadWord($data['Commentaire'])) {
            error_log("Bad word detected in 'Commentaire': " . $data['Commentaire']);
            throw new Exception("Le champ 'Commentaire' contient des mots interdits.");
        }

        $db = Config::getConnexion();
        $sql = "INSERT INTO avis (Nom, LieuDuBungalow, ActivitéUtilisée, Note, Commentaire) 
                VALUES (:nom, :lieu, :activite, :note, :commentaire)";
        
        try {
            $req = $db->prepare($sql);
            
            // Bind values for security
            $req->bindValue(':nom', $data['Nom'], PDO::PARAM_STR);
            $req->bindValue(':lieu', $data['LieuDuBungalow'], PDO::PARAM_STR);
            $req->bindValue(':activite', $data['ActivitéUtilisée'], PDO::PARAM_STR);
            $req->bindValue(':note', $data['Note'], PDO::PARAM_INT);
            $req->bindValue(':commentaire', $data['Commentaire'], PDO::PARAM_STR);

            // Debug: Log the SQL query and parameters
            error_log("Executing SQL: $sql");
            error_log("Parameters: " . print_r([
                ':nom' => $data['Nom'],
                ':lieu' => $data['LieuDuBungalow'],
                ':activite' => $data['ActivitéUtilisée'],
                ':note' => $data['Note'],
                ':commentaire' => $data['Commentaire']
            ], true));
            
            $req->execute();
            
            // Debug: Log success
            error_log("Avis ajouté avec succès !");
            
            // Return the last inserted ID
            return $db->lastInsertId();
            
        } catch(PDOException $e) {
            // Debug: Log the error
            error_log("Erreur base de données: " . $e->getMessage());
            throw new Exception("Erreur base de données: " . $e->getMessage());
        }
    }

    public function updateAvis($data) {
        // Debug: Log the input data
        error_log("Input data for updateAvis: " . print_r($data, true));

        // Check for bad words in each field individually
        if ($this->containsBadWord($data['Nom'])) {
            error_log("Bad word detected in 'Nom': " . $data['Nom']);
            throw new Exception("Le champ 'Nom' contient des mots interdits.");
        }
        if ($this->containsBadWord($data['LieuDuBungalow'])) {
            error_log("Bad word detected in 'LieuDuBungalow': " . $data['LieuDuBungalow']);
            throw new Exception("Le champ 'Lieu du Bungalow' contient des mots interdits.");
        }
        if ($this->containsBadWord($data['ActivitéUtilisée'])) {
            error_log("Bad word detected in 'ActivitéUtilisée': " . $data['ActivitéUtilisée']);
            throw new Exception("Le champ 'Activité Utilisée' contient des mots interdits.");
        }
        if ($this->containsBadWord($data['Commentaire'])) {
            error_log("Bad word detected in 'Commentaire': " . $data['Commentaire']);
            throw new Exception("Le champ 'Commentaire' contient des mots interdits.");
        }

        $db = Config::getConnexion();
        $sql = "UPDATE avis SET 
                Nom = :nom,
                LieuDuBungalow = :lieu,
                ActivitéUtilisée = :activite,
                Note = :note,
                Commentaire = :commentaire
                WHERE IDUtilisateur = :id";

        try {
            $req = $db->prepare($sql);

            // Bind values for security
            $req->bindValue(':nom', $data['Nom'], PDO::PARAM_STR);
            $req->bindValue(':lieu', $data['LieuDuBungalow'], PDO::PARAM_STR);
            $req->bindValue(':activite', $data['ActivitéUtilisée'], PDO::PARAM_STR);
            $req->bindValue(':note', $data['Note'], PDO::PARAM_INT);
            $req->bindValue(':commentaire', $data['Commentaire'], PDO::PARAM_STR);
            $req->bindValue(':id', $data['IDUtilisateur'], PDO::PARAM_INT);

            // Debug: Log the SQL query and parameters
            error_log("Executing SQL: $sql");
            error_log("Parameters: " . print_r([
                ':nom' => $data['Nom'],
                ':lieu' => $data['LieuDuBungalow'],
                ':activite' => $data['ActivitéUtilisée'],
                ':note' => $data['Note'],
                ':commentaire' => $data['Commentaire'],
                ':id' => $data['IDUtilisateur']
            ], true));

            $req->execute();

            // Debug: Log success
            error_log("Avis modifié avec succès !");
            return true;

        } catch (PDOException $e) {
            // Debug: Log the error
            error_log("Erreur base de données: " . $e->getMessage());
            throw new Exception("Erreur base de données: " . $e->getMessage());
        }
    }
    
    public function getAvis($id) {
        $db = Config::getConnexion();
        $req = $db->prepare("SELECT * FROM avis WHERE IDUtilisateur = ?");
        $req->execute([$id]);
        return $req->fetch();
    }

    public function listAvisrate() {
        $sql = "SELECT Note, COUNT(*) as count FROM avis GROUP BY Note";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            throw new Exception("Erreur lors de la récupération des statistiques: " . $e->getMessage());
        }
    }
}