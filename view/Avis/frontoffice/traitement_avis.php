<?php
require_once '../../../controller/aviscontroller.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?error=Methode non autorisee");
    exit();
}

try {
    // Récupération des données
    $data = [
        'Nom' => $_POST['Nom'],
        'LieuDuBungalow' => $_POST['LieuDuBungalow'],
        'ActivitéUtilisée' => $_POST['ActivitéUtilisée'],
        'Note' => (int)$_POST['Note'],
        'Commentaire' => $_POST['Commentaire']
    ];

    // Validation basique
    foreach ($data as $key => $value) {
        if (empty($value)) {
            throw new Exception("Le champ $key est requis");
        }
    }

    if ($data['Note'] < 1 || $data['Note'] > 5) {
        throw new Exception("La note doit être entre 1 et 5");
    }

    // Appel au contrôleur
    $controller = new AvisController();
    $id = $controller->addAvis($data);

    if ($id) {
        header("Location: index.php?success=Avis ajouté&id=$id");
    } else {
        throw new Exception("Erreur lors de l'ajout");
    }

} catch (Exception $e) {
    header("Location: index.php?error=".urlencode($e->getMessage()));
}