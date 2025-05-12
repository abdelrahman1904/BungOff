<?php
require_once __DIR__.'/../../../config.php';
require_once __DIR__.'/../../../model/reponse.php';
require_once __DIR__.'/../../../controller/reponsecontroller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_reponse'])) {
    $reponseController = new ReponseController();
    
    try {
        $reponseId = $reponseController->create([
            'poste_admin' => $_POST['poste_admin'],
            'reponse_admin' => $_POST['reponse_admin'],
            'date_reponse' => $_POST['date_reponse'],
            'IDUtilisateur' => $_POST['IDUtilisateur']
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Réponse ajoutée avec succès',
            'id' => $reponseId
        ]);
    } catch(Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée'
    ]);
}
?>