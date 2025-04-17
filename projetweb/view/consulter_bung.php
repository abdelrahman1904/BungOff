<?php
// Logic PHP avant tout contenu HTML
require_once '../controller/bungalowC.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $capacite = $_POST['capacite'];
    $prix_nuit = $_POST['prix_nuit'];
    $localisation = $_POST['localisation'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    // Traiter l'upload d'image si elle est modifiée
    if (!empty($image)) {
        move_uploaded_file($_FILES['image']['tmp_name'], 'frontoffice/image_video1/' . $image);
    } else {
        // Utiliser l'image actuelle si elle n'a pas été changée
        $image = $_POST['old_image'];
    }

    // Appeler la méthode pour mettre à jour les données du bungalow
    $bungalowC = new BungalowC();
    $bungalowC->modifierBungalow($id, $nom, $capacite, $prix_nuit, $localisation, $type, $description, $image);

    // Rediriger vers la page de consultation après la mise à jour
    header("Location: consulter_bung.php");
    exit();
}
// Vérifie si un paramètre "supprimer" est présent dans l'URL
if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $bungalowC = new BungalowC();

    // Appel de la méthode de suppression
    $bungalowC->supprimerBungalow($id);

    header("Location: consulter_bung.php");
    exit();
}


?>


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter Bungalows - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="back.css">
    <link rel="stylesheet" href="consulter_bung.css">
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
        <a href="activity.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
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
        <div class="bungalow-header">
            <a href="newback_bung.html" class="add-activity-link">
                ⬅ Retour
            </a>
        </div>
        <div class="bungalow-container">
            <div class="bungalow-header">
                <h1><i class="fas fa-home"></i> Liste des Bungalows</h1>
            </div>
            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
    <div class="alert alert-success text-center" role="alert">
        ✅ Le bungalow a été supprimé avec succès !
    </div>
<?php endif; ?>
            <div class="table-responsive">
            <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>IDB</th>
                            <th>Nom</th>
                            <th>Capacité</th>
                            <th>Prix/nuit</th>
                            <th>Localisation</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         $BungalowC = new BungalowC();
                         $listeBungalows = $BungalowC->afficherBungalows();
                        
                        
                        if (!empty($listeBungalows)) {
                            foreach ($listeBungalows as $bungalow) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($bungalow['IDB']) . '</td>';
                                echo '<td>' . htmlspecialchars($bungalow['nom']) . '</td>';
                                echo '<td>' . htmlspecialchars($bungalow['capacite']) . '</td>';
                                echo '<td>' . htmlspecialchars($bungalow['prix_nuit']) . ' DT</td>';
                                echo '<td>' . htmlspecialchars($bungalow['localisation']) . '</td>';
                                echo '<td><span class="type-badge ' . strtolower($bungalow['type']) . '">' . htmlspecialchars($bungalow['type']) . '</span></td>';
                                echo '<td class="description-cell" title="' . htmlspecialchars($bungalow['description']) . '">' . htmlspecialchars($bungalow['description']) . '</td>';
                                echo '<td><img src="frontoffice/image_video1/' . htmlspecialchars($bungalow['image']) . '" alt="Bungalow" width="80" class="img-thumbnail"></td>';
                                
                                // Correction ici : Fermeture correcte du premier <td> de la modification
                                // Action buttons: Edit and Delete
                                
    echo '<td class="actions-cell">
    <a href="modifier_bung.php?id=' . htmlspecialchars($bungalow['IDB']) . '" class="edit-btn" title="Modifier">
        <button><i class="fas fa-edit"></i> Modifier</button>
    </a>
    <a href="consulter_bung.php?supprimer=' . htmlspecialchars($bungalow['IDB']) . '" 
       class="delete-btn" 
       onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer le bungalow ' . htmlspecialchars($bungalow['IDB']) . ' ?\')">
        <button><i class="fas fa-trash-alt"></i> Supprimer</button>
    </a>
</td>';



echo '</tr>';
}
} else {
echo '<tr><td colspan="9" class="text-center">Aucun bungalow trouvé</td></tr>';
}
?>
        </tbody>
    </table>
</div>

<script>
    function confirmDelete() {
        return confirm("Êtes-vous sûr de vouloir supprimer ce bungalow ?");
    }
</script>

    <script>
        // Toggle sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.content').classList.toggle('collapsed-content');
        });
    </script>
</body>
</html>
