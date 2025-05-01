<?php
require_once '../controllers/activiteC.php';
require_once '../models/activite.php';

$activiteC = new ActiviteC();

// Vérifie si l'ID est fourni
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $activite = $activiteC->recupererActivite($id);
} else {
    header("Location: consulter_act.php");
    exit();
}

// Traitement du formulaire sans validation PHP (cette partie est supprimée)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $guide = $_POST['guide'] ?? '';
    $description = $_POST['description'] ?? '';
    $duree = $_POST['duree'] ?? '';
    $type = $_POST['type'] ?? '';
    $prix = $_POST['prix'] ?? '';
    $nbp = $_POST['nbp'] ?? '';

    // Gestion de la photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $photo = $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], 'frontoffice/image/' . $photo);
    } else {
        $photo = $activite['photo']; // ancienne photo
    }

    $a = new Activite($titre, $guide, $description, $duree, $type, $prix, $photo, $nbp);
    $activiteC->modifierActivite($titre, $guide, $description, $duree, $type, $prix, $photo, $nbp, $id);

    header("Location: consulter_act.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Activité - Backoffice</title>
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
        <a href="new_act.php"><i class="fas fa-campground"></i> Activités</a>
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
            <a href="consulter_act.php" class="add-activity-link">
                ⬅ consulter_activité
            </a>
        </div>
        <div class="form-container">
            <div class="form-header">
                <h2><i class="fas fa-edit"></i> Modifier une activité</h2>
                <p>Modifiez les champs ci-dessous puis validez</p>
            </div>

            <form id="activityForm" action="modifier_act.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" class="form-control" name="titre" id="titre" value="<?= htmlspecialchars($activite['titre']) ?>" required>
                </div>
            
                <div class="form-group">
                    <label for="guide">Guide</label>
                    <input type="text" class="form-control" name="guide" id="guide" value="<?= htmlspecialchars($activite['guide']) ?>" required>
                </div>
            
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description" required><?= htmlspecialchars($activite['description']) ?></textarea>
                </div>
            
                <div class="form-group">
                    <label for="duree">Durée</label>
                    <input type="text" class="form-control" name="duree" id="duree" value="<?= htmlspecialchars($activite['duree']) ?>" required>
                </div>
            
                <div class="form-group">
                    <label for="type">Type</label>
                    <select class="form-control" name="type" id="type" required>
                        <option value="">Sélectionnez un type</option>
                        <option value="sport" <?= ($activite['type'] === 'sport') ? 'selected' : '' ?>>Sportive</option>
                        <option value="Aquatique" <?= ($activite['type'] === 'Aquatique') ? 'selected' : '' ?>>Aquatique</option>
                        <option value="nature" <?= ($activite['type'] === 'nature') ? 'selected' : '' ?>>Nature</option>
                        <option value="loisir" <?= ($activite['type'] === 'loisir') ? 'selected' : '' ?>>Loisir</option>
                    </select>
                </div>
            
                <div class="form-group">
                    <label for="prix">Prix</label>
                    <input type="text" class="form-control" name="prix" id="prix" value="<?= htmlspecialchars($activite['prix']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="photo">Photo</label>
                    <input type="file" class="form-control" name="photo" id="photo">
                        <?php if (!empty($activite['photo'])): ?>
                            <p>Photo actuelle :</p>
                            <img src="frontoffice/image/<?= htmlspecialchars($activite['photo']) ?>" alt="Photo actuelle" style="max-width: 200px; border-radius: 8px; box-shadow: 0 0 5px rgba(0,0,0,0.3);">
                        <?php else: ?>
                            <p>Aucune photo enregistrée.</p>
                        <?php endif; ?>
                </div>

            
                <div class="form-group">
                    <label for="nbp">Nombre de participants</label>
                    <input type="text" class="form-control" name="nbp" id="nbp" value="<?= htmlspecialchars($activite['NBp']) ?>" required>
                </div>
            
                <button type="submit" class="btn-submit">Modifier l'activité</button>
            </form>
            
        </div>
    </div>

    <script>
document.getElementById('activityForm').addEventListener('submit', function (event) {
    event.preventDefault();
    let isValid = true;
    let globalErrors = [];

    const values = {
        titre: document.getElementById('titre').value.trim(),
        guide: document.getElementById('guide').value.trim(),
        description: document.getElementById('description').value.trim(),
        duree: document.getElementById('duree').value.trim(),
        type: document.getElementById('type').value.trim(),
        prix: document.getElementById('prix').value.trim(),
        nbp: document.getElementById('nbp').value.trim()
    };

    if (!values.titre || !values.guide || !values.description || !values.duree || !values.type || !values.prix || !values.nbp) {
        globalErrors.push("Tous les champs doivent être remplis.");
        isValid = false;
    }

    if (!/^\d+$/.test(values.duree)) {
        globalErrors.push("La durée doit être un nombre positif.");
        isValid = false;
    }

    if (!/^\d+$/.test(values.prix)) {
        globalErrors.push("Le prix doit être un nombre positif.");
        isValid = false;
    }

    if (!/^\d+$/.test(values.nbp)) {
        globalErrors.push("Le nombre de participants doit être un nombre positif.");
        isValid = false;
    }

    if (/\d/.test(values.titre)) {
        globalErrors.push("Le titre ne doit pas contenir de chiffres.");
        isValid = false;
    }

    if (/\d/.test(values.guide)) {
        globalErrors.push("Le nom du guide ne doit pas contenir de chiffres.");
        isValid = false;
    }
    
    if (!isValid) {
        globalErrors.forEach(err => {
            alert(err);
        });
    } else {
        alert('Modification réussie !');
        this.submit();
    }
});
    </script>

</body>
</html>
