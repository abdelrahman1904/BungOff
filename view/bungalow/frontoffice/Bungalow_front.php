
<?php
include_once __DIR__ . '/../../../config.php';
include_once "../../../controller/userlistC.php";
include_once "../../../controller/userlistC.php";
$UserC = new userlistC();
$var = $UserC->allusers();

session_start();
$username = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : 'Guest';
$email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : 'No Email';
$image = isset($_SESSION['user']['image']) ? $_SESSION['user']['image'] : 'No image';

try {
    $pdo = config::getConnexion();

    // Gestion de la recherche
    $sql = "SELECT * FROM bungalow";
    $params = [];

    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $searchTerm = trim($_GET['search']);
        $sql .= " WHERE nom LIKE :search OR localisation LIKE :search";
        $params[':search'] = '%' . $searchTerm . '%';
    }

    $sql .= " ORDER BY IDB DESC";

    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();
    $bungalows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // >>> Organiser les bungalows par localisation (lieu) comme dans activite.php <<<
    $groupedByLieu = [];
    foreach ($bungalows as $bungalow) {
        $lieu = $bungalow['localisation'];
        if (!isset($groupedByLieu[$lieu])) {
            $groupedByLieu[$lieu] = [];
        }
        $groupedByLieu[$lieu][] = $bungalow;
    }

    // Format JSON (utile si tu veux l'utiliser en JS ensuite)
    $bungalowsJSON = json_encode($groupedByLieu);

} catch (PDOException $e) {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
            padding-top: 80px; 
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
    top: 0;
    left: 0;
    right: 0; /* Ajouté pour s'étendre sur toute la largeur */
    z-index: 1000;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.header-container {
    width: 100%;
    max-width: 1400px; /* ou la largeur maximale que vous souhaitez */
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
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
    text-align: center;
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
  /* Styles existants conservés... */

  .search-container {
    position: relative;
    margin-left: 15px;
}

.search-form {
    display: flex;
    align-items: center;
}

.search-input {
    padding: 8px 15px;
    border-radius: 20px;
    border: none;
    outline: none;
    width: 200px;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.search-input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.search-buttons {
    display: flex;
    margin-left: 10px;
}

.search-submit, .search-refresh {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    margin-left: 5px;
    transition: all 0.3s ease;
}

.search-submit:hover {
    background: var(--accent-beige);
}

.search-refresh:hover {
    background: #4CAF50;
}

/* Pour les écrans mobiles */
@media (max-width: 768px) {
    .search-input {
        width: 150px;
    }
    
    .search-container {
        margin-left: 10px;
    }
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
        .cta-button.mes-reservations {
    background-color: var(--accent-light);
    color: white;
    padding: 10px 20px;  /* Réduit les paddings pour rendre le bouton plus petit */
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;  /* Taille de police réduite pour un bouton plus petit */
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    border: none;
    cursor: pointer;
    display: inline-block;
}

.cta-button.mes-reservations:hover {
    background-color: #6c5e4b; /* Un ton plus sombre pour différencier */
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

.btn-reservation-container {
    text-align: center;
    margin-top: 20px;
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
        .reservation-form {
    margin-top: 10px;
    background-color: #f3f3f3;
    padding: 10px;
    border-radius: 8px;
}

.reservation-form input,
.reservation-form button {
    margin: 5px 0;
    padding: 8px;
    width: 100%;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.reservation-form button {
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
}

.reservation-form button:hover {
    background-color: #0056b3;
}
.bungalow-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: linear-gradient(135deg,rgb(20, 212, 237),rgb(66, 165, 211));
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 20px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    z-index: 10;
    transition: transform 0.2s ease-in-out;
}

.bungalow-card {
    position: relative; /* Nécessaire pour positionner le badge */
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(16, 181, 231, 0.1);
    background-color: #fff;
    padding: 16px;
    margin-bottom: 24px;
    width: 300px;
}

.bungalow-badge:hover {
    transform: scale(1.05);
}
/* Pour positionner la dropdown près de l’image */
.dropdown-menu {
  position: absolute;
  top: 70px; /* Ajuste en fonction de ta top-bar */
  right: 20px;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  padding: 10px;
  min-width: 200px;
  display: none; /* Par défaut, on la cache */
  z-index: 999;
}

  .dropdown {
  position: relative;
}

.dropdown-menu {
  position: absolute;
  top: 60px;
  right: 0;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  list-style: none;
  padding: 10px 0;
  display: none;
  min-width: 180px;
  z-index: 1000;
}


.user-profile i {
    display: inline-block;
    font-size: 30px; /* Ajuste la taille si nécessaire */
}

.dropdown-menu li {
  padding: 10px 20px;
}

.dropdown-menu li a {
  color: #333;
  text-decoration: none;
  display: block;
  font-weight: bold;
}

.dropdown-menu li a:hover {
  background-color: #f0f0f0;
}

.dropdown-header {
  padding: 10px 20px;
  background-color: #f7f7f7;
  font-size: 14px;
  color: #555;
  border-bottom: 1px solid #ddd;
  text-align: center;
}

.dropdown-divider {
  border-top: 1px solid #eee;
  margin: 5px 0;
}
.dropdown-menu {
  z-index: 9999;
}


    </style>
</head>

<body>

</div>

<header>
  <div class="logo">
    <img src="image_video1/maison.jpg" alt="Logo BungOFF" class="logo-img">
    Bung<span class="off">OFF</span>
  </div>

  <nav>
    <ul>
      <li><a href="../../User/frontoffice/homePage.php">Accueil</a></li>
      <li class="active"><a href="bungalow_front.php"><i class="fas fa-home"></i> Bungalows</a></li>
      <li><a href="../../views/frontoffice/activite.php"><i class="fas fa-bicycle"></i> Activités</a></li>
      <li><a href="#"><i class="fas fa-car"></i> Transports</a></li>
      <li><a href="../../Compagne/frontoffice/promotions_front.php"><i class="fas fa-credit-card"></i> Promotions</a></li>
      <li><a href="../../Avis/frontoffice/index.php"><i class="fas fa-comments"></i> Avis</a></li>
      <li><a href="mes_reservations.php" class="cta-button mes-reservations">mes réservations</a></li>
    </ul>
  </nav>

  <div class="extra-info">
    <!-- Météo -->
    <div class="weather" id="boutonMeteo">
      <i class="fas fa-cloud weather-icon"></i>
      <div class="weather-info">
        <a href="meteo.php" class="btn-meteo">Voir la météo</a>
      </div>
    </div>

    <!-- Recherche -->
    <div class="search-container">
      <form method="GET" action="" class="search-form">
        <input type="text" name="search" placeholder="Rechercher bungalow..." class="search-input">
        <div class="search-buttons">
          <button type="submit" class="search-submit"><i class="fas fa-check"></i></button>
          <button type="button" class="search-refresh"><i class="fas fa-sync-alt"></i></button>
        </div>
        <i class="fas fa-search search-icon"></i>
   <img id="profileDropdown"
     src="../../User/frontoffice/user_images/<?php echo htmlspecialchars($image); ?>" 
     alt="Profile" 
     style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; cursor: pointer;">


            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li class="dropdown-header text-center">
                    <strong><?php echo htmlspecialchars($username); ?></strong><br>
                    <small><?php echo htmlspecialchars($email); ?></small>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="../../User/frontoffice/editProfile.php">Edit Profile</a></li>
                <li><a class="dropdown-item" href="../../User/frontoffice/logout.php">Log Out</a></li>
            </ul>
        </div>
  </div>
      </form>
    </div>

    
</header>







</div>
<section class="bungalow-list" id="bungalows">
<h1 class="animate__animated animate__fadeIn">Nos Bungalows</h1>

    
    <div class="bungalows-container">
        <?php foreach ($bungalows as $bungalow): ?>
            <div class="bungalow-card">
                <span class="bungalow-badge">Nouveau</span>
                <img src="image_video1/<?php echo htmlspecialchars($bungalow['image']); ?>" alt="Bungalow à <?php echo htmlspecialchars($bungalow['localisation']); ?>" class="bungalow-image">
                <div class="bungalow-info">
                    <h3><?php echo htmlspecialchars($bungalow['nom']); ?></h3>
                    <div class="bungalow-location">
                        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($bungalow['localisation']); ?>
                    </div>
                    <p><?php echo htmlspecialchars($bungalow['description']); ?></p>
                    <div class="bungalow-features">
                        <div class="bungalow-feature">
                            <i class="fas fa-bed"></i> <?php echo htmlspecialchars($bungalow['type']); ?>
                        </div>
                        <div class="bungalow-feature">
                            <i class="fas fa-users"></i> <?php echo htmlspecialchars($bungalow['capacite']); ?> Personnes
                        </div>
                    </div>
                    <div class="bungalow-price">
                        <?php echo htmlspecialchars($bungalow['prix_nuit']); ?> dt <span>/nuit</span>
                    </div>
                    <a href="reservationFront.php?id_bungalow=<?php echo $bungalow['IDB']; ?>" class="cta-button">Réserver maintenant</a>
                    <div class="btn-reservation-container">

</div>


                    <!-- Formulaire caché de réservation -->
                  
                </div>
            </div>
        <?php endforeach; ?>
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
document.addEventListener('DOMContentLoaded', function () {
    const reserveButtons = document.querySelectorAll('.btn-reserve');
    
    reserveButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Afficher le formulaire de réservation caché
            const form = this.closest('.bungalow-card').querySelector('.bungalow-reservation-form');
            form.style.display = 'block';
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la barre de recherche
    const searchToggle = document.querySelector('.search-toggle');
    const searchContainer = document.querySelector('.search-container');
    const searchInput = document.querySelector('.search-input');
    const searchRefresh = document.querySelector('.search-refresh');
    
    // Toggle de la barre de recherche
    searchToggle.addEventListener('click', function() {
        searchContainer.classList.toggle('active');
        if (searchContainer.classList.contains('active')) {
            searchInput.focus();
        }
    });
    
    // Bouton refresh
    searchRefresh.addEventListener('click', function() {
        window.location.href = window.location.pathname;
    });
    
    // Fermer la recherche si on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!searchContainer.contains(e.target) && e.target !== searchToggle) {
            searchContainer.classList.remove('active');
        }
    });
    
    // Empêcher la propagation du clic dans la barre de recherche
    searchContainer.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});

</script>

<script>
  const profile = document.getElementById('profileDropdown');
  const dropdown = document.querySelector('.dropdown-menu');

  profile.addEventListener('click', function () {
    dropdown.classList.toggle('show');
  });

  // Optionnel : clique en dehors pour fermer
  document.addEventListener('click', function (event) {
    if (!profile.contains(event.target) && !dropdown.contains(event.target)) {
      dropdown.classList.remove('show');
    }
  });
</script>








</body>
</html>