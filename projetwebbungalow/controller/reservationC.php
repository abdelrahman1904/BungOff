<?php
require_once __DIR__ . '/../model/reservation.php'; // Correctement fait référence à model/reservation.php
require_once __DIR__ . '/../model/config.php'; // Correctement fait référence à model/config.php


class reservationC {

    // Ajouter une réservation
    
    public function ajouterReservation($reservation) {
        // Vérification de l'IDB
        echo 'IDB: ' . $reservation->getIDB();  // Affiche l'ID du bungalow dans la console ou les logs
    
        // Get the price per night for the bungalow
        $prix_nuit = $this->getPrixBungalowById($reservation->getIDB());
        
        // Calculer le nombre de nuits
        $date_arrive = new DateTime($reservation->getDateArrive());
        $date_depart = new DateTime($reservation->getDateDepart());
        $interval = $date_arrive->diff($date_depart);
        $nights = $interval->days;
    
        // Calculer le prix total
        $prix_total = $prix_nuit * $nights;
    
        // Vérification du token utilisateur
        if (!isset($_SESSION['reservation_user_token'])) {
            die('Token utilisateur manquant.');
        }
        $token_user = $_SESSION['reservation_user_token'];
    
        // Insertion de la réservation dans la base de données
        $sql = "INSERT INTO reservation (date_arrive, date_depart, nbp, prix_total, IDB, token_user)
                VALUES (:date_arrive, :date_depart, :nbp, :prix_total, :IDB, :token_user)";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'date_arrive' => $reservation->getDateArrive(),
                'date_depart' => $reservation->getDateDepart(),
                'nbp' => $reservation->getNbp(),
                'prix_total' => $prix_total,
                'IDB' => $reservation->getIDB(),
                'token_user' => $token_user
            ]);
        } catch (PDOException $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
   
    

    // Afficher une réservation par ID
    public function afficherReservationById($id) {
        $sql = "SELECT * FROM reservation WHERE IDR = :id"; // Sélectionner la réservation par son ID
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT); // Lier l'ID en paramètre
            $query->execute();
            $reservation = $query->fetch(PDO::FETCH_ASSOC); // Récupère une ligne (une réservation)
            return $reservation;
        } catch (PDOException $e) {
            die('Erreur lors de la récupération de la réservation: ' . $e->getMessage());
        }
    }

    // Afficher toutes les réservations
    public function afficherReservations() {
        $sql = "SELECT * FROM reservation";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll();
        } catch (PDOException $e) {
            die('Erreur lors de l\'affichage des réservations: ' . $e->getMessage());
        }
    }

    // Supprimer une réservation
    public function supprimerReservation($id) {
        $sql = "DELETE FROM reservation WHERE IDR = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
 public function getAllReservations() {
        $sql = "SELECT * FROM reservation"; // Récupère toutes les réservations de la base de données
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(); // Retourne toutes les réservations sous forme de tableau
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage()); // Gérer les erreurs de la base de données
        }
    }
    // Modifier une réservation
    public function modifierReservation($id, $date_arrive, $date_depart, $nbp, $prix_total, $IDB) {
        $sql = "UPDATE reservation SET date_arrive = :date_arrive, date_depart = :date_depart, 
                nbp = :nbp, prix_total = :prix_total, IDB = :IDB WHERE IDR = :id"; // Utilisation de IDR comme identifiant
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'date_arrive' => $date_arrive,
                'date_depart' => $date_depart,
                'nbp' => $nbp,
                'prix_total' => $prix_total,
                'IDB' => $IDB
            ]);
        } catch (PDOException $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }

    // Méthode pour récupérer le prix d'un bungalow par son ID
    public function getPrixBungalowById($id_bungalow) {
        $sql = "SELECT prix_nuit FROM bungalow WHERE IDB = :id_bungalow";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':id_bungalow', $id_bungalow, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result['prix_nuit'];
            } else {
                throw new Exception("Bungalow non trouvé.");
            }
        } catch (PDOException $e) {
            die('Erreur lors de la récupération du prix du bungalow: ' . $e->getMessage());
        }
    }
   // Méthode pour récupérer une réservation par son ID
public function getReservationById($idr)
{
    $sql = "SELECT r.*, b.prix_nuit 
    FROM reservation r
    JOIN bungalow b ON r.IDB = b.IDB
    WHERE r.IDR = :idr";

$db = config::getConnexion();
try {
$query = $db->prepare($sql);
$query->bindParam(':idr', $idr);
$query->execute();
return $query->fetch();
} catch (PDOException $e) {
die('Erreur: ' . $e->getMessage());
}
}

}
?>
