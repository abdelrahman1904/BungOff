<?php
require_once '../../model/activite.php';
require_once '../../controller/activiteC.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gestion de l’upload de la photo
    $photoName = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photoName = basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], '../image/' . $photoName); // tu ajustes le dossier si besoin
    }

    $titre = $_POST['titre'];
    $guide = $_POST['guide'];
    $description = $_POST['description'];
    $duree = $_POST['duree'];
    $type = $_POST['type'];
    $prix = $_POST['prix'];
    $nbp = $_POST['nbp'];

    $activite = new Activite($titre, $guide, $description, $duree, $type, $prix, $photoName, $nbp);
    $activiteC = new ActiviteC();
    $activiteC->ajouterActivite($activite);

    header("Location: consulter_act.php"); // ou une autre redirection
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Activité - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="activity.css">
    <link rel="stylesheet" href="ajouter_act.css">
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
    <div class="activity-header">
        <a href="new_act.php" class="add-activity-link">
            ⬅ Activité
        </a>
    </div>
    <div class="form-container">
        <div class="form-header">
            <h2><i class="fas fa-plus-circle"></i> Ajouter une nouvelle activité</h2>
            <p>Remplissez le formulaire pour créer une nouvelle activité</p>
        </div>

        <div class="form-errors mb-3"></div>

        <form id="activityForm" action="ajouter_act.php" method="POST" enctype="multipart/form-data">

<div class="mb-3">
    <label for="titre" class="form-label">Titre</label>
    <input type="text" class="form-control" name="titre" id="titre">
    <div class="text-danger"></div>
</div>

<div class="mb-3">
    <label for="guide" class="form-label">Guide</label>
    <input type="text" class="form-control" name="guide" id="guide">
    <div class="text-danger"></div>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" name="description" id="description"></textarea>
    <div class="text-danger"></div>
</div>

<div class="mb-3">
    <label for="duree" class="form-label">Durée</label>
    <input type="text" class="form-control" name="duree" id="duree" placeholder="En minutes">
    <div class="text-danger"></div>
</div>


<div class="mb-3">
    <label for="type" class="form-label">Type</label>
    <select class="form-control" name="type" id="type">
        <option value="">Sélectionnez un type</option>
        <option value="sport">Sportive</option>
        <option value="Aquatique">Aquatique</option>
        <option value="nature">Nature</option>
        <option value="loisir">Loisir</option>
    </select>
    <div class="text-danger"></div>
</div>

<div class="mb-3">
    <label for="prix" class="form-label">Prix</label>
    <input type="text" class="form-control" name="prix" id="prix">
    <div class="text-danger"></div>
</div>

<div class="mb-3">
    <label for="photo" class="form-label">Photo</label>
    <input type="file" class="form-control" name="photo" id="photo">
    <div class="text-danger"></div>
</div>

<div class="mb-3">
    <label for="nbp" class="form-label">Nombre de participants</label>
    <input type="text" class="form-control" name="nbp" id="nbp">
    <div class="text-danger"></div>
</div>

<button type="submit" class="btn btn-primary">Enregistrer l'activité</button>
</form>

