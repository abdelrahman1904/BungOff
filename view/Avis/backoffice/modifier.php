<?php
require_once __DIR__.'/../../../model/reponse.php';
require_once __DIR__.'/../../../controller/reponsecontroller.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $id_reponse = $_POST['id'] ?? null;
    $poste_admin = $_POST['poste_admin'] ?? '';
    $reponse_admin = $_POST['reponse_admin'] ?? '';
    $date_reponse = $_POST['date_reponse'] ?? '';

    if (!$id_reponse) {
        header("Location: index.php?error=ID+reponse+manquant");
        exit();
    }

    // Validation des données
    if (empty($poste_admin) || empty($reponse_admin) || empty($date_reponse)) {
        header("Location: index.php?error=Tous+les+champs+sont+obligatoires");
        exit();
    }

    // Préparer les données pour la mise à jour
    $reponseData = [
        'poste_admin' => trim($poste_admin),
        'reponse_admin' => trim($reponse_admin),
        'date_reponse' => $date_reponse
    ];

    // Effectuer la mise à jour
    $controller = new ReponseController();
    $result = $controller->update($id_reponse, $reponseData);

    if ($result) {
        header("Location: index.php?success=Reponse+modifiée+avec+succès");
    } else {
        // Aucune ligne modifiée (soit l'ID n'existe pas, soit les données sont identiques)
        header("Location: index.php?error=Aucune+modification+effectuée");
    }
    exit();
} else {
    // Si on accède à cette page sans soumettre le formulaire
    header("Location: index.php");
    exit();
}
?>