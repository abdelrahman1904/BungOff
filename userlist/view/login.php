<?php
// login_process.php

require_once "../controller/userlistC.php";
require_once "../vendor/autoload.php"; // Include Composer autoload for Google Authenticator

$error = ''; // Initialize an error message variable

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Verify reCAPTCHA
    $secretKey = '6LeFlQYrAAAAALHkaBrWOYlDjnX1BvYl_WqY8P0G'; // Replace with your reCAPTCHA secret key
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
    $responseData = json_decode($verifyResponse);

    if ($responseData->success) {
        $userlistC = new userlistC();
        $user = $userlistC->findUserByUsernameAndPassword($username, $password);

        if ($user) {
            session_start();
            $_SESSION['user'] = $user;

            header('Location:homePage.php'); // Redirect to the dashboard upon successful login
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    } else {
        $error = 'reCAPTCHA verification failed. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <title>Login</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<video class="full-width-video" autoplay muted loop>
    <source src="image_video/Lets Discover Tunisia.mp4" type="video/mp4">
    Votre navigateur ne supporte pas la lecture de vidéos.
</video>
<section class="pdt-120 pdb-120 mt-5">
    <div class="container" style="margin-top: 210px; position: relative; z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center ">
                            <img src="image_video/projet web bungoff.png" alt="Logo" class="img-fluid rounded-circle">
                        </div>
                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="g-recaptcha" data-sitekey="6LeFlQYrAAAAAGKJr3kdkRdPjSz8Y0nSvtQ2E30I" data-callback="enableLoginButton"></div> <!-- Replace with your reCAPTCHA site key -->
                            <?php if (!empty($error)): ?>
                                <div class="text-danger text-center mt-2"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <div class="d-grid">
                                <input type="submit" id="loginButton" class="btn btn-primary btn-block mt-3" value="Login" disabled>
                            </div>
                        </form>
                        <div class="mt-4 text-center">
                            <p>No Account? <a href="registration.php" class="text-decoration-none">Sign Up Here</a></p>
                            <p><a href="forgot_password.php" class="text-decoration-none">Forgot Password?</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .full-width-video {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
    }
</style>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });

    function enableLoginButton() {
        document.getElementById('loginButton').disabled = false;
    }
</script>
</body>
</html>