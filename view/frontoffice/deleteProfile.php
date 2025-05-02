<?php
require_once '../../controller/userlistC.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userController = new userlistC();
    $userController->deleteuser($_POST['id']);

    // Destroy the session and redirect to index
    session_start();
    session_destroy();
    header('Location: index.html');
    exit();
} else {
    header('Location: ../frontoffice/editProfile.php');
    exit();
}
?>