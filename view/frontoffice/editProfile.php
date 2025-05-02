<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../../controller/userlistC.php"; // Include the userlistC class
require_once "../../vendor/autoload.php"; // Include Composer autoload for Google Authenticator

// Move the `use` statements here, at the top of the file
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

$id = $_SESSION['user']['id'] ?? null; // Get the user ID from the session
$username = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : 'Guest';
$email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : 'No Email';
$name = isset($_SESSION['user']['fullname']) ? $_SESSION['user']['fullname'] : 'No fullname';
$age = isset($_SESSION['user']['age']) ? $_SESSION['user']['age'] : 'No age';
$image = isset($_SESSION['user']['image']) ? $_SESSION['user']['image'] : 'No image';

// Ensure the secret and QR code URL are generated correctly
$googleAuthenticator = new GoogleAuthenticator();
$secret = $googleAuthenticator->generateSecret(); // Generate a secret key for the user
$qrCodeUrl = GoogleQrUrl::generate($email, $secret, 'YourAppName'); // Replace 'YourAppName' with your app's name

$is2FAEnabled = $_SESSION['user']['is2f'] ?? false; // Check if 2FA is enabled for the user
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
                <img src="user_images/<?php echo htmlspecialchars($image); ?>" alt="Profile" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px;">
                <p><strong>Nom & Prenom :</strong> <?php echo htmlspecialchars($name); ?></p>
                <p><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($username); ?></p>
                <p><strong>Age :</strong> <?php echo htmlspecialchars($age); ?></p>
                <p><strong>Email :</strong> <?php echo htmlspecialchars($email); ?></p>
            </div>

            <!-- Two-Factor Authentication Section -->
            <div class="">
                <?php if ($is2FAEnabled): ?>
                    <!-- Show button to deactivate 2FA -->
                    <form method="POST" action="disable2FA.php">
                        <button type="submit" class="btn btn-danger mt-1">Disable Two-Factor Authentication</button>
                    </form>
                <?php else: ?>
                    <!-- Show QR code and input field to activate 2FA -->
                    <button id="enable2FA" class="btn btn-secondary mt-3">Enable Two-Factor Authentication</button>
                    <div id="qrCodeContainer" style="display: none;" class="mt-3">
                        <p>Scan this QR code with the Google Authenticator app:</p>
                        <img src="<?php echo htmlspecialchars($qrCodeUrl); ?>" alt="QR Code" class="img-fluid">
                        <form id="verify2FAForm" method="POST" action="verify2FA.php?id=<?php echo htmlspecialchars($id); ?>" class="mt-3">
                            <input type="hidden" name="secret" value="<?php echo htmlspecialchars($secret); ?>">
                            <div class="mb-3">
                                <label for="otpCode" class="form-label">Enter the code from your app:</label>
                                <input type="text" id="otpCode" name="otpCode" class="form-control" placeholder="Enter OTP code" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Verify and Activate</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Edit Profile Section -->
        <div class="col-md-6">
            <h3>Modifier Mes Informations</h3>
            <form action="updateProfile.php" method="POST" enctype="multipart/form-data" class="card p-3">
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
            <div class="d-flex justify-content-between">
            <button id="activateFaceRecognition" class="btn btn-secondary mt-3">Activate Face Recognition</button>
            <form action="deleteProfile.php" method="POST" class="mt-3">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SESSION['user']['id']); ?>">
            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre profil ?');">Supprimer le Profil</button>
        </form>
        </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('enable2FA')?.addEventListener('click', function () {
        document.getElementById('qrCodeContainer').style.display = 'block';
    });

    document.getElementById('activateFaceRecognition').addEventListener('click', async function () {
        const profileImage = "<?php echo htmlspecialchars($image); ?>";
        const email = "<?php echo htmlspecialchars($email); ?>";

        if (!profileImage || profileImage === 'No image') {
            alert('No profile image found. Please upload an image first.');
            return;
        }

        try {
            // Fetch the profile image from the server
            const response = await fetch(`user_images/${profileImage}`);
            const blob = await response.blob();

            // Prepare the form data
            const formData = new FormData();
            const renamedFile = new File([blob], `${email}.jpg`, { type: blob.type }); // Rename the file to the user's email
            formData.append('image', renamedFile);
            formData.append('email', email);

            // Send the image to the backend
            const registerResponse = await fetch('http://127.0.0.1:5000/register', {
                method: 'POST',
                body: formData
            });

            const result = await registerResponse.json();
            if (registerResponse.ok) {
                alert(result.message); // Display success message
            } else {
                alert(result.error || 'Failed to activate face recognition.');
            }
        } catch (error) {
            console.error('Error during face recognition activation:', error);
            alert('An error occurred while activating face recognition.');
        }
    });
</script>
</body>
</html>
