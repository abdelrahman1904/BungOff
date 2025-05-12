<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../../../controller/userlistC.php";

if (isset($_SESSION['user']['id'])) {
    $id = $_SESSION['user']['id'];
    $userController = new userlistC();

    // Deactivate 2FA in the database
    $userController->deactivate2f($id);

    // Update session data
    $_SESSION['user']['is2f'] = false;
    unset($_SESSION['user']['2fa_secret']); // Remove the secret key

    header('Location: editProfile.php?2fa=disabled');
    exit();
} else {
    header('Location: login.php');
    exit();
}
?>
