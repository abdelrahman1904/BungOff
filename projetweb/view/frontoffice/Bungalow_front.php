
<?php
require_once '../../model/config.php';


try {
    // Connexion à la base de données
    $pdo = config::getConnexion();

    // Requête pour récupérer les bungalows
    $sql = "SELECT * FROM bungalow ORDER BY IDB DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Récupération des données
    $bungalows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Gestion des erreurs PDO
    die('Erreur lors de la récupération des bungalows : ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BungOFF - Bungalows Premium</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        /* Base styles with enhancements */
        :root {
            --primary-blue: #126cb6;
            --accent-beige: #b49786;
            --light-bg: #f5f5f5;
            --dark-text: #333;
            --white: #fff;
            --light-text: #ddd;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
            line-height: 1.6;
            
        }
        
        /* Enhanced Header */
        header {
            background-color: var(--primary-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 5%;
            height: 80px;
            position: fixed;
            width: 90%;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        
        header.scrolled {
            height: 70px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.15);
        }
        
        .logo {
            display: flex;
            align-items: center;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .logo-img {
            width: 40px;
            height: 40px;
            margin-right: 12px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent-beige);
        }
        
        .off {
            color: var(--accent-beige);
            font-weight: 800;
        }
        
        nav ul {
            list-style: none;
            display: flex;
            padding: 0;
            margin: 0;
        }
        
        nav ul li {
            margin: 0 12px;
            position: relative;
        }
        
        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 4px;
            transition: var(--transition);
        }
        
        nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        nav ul li a i {
            margin-right: 8px;
            color: var(--accent-beige);
            font-size: 0.9em;
        }
        
        nav ul li.active a {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .extra-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .weather {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 6px 12px;
            border-radius: 20px;
        }
        
        .weather-icon {
            font-size: 20px;
            margin-right: 8px;
            color: var(--light-text);
        }
        
        .weather-info {
            line-height: 1.2;
        }
        
        .ville {
            font-size: 12px;
        }
        
        .temperature {
            font-size: 14px;
            font-weight: 600;
        }
        
        .search-icon, .login-icon {
            font-size: 18px;
            cursor: pointer;
            transition: var(--transition);
            padding: 8px;
            border-radius: 50%;
        }
        
        .search-icon:hover, .login-icon:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        /* Hero Video Section */
        .video-container {
            position: relative;
            height: 100vh;
            overflow: hidden;
            margin-top: 80px;
        }
        
        .full-width-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.5));
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding: 0 20px;
        }
        
        .intro-text {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
            max-width: 800px;
        }
        
        .cta-button {
            background-color: var(--accent-beige);
            color: white;
            padding: 15px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            border: none;
            cursor: pointer;
        }
        
        .cta-button:hover {
            background-color: #9c7f6e;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
        /* Bungalow List Section */
        .bungalow-list {
            padding: 80px 5%;
            text-align: center;
            background-color: var(--white);
        }
        
        .bungalow-list h1 {
            color: var(--primary-blue);
            font-size: 2.8rem;
            margin-bottom: 60px;
            position: relative;
            display: inline-block;
        }
        
        .bungalow-list h1::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: var(--accent-beige);
            border-radius: 2px;
        }
        
        .bungalows-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .bungalow-item {
            background-color: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s forwards;
        }
        
        .bungalow-item:nth-child(1) { animation-delay: 0.1s; }
        .bungalow-item:nth-child(2) { animation-delay: 0.2s; }
        .bungalow-item:nth-child(3) { animation-delay: 0.3s; }
        .bungalow-item:nth-child(4) { animation-delay: 0.4s; }
        .bungalow-item:nth-child(5) { animation-delay: 0.5s; }
        .bungalow-item:nth-child(6) { animation-delay: 0.6s; }
        
        .bungalow-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .bungalow-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .bungalow-item:hover .bungalow-image {
            transform: scale(1.05);
        }
        
        .bungalow-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--accent-beige);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .bungalow-info {
            padding: 20px;
            text-align: left;
        }
        
        .bungalow-info h3 {
            color: var(--primary-blue);
            font-size: 1.4rem;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .bungalow-location {
            display: flex;
            align-items: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .bungalow-location i {
            margin-right: 5px;
            color: var(--accent-beige);
        }
        
        .bungalow-info p {
            color: var(--dark-text);
            font-size: 15px;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .bungalow-features {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .bungalow-feature {
            display: flex;
            align-items: center;
        }
        
        .bungalow-feature i {
            margin-right: 5px;
            color: var(--accent-beige);
        }
        
        .bungalow-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 15px;
        }
        
        .bungalow-price span {
            font-size: 14px;
            font-weight: normal;
            color: #666;
        }
        
        .bungalow-button {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background-color: var(--primary-blue);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }
        
        .bungalow-button:hover {
            background-color: #0d5a9e;
        }
        
        /* Blue Info Section */
        .blue-section {
            background-color: var(--primary-blue);
            color: white;
            padding: 60px 5%;
        }
        
        .info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .services-info, .contact-info, .social-info {
            margin: 10px 0;
        }
        
        .services-info h3, .contact-info h3, .social-info h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--accent-beige);
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .services-info h3::after, 
        .contact-info h3::after, 
        .social-info h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: var(--accent-beige);
        }
        
        .services-info p, .contact-info p, .social-info p {
            color: var(--light-text);
            font-size: 15px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        
        .services-info p i, 
        .contact-info p i, 
        .social-info p i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .reserve-now {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
        }
        
        .reserve-now p {
            font-weight: 700;
            color: var(--accent-beige);
            font-size: 1.5rem;
            margin-bottom: 20px;
            text-align: right;
        }
        
        /* Footer */
        footer {
            background-color: #222;
            color: var(--light-text);
            padding: 30px 5%;
            text-align: center;
            font-size: 14px;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .footer-links a {
            color: var(--light-text);
            margin: 0 15px;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .footer-links a:hover {
            color: var(--accent-beige);
        }
        
        .social-icons {
            margin: 20px 0;
        }
        
        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            margin: 0 8px;
            color: white;
            line-height: 40px;
            transition: var(--transition);
        }
        
        .social-icons a:hover {
            background-color: var(--accent-beige);
            transform: translateY(-5px);
        }
        
        /* Reservation Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .reservation-modal {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
            transform: translateY(-50px);
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active .reservation-modal {
            transform: translateY(0);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .modal-header h3 {
            color: var(--primary-blue);
            margin: 0;
            font-size: 1.5rem;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            transition: var(--transition);
        }
        
        .close-modal:hover {
            color: var(--primary-blue);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-text);
        }
        
        .form-group input, 
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: var(--transition);
        }
        
        .form-group input:focus, 
        .form-group select:focus {
            border-color: var(--primary-blue);
            outline: none;
        }
        
        .submit-reservation {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: var(--transition);
        }
        
        .submit-reservation:hover {
            background-color: #0d5a9e;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            nav ul {
                display: none;
            }
            
            .intro-text {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            header {
                padding: 15px 20px;
            }
            
            .video-container {
                height: 70vh;
            }
            
            .intro-text {
                font-size: 1.8rem;
            }
            
            .bungalow-list h1 {
                font-size: 2.2rem;
            }
            
            .reserve-now {
                align-items: center;
                text-align: center;
                margin-top: 30px;
            }
            
            .reserve-now p {
                text-align: center;
            }
        }
        
        @media (max-width: 480px) {
            .logo {
                font-size: 24px;
            }
            
            .logo-img {
                width: 30px;
                height: 30px;
            }
            
            .extra-info {
                gap: 10px;
            }
            
            .weather {
                display: none;
            }
            
            .intro-text {
                font-size: 1.5rem;
            }
            
            .reservation-modal {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
<!-- Modal de réservation -->
<div id="reservationModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Réserver ce bungalow</h2>
        <form action="traitement_reservation.php" method="POST">
            <input type="hidden" id="bungalow_nom" name="bungalow_nom" value="">
            <label for="date_arrivee">Date d'arrivée :</label>
            <input type="date" name="date_arrivee" required>

            <label for="date_depart">Date de départ :</label>
            <input type="date" name="date_depart" required>

            <label for="nb_personnes">Nombre de personnes :</label>
            <input type="number" name="nb_personnes" min="1" max="10" required>

            <button type="submit">Envoyer la réservation</button>
        </form>
    </div>
</div>

<header>
    <div class="logo">
        <img src="image_video1/maison.jpg" alt="Logo BungOFF" class="logo-img">
        Bung<span class="off">OFF</span>
    </div>
    <nav>
        <ul>
            <li><a href="index.html">Accueil</a></li>
            <li class="active"><a href="bungalows.html"><i class="fas fa-home"></i> Bungalows</a></li>
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
        <i class="fas fa-user login-icon"></i>
    </div>
</header>

<div class="video-container">
    <video class="full-width-video" autoplay muted loop>
        <source src="image_video1/tunisie.mp4" type="video/mp4">
        Votre navigateur ne supporte pas la lecture de vidéos.
    </video>
    <div class="video-overlay">
        <h1 class="intro-text animate__animated animate__fadeIn">Découvrez nos bungalows premium</h1>
        <a href="#bungalows" class="cta-button animate__animated animate__fadeInUp animate__delay-1s">
            Explorer nos offres
        </a>
    </div>
</div>

</div>
<section class="bungalow-list" id="bungalows">
    <h1 class="animate__animated animate__fadeIn">Nos Bungalows</h1>
    
    <div class="bungalows-container">
    <?php foreach ($bungalows as $b): ?>
        <div class="bungalow-item">
            <span class="bungalow-badge">Nouveau</span>
            <img src="image_video1/<?php echo htmlspecialchars($b['image']); ?>" alt="Bungalow à <?php echo htmlspecialchars($b['localisation']); ?>" class="bungalow-image">
            <div class="bungalow-info">
                <h3><?php echo htmlspecialchars($b['nom']); ?></h3>
                <div class="bungalow-location">
                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($b['localisation']); ?>
                </div>
                <p><?php echo htmlspecialchars($b['description']); ?></p>
                <div class="bungalow-features">
                    <div class="bungalow-feature">
                        <i class="fas fa-bed"></i> <?php echo htmlspecialchars($b['type']); ?>
                    </div>
                    <div class="bungalow-feature">
                        <i class="fas fa-users"></i> <?php echo htmlspecialchars($b['capacite']); ?> Personnes
                    </div>
                    <div class="bungalow-feature">
                        <i class="fas fa-ruler-combined"></i> 75m² <!-- Optionnel si tu veux ajouter une colonne 'surface' -->
                    </div>
                </div>
                <div class="bungalow-price">
                    <?php echo htmlspecialchars($b['prix_nuit']); ?> dt <span>/nuit</span>
                </div>
                <button class="bungalow-button" data-bungalow="<?php echo htmlspecialchars($b['nom']); ?>">Réserver maintenant</button>

            </div>
        </div>
    <?php endforeach; ?>
</div>
<form action="index.php" method="POST">
    <!-- Le nom du bungalow sera rempli via JavaScript lorsqu'une carte de bungalow est sélectionnée -->
    <input type="hidden" id="bungalow_nom" name="bungalow_nom" value="">

    <label for="date_arrivee">Date d'arrivée :</label>
    <input type="date" name="date_arrivee" required>

    <label for="date_depart">Date de départ :</label>
    <input type="date" name="date_depart" required>

    <label for="nb_personnes">Nombre de personnes :</label>
    <input type="number" name="nb_personnes" min="1" max="10" required>

    <button type="submit">Envoyer la réservation</button>
</form>
</div>
<div class="container my-5">
    <h2 class="mb-4">Nos Bungalows</h2>
    <div class="row">
        <?php foreach ($bungalows as $bungalow): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="image_video1/<?= $bungalow['image'] ?>" class="card-img-top" alt="Image du bungalow">
                    <div class="card-body">
                        <h5 class="card-title"><?= $bungalow['nom'] ?></h5>
                        <p class="card-text"><?= $bungalow['description'] ?></p>
                        <p><strong>Prix :</strong> <?= $bungalow['prix_nuit'] ?> € / nuit</p>
                        <p><strong>Capacité :</strong> <?= $bungalow['capacite'] ?> personnes</p>
                        <p><strong>Localisation :</strong> <?= $bungalow['localisation'] ?></p>
                        <p><strong>Type :</strong> <?= $bungalow['type'] ?></p>
                        
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<section class="blue-section">
    <div class="info">
        <div class="services-info">
            <h3>Nos Services</h3>
            <p><i class="fas fa-home"></i> Location de bungalows haut de gamme</p>
            <p><i class="fas fa-utensils"></i> Petit-déjeuner inclus</p>
            <p><i class="fas fa-concierge-bell"></i> Service de conciergerie 24/7</p>
            <p><i class="fas fa-swimming-pool"></i> Accès aux piscines et spas</p>
        </div>

        <div class="contact-info">
            <h3>Contactez-nous</h3>
            <p><i class="fas fa-envelope"></i> Email : contact@bungoff.com</p>
            <p><i class="fas fa-phone"></i> Téléphone : +216 94245514</p>
            <p><i class="fas fa-map-marker-alt"></i> Adresse : Ariana, Tunisie</p>
            <p><i class="fas fa-clock"></i> Ouvert 7j/7 de 8h à 20h</p>
        </div>

        <div class="social-info">
            <h3>Suivez-nous</h3>
            <p><i class="fab fa-facebook-f"></i> Facebook</p>
            <p><i class="fab fa-instagram"></i> Instagram</p>
            <p><i class="fab fa-twitter"></i> Twitter</p>
            <p><i class="fab fa-tripadvisor"></i> TripAdvisor</p>
        </div>

        <div class="reserve-now">
            <p>Prêt pour l'évasion ?</p>
            <button class="cta-button">
                Réservez dès maintenant
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>
</section>

<footer>
    <div class="footer-links">
        <a href="#">Mentions légales</a>
        <a href="#">CGV</a>
        <a href="#">Politique de confidentialité</a>
        <a href="#">Plan du site</a>
    </div>
    <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-pinterest"></i></a>
    </div>
    <p>&copy; 2023 BungOFF. Tous droits réservés.</p>
</footer>

<script>
    // Header scroll effect
    window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});



    
    


</script>



</div>

</body>
</html>