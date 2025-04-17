<?php
require_once '../model/bungalow.php';
require_once '../controller/bungalowC.php';
$errors = [];
$imageName = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gestion de l’upload de l’image
    $imageName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = basename($_FILES['image']['name']);
    }

    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $capacite = $_POST['capacite'];
    $prix_nuit = $_POST['prix_nuit'];
    $localisation = $_POST['localisation'];
    $type = $_POST['type'];
    $description = $_POST['description'];

    // Contrôle de saisie simple
    if (empty($nom)) {
        $errors[] = "Le nom est requis.";
    }
    if (empty($capacite) || !is_numeric($capacite) || $capacite <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }
    if (empty($prix_nuit) || !is_numeric($prix_nuit) || $prix_nuit <= 0) {
        $errors[] = "Le prix par nuit doit être un nombre positif.";
    }
    if (empty($localisation)) {
        $errors[] = "La localisation est requise.";
    }
    if (empty($type)) {
        $errors[] = "Le type est requis.";
    }
    if (empty($description)) {
        $errors[] = "La description est requise.";
    }

    // Si aucun problème, on ajoute le bungalow en base de données
    if (empty($errors)) {
        // Création de l’objet Bungalow
        $bungalow = new Bungalow($nom, $capacite, $prix_nuit, $localisation, $type, $description, $imageName);
        
        // Insertion en base de données
        $bungalowC = new BungalowC();
        $bungalowC->ajouterBungalow($bungalow);

        // Redirection ou message de confirmation
        header("Location: ajouter_bung.php?success=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Backoffice Bungalow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="ajouter_bung.css">
</head>

<body>

    <!-- Barre en haut -->
    <div class="top-bar">
        <div class="logo d-flex align-items-center gap-2">
            Bung<span class="off">OFF</span>
            <i id="toggleSidebar" class="fas fa-bars" style="cursor: pointer;"></i>
        </div>
        
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

    <!-- Barre latérale -->
    <div class="sidebar">
        <a href="activity.html"><i class="fas fa-tachometer-alt"></i> dashboard</a>
        <a href="#"><i class="fas fa-user"></i> Utilisateurs</a>
        <a href="#"><i class="fas fa-home"></i> Bungalows</a>
        <a href="new_act.html"><i class="fas fa-campground"></i> Activités</a>
        <a href="#"><i class="fas fa-car"></i> Transport</a>
        <a href="#"><i class="fas fa-credit-card"></i> Paiement</a>
        <a href="#"><i class="fas fa-star"></i> Avis</a>
        <div class="logout">
            <a href="#"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="content">
        <a href="newback_bung.html" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour à la liste des bungalows
        </a>
        
        <div class="form-container">
            <h2>Formulaire d'ajout de bungalow</h2>
            <form action="ajouter_bung.php" method="POST" enctype="multipart/form-data" id="form-bungalow">

                <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div class="alert alert-success">Le bungalow a été ajouté avec succès !</div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="mb-3">
                    <label for="capacite" class="form-label">Capacité</label>
                    <input type="number" class="form-control" id="capacite" name="capacite" required>
                </div>
                <div class="mb-3">
                    <label for="prix" class="form-label">Prix par nuit</label>
                    <input type="number" class="form-control" id="prix" name="prix_nuit" required>
                </div>
                <div class="mb-3">
                    <label for="localisation" class="form-label">Localisation</label>
                    <input type="text" class="form-control" id="localisation" name="localisation" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="">Sélectionnez un type</option>
                        <option value="standard">Standard</option>
                        <option value="premium">Premium</option>
                        <option value="luxe">Luxe</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>

        </div>
    </div>
    
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.content').classList.toggle('collapsed-content');
        });

        // Validation de formulaire en JS
        document.getElementById('form-bungalow').addEventListener('submit', function (event) {
            let valid = true;

            // Vérification du nom
            const nom = document.getElementById('nom').value;
            if (nom.trim() === '') {
                alert('Le nom est requis.');
                valid = false;
            }

            // Vérification de la capacité
            const capacite = document.getElementById('capacite').value;
            if (capacite <= 0 || isNaN(capacite)) {
                alert('La capacité doit être un nombre positif.');
                valid = false;
            }

            // Vérification du prix par nuit
            const prixNuit = document.getElementById('prix').value;
            if (prixNuit <= 0 || isNaN(prixNuit)) {
                alert('Le prix par nuit doit être un nombre positif.');
                valid = false;
            }

            // Vérification de la localisation
            const localisation = document.getElementById('localisation').value;
            if (localisation.trim() === '') {
                alert('La localisation est requise.');
                valid = false;
            }

            // Vérification du type
            const type = document.getElementById('type').value;
            if (type === '') {
                alert('Le type est requis.');
                valid = false;
            }

            // Vérification de la description
            const description = document.getElementById('description').value;
            if (description.trim() === '') {
                alert('La description est requise.');
                valid = false;
            }

            // Si le formulaire n'est pas valide, on empêche la soumission
            if (!valid) {
                event.preventDefault();
            }
        });
    </script>
    
</body>
</html>
