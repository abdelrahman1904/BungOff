<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../controller/userlistC.php"; // Include the userlistC class
require_once "../vendor/autoload.php"; // Include Composer autoload for Google Authenticator

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

$username = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : 'Guest';
$email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : 'No Email';
$name = isset($_SESSION['user']['fullname']) ? $_SESSION['user']['fullname'] : 'No fullname';
$age = isset($_SESSION['user']['age']) ? $_SESSION['user']['age'] : 'No age';
$image = isset($_SESSION['user']['image']) ? $_SESSION['user']['image'] : 'default.png';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Profil</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
<main class="container mt-5">
    <div class="row">
        <!-- General User Information Section -->
        <div class="col-md-6">
            <a href="homePage.php" class="btn btn-primary mb-2">Retour</a>
            <h3>Mes Informations</h3>
            <div class="card p-3 d-flex align-items-center">
                <img src="../view/user_images/<?php echo htmlspecialchars($image); ?>" alt="Profile" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px;">
                <p><strong>Nom & Prenom :</strong> <?php echo htmlspecialchars($name); ?></p>
                <p><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($username); ?></p>
                <p><strong>Age :</strong> <?php echo htmlspecialchars($age); ?></p>
                <p><strong>Email :</strong> <?php echo htmlspecialchars($email); ?></p>
            </div>
        </div>

        <!-- Edit Profile Section -->
        <div class="col-md-6">
            <h3>Modifier Mes Informations</h3>
            <form action="../controller/updateProfile.php" method="POST" enctype="multipart/form-data" class="card p-3">
                <div class="mb-3">
                    <label for="fullname" class="form-label">Nom Complet</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($_SESSION['user']['fullname'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Mot de Passe (Laissez vide pour conserver l'ancien)</label>
                    <input type="password" class="form-control" id="pass" name="pass">
                </div>
                <div class="mb-3">
                    <label for="age" class="form-label">Âge</label>
                    <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($_SESSION['user']['age'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Photo de Profil</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
