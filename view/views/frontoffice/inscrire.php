<?php
include_once __DIR__ . '/../../../config.php'; // Chemin correct vers config.php
session_start(); // Démarrer la session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user'])) {
        echo "Veuillez vous connecter pour vous inscrire à une activité.";
        exit;
    } 

    // Récupérer les données de l'utilisateur depuis la session
    $userID = $_SESSION['user']['id'];
 // Assurez-vous d'avoir l'ID utilisateur dans la session

    $titre = $_POST['titre'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];

    try {
        $pdo = config::getConnexion();
        $pdo->beginTransaction(); // Commencer la transaction

        // 1. Récupérer IDA (id de l'activité)
        $stmt = $pdo->prepare("SELECT IDA FROM activite WHERE titre = ?");
        $stmt->execute([$titre]);
        $activite = $stmt->fetch();

        if (!$activite) {
            echo "Activité introuvable.";
            exit;
        }
        $IDA = $activite['IDA'];

        // 2. Récupérer IDP (id de la planification)
        $stmt = $pdo->prepare("SELECT IDP, capacite FROM planification WHERE nom_activite = ? AND date = ? AND heure_debut = ?");
        $stmt->execute([$titre, $date, $heure]);
        $planification = $stmt->fetch();

        if (!$planification) {
            echo "Planification introuvable.";
            exit;
        }
        $IDP = $planification['IDP'];
        $capacite = $planification['capacite'];

        if ($capacite > 0) {
            // 3. Insérer dans inscription avec l'ID utilisateur
            $stmt = $pdo->prepare("INSERT INTO inscription (IDP, IDA, user_id) VALUES (?, ?, ?)");
            $stmt->execute([$IDP, $IDA, $userID]);

            // 4. Réduire la capacité de 1
            $stmt = $pdo->prepare("UPDATE planification SET capacite = capacite - 1 WHERE IDP = ?");
            $stmt->execute([$IDP]);

            // 5. Mettre à jour is_seen à 0
            $stmt = $pdo->prepare("UPDATE inscription SET is_seen = 0 WHERE IDP = ?");
            $stmt->execute([$IDP]);

            $pdo->commit(); // Commit de la transaction

            echo "Inscription réussie !";
        } else {
            echo "Il n'y a plus de places disponibles pour cette activité.";
        }

    } catch (Exception $e) {
        $pdo->rollBack(); // Annuler la transaction en cas d'erreur
        echo "Erreur : " . $e->getMessage();
    }
}
?>
