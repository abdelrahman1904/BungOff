<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Promotions - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .choice-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
            flex-wrap: wrap;
        }
        .choice-btn {
            padding: 20px 40px;
            font-size: 1.2rem;
            font-weight: bold;
            border-radius: 10px;
            text-decoration: none;
            color: white;
            transition: transform 0.3s;
        }
        .choice-btn:hover {
            transform: scale(1.05);
        }
        .btn-campaign {
            background-color: rgb(63, 131, 203);
        }
        .btn-promotion {
            background-color: rgb(59, 189, 89);
        }
        .btn-newsletter {
            background-color: rgb(155, 89, 182); /* Purple color for newsletter */
        }
    </style>
</head>
<body>
    <!-- Barre en haut -->
    <div class="top-bar">
        <div class="logo">Bung<span class="off">OFF</span></div>
        <div class="right-icons">
            <form class="search-form d-inline-flex">
                <input type="text" class="form-control" placeholder="Recherche...">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <div class="login-icon d-inline-flex ms-3">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

     <div class="sidebar">
  <a href="../../User/backoffice/homePage.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
  <a href="../../User/backoffice/userlist.php"><i class="fas fa-user"></i> Utilisateurs</a>
  <a href="#"><i class="fas fa-home"></i> Bungalows</a>
<<<<<<< HEAD
  <a href="#"><i class="fas fa-campground"></i> Activités</a>
=======
  <a href="../../views/new_act.php"><i class="fas fa-campground"></i> Activités</a>
>>>>>>> 77c66e1 (Integration+bungalow)
  <a href="#"><i class="fas fa-car"></i> Transports</a>
  <a href="promotion.php"><i class="fas fa-credit-card"></i> Promotion</a>
  <a href="../../Avis/backoffice/index.php"><i class="fas fa-star"></i> Avis</a>
  <div class="logout">
    <a href="#"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a>
  </div>
</div>

    <!-- Contenu principal -->
    <div class="content">
        <h1>Gestion des Promotions, Campagnes et Newsletter</h1>

        <div class="choice-container">
            <a href="manage_campaign.php" class="choice-btn btn-campaign">Gérer les Campagnes</a>
            <a href="manage_promotion.php" class="choice-btn btn-promotion">Gérer les Promotions</a>
            <a href="manage_newsletter.php" class="choice-btn btn-newsletter">Gérer la Newsletter</a>
        </div>
    </div>
</body>
</html>