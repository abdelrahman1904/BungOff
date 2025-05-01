<?php
// Connexion à la base de données
require_once '../models/config.php'; // Mets ici ton fichier de connexion si besoin

// Vérifier si l'ID de la planification est passé
if (!isset($_GET['id'])) {
    die('ID de planification manquant.');
}

$idp = $_GET['id'];

// Préparer la requête pour récupérer les participants avec les infos planification
try {
    $pdo = config::getConnexion(); // ta connexion

    $query = $pdo->prepare("
    SELECT 
        i.user_id,
        u.fullname,
        u.email,
        p.*
    FROM inscription i
    JOIN userlist u ON i.user_id = u.id
    JOIN planification p ON i.IDP = p.IDP
");
$query->execute();
$participants = $query->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    die('Erreur de base de données : ' . $e->getMessage());
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
    <link rel="stylesheet" href="consulter_act.css">
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
        <a href="new_act.html"><i class="fas fa-campground"></i> Activités</a>
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
            <a href="consulter_plan.php" class="add-activity-link">
                ⬅ Retour à planification
            </a>
          </div>
        <div class="activities-container">
        <div class="activities-header">
                <h1><i class="fas fa-list-alt"></i> Liste des Participants</h1>
                <div class="table-footer">
                <div class="export-btns">
                    <a href="export_pdf.php?id=<?= $idp ?>" class="export-btn" target="_blank">
                        <i class="fas fa-file-pdf"></i> exporter en PDF
                    </a>

                </div>
            </div>
            </div>
            
            <div class="table-responsive">
            <table class="table align-middle table-striped table-bordered shadow rounded" style="overflow: hidden; background-color: #f8f9fa;">
                    <thead style="background: linear-gradient(90deg, #00B4DB, #0083B0); color: white;">
                        <tr>
                        <th>ID User</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Activité</th>
                        <th>Lieu</th>
                        <th>Date</th>
                        <th>Heure Début</th>
                        <th>Heure Fin</th>
                        </tr>
                    </thead>
                    <tbody>
            <?php foreach ($participants as $participant): ?>
                <tr>
                    <td><?= htmlspecialchars($participant['user_id']) ?></td>
                    <td><?= htmlspecialchars($participant['fullname']) ?></td>
                    <td><?= htmlspecialchars($participant['email']) ?></td>
                    <td><?= htmlspecialchars($participant['nom_activite']) ?></td>
                    <td><?= htmlspecialchars($participant['lieu']) ?></td>
                    <td><?= htmlspecialchars($participant['date']) ?></td>
                    <td><?= htmlspecialchars($participant['heure_debut']) ?></td>
                    <td><?= htmlspecialchars($participant['heure_fin']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
                </table>
            </div>

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
