<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include controllers
require_once '../../../controller/controller.php';

// Vérifier si un ID de campagne est passé en paramètre
if (!isset($_GET['id'])) {
    die("Aucune campagne spécifiée.");
}

$campaignController = new CompagneController();
$campaign = $campaignController->getCompagne($_GET['id']);

if (!$campaign) {
    die("Campagne non trouvée.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affiche - <?php echo htmlspecialchars($campaign['nom']); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #ff6f61, #4a90e2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .poster-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 600px;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .poster-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2), transparent);
            transform: rotate(45deg);
            animation: shine 5s infinite;
        }

        @keyframes shine {
            0% { transform: rotate(45deg) translateX(-50%); }
            50% { transform: rotate(45deg) translateX(50%); }
            100% { transform: rotate(45deg) translateX(-50%); }
        }

        .campaign-title {
            font-size: 2.5rem;
            color: #ff6f61;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .campaign-description {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .campaign-dates {
            font-size: 1.1rem;
            color: #4a90e2;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .reserve-btn {
            padding: 12px 30px;
            background-color: #ff6f61;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .reserve-btn:hover {
            background-color: #e65b50;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .poster-container {
                width: 95%;
                padding: 20px;
            }

            .campaign-title {
                font-size: 2rem;
            }

            .campaign-description {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="poster-container">
        <h1 class="campaign-title animate__animated animate__bounceIn"><?php echo htmlspecialchars($campaign['nom']); ?></h1>
        <p class="campaign-description animate__animated animate__fadeIn" style="animation-delay: 0.5s;">
            <?php echo htmlspecialchars($campaign['description']); ?>
        </p>
        <p class="campaign-dates animate__animated animate__fadeIn" style="animation-delay: 1s;">
            Du <?php echo htmlspecialchars($campaign['date_debut']); ?> au <?php echo htmlspecialchars($campaign['date_fin']); ?>
        </p>
        <button class="reserve-btn animate__animated animate__pulse" style="animation-delay: 1.5s;">
            Réserver Maintenant
        </button>
    </div>
</body>
</html>