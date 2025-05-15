<?php
include dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/model/activite.php';

class ActiviteC {
    public function ajouterActivite($activite) {
        $sql = "INSERT INTO activite (titre, guide, description, duree, type, prix, photo, NBp) 
                VALUES (:titre, :guide, :description, :duree, :type, :prix, :photo, :nbp)";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'titre' => $activite->getTitre(),
                'guide' => $activite->getGuide(),
                'description' => $activite->getDescription(),
                'duree' => $activite->getDuree(),
                'type' => $activite->getType(),
                'prix' => $activite->getPrix(),
                'photo' => $activite->getPhoto(),
                'nbp' => $activite->getNbp(),
            ]);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    public function afficherActivites() {
        $sql = "SELECT * FROM activite";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll();
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    // Dans ton ActiviteC.php (ou modèle SQL)
    public function supprimerActivite($id) {
        $sql1 = "DELETE FROM inscription WHERE IDA = :id";
        $sql2 = "DELETE FROM activite WHERE IDA = :id";

        $db = config::getConnexion();
        try {
            $query1 = $db->prepare($sql1);
            $query1->execute(['id' => $id]);

            $query2 = $db->prepare($sql2);
            $query2->execute(['id' => $id]);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    
    public function modifierActivite($titre, $guide, $description, $duree, $type, $prix, $photo, $nbp, $id) {
        $sql = "UPDATE activite 
                SET titre = :titre, guide = :guide, description = :description, 
                    duree = :duree, type = :type, prix = :prix, photo = :photo, NBp = :nbp 
                WHERE IDA = :id";
    
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'titre' => $titre,
                'guide' => $guide,
                'description' => $description,
                'duree' => $duree,
                'type' => $type,
                'prix' => $prix,
                'photo' => $photo,
                'nbp' => $nbp,
                'id' => $id
            ]);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
    
    public function recupererActivite($id) {
        $sql = "SELECT * FROM activite WHERE IDA = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch();
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    } 
    public function titreExiste($titre) {
        // Vérifier si un titre existe déjà dans la base de données
        $sql = "SELECT COUNT(*) FROM activite WHERE titre = :titre";  // Assurez-vous que la table est 'activite', pas 'activites'
        $db = config::getConnexion();  // Récupère la connexion
        try {
            $stmt = $db->prepare($sql);  // Prépare la requête
            $stmt->bindParam(':titre', $titre);  // Lie le paramètre
            $stmt->execute();  // Exécute la requête
            return $stmt->fetchColumn() > 0;  // Retourne true si le titre existe
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    public function recupererActiviteParTitre($titre) {
        $sql = "SELECT * FROM activite WHERE titre = :titre";
        $db = config::getConnexion();
    
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':titre', $titre, PDO::PARAM_STR);
            $query->execute();
            return $query->fetch(); // Retourne l'activité trouvée, ou false si non trouvé
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    
       
}
?>
