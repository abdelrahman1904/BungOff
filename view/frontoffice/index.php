<?php
// Inclure le contrôleur
require_once '../../controller/aviscontroller.php';

// Créer une instance du contrôleur
$avisController = new AvisController();

// Récupérer la liste des avis
$listeAvis = $avisController->listAvis();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = [
            'Nom' => $_POST['Nom'] ?? '',
            'LieuDuBungalow' => $_POST['LieuDuBungalow'] ?? '',
            'ActivitéUtilisée' => $_POST['ActivitéUtilisée'] ?? '',
            'Note' => $_POST['Note'] ?? '',
            'Commentaire' => $_POST['Commentaire'] ?? ''
        ];

        // Debug: Log the input data
        error_log("Form data: " . print_r($data, true));

        $avisController->addAvis($data);
        header('Location: index.php');
    } catch (Exception $e) {
        echo "<script>alert('Erreur : " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <title>Gestion des Avis</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        .btn {
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-add {
            background-color: #4CAF50;
        }
        .btn-add:hover {
            background-color: #45a049;
        }
        .btn-edit {
            background-color: #2196F3;
        }
        .btn-edit:hover {
            background-color: #0b7dda;
        }
        .btn-delete {
            background-color: #f44336;
        }
        .btn-delete:hover {
            background-color: #d32f2f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-cell {
            white-space: nowrap;
        }
        .stars {
            color: gold;
            font-size: 1.2em;
        }
    </style>
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
    <!-- Include SweetAlert CSS and JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
    <div class="logo">
        <img src="image/maison.jpg" alt="Logo BungOFF" class="logo-img">
        Bung<span class="off">OFF</span>
    </div>
    <nav>
        <ul>
            <li><a href="#">Accueil</a></li>
            <li><a href="#"><i class="fas fa-home"></i> Bungalows</a></li>
            <li><a href="activite.html"><i class="fas fa-bicycle"></i> Activités</a></li>
            <li><a href="#"><i class="fas fa-car"></i> Transports</a></li>
            <li><a href="#"><i class="fas fa-credit-card"></i> Promotions</a></li>
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
        <a href="login.php"><i class="fas fa-user login-icon"></i></a>
    </div>
</header>
    <div class="container">
        <h1>Gestion des Avis</h1>
        
        <!-- Formulaire principal -->
        <form id="avisForm" action="" method="POST">
            <input type="hidden" name="IDUtilisateur" value="">
            
            <div class="form-group">
                <label for="Nom">Nom:</label>
                <input type="text" id="Nom" name="Nom" required>
            </div>
            
            <div class="form-group">
                <label for="LieuDuBungalow">Lieu du Bungalow:</label>
                <input type="text" id="LieuDuBungalow" name="LieuDuBungalow" required>
            </div>
            
            <div class="form-group">
                <label for="ActiviteUtilisee">Activité Utilisée:</label>
                <input type="text" id="ActiviteUtilisee" name="ActivitéUtilisée" required>
            </div>
            
            <div class="form-group">
                <label for="Note">Note:</label>
                <select id="Note" name="Note">
                    <option value="">Choisir une note</option>
                    <option value="1">1 ★</option>
                    <option value="2">2 ★★</option>
                    <option value="3">3 ★★★</option>
                    <option value="4">4 ★★★★</option>
                    <option value="5">5 ★★★★★</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="Commentaire">Commentaire:</label>
                <textarea id="Commentaire" name="Commentaire" required></textarea>
            </div>
            
            <button type="button" id="btnAjouter" class="btn btn-add">Ajouter Avis</button>        </form>
        
        <!-- Tableau des avis -->
        <h2>Liste des Avis</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Lieu</th>
                    <th>Activité</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listeAvis as $avis): ?>
                    <tr>
                        <td><?= htmlspecialchars($avis['IDUtilisateur']) ?></td>
                        <td><?= htmlspecialchars($avis['Nom']) ?></td>
                        <td><?= htmlspecialchars($avis['LieuDuBungalow']) ?></td>
                        <td><?= htmlspecialchars($avis['ActivitéUtilisée']) ?></td>
                        <td><?= htmlspecialchars($avis['Note']) ?></td>
                        <td><?= htmlspecialchars($avis['Commentaire']) ?></td>
                        <td class="action-cell">
                            <form action="formulaire_modification.php" method="POST" style="display: inline;">
                                <input type="hidden" name="IDUtilisateur" value="<?= $avis['IDUtilisateur'] ?>">
                                <button type="submit" name="action" value="edit" class="btn btn-edit">Modifier</button>
                            </form>
                            <form action="supprimer.php" method="POST" style="display: inline;">
                                <input type="hidden" name="IDUtilisateur" value="<?= $avis['IDUtilisateur'] ?>">
                                <button type="submit" name="action" value="delete" class="btn btn-delete">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        document.getElementById('btnAjouter').addEventListener('click', function() {
            // Liste des "mots interdits" (noms d'animaux)
            const badWords = ['chien', 'chat', 'singe', 'lion', 'tigre', 'éléphant', 'cheval', 'lapin', 'renard', 'ours'];

            // Récupération des valeurs
            const nom = document.getElementById('Nom').value.trim();
            const lieu = document.getElementById('LieuDuBungalow').value.trim();
            const activite = document.getElementById('ActiviteUtilisee').value.trim();
            const note = document.getElementById('Note').value;
            const commentaire = document.getElementById('Commentaire').value.trim();

            // Validation
            let erreurs = [];
            let containsBadWord = false;

            // Vérification des mots interdits
            [nom, lieu, activite, commentaire].forEach(input => {
                badWords.forEach(word => {
                    if (input.toLowerCase().includes(word)) {
                        containsBadWord = true;
                    }
                });
            });

            if (containsBadWord) {
                Swal.fire({
                    icon: 'error',
                    title: 'Avis supprimé',
                    text: 'Votre avis contient des mots interdits et a été supprimé.',
                }).then(() => {
                    // Soumission du formulaire pour suppression
                    const deleteForm = document.createElement('form');
                    deleteForm.method = 'POST';
                    deleteForm.action = 'supprimer.php';

                    // Ajout d'un champ caché pour l'ID utilisateur
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'IDUtilisateur';
                    hiddenInput.value = '0'; // Utilisez une valeur par défaut ou gérez dynamiquement si nécessaire
                    deleteForm.appendChild(hiddenInput);

                    document.body.appendChild(deleteForm);
                    deleteForm.submit();
                });
                return;
            }

            if (nom.length < 2 || nom.length > 50) {
                erreurs.push("- Le nom doit contenir entre 2 et 50 caractères");
            }

            if (lieu.length < 3 || lieu.length > 100) {
                erreurs.push("- Le lieu doit contenir entre 3 et 100 caractères");
            }

            if (activite.length < 3 || activite.length > 50) {
                erreurs.push("- L'activité doit contenir entre 3 et 50 caractères");
            }

            if (!note || note < 1 || note > 5) {
                erreurs.push("- Veuillez sélectionner une note valide (1 à 5)");
            }

            if (commentaire.length < 10 || commentaire.length > 500) {
                erreurs.push("- Le commentaire doit contenir entre 10 et 500 caractères");
            }

            // Affichage des erreurs ou soumission
            if (erreurs.length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Erreurs détectées',
                    html: "Veuillez corriger les erreurs suivantes :<br><ul><li>" + erreurs.join("</li><li>") + "</li></ul>",
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Avis ajouté',
                    text: 'Votre avis a été ajouté avec succès.',
                }).then(() => {
                    document.getElementById('avisForm').submit();
                });
            }
        });
    </script>
    <video class="full-width-video" autoplay muted loop style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; object-fit: cover;">
        <source src="image_video/Lets Discover Tunisia.mp4" type="video/mp4">
        Votre navigateur ne supporte pas la lecture de vidéos.
    </video>
</body>
</html>