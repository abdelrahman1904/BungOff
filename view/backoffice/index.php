<?php
require_once "../../controller/userlistC.php";

$error = ''; // Initialize an error message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $userlistC = new userlistC();
    $user = $userlistC->findUserByUsernameAndPassword($username, $password);

    if ($user) {
        if ($user['role'] === 'admin') {
            session_start();
            $_SESSION['user'] = $user;

            header('Location: homePage.php'); // Redirect to the admin dashboard
            exit();
        } else {
            $error = 'Access denied. Only admins can log in.';
        }
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <style>
        .full-width-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1; /* Ensure the video stays in the background */
        }
        .container {
            position: relative;
            z-index: 1; /* Ensure the content stays above the video */
        }
    </style>
</head>
<body>
<video class="full-width-video" autoplay muted loop>
    <source src="../frontoffice/image_video/Lets Discover Tunisia.mp4" type="video/mp4">
    Votre navigateur ne supporte pas la lecture de vidéos.
</video>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Admin Login</h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <?php if (!empty($error)): ?>
                            <div class="text-danger text-center mb-3"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
