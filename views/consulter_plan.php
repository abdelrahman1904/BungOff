<?php
// Logic PHP avant tout contenu HTML
require_once '../controllers/PlanificationC.php';

if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $planificationC = new PlanificationC();
    $planificationC->supprimerPlanification($id);
    
    // Redirection après suppression
    header("Location: consulter_plan.php");
    exit(); // Important pour arrêter l'exécution du script
}

// Récupérer les données de la planification
$planificationC = new PlanificationC();
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $motCle = $_GET['search'];
    $listePlanifications = $planificationC->chercherParActivite($motCle);
} else {
    $listePlanifications = $planificationC->afficherPlanifications();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter Planification - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="activity.css">
    <link rel="stylesheet" href="consulter_plan.css">
</head>
<body>

    <!-- Barre en haut (identique) -->
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

    <!-- Barre latérale (identique) -->
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

    <!-- Contenu principal amélioré -->
    <div class="content">
        <div class="activity-header">
            <a href="new_act.php" class="add-activity-link">
                ⬅ Planification
            </a>
          </div>
        <div class="activities-container">
            <div class="activities-header">
                <h1><i class="fas fa-list-alt"></i> Liste des Planifications</h1>
                <div class="header-actions">
                    <div class="search-box">
                    <form method="GET" action="consulter_plan.php" class="search-form d-inline-flex">
    <input type="text" name="search" class="form-control" placeholder="Recherche par activité...">
    <button type="submit" class="btn btn-primary">
        
    </button>
</form>

                    </div>
                </div>
            </div>

            <div class="filters">
    <a href="consulter_plan.php" class="reset-btn">
        <i class="fas fa-sync-alt"></i> Réinitialiser
    </a>
</div>


            <div class="table-responsive">
                <table class="activities-table">
                    <thead>
                        <tr>
                            <th>ID Planification</th>
                            <th>Activité</th>
                            <th>Lieu</th>
                            <th>Date</th>
                            <th>Heure de début</th>
                            <th>Heure de fin</th>
                            <th>capacite</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- PHP pour afficher les lignes dynamiquement -->
                        <?php foreach ($listePlanifications as $planification): ?>
                            <tr>
                                <td><?= htmlspecialchars($planification['IDP']) ?></td>
                                <td><?= htmlspecialchars($planification['nom_activite']) ?></td>
                                <td><?= htmlspecialchars($planification['lieu']) ?></td>
                                <td><?= htmlspecialchars($planification['date']) ?></td>
                                <td><?= htmlspecialchars($planification['heure_debut']) ?></td>
                                <td><?= htmlspecialchars($planification['heure_fin']) ?></td>
                                <td><?= htmlspecialchars($planification['capacite']) ?></td>
                                <td class="actions-cell">
                               <!-- Bouton Modifier -->
                                <a href="modifier_plan.php?id=<?= $planification['IDP'] ?>" 
                                    class="edit-btn" 
                                    onclick="return confirmEdit();" 
                                    style="color: #3498db;" 
                                    onmouseover="this.style.color='#2ecc71';" 
                                    onmouseout="this.style.color='#3498db';">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Bouton Supprimer -->
                                <a href="consulter_plan.php?supprimer=<?= $planification['IDP'] ?>" 
                                    class="delete-btn" 
                                    onclick="return confirmDelete();" 
                                    style="color: #3498db;" 
                                    onmouseover="this.style.color='#e74c3c';" 
                                    onmouseout="this.style.color='#3498db';">
                                    <i class="fas fa-trash-alt"></i>
                                </a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
                    <a href="participants.php?id=<?= $planification['IDP'] ?>" class="export-btn">
                        <i class="fas fa-users"></i> Voir participants
                    </a>

                    <style>
                    .export-btn {
                        background-color: #e67e22;
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 30px;
                        text-decoration: none;
                        cursor: pointer;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        font-weight: bold;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
                        transition: background-color 0.3s, transform 0.2s;
                    }

                    .export-btn:hover {
                        background-color: #d35400;
                        transform: scale(1.05);
                        text-decoration: none;
                    }
                    </style>


        </div>
    </div>

    <script>
        // Script pour la sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.content').classList.toggle('collapsed-content');
        });
        function confirmEdit() {
        return confirm("Voulez-vous vraiment modifier cette planification");
    }

        function confirmDelete() {
            return confirm("Êtes-vous sûr de vouloir supprimer cette planification ?");
        }
        
    </script>
</body>
</html>