</div>
</div>

    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.content').classList.toggle('collapsed-content');
        });

        // Animation pour les champs du formulaire
        const formGroups = document.querySelectorAll('.form-group');
        formGroups.forEach((group, index) => {
            group.style.animationDelay = `${0.2 + index * 0.1}s` ;
            
            // Ajout d'un effet quand un champ est focus
            const input = group.querySelector('input, textarea, select');
            if (input) {
                input.addEventListener('focus', () => {
                    group.classList.add('focused');
                });
                input.addEventListener('blur', () => {
                    if (!input.value) {
                        group.classList.remove('focused');
                    }
                });
            }
        });
        document.getElementById('activityForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Empêche l'envoi du formulaire

    const titre = document.getElementById('titre').value.trim();
    const guide = document.getElementById('guide').value.trim();
    const description = document.getElementById('description').value.trim();
    const duree = document.getElementById('duree').value.trim();
    const type = document.getElementById('type').value;
    const prix = document.getElementById('prix').value.trim();
    const nbp = document.getElementById('nbp').value.trim();
    const photo = document.getElementById('photo').files[0];

    const errors = [];

    // Fonction pour afficher les erreurs sous chaque champ avec animation
    function showError(fieldId, errorMessage) {
        const field = document.getElementById(fieldId);
        const errorDiv = field.nextElementSibling; // Sélectionne le div d'erreur sous chaque champ
        if (errorDiv && errorDiv.classList.contains('text-danger')) {
            errorDiv.innerHTML = errorMessage;
            errorDiv.classList.add('fade-in');
        }
    }

    // Fonction pour vérifier si un titre est déjà présent dans la base de données (exemple côté client)
    function checkTitleUnique(titre) {
        const existingTitles = ["Titre1", "Titre2", "Titre3"]; // Exemples, remplacez par une vérification serveur
        return existingTitles.includes(titre);
    }

    // Vérification des champs
    if (titre === '') {
        errors.push("Le titre est vide");
        showError('titre', '⚠️ Le titre est vide');
    } else if (/\d/.test(titre)) {
        errors.push("Le titre ne doit pas contenir de chiffres.");
        showError('titre', '⚠️ Le titre ne doit pas contenir de chiffres.');
    } else if (/[^a-zA-Z\s]/.test(titre)) {
        errors.push("Le titre ne doit pas contenir de caractères spéciaux.");
        showError('titre', '⚠️ Le nom du guide ne doit pas contenir de caractères spéciaux.');
    }

    if (guide === '') {
        errors.push("Le nom du guide est vide");
        showError('guide', '⚠️ Le nom du guide est vide');
    } else if (/\d/.test(guide)) {
        errors.push("Le nom du guide ne doit pas contenir de chiffres.");
        showError('guide', '⚠️ Le nom du guide ne doit pas contenir de chiffres.');
    } else if (/[^a-zA-Z\s]/.test(guide)) {
        errors.push("Le nom du guide ne doit pas contenir de caractères spéciaux.");
        showError('guide', '⚠️ Le nom du guide ne doit pas contenir de caractères spéciaux.');
    }

    if (description === '') {
        errors.push("La description est vide.");
        showError('description', '⚠️ La description est vide.');
    }

    if (duree === '') {
        errors.push("La durée est vide.");
        showError('duree', '⚠️ La durée est vide.');
    } else if (isNaN(duree) || duree <= 0) {
        errors.push("La durée doit être un nombre positif.");
        showError('duree', '⚠️ La durée doit être un nombre positif.');
    }

    if (type === '') {
        errors.push("Le type est vide");
        showError('type', '⚠️ Le type est vide');
    }

    if (prix === '') {
        errors.push("Le prix est vide");
        showError('prix', '⚠️ Le prix est vide');
    } else if (isNaN(prix) || prix <= 0) {
        errors.push("Le prix doit être un nombre positif.");
        showError('prix', '⚠️ Le prix doit être un nombre positif.');
    }

    if (nbp === '') {
        errors.push("Le nombre de participants est vide");
        showError('nbp', '⚠️ Le nombre de participants est vide');
    } else if (isNaN(nbp) || nbp <= 0) {
        errors.push("Le nombre de participants doit être un nombre positif.");
        showError('nbp', '⚠️ Le nombre de participants doit être un nombre positif.');
    }

    if (!photo) {
        errors.push("La photo est vide.");
        showError('photo', '⚠️ La photo est vide.');
    } else if (!/\.(jpg|jpeg|png)$/i.test(photo.name)) {
        errors.push("Le fichier photo doit être au format JPG, JPEG ou PNG.");
        showError('photo', '⚠️ Le fichier photo doit être au format JPG, JPEG ou PNG.');
    }

    if (errors.length > 0) {
        return; // Empêche l'envoi du formulaire si des erreurs existent
    }

    // Si tout est correct, soumettre le formulaire et afficher un message de succès
    // Exemple de message de succès
    alert("L'activité a été enregistrée avec succès !");
    this.submit();
});


    </script>

</body>
</html>
