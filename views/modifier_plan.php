<?php
require_once '../controllers/planificationC.php';
require_once '../models/planification.php';
require_once '../controllers/activiteC.php';
$activiteC = new ActiviteC();
$listeActivites = $activiteC->afficherActivites();

$planC = new PlanificationC();

// Vérifier l'ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $plan = $planC->recupererPlanification($id);
} else {
    header("Location: consulter_plan.php");
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activite = $_POST['nom_activite'] ?? '';
    $lieu = $_POST['lieu'] ?? '';
    $date = $_POST['date'] ?? '';
    $heureDebut = $_POST['heure_debut'] ?? '';
    $heureFin = $_POST['heure_fin'] ?? '';
    $capacite = $_POST['capacite'] ?? '';

    $p = new Planification($lieu, $date, $heureDebut, $heureFin, $capacite, $activite);
    $planC->modifierPlanification($p, $id);

    header("Location: consulter_plan.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Planification - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="ajouter_plan.css">
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
        <a href="new_act.php"><i class="fas fa-campground"></i> Activités</a>
        <a href="#"><i class="fas fa-calendar-alt"></i> Planification</a>
        <a href="#"><i class="fas fa-credit-card"></i> Paiement</a>
        <a href="#"><i class="fas fa-star"></i> Avis</a>
        <div class="logout">
            <a href="#"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="content">
        <div class="activity-header">
            <a href="consulter_plan.php" class="add-activity-link">⬅ Retour</a>
        </div>
        <div class="form-container">
            <div class="form-header">
                <h2><i class="fas fa-calendar-plus"></i> Modifier une planification</h2>
                <p>Remplissez le formulaire pour modifier une planification</p>
            </div>

            <form id="planificationForm" action="modifier_plan.php?id=<?= $id ?>" method="POST">
            <div class="form-group">
    <label>Activité</label>
    <select class="form-control" id="nom_activite" name="nom_activite">
        <?php foreach ($listeActivites as $activite): ?>
            <option value="<?= htmlspecialchars($activite['titre']) ?>" <?= ($plan['nom_activite'] == $activite['titre']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($activite['titre']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

    <div class="form-group">
        <label>Lieu</label>
        <input type="text" class="form-control" id="lieu" name="lieu" value="<?= htmlspecialchars($plan['lieu']) ?>">
    </div>
    <div class="form-group">
        <label>Date</label>
        <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($plan['date']) ?>">
    </div>
    <div class="form-group">
        <label>Heure de début</label>
        <input type="time" class="form-control" id="heure_debut" name="heure_debut" value="<?= htmlspecialchars($plan['heure_debut']) ?>">
    </div>
    <div class="form-group">
        <label>Heure de fin</label>
        <input type="time" class="form-control" id="heure_fin" name="heure_fin" value="<?= htmlspecialchars($plan['heure_fin']) ?>">
    </div>
    <div class="form-group">
        <label>Capacité</label>
        <input type="number" class="form-control" id="capacite" name="capacite" value="<?= htmlspecialchars($plan['capacite']) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>


        </div>
    </div>

    <script>
    document.getElementById('toggleSidebar').addEventListener('click', function () {
        document.querySelector('.sidebar').classList.toggle('collapsed');
        document.querySelector('.content').classList.toggle('collapsed-content');
    });

    const formGroups = document.querySelectorAll('.form-group');
    formGroups.forEach((group, index) => {
        group.style.animationDelay = `${0.2 + index * 0.1}s`;

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

    // Ton contrôle de saisie personnalisé
    document.getElementById('planificationForm').addEventListener('submit', function(event) {
        const nomActivite = document.getElementById('nom_activite').value.trim();
        const lieu = document.getElementById('lieu').value.trim();
        const date = document.getElementById('date').value.trim();
        const heureDebut = document.getElementById('heure_debut').value.trim();
        const heureFin = document.getElementById('heure_fin').value.trim();
        const capacite = document.getElementById('capacite').value.trim();

        if (!nomActivite || !lieu || !date || !heureDebut || !heureFin || !capacite) {
            alert('Veuillez remplir tous les champs !');
            event.preventDefault();
            return;
        }

        if (isNaN(capacite) || capacite <= 0) {
            alert('La capacité doit être un nombre supérieur à 0.');
            event.preventDefault();
            return;
        }

        alert('Modification réussie !');
    });
</script>

</body>
</html>
