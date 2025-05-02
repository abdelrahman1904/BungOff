<?php
include_once "../../controller/userlistC.php";

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        /* Styles précédents conservés... */
        /* Styles de base */
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
            ;
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
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
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
            transition: color 0.3s;
        }
        
        .search-icon:hover, .login-icon:hover {
            color: #4a90e2;
        }
        
        /* Hero Section avec image et cartes intégrées */
        .hero-container {
            position: relative;
            width: 100%;
            height: 90vh;
        }
        
        .hero-image {
            width: 100%;
            height: 100%;
            background-image: url('image/bungalow-hero.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.3);
        }
        
        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            width: 100%;
            padding: 0 20px;
            z-index: 2;
        }
        
        .hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            animation: slideInDown 1s ease-out;
        }
        
        .hero-content p {
            font-size: 1.5rem;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
            animation: fadeIn 2s ease-in;
        }
        
        .cta-button {
            padding: 12px 30px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            animation: pulse 2s infinite;
        }
        
        .cta-button:hover {
            background-color: #3a7bc8;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Cartes sur l'image hero */
        .cards-on-hero {
            position: absolute;
            bottom: -80px;
            left: 0;
            width: 100%;
            z-index: 3;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            padding: 0 5%;
            box-sizing: border-box;
        }
        
        .hero-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            width: 300px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
        }
        
        .hero-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        
        .hero-card i {
            font-size: 2.5rem;
            color: #4a90e2;
            margin-bottom: 20px;
        }
        
        .hero-card h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .hero-card p {
            color: #666;
        }
        
        /* Section des destinations */
        .destinations {
            padding: 150px 5% 80px;
            text-align: center;
            background-color: white;
        }
        
        .section-title {
            font-size: 2.5rem;
            margin-bottom: 50px;
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
        
        .destinations-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 50px;
        }
        
        .destination {
            width: 350px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            position: relative;
        }
        
        .destination:hover {
            transform: translateY(-10px);
        }
        
        .destination img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .destination:hover img {
            transform: scale(1.05);
        }
        
        .destination-info {
            padding: 25px;
            background: white;
        }
        
        .destination-info h3 {
            margin-bottom: 15px;
            color: #333;
            font-size: 1.5rem;
        }
        
        .destination-info p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .explore-btn {
            padding: 8px 20px;
            background-color: transparent;
            border: 2px solid #4a90e2;
            color: #4a90e2;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .explore-btn:hover {
            background-color: #4a90e2;
            color: white;
        }
        
        /* Section Villes */
        .cities-section {
            padding: 80px 5%;
            background-color: #f5f9ff;
            text-align: center;
        }
        
        .cities-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 50px;
        }
        
        .city-card {
            width: 300px;
            height: 200px;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .city-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .city-card:hover img {
            transform: scale(1.1);
        }
        
        .city-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            display: flex;
            align-items: flex-end;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .city-name {
            color: white;
            font-size: 1.8rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }
        
        /* Section bleue */
        .blue-section {
            background-color: #126cb6;
            color: white;
            padding: 60px 5%;
        }
        
        .info {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .services-info, .contact-info, .social-info, .reserve-now {
            flex: 1;
            min-width: 250px;
            margin: 20px;
        }
        
        .blue-section h3 {
            font-size: 1.5rem;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .blue-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background-color: white;
        }
        
        .blue-section p {
            margin-bottom: 15px;
            line-height: 1.6;
        }
        /* Nouveaux styles pour la galerie de bungalows */
        .bungalow-gallery {
            padding: 100px 5%;
            background-color: #f9f9f9;
        }
        
        .gallery-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            grid-template-rows: auto auto;
            gap: 15px;
            margin-bottom: 50px;
        }
        
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.05);
        }
        
        .gallery-item.large {
            grid-column: span 2;
            grid-row: span 2;
            height: 400px;
        }
        
        .gallery-item.medium {
            height: 190px;
        }
        
        .gallery-item.small {
            height: 190px;
        }
        
        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
            color: white;
            padding: 20px;
        }
        
        .gallery-overlay h3 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        /* Styles pour les sections alternées */
        .bungalow-feature {
            display: flex;
            align-items: center;
            margin-bottom: 60px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .feature-image, .feature-content {
            flex: 1;
            padding: 30px;
        }
        
        .feature-image img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 5px;
            transition: transform 0.5s;
        }
        
        .feature-image:hover img {
            transform: scale(1.03);
        }
        
        .feature-content h3 {
            font-size: 2rem;
            color: #4a90e2;
            margin-bottom: 20px;
        }
        
        .feature-content p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 25px;
        }
        
        .feature-btn {
            padding: 12px 30px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .feature-btn:hover {
            background-color: #3a7bc8;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Alternance des positions */
        .feature-reverse {
            flex-direction: row-reverse;
        }
        
        /* Animations */
        .animate-delay-1 {
            animation-delay: 0.2s;
        }
        
        .animate-delay-2 {
            animation-delay: 0.4s;
        }
        
        .animate-delay-3 {
            animation-delay: 0.6s;
        }
        
        .animate-delay-4 {
            animation-delay: 0.8s;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .gallery-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .gallery-item.large {
                grid-column: span 2;
                height: 300px;
            }
            
            .bungalow-feature {
                flex-direction: column;
            }
            
            .feature-reverse {
                flex-direction: column;
            }
            
            .feature-image, .feature-content {
                width: 100%;
            }
            
            .feature-image img {
                height: 250px;
            }
        }
        
        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: 1fr;
            }
            
            .gallery-item.large {
                height: 250px;
            }
            
            .gallery-item.medium, .gallery-item.small {
                height: 150px;
            }
        }
        /* Styles pour l'image principale du hero */
