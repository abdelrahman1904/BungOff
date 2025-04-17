<?php
require_once '../../models/vehicule.php';
require_once '../../controllers/vehiculeC.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $type = $_POST['type'];
    $model = $_POST['model'];
    $matricule = $_POST['matricule'];
    $capacite = $_POST['capacite'];
    $dispo = $_POST['dispo'];

    // Vérification si toutes les données sont présentes
    if (!empty($type) && !empty($model) && !empty($matricule) && !empty($capacite)) {
        // Création de l'objet Véhicule
        $vehicule = new Vehicule($type, $model, $matricule, $capacite, $dispo);
        $vehiculeC = new VehiculeC();

        // Tentative d'ajout du véhicule
        try {
            $vehiculeC->ajouterVehicule($vehicule);
            // Si l'ajout a réussi, message de succès
            echo "<script>
                    alert('Le véhicule a été ajouté avec succès.');
                    window.location.href = 'btransport.html'; // Rediriger vers une autre page
                  </script>";
        } catch (Exception $e) {
            // Si une erreur survient lors de l'ajout
            echo "<script>alert('Erreur lors de l\'ajout du véhicule. Veuillez réessayer.');</script>";
        }
    } else {
        // Si les données ne sont pas valides
        echo "<script>alert('Veuillez remplir tous les champs.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Véhicule - Backoffice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="ajout.css">
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
    <a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="#"><i class="fas fa-user"></i> Utilisateurs</a>
    <a href="#"><i class="fas fa-home"></i> Bungalows</a>
    <a href="#"><i class="fas fa-campground"></i><span>Activités</span> </a>
    <a href="btransport.html"><i class="fas fa-car"></i> <span>Transports</span></a>
    <a href="#"><i class="fas fa-credit-card"></i><span>promotions</span></a>
    <a href="#"><i class="fas fa-star"></i> <span>avis</span></a>
    <div class="logout">
        <a href="#"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a>
    </div>
</div>

<!-- Contenu principal -->
<div class="content">
    <div class="transport-header">
        <a href="btransport.html" class="add-transport-link">⬅ Transports</a>
    </div>
    <div class="form-container">
        <div class="form-header">
            <center> <h2><i class="fas fa-plus-circle"></i> Ajouter un véhicule</h2>
            <p>Remplissez le formulaire pour ajouter un nouveau véhicule</p></center>
        </div>

        <form id="vehicleForm" action="ajouterV.php" method="POST" novalidate>
            <div class="form-group">
                <label for="type">Type</label>
                <select class="form-control" name="type" id="type">
                    <option value="">-- Choisir un type --</option>
                    <option value="bus">Bus</option>
                    <option value="minibus">Minibus</option>
                    <option value="voiture">Voiture</option>
                </select>
            </div>
            <div class="form-group">
                <label for="matricule">Matricule</label>
                <input type="text" class="form-control" name="matricule" id="matricule">
            </div>
            <div class="form-group">
                <label for="model">Modèle</label>
                <input type="text" class="form-control" name="model" id="model">
            </div>
            <div class="form-group">
                <label for="capacite">Capacité</label>
                <input type="number" class="form-control" name="capacite" id="capacite">
            </div>
            <div class="form-group">
                <label for="dispo">Disponibilité</label>
                <select class="form-control" name="dispo" id="dispo">
                    <option value="">-- Choisir une option --</option>
                    <option value="oui">Oui</option>
                    <option value="non">Non</option>
                </select>
            </div>
            <button type="submit" class="btn-submit">Ajouter le véhicule</button>
        </form>
    </div>
</div>

<script src="scriptB.js" ></script>


</body>
</html>
