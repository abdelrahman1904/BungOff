<?php
require_once "../controller/userlistC.php";
require_once "../model/userlist.php"; // Assuming you have a User class defined here

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']['id'])) {
    header("Location: ../view/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['id'];
    $fullname = htmlspecialchars($_POST['fullname']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $age = htmlspecialchars($_POST['age']);
    $image = $_FILES['image'];

    // Retain the old password if the password field is empty
    $pass = empty($_POST['pass']) ? $_SESSION['user']['pass'] : $_POST['pass']; // Store plain text password

    // Handle image upload
    $imagePath = $_SESSION['user']['image']; // Default to current image
    if ($image['size'] > 0 && $image['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../view/user_images/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $randomName = uniqid('img_', true) . '.' . $extension; // Generate a unique random name
        $imagePath = $targetDir . $randomName;

        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            $imagePath = $randomName; // Save only the random name in the database
        }
    }

    // Create a User object
    $user = new userlist($fullname, $username, $email, $pass, $age, $imagePath);

    // Call the updateuser function
    $userC = new userlistC();
    $userC->updateuser($user, $userId);

    // Update session variables
    $_SESSION['user']['fullname'] = $fullname;
    $_SESSION['user']['username'] = $username;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['age'] = $age;
    $_SESSION['user']['image'] = $imagePath;
    $_SESSION['user']['pass'] = $pass;

    // Redirect to the profile page
    header("Location: ../view/homePage.php");
    exit();
}
?>
