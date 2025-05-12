<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../model/newsletter_model.php';

$newsletterModel = new NewsletterModel();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if ($newsletterModel->isSubscribed($email)) {
            $message = "<p class='error'>Vous êtes déjà inscrit à la newsletter.</p>";
        } else {
            if ($newsletterModel->subscribe($email)) {
                $message = "<p class='success'>Merci de vous être inscrit à notre newsletter !</p>";
            } else {
                $message = "<p class='error'>Erreur lors de l'inscription. Veuillez réessayer.</p>";
            }
        }
    } else {
        $message = "<p class='error'>Adresse e-mail invalide.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription à la Newsletter - BungOFF</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5%;
            background-color: #126cb6;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .logo-img {
            height: 40px;
            margin-right: 10px;
        }

        .off {
            color: #b49786;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            padding: 0;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        nav ul li a i {
            margin-right: 5px;
            color: #b49786;
        }

        .extra-info {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .weather {
            display: flex;
            align-items: center;
        }

        .weather-icon {
            font-size: 22px;
            margin-right: 6px;
            color: #dde0ee;
        }

        .ville, .temperature {
            font-size: 14px;
        }

        .search-icon, .login-icon {
            font-size: 20px;
            margin-left: 20px;
            cursor: pointer;
            color: #f8f5f5;
        }

        .newsletter-section {
            padding: 50px 5%;
            background-color: #f9f9f9;
            text-align: center;
        }

        .section-title {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #333;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #4a90e2;
        }

        .newsletter-form {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .newsletter-form input[type="email"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 300px;
        }

        .newsletter-form button {
            padding: 10px 20px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .newsletter-form button:hover {
            background-color: #3a7bc8;
        }

        .success { color: green; }
        .error { color: red; }

        @media (max-width: 768px) {
            .newsletter-form input[type="email"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="image/maison.jpg" alt="Logo BungOFF" class="logo-img">
            Bung<span class="off">OFF</span>
        </div>
        <nav>
            <ul>
                <li><a href="index.html">Accueil</a></li>
                <li><a href="#"><i class="fas fa-home"></i> Bungalows</a></li>
                <li><a href="activite.html"><i class="fas fa-bicycle"></i> Activités</a></li>
                <li><a href="#"><i class="fas fa-car"></i> Transports</a></li>
                <li><a href="promotions_front.php"><i class="fas fa-credit-card"></i> Promotions</a></li>
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
            <i class="fas fa-user login-icon"></i>
        </div>
    </header>

    <section class="newsletter-section">
        <h2 class="section-title animate__animated animate__fadeIn">Inscrivez-vous à notre Newsletter</h2>
        <p class="animate__animated animate__fadeIn" style="text-align: center; margin-bottom: 20px;">
            Recevez nos dernières promotions et offres exclusives directement dans votre boîte de réception !
        </p>
        <form method="POST" class="newsletter-form">
            <input type="email" name="email" placeholder="Entrez votre adresse e-mail" required>
            <button type="submit">S'inscrire</button>
        </form>
        <?php echo $message; ?>
    </section>
</body>
</html>