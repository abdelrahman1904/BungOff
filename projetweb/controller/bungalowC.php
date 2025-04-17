<?php
require_once '../model/bungalow.php'; // Assure-toi que le chemin est correct
require_once '../model/config.php'; // Fichier de connexion PDO à la base de données

class BungalowC {

    public function ajouterBungalow($bungalow) {
        $sql = "INSERT INTO bungalow ( nom, capacite, prix_nuit, localisation, type, description, image)
                VALUES ( :nom, :capacite, :prix_nuit, :localisation, :type, :description, :image)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);

            $query->execute([
                'nom' => $bungalow->getNom(),
                'capacite' => $bungalow->getCapacite(),
                'prix_nuit' => $bungalow->getPrixNuit(),
                'localisation' => $bungalow->getLocalisation(),
                'type' => $bungalow->getType(),
                'description' => $bungalow->getDescription(),
                'image' => $bungalow->getImage()
            ]);
        } catch (PDOException $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    public function afficherBungalowById($id) {
        $sql = "SELECT * FROM bungalow WHERE IDB = :id"; // Sélectionner le bungalow par son IDB
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT); // Lier l'ID en paramètre
            $query->execute();
            $bungalow = $query->fetch(PDO::FETCH_ASSOC); // Récupère une ligne (un bungalow)
            return $bungalow;
        } catch (PDOException $e) {
            die('Erreur lors de la récupération du bungalow: ' . $e->getMessage());
        }
    }
    
    // ✅ Méthode pour afficher les bungalows
    public function afficherBungalows() {
        $sql = "SELECT * FROM bungalow";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll();
        } catch (PDOException $e) {
            die('Erreur lors de l\'affichage des bungalows: ' . $e->getMessage());
        }
    }
    public function supprimerbungalow($id) {
        $sql = "DELETE FROM bungalow WHERE IDB = :id"; // IDB est le nom de la colonne dans ta base de données
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id);
            $query->execute();
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    
    public function modifierBungalow($id, $nom, $capacite, $prix_nuit, $localisation, $type, $description, $image) {
        $sql = "UPDATE bungalow SET nom = :nom, capacite = :capacite, prix_nuit = :prix_nuit, 
                localisation = :localisation, type = :type, description = :description, image = :image 
                WHERE IDB = :id"; // Utilisation de IDB comme identifiant
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'nom' => $nom,
                'capacite' => $capacite,
                'prix_nuit' => $prix_nuit,
                'localisation' => $localisation,
                'type' => $type,
                'description' => $description,
                'image' => $image
            ]);
        } catch (PDOException $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
   
}
?>
