<?php
require_once '../models/activite.php';
require_once '../controllers/activiteC.php';

$titre = $_POST['titre'] ?? '';
$guide = $_POST['guide'] ?? '';
$description = $_POST['description'] ?? '';
$duree = $_POST['duree'] ?? '';
$type = $_POST['type'] ?? '';
$prix = $_POST['prix'] ?? '';
$nbp = $_POST['nbp'] ?? '';
$photoName = '';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($titre)) {
        $errors['titre'] = "Le titre est requis.";
    } elseif (preg_match('/\d/', $titre)) {
        $errors['titre'] = "Le titre ne doit pas contenir de chiffres.";
    } else {
        $activiteC = new ActiviteC();
        if ($activiteC->titreExiste($titre)) {
            $errors['titre'] = "Le titre de l'activité existe déjà.";
        }
    }

    if (empty($guide)) {
        $errors['guide'] = "Le guide est requis.";
    } elseif (preg_match('/\d/', $guide)) {
        $errors['guide'] = "Le nom du guide ne doit pas contenir de chiffres.";
    }

    if (empty($description)) {
        $errors['description'] = "La description est requise.";
    }

    if (empty($duree)) {
        $errors['duree'] = "La durée est requise.";
    } elseif (!is_numeric($duree) || $duree <= 0) {
        $errors['duree'] = "La durée doit être un nombre positif.";
    }

    if (empty($type)) {
        $errors['type'] = "Le type est requis.";
    }

    if (empty($prix)) {
        $errors['prix'] = "Le prix est requis.";
    } elseif (!is_numeric($prix) || $prix <= 0) {
        $errors['prix'] = "Le prix doit être un nombre positif.";
    }

    if (empty($nbp)) {
        $errors['nbp'] = "Le nombre de participants est requis.";
    } elseif (!is_numeric($nbp) || $nbp <= 0) {
        $errors['nbp'] = "Le nombre de participants doit être un nombre positif.";
    }

    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== 0) {
        $errors['photo'] = "Une photo valide est requise.";
    }

    if (empty($errors)) {
        $photoName = basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], '../image/' . $photoName);

        $activite = new Activite($titre, $guide, $description, $duree, $type, $prix, $photoName, $nbp);
        $activiteC->ajouterActivite($activite);
        header("Location: ajouter_act.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Activité - Backoffice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="activity.css">
    <link rel="stylesheet" href="ajouter_act.css">
</head>
<body>

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

<div class="content">
    <div class="activity-header">
        <a href="new_act.html" class="add-activity-link">⬅ Activité</a>
    </div>

    <div class="form-container">
        <div class="form-header">
            <h2><i class="fas fa-plus-circle"></i> Ajouter une nouvelle activité</h2>
            <p>Remplissez le formulaire pour créer une nouvelle activité</p>
        </div>

        <?php
// Votre code de traitement ici : validation, stockage des erreurs, etc.

// Affichage des erreurs si elles existent
            if (!empty($erreurs)) {
            echo '<div class="form-errors">';
            foreach ($erreurs as $err) {
                echo "<div class='error-item'><span class='error-icon'>⚠️</span><span class='error-message'>$err</span></div>";
            }
            echo '</div>';
            }
        ?>

        <form id="activityForm" action="ajouter_act.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" class="form-control" name="titre" id="titre" value="<?= htmlspecialchars($titre ?? '') ?>">
                <?php if (!empty($errors['titre'])) : ?>
                    <div class="text-danger"><?= $errors['titre'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="guide">Guide</label>
                <input type="text" class="form-control" name="guide" id="guide" value="<?= htmlspecialchars($guide ?? '') ?>">
                <?php if (!empty($errors['guide'])) : ?>
                    <div class="text-danger"><?= $errors['guide'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description"><?= htmlspecialchars($description ?? '') ?></textarea>
                <?php if (!empty($errors['description'])) : ?>
                    <div class="text-danger"><?= $errors['description'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="duree">Durée</label>
                <input type="text" class="form-control" name="duree" id="duree" value="<?= htmlspecialchars($duree ?? '') ?>">
                <?php if (!empty($errors['duree'])) : ?>
                    <div class="text-danger"><?= $errors['duree'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <select class="form-control" name="type" id="type">
                    <option value="">Sélectionnez un type</option>
                    <option value="sport" <?= ($type ?? '') === 'sport' ? 'selected' : '' ?>>Sportive</option>
                    <option value="Aquatique" <?= ($type ?? '') === 'Aquatique' ? 'selected' : '' ?>>Aquatique</option>
                    <option value="nature" <?= ($type ?? '') === 'nature' ? 'selected' : '' ?>>Nature</option>
                    <option value="loisir" <?= ($type ?? '') === 'loisir' ? 'selected' : '' ?>>Loisir</option>
                </select>
                <?php if (!empty($errors['type'])) : ?>
                    <div class="text-danger"><?= $errors['type'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="prix">Prix</label>
                <input type="text" class="form-control" name="prix" id="prix" value="<?= htmlspecialchars($prix ?? '') ?>">
                <?php if (!empty($errors['nbp'])) : ?>
                    <div class="text-danger"><?= $errors['nbp'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" class="form-control" name="photo" id="photo">
                <?php if (!empty($photo)) : ?>
                    <p>Image actuelle :</p>
                    <img src="image/<?= htmlspecialchars($photo) ?>" alt="Photo actuelle" style="max-width: 150px;">
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="nbp">Nombre de participants</label>
                <input type="text" class="form-control" name="nbp" id="nbp" value="<?= htmlspecialchars($nbp ?? '') ?>">
            </div>

            <div class="form-errors"></div>

            <button type="submit" class="btn-submit">Enregistrer l'activité</button>
        </form>
    </div>
</div>

<script>
let existingTitles = ['TitreExist1', 'TitreExist2']; // Exemple de titres existants (remplacer par un tableau dynamique de titres si nécessaire)

document.getElementById('activityForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Empêcher la soumission par défaut du formulaire

    // Réinitialisation de la variable isValid et du message d'erreur
    let isValid = true;
    let globalErrors = [];
    let errorContainer = document.querySelector('.form-errors');
    errorContainer.innerHTML = ''; // Vider les erreurs précédentes

    // Récupération des valeurs des champs
    const values = {
        titre: document.getElementById('titre').value.trim(),
        guide: document.getElementById('guide').value.trim(),
        description: document.getElementById('description').value.trim(),
        duree: document.getElementById('duree').value.trim(),
        type: document.getElementById('type').value.trim(),
        prix: document.getElementById('prix').value.trim(),
        nbp: document.getElementById('nbp').value.trim(),
        photo:document.getElementById('photo').value.trim(),
    };

    // Vérification si le titre existe déjà
    if (existingTitles.includes(values.titre)) {
        showError('titre', "Le titre de l'activité existe déjà.");
        isValid = false;
    }

    // Réinitialiser toutes les erreurs visibles
    const fields = ['titre', 'guide', 'description', 'duree', 'type', 'prix', 'nbp'];
    fields.forEach(field => {
        const parent = document.getElementById(field).parentElement;
        const error = parent.querySelector('.error-message');
        if (error) {
            error.textContent = ''; // Enlever le message d'erreur pour ce champ
        }
    });

    // Vérification des champs et ajout d'erreurs
    if (Object.values(values).every(v => v === '')) {
        globalErrors.push("Tous les champs doivent être remplis.");
        isValid = false;
    }

    // Vérification individuelle de chaque champ
    if (isNaN(values.duree) || Number(values.duree) <= 0) {
        showError('duree', "La durée doit être un nombre positif.");
        isValid = false;
    }

    if (isNaN(values.prix) || Number(values.prix) <= 0) {
        showError('prix', "Le prix doit être un nombre positif.");
        isValid = false;
    }

    if (isNaN(values.nbp) || Number(values.nbp) <= 0) {
        showError('nbp', "Le nombre de participants doit être un nombre positif.");
        isValid = false;
    }

    // Si des erreurs globales existent, les afficher
    if (globalErrors.length > 0) {
        globalErrors.forEach(msg => {
            const p = document.createElement('p');
            p.textContent = msg;
            p.style.color = 'red';
            errorContainer.appendChild(p);
        });
    }

    // Si tout est valide, soumettre le formulaire
    if (isValid) {
        // Ajouter le titre à la liste des titres existants (s'il n'existe pas déjà)
        existingTitles.push(values.titre);
        document.getElementById('activityForm').submit();
    } else {
        // Préremplir les champs avec les valeurs saisies en cas d'erreur
        // Préremplir les champs avec les valeurs saisies en cas d'erreur
            document.getElementById('titre').value = values.titre;
            document.getElementById('guide').value = values.guide;
            document.getElementById('description').value = values.description;
            document.getElementById('duree').value = values.duree;
            document.getElementById('type').value = values.type; // Réafficher l'option sélectionnée
            document.getElementById('prix').value = values.prix;
            document.getElementById('nbp').value = values.nbp;
            document.getElementById('photo').value=values.photo;
    }

    // Fonction pour afficher un message d'erreur spécifique à un champ
    function showError(fieldId, message) {
        const parent = document.getElementById(fieldId).parentElement;
        let errorDiv = parent.querySelector('.error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.classList.add('error-message');
            errorDiv.style.color = 'red';
            parent.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }
});

document.getElementById('toggleSidebar').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.content').classList.toggle('collapsed-content');
        });
</script>


</body>
</html>
