<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . '/../../../config.php';

// Then include other files
require_once __DIR__ . '/../../../controller/controller.php';
require_once __DIR__ . '/../../../model/promotion_model.php';
require_once __DIR__ . '/../../../model/model.php';
require_once __DIR__ . '/../vendor/autoload.php';


// Fetch campaigns
$compagne = new Compagne();
$campaigns = $compagne->readAll(); // Récupérer toutes les campagnes

// Fetch promotions
$promotion = new Promotion();
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'idP';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$promotions = $promotion->readAll($search, $sort, $order);

// Group promotions by campaign
$promotionsByCampaign = [];
foreach ($promotions as $promotion) {
    $promotionsByCampaign[$promotion['idC']][] = $promotion;
}

// Créer le dossier qrcodes s'il n'existe pas
$qrcodesDir = 'qrcodes/';
if (!file_exists($qrcodesDir)) {
    mkdir($qrcodesDir, 0777, true);
}

// Nettoyer les anciens QR Codes
$oldQrFiles = glob($qrcodesDir . 'campaign_*.png');
foreach ($oldQrFiles as $oldFile) {
    unlink($oldFile);
}

// Générer un QR Code pour chaque campagne avec QRCode Monkey API
$baseUrl = "http://192.168.60.1/web10-Copie/frontoffice";
foreach ($campaigns as &$campaign) {
    $qrCodeFile = $qrcodesDir . 'campaign_' . $campaign['id'] . '.png';
    $campaign['qrCodeFile'] = $qrCodeFile;

    // Contenu du QR Code : lien vers la page d'affiche de la campagne
    $qrContent = urlencode($baseUrl . "/campaign_poster.php?id=" . $campaign['id']);

    // Générer le QR Code via QRCode Monkey API et sauvegarder l'image
    if (!file_exists($qrCodeFile)) {
        $qrcodeMonkeyUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . $qrContent;
        $imageContent = @file_get_contents($qrcodeMonkeyUrl);
        if ($imageContent !== false && file_put_contents($qrCodeFile, $imageContent)) {
            error_log("QR Code généré avec succès pour la campagne ID " . $campaign['id']);
        } else {
            error_log("Échec de la génération du QR Code pour la campagne ID " . $campaign['id']);
        }
    }
}
unset($campaign);
//http://192.168.60.1/web10-Copie/frontoffice/campaign_poster.php?id=101 // test qrCode
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotions - BungOFF</title>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>

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
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        nav ul li a:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
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

        .search-icon {
            font-size: 20px;
            margin-left: 20px;
            cursor: pointer;
            color: #f8f5f5;
            transition: color 0.3s;
        }

        .search-icon:hover {
            color: #4a90e2;
        }

        .login-icon {
            font-size: 20px;
            margin-left: 20px;
            cursor: pointer;
            color: #f8f5f5;
        }

        .promotions-section {
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

        .campaign-container {
            margin-bottom: 40px;
        }

        .campaign-header {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            border-bottom: 2px solid #4a90e2;
        }

        .campaign-header h3 {
            margin: 0;
            font-size: 1.8rem;
            color: #333;
        }

        .campaign-header .qr-code {
            margin-top: 10px;
            text-align: center;
        }

        .campaign-header .qr-code img {
            width: 100px;
            height: 100px;
            border: 2px solid #4a90e2;
            border-radius: 10px;
        }

        .promotions-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            padding: 20px;
            background-color: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .promotion-card {
            width: 350px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            background: white;
        }

        .promotion-card:hover {
            transform: translateY(-10px);
        }

        .promotion-info {
            padding: 25px;
        }

        .promotion-info h4 {
            margin-bottom: 15px;
            color: #333;
            font-size: 1.3rem;
        }

        .promotion-info p {
            color: #666;
            margin-bottom: 15px;
        }

        .promotion-info .pourcentage {
            font-weight: bold;
            color: #4a90e2;
        }

        .reserve-btn {
            padding: 8px 20px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .reserve-btn:hover {
            background-color: #3a7bc8;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .sort-container {
            margin-bottom: 20px;
            text-align: right;
        }

        .sort-container select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .newsletter-button {
            display: block;
            width: fit-content;
            margin: 30px auto;
            padding: 10px 20px;
            background-color: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .newsletter-button:hover {
            background-color: #3a7bc8;
        }

        @media (max-width: 768px) {
            .promotion-card {
                width: 100%;
            }

            .campaign-header .qr-code img {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
   <header>
    <div class="logo">
        <img src="image_video/maison.jpg" alt="Logo BungOFF" class="logo-img">
        Bung<span class="off">OFF</span>
    </div>
    <nav>
        <ul>
            <li><a href="../../User/frontoffice/homePage.php">Accueil</a></li>
            <li><a href="#"><i class="fas fa-home"></i> Bungalows</a></li>
            <li><a href="#"><i class="fas fa-bicycle"></i> Activités</a></li>
            <li><a href="#"><i class="fas fa-car"></i> Transports</a></li>
            <li><a href="promotions_front.php"><i class="fas fa-credit-card"></i> Promotions</a></li>
            <li><a href="../../Avis/frontoffice/index.php"><i class="fas fa-comments"></i> Avis</a></li>
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

    <!-- Promotions Section -->
    <section class="promotions-section">
        <h2 class="section-title animate__animated animate__fadeIn">Nos Promotions</h2>
        <p class="animate__animated animate__fadeIn" style="text-align: center; margin-bottom: 20px;">Découvrez nos offres exclusives pour vos prochaines vacances</p>
        
        <div class="sort-container">
            <form action="promotions_front.php" method="GET">
                <select name="sort" onchange="this.form.submit()">
                    <option value="idP" <?php echo $sort == 'idP' ? 'selected' : ''; ?>>Trier par ID</option>
                    <option value="pourcentage" <?php echo $sort == 'pourcentage' ? 'selected' : ''; ?>>Trier par Pourcentage</option>
                    <option value="date_debutP" <?php echo $sort == 'date_debutP' ? 'selected' : ''; ?>>Trier par Date de Début</option>
                </select>
                <input type="hidden" name="order" value="<?php echo $order; ?>">
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
            </form>
        </div>

        <?php if (empty($campaigns)): ?>
            <p>Aucune campagne disponible pour le moment.</p>
        <?php else: ?>
            <?php foreach ($campaigns as $campaign): ?>
                <div class="campaign-container">
                    <div class="campaign-header">
                        <h3><?php echo htmlspecialchars($campaign['nom']); ?></h3>
                        <p>Du <?php echo htmlspecialchars($campaign['date_debut']); ?> au <?php echo htmlspecialchars($campaign['date_fin']); ?></p>
                        <div class="qr-code">
                            <?php if (file_exists($campaign['qrCodeFile'])): ?>
                                <img src="<?php echo $campaign['qrCodeFile']; ?>" alt="QR Code pour <?php echo htmlspecialchars($campaign['nom']); ?>">
                            <?php else: ?>
                                <p style="color: red;">Erreur lors de la génération du QR Code pour <?php echo htmlspecialchars($campaign['nom']); ?>. Vérifiez votre connexion ou les logs d'erreurs.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="promotions-container">
                        <?php
                        $campaignPromotions = isset($promotionsByCampaign[$campaign['id']]) ? $promotionsByCampaign[$campaign['id']] : [];
                        if (empty($campaignPromotions)):
                        ?>
                            <p>Aucune promotion associée à cette campagne.</p>
                        <?php else: ?>
                            <?php foreach ($campaignPromotions as $promotion): ?>
                                <div class="promotion-card animate__animated animate__fadeInUp">
                                    <div class="promotion-info">
                                        <h4><?php echo htmlspecialchars($promotion['titreP']); ?></h4>
                                        <p><?php echo htmlspecialchars($promotion['descriptionP']); ?></p>
                                        <p class="pourcentage">Réduction : <?php echo htmlspecialchars($promotion['pourcentage']); ?>%</p>
                                        <?php if ($promotion['codePromo']): ?>
                                            <p>Code Promo : <?php echo htmlspecialchars($promotion['codePromo']); ?></p>
                                        <?php endif; ?>
                                        <p class="date">Du <?php echo htmlspecialchars($promotion['date_debutP']); ?> au <?php echo htmlspecialchars($promotion['date_finP']); ?></p>
                                        <button class="reserve-btn">Réserver Maintenant</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Ajout de la section newsletter -->
        <a href="newsletter.php" class="newsletter-button animate__animated animate__fadeIn">S'inscrire à la Newsletter</a>
    </section>

    <section class="blue-section">
    <div class="info">
        <div class="services-info animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
            <h3>Nos Services</h3>
            <p>Location de bungalows</p>
            <p>Activités de groupe</p>
            <p>Transports privés</p>
            <p>Guide touristique</p>
            <p>Service de restauration</p>
        </div>

        <div class="contact-info animate__animated animate__fadeIn" style="animation-delay: 0.4s;">
            <h3>Contactez-nous</h3>
            <p><i class="fas fa-envelope"></i> Email : contact@bungoff.com</p>
            <p><i class="fas fa-phone"></i> Téléphone : +216 94245514</p>
            <p><i class="fas fa-map-marker-alt"></i> Adresse : Ariana, Tunisie</p>
        </div>

        <div class="social-info animate__animated animate__fadeIn" style="animation-delay: 0.6s;">
            <h3>Suivez-nous</h3>
            <p><i class="fab fa-facebook"></i> Facebook</p>
            <p><i class="fab fa-instagram"></i> Instagram</p>
            <p><i class="fab fa-twitter"></i> Twitter</p>
            <p><i class="fab fa-tiktok"></i> TikTok</p>
        </div>

        <div class="reserve-now animate__animated animate__fadeIn" style="animation-delay: 0.8s;">
            <h3>Prêt à partir?</h3>
            <p>Réservez dès maintenant votre bungalow de rêve!</p>
            <button class="reserve-btn">Réserver Maintenant</button>
        </div>
    </div>
</section>
<style>
.blue-section {
    background-color: #126cb6;
    padding: 50px 5%;
    color: white;
    text-align: center;
}

.blue-section .info {
    display: flex;
    flex-direction: row; /* Alignement horizontal */
    justify-content: space-around; /* Espacement égal entre les éléments */
    flex-wrap: wrap; /* Permet de passer à la ligne si nécessaire sur petits écrans */
    gap: 20px; /* Espacement entre les colonnes */
}

.blue-section .services-info,
.blue-section .contact-info,
.blue-section .social-info,
.blue-section .reserve-now {
    flex: 1; /* Chaque section prend une part égale de l'espace */
    min-width: 200px; /* Largeur minimale pour éviter que les éléments ne deviennent trop étroits */
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.1); /* Légère opacité pour différencier */
    border-radius: 10px;
}

.blue-section h3 {
    margin-bottom: 15px;
}

.blue-section p {
    margin: 5px 0;
}

.blue-section .reserve-btn {
    padding: 8px 20px;
    background-color: #4a90e2;
    color: white;
    border: none;
    border-radius: 30px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
}

.blue-section .reserve-btn:hover {
    background-color: #3a7bc8;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Responsivité */
@media (max-width: 768px) {
    .blue-section .info {
        flex-direction: column; /* Retour à une disposition verticale sur petits écrans */
        align-items: center;
    }

    .blue-section .services-info,
    .blue-section .contact-info,
    .blue-section .social-info,
    .blue-section .reserve-now {
        width: 100%; /* Pleine largeur sur petits écrans */
    }
}
</style>