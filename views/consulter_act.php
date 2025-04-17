<?php
// Logic PHP avant tout contenu HTML
require_once '../controllers/activiteC.php';

if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $activiteC = new ActiviteC();
    $activiteC->supprimerActivite($id);
    
    // Redirection après suppression
    header("Location: consulter_act.php");
    exit(); // Important pour arrêter l'exécution du script
}

// Maintenant, placez le reste du contenu HTML
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter Activités - Backoffice</title>
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
            <a href="new_act.html" class="add-activity-link">
                ⬅ Activité
            </a>
        </div>
        <div class="activities-container">
            <div class="activities-header">
                <h1><i class="fas fa-list-alt"></i> Liste des Activités</h1>
                <div class="header-actions">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Rechercher une activité...">
                        <button id="searchBtn"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="filters">
                <div class="filter-group">
                    <label for="sortSelect">Trier par :</label>
                    <select id="sortSelect" class="filter-select">
                        <option value="id">ID</option>
                        <option value="titre">Titre</option>
                        <option value="prix">Prix</option>
                        <option value="duree">Durée</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="typeFilter">Filtrer par type :</label>
                    <select id="typeFilter" class="filter-select">
                        <option value="all">Tous</option>
                        <option value="sport">Sport</option>
                        <option value="culture">Culture</option>
                        <option value="nature">Nature</option>
                        <option value="loisir">Loisir</option>
                    </select>
                </div>
                <button id="resetFilters" class="reset-btn">
                    <i class="fas fa-sync-alt"></i> Réinitialiser
                </button>
            </div>

            <div class="table-responsive">
                <table class="activities-table">
                    <thead>
                        <tr>
                            <th>IDP</th>
                            <th>Titre</th>
                            <th>Guide</th>
                            <th>Description</th>
                            <th>Durée</th>
                            <th>Type</th>
                            <th>Photo</th>
                            <th>Prix</th>
                            <th>nbp</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $activiteC = new ActiviteC();
                        $listeActivites = $activiteC->afficherActivites();
                        foreach ($listeActivites as $activite) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($activite['IDA']) . '</td>';
                            echo '<td>' . htmlspecialchars($activite['titre']) . '</td>';
                            echo '<td>' . htmlspecialchars($activite['guide']) . '</td>';
                            echo '<td class="description-cell">' . htmlspecialchars($activite['description']) . '</td>';
                            echo '<td>' . htmlspecialchars($activite['duree']) . '</td>';
                            echo '<td><span class="type-badge ' . strtolower($activite['type']) . '">' . htmlspecialchars($activite['type']) . '</span></td>';
                            echo '<td><img src="frontoffice/image/' . htmlspecialchars($activite['photo']) . '" alt="' . htmlspecialchars($activite['photo']) . '" width="100"></td>';
                            echo '<td>' . htmlspecialchars($activite['prix']) . ' DT</td>';
                            echo '<td>' . htmlspecialchars($activite['NBp']) . '</td>';
                            echo '<td class="actions-cell">
                            <!-- Bouton Modifier -->
                            <a href="modifier_act.php?id=' . htmlspecialchars($activite['IDA']) . '" 
                                class="edit-btn"
                                onclick="return confirmEdit();">
                                <i class="fas fa-edit"></i>
                            </a>
  
                            <!-- Bouton Supprimer -->
                            <a href="consulter_act.php?supprimer=' . htmlspecialchars($activite['IDA']) . '" 
                                class="delete-btn" 
                                onclick="return confirmDelete();">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>';
                            echo '</tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                <div class="export-btns">
                    <button class="export-btn"><i class="fas fa-file-excel"></i> Excel</button>
                    <button class="export-btn"><i class="fas fa-file-pdf"></i> PDF</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Êtes-vous sûr de vouloir supprimer cette activité ?");
        }
        
        function confirmEdit() {
            return confirm("Voulez-vous vraiment modifier cette activité ?");
        }
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.content').classList.toggle('collapsed-content');
        });
    </script>
</body>
</html>
