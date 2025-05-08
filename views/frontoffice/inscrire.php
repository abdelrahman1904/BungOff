<?php
require_once '../../models/config.php'; // Chemin correct vers config.php
// ythabet ken jetou min method post wala le w mbaed ychouf les donne ili jetou min boutton s'inscrire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        // keen fama blasa rahou bech yamel inscription
        if ($capacite > 0) {
            // 3. Insérer dans inscription
            $stmt = $pdo->prepare("INSERT INTO inscription (IDP, IDA) VALUES (?, ?)");
            $stmt->execute([$IDP, $IDA]);

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