.hero-image {
    position: relative;
    width: 100%;
    height: 90vh;
    overflow: hidden;
}

.hero-main-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    animation: zoomIn 15s infinite alternate;
}

@keyframes zoomIn {
    0% {
        transform: scale(1);
    }
    100% {
        transform: scale(1.1);
    }
}

/* Styles pour les images dans les cartes */
.card-icon-image {
    width: 60px;
    height: 60px;
    margin-bottom: 15px;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.hero-card:hover .card-icon-image {
    transform: scale(1.1) rotate(5deg);
}

/* Adaptation pour mobile */
@media (max-width: 768px) {
    .hero-main-image {
        object-position: 60% center;
    }
    
    .card-icon-image {
        width: 50px;
        height: 50px;
    }
}
    </style>
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

<div class="hero-container">
    <div class="hero-image">
        <img src="image/heroo.jpg" alt="Bungalow de luxe en bord de mer" class="hero-main-image">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="animate__animated animate__fadeInDown">Votre Évasion Parfaite</h1>
            <p class="animate__animated animate__fadeIn">Découvrez des bungalows uniques dans les plus belles destinations de Tunisie</p>
            <button class="cta-button animate__animated animate__pulse">Réserver Maintenant</button>
        </div>
    </div>
    
    <!-- Cartes sur l'image hero -->
    <div class="cards-on-hero">
        <div class="hero-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <i class="fas fa-home"></i>
            <h3>Bungalows Exclusifs</h3>
            <p>Découvrez nos bungalows soigneusement sélectionnés pour un confort optimal et une immersion totale dans la nature.</p>
        </div>
        
        <div class="hero-card animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
            <i class="fas fa-map-marked-alt"></i>
            <h3>Meilleures Destinations</h3>
            <p>Tabarka, Tozeur, Rafraf et bien d'autres - des lieux uniques pour des expériences inoubliables.</p>
        </div>
        
        <div class="hero-card animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
            <i class="fas fa-concierge-bell"></i>
            <h3>Services Premium</h3>
            <p>Nous offrons des services personnalisés pour rendre votre séjour encore plus spécial.</p>
        </div>
    </div>
</div>

<!-- Nouvelle section Galerie de Bungalows -->
<section class="bungalow-gallery">
    <div class="gallery-container">
        <h2 class="section-title animate__animated animate__fadeIn">Nos Bungalows</h2>
        <p class="animate__animated animate__fadeIn" style="text-align: center; margin-bottom: 50px;">Découvrez notre sélection exclusive de bungalows</p>
        
        <div class="gallery-grid">
            <!-- Grande image à gauche -->
            <div class="gallery-item large animate__animated animate__fadeInLeft">
                <img src="image/bungalow1.jpg" alt="Bungalow Principal">
                <div class="gallery-overlay">
                    <h3>Bungalow Deluxe</h3>
                </div>
            </div>
            
            <!-- Trois petites images à droite (haut) -->
            <div class="gallery-item small animate__animated animate__fadeIn animate-delay-1">
                <img src="image/bungalow2.jpg" alt="Bungalow Vue Mer">
                <div class="gallery-overlay">
                    <h3>Vue Mer</h3>
                </div>
            </div>
            
            <div class="gallery-item small animate__animated animate__fadeIn animate-delay-2">
                <img src="image/bungalow3.jpg" alt="Bungalow Jardin">
                <div class="gallery-overlay">
                    <h3>Jardin Privé</h3>
                </div>
            </div>
            
            <div class="gallery-item small animate__animated animate__fadeIn animate-delay-3">
                <img src="image/bungalow4.jpg" alt="Bungalow Piscine">
                <div class="gallery-overlay">
                    <h3>Accès Piscine</h3>
                </div>
            </div>
            
            <!-- Trois petites images à droite (bas) -->
            <div class="gallery-item medium animate__animated animate__fadeIn animate-delay-1">
                <img src="image/bungalow5.jpg" alt="Bungalow Terrasse">
                <div class="gallery-overlay">
                    <h3>Grande Terrasse</h3>
                </div>
            </div>
            
            <div class="gallery-item medium animate__animated animate__fadeIn animate-delay-2">
                <img src="image/bungalow6.jpg" alt="Bungalow Chambre">
                <div class="gallery-overlay">
                    <h3>Chambre Spacieuse</h3>
                </div>
            </div>
            
            <div class="gallery-item medium animate__animated animate__fadeIn animate-delay-3">
                <img src="image/bungalow7.jpg" alt="Bungalow Salle de Bain">
                <div class="gallery-overlay">
                    <h3>Salle de Bain Luxueuse</h3>
                </div>
            </div>
        </div>
        
        <!-- Sections alternées photo/description -->
        <div class="bungalow-feature animate__animated animate__fadeIn">
            <div class="feature-image">
                <img src="image/bungalow8.jpg" alt="Bungalow Familial">
            </div>
            <div class="feature-content">
                <h3>Bungalow Familial</h3>
                <p>Notre bungalow familial offre tout l'espace et le confort dont vous avez besoin pour des vacances en famille inoubliables. Avec deux chambres spacieuses, une cuisine entièrement équipée et un salon lumineux, vous vous sentirez comme chez vous.</p>
                <p>Profitez de la terrasse privée avec vue sur le jardin et de l'accès direct à la piscine commune.</p>
                
            </div>
        </div>
        
        <div class="bungalow-feature feature-reverse animate__animated animate__fadeIn">
            <div class="feature-image">
                <img src="image/bungalow9.jpg" alt="Bungalow Romantique">
            </div>
            <div class="feature-content">
                <h3>Bungalow Romantique</h3>
                <p>Parfait pour les couples en quête d'intimité et de romance, notre bungalow romantique offre un cadre idyllique avec lit king-size, bain à remous privé et terrasse avec vue sur le coucher de soleil.</p>
                <p>Nous proposons des services supplémentaires comme des dîners aux chandelles, des paniers pique-nique et des excursions privées pour rendre votre séjour encore plus spécial.</p>
                
            </div>
        </div>
        
        <div class="bungalow-feature animate__animated animate__fadeIn">
            <div class="feature-image">
                <img src="image/bungalow10.jpg" alt="Bungalow Écologique">
            </div>
            <div class="feature-content">
                <h3>Bungalow Écologique</h3>
                <p>Pour les voyageurs soucieux de l'environnement, notre bungalow écologique combine confort moderne et respect de la nature. Construit avec des matériaux durables, équipé de panneaux solaires et d'un système de récupération d'eau de pluie.</p>
                <p>Vous apprécierez le calme de ce havre de paix, entouré d'une végétation luxuriante et de sentiers de randonnée.</p>
                
            </div>
        </div>
    </div>
</section>
<!-- Section des villes -->
<section class="cities-section">
    <h2 class="section-title animate__animated animate__fadeIn">Nos Destinations</h2>
    <p class="animate__animated animate__fadeIn" style="animation-delay: 0.2s;">Découvrez nos bungalows dans ces magnifiques villes tunisiennes</p>
    
    <div class="cities-container">
        <div class="city-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <img src="image/tozeurr.jpg" alt="Tozeur">
            <div class="city-overlay">
                <h3 class="city-name">Tozeur</h3>
            </div>
        </div>
        
        <div class="city-card animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
            <img src="image/tabarkaa.jpg" alt="Tabarka">
            <div class="city-overlay">
                <h3 class="city-name">Tabarka</h3>
            </div>
        </div>
        
        <div class="city-card animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
            <img src="image/rafraff.jpg" alt="Rafraf">
            <div class="city-overlay">
                <h3 class="city-name">Rafraf</h3>
            </div>
        </div>
    </div>
</section>
<!-- Section bleue -->
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
