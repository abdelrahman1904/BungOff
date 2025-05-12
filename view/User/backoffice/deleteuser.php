<?php
include_once '../../../controller/userlistC.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userController = new userlistC();
    $userController->deleteuser($_POST['id']);
    header('Location: userlist.php');
    exit();
} else {
    header('Location: userlist.php');
    exit();
}
?>