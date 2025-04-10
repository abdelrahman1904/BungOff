<?php
include_once "../controller/userlistC.php";

$UserC = new userlistC();
$var = $UserC->allusers();

session_start();
$username = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : 'Guest';
$email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : 'No Email';
$image = isset($_SESSION['user']['image']) ? $_SESSION['user']['image'] : 'No image';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BungOFF - Réservation de Bungalows</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>

<header>
    <div class="logo">
        <img src="image_video/maison.jpg" alt="Logo BungOFF" class="logo-img">
        Bung<span class="off">OFF</span>
    </div>
    <nav>
        <ul>
            <li><a href="#">Accueil</a></li>
            <li><a href="#"><i class="fas fa-home"></i> Bungalows</a></li>
            <li><a href="#"><i class="fas fa-bicycle"></i> Activités</a></li>
            <li><a href="#"><i class="fas fa-car"></i> Transports</a></li>
            <li><a href="#"><i class="fas fa-credit-card"></i> Paiement</a></li>
            <li><a href="#"><i class="fas fa-comments"></i> Avis</a></li>
        </ul>
    </nav>
    <div class="extra-info">
        <div class="weather">
            <i class="fas fa-cloud weather-icon"></i>
            <div class="weather-info">
                <div class="ville">Tunis</div>
                <div class="temperature">22°C</div>
            </div>
        </div>
        <i class="fas fa-search search-icon"></i>
        
        <div class="dropdown">
            <img src="user_images/<?php echo htmlspecialchars($image); ?>" alt="Profile" class="img-fluid rounded-circle" style="width: 50px; height: 50px; cursor: pointer;" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li class="dropdown-header text-center">
                    <strong><?php echo htmlspecialchars($username); ?></strong><br>
                    <small><?php echo htmlspecialchars($email); ?></small>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="editProfile.php">Edit Profile</a></li>
                <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
            </ul>
        </div>
    </div>
</header>

<video class="full-width-video" autoplay muted loop>
    <source src="image_video/tunisie.mp4" type="video/mp4">
    Votre navigateur ne supporte pas la lecture de vidéos.
</video>

<p id="intro-text" class="intro-text">Découvrez nos bungalows immersifs et réservez votre séjour inoubliable !</p>

<section class="destinations">
    <div class="destination">
        <img src="image_video/tabarka.jpg" alt="Bungalow à Tabarka">
        <div class="destination-info">
            <h3>Tabarka</h3>
            <p>Tabarka est célèbre pour ses plages pittoresques et ses festivals de musique en plein air.</p>
        </div>
    </div>

    <div class="destination">
        <img src="image_video/rafraf.jpg" alt="Bungalow à Rafraf">
        <div class="destination-info">
            <h3>Rafraf</h3>
            <p>Rafraf offre une vue imprenable sur la Méditerranée et des plages de sable fin.</p>
        </div>
    </div>

    <div class="destination">
        <img src="image_video/tozeur.jpg" alt="Bungalow à Tozeur">
        <div class="destination-info">
            <h3>Tozeur</h3>
            <p>Découvrez Tozeur, son oasis et ses paysages désertiques spectaculaires.</p>
        </div>
    </div>
</section>

<section class="blue-section">
    <div class="info">
        <div class="services-info">
            <h3>Nos Services</h3>
            <p>Location de bungalows</p>
            <p>Activités de groupe</p>
            <p>Transports privés</p>
        </div>

        <div class="contact-info">
            <h3>Contactez-nous</h3>
            <p>Email : contact@bungoff.com</p>
            <p>Téléphone : +216 94245514</p>
            <p>Adresse : Ariana, Tunisie</p>
        </div>

        <div class="social-info">
            <h3>Suivez-nous</h3>
            <p>Facebook</p>
            <p>Instagram</p>
            <p>Twitter</p>
        </div>

        <div class="reserve-now">
            <p>Réservez dès maintenant !</p>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
