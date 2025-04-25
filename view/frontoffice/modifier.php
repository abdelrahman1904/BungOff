<?php
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../controller/AvisController.php';

$controller = new AvisController();

if ($controller->updateAvis([
    'id' => $_POST['id'],
    'nom' => $_POST['nom'],
    'lieu' => $_POST['lieu'],
    'activite' => $_POST['activite'],
    'note' => $_POST['note'],
    'commentaire' => $_POST['commentaire']
])) {
    header("Location: index.php");
} else {
    header("Location: index.php");
}