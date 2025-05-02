<?php
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
require_once "../../controller/userlistC.php";
require_once "../../vendor/autoload.php";

$error = ''; // Initialize an error message variable

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['otpCode'])) {
    // Handle OTP verification
    $otpCode = $_POST['otpCode'];
    $secret = $_SESSION['user']['is2f_secret'] ?? null;

    if ($secret) {
        $googleAuthenticator = new GoogleAuthenticator();
        if ($googleAuthenticator->checkCode($secret, $otpCode)) {
            // OTP is valid, log the user in
            header('Location: homePage.php'); // Redirect to the dashboard
            exit();
        } else {
            $error = 'Invalid OTP code. Please try again.';
        }
    } else {
        $error = 'Two-factor authentication is not properly configured.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
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

            if ($_SESSION['user']['is2f']) {
                // Retrieve the secret from the database
                $secret = $user['is2f_secret'] ?? null;
                if ($secret) {
                    $_SESSION['user']['is2f_secret'] = $secret; // Store the secret in the session
                    header('Location: login.php?2fa=required');
                    exit();
                } else {
                    $error = 'Two-factor authentication is not properly configured.';
                }
            } else {
                header('Location: homePage.php'); // Log the user in if 2FA is not enabled
                exit();
            }
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
<style>
    #webcamContainer {
        display: none; /* Initially hidden */
        margin-top: 20px;
        text-align: center;
    }
    #webcamFeed {
        width: 80%;
        max-width: 600px;
        border: 5px solid white;
        border-radius: 10px;
    }
</style>
<div id="webcamContainer">
    <video id="webcamFeed" autoplay></video>
    <button id="captureButton" class="btn btn-primary mt-3">Capture</button>
</div>
<script>
    async function loginWithFaceID() {
        const webcamContainer = document.getElementById('webcamContainer');
        const webcamFeed = document.getElementById('webcamFeed');
        const captureButton = document.getElementById('captureButton');

        try {
            // Show the webcam container
            webcamContainer.style.display = 'block';

            // Access the webcam
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            webcamFeed.srcObject = stream;

            // Wait for the user to capture the image
            captureButton.onclick = async function () {
                // Capture a frame from the webcam
                const canvas = document.createElement('canvas');
                canvas.width = webcamFeed.videoWidth;
                canvas.height = webcamFeed.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(webcamFeed, 0, 0, canvas.width, canvas.height);

                // Stop the webcam
                stream.getTracks().forEach(track => track.stop());
                webcamContainer.style.display = 'none';

                // Convert the image to a Blob
                const imageBlob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg'));

                // Send the image to the backend
                const formData = new FormData();
                formData.append('image', imageBlob);

                const response = await fetch('http://127.0.0.1:5000/login', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (response.ok) {
                    alert(result.message); // Display success message
                    // Redirect to the homepage or dashboard
                    window.location.href = 'homePage.php';
                } else {
                    alert(result.error || result.message); // Display error message
                }
            };
        } catch (error) {
            console.error('Error during Face ID login:', error);
            alert('An error occurred while trying to log in with Face ID.');
            webcamContainer.style.display = 'none';
        }
    }
</script>
<section class="pdt-120 pdb-120 mt-5">
    <div class="container" style="margin-top: 210px; position: relative; z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center ">
                            <img src="image_video/projet web bungoff.png" alt="Logo" class="img-fluid rounded-circle">
                        </div>
                        <?php if (isset($_GET['2fa']) && $_GET['2fa'] === 'required'): ?>
                            <!-- OTP Verification Form -->
                            <form method="post">
                                <div class="mb-3">
                                    <label for="otpCode" class="form-label">Enter OTP Code</label>
                                    <input type="text" id="otpCode" name="otpCode" class="form-control" placeholder="Enter the code from your app" required>
                                </div>
                                <?php if (!empty($error)): ?>
                                    <div class="text-danger text-center mt-2"><?php echo $error; ?></div>
                                <?php endif; ?>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-block mt-3">Verify OTP</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <!-- Regular Login Form -->
                            <form method="post">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password">
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
                        <?php endif; ?>
                        <div class="d-grid mt-3">
                            <button type="button" class="btn btn-secondary btn-block" onclick="loginWithFaceID()">Login with Face ID</button>
                        </div>
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
<?php
// ...existing code...
?>
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