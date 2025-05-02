<?php
require_once "../../vendor/autoload.php"; // Include Composer autoload for Google Authenticator
include "../../controller/userlistC.php";
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the URL parameters
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    $secret = isset($_GET['secret']) ? $_GET['secret'] : null;

    if (!$id) {
        // Redirect if the ID is missing
        header('Location: editProfile.php?error=missing_id');
        exit();
    }

    $secret = $_POST['secret'];
    $otpCode = $_POST['otpCode'];
    $r = new userlistC();   
    $r->activate2f($id, $secret);
    $googleAuthenticator = new GoogleAuthenticator();

    if ($googleAuthenticator->checkCode($secret, $otpCode)) {
        // OTP is valid
        session_start();
        $_SESSION['user']['is2f'] = true; // Mark 2FA as enabled for the user
        $_SESSION['user']['2fa_secret'] = $secret; // Store the secret key for future verification
        header('Location: editProfile.php?2fa=success');
        exit();
    } else {
        // OTP is invalid
        header('Location: editProfile.php?2fa=failed');
        exit();
    }
}
?>
