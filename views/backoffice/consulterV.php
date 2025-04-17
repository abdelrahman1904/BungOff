<?php
require_once '../../controllers/vehiculeC.php'; 

$vehiculeC = new VehiculeC();

// Récupérer les valeurs des filtres et de la recherche
$search = $_GET['search'] ?? null;
$sortBy = $_GET['sort'] ?? null;
$filterDispo = $_GET['filter_dispo'] ?? null;

// Choisir quelle action appliquer seule
if ($search !== null && $search !== '') {
    // Recherche par matricule
    $listeVehicules = $vehiculeC->rechercherVehiculesParMatricule($search);
} elseif ($sortBy !== null) {
    // Trier par un critère spécifique
    $listeVehicules = $vehiculeC->trierVehicules($sortBy);
} elseif ($filterDispo !== null && in_array($filterDispo, ['0', '1'])) {
    // Filtrer par disponibilité
    $listeVehicules = $vehiculeC->filtrerVehiculesParDispo($filterDispo);
} else {
    // Afficher tous les véhicules si aucun filtre n'est appliqué
    $listeVehicules = $vehiculeC->afficherVehicules();
    
}

$vehiculeC = new VehiculeC();

// 1) Suppression
if (isset($_GET['supprimer'])) {
    $vehiculeC->supprimerVehicule($_GET['supprimer']);
    header("Location: consulterV.php");
    exit();
}

// 2) Mise à jour
if (isset($_POST['update'])) {
    $id       = $_POST['id_vehicule'];
    $type     = $_POST['type'];
    $model    = $_POST['model'];
    $matricule= $_POST['matricule'];
    $capacite = $_POST['capacite'];
    $dispo    = $_POST['dispo'];
    // Crée un objet Vehicule et donne-lui l’ID
    $v = new Vehicule($type, $model, $matricule, $capacite, $dispo);
    $v->setIdVehicule($id);
    $vehiculeC->modifierVehicule($v, $id); 
    header("Location: consulterV.php");
    exit();
}

// 3) Détecte l’ID à éditer (via ?modifier=XX)
$modifyId = $_GET['modifier'] ?? null;


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Consulter Véhicules - Backoffice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="consulter_act.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="consulter.css">
    
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
    <a href="index.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="#"><i class="fas fa-user"></i> Utilisateurs</a>
    <a href="#"><i class="fas fa-home"></i> Bungalows</a>
    <a href="#"><i class="fas fa-campground"></i> Activités</a>
    <a href="btransport.html"><i class="fas fa-car"></i> Transports</a>
    <a href="#"><i class="fas fa-credit-card"></i> Paiement</a>
    <a href="#"><i class="fas fa-star"></i> Avis</a>
    <div class="logout">
        <a href="#"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a>
    </div>
</div>

<div class="content">
<div class="transport-header">
        <a href="btransport.html" class="add-transport-link">⬅ Transports</a>
    </div>

    <div class="transport-container">
        <div class="transport-header">
            <h1><i class="fas fa-car"></i> Liste des Véhicules</h1>
        </div>
<form method="GET" class="d-flex gap-3 mb-3 align-items-end">
    <div>
        <label>Rechercher Matricule :</label>
        <input type="text" name="search" class="form-control" 
               oninput="this.form.submit()" 
               value="<?= htmlspecialchars($search ?? '') ?>">
    </div>

    <div>
        <label>Trier par :</label>
        <select name="sort" class="form-select" onchange="this.form.submit()">
            <option value="">--</option>
            <option value="id_vehicule" <?= ($sortBy ?? '') === 'id_vehicule' ? 'selected' : '' ?>>ID</option>
            <option value="matricule" <?= ($sortBy ?? '') === 'matricule' ? 'selected' : '' ?>>Matricule</option>
            <option value="capacite" <?= ($sortBy ?? '') === 'capacite' ? 'selected' : '' ?>>Capacité</option>
            <option value="dispo" <?= ($sortBy ?? '') === 'dispo' ? 'selected' : '' ?>>Disponibilité</option>
        </select>
    </div>

    <!-- Filtrage par Disponibilité -->
    <div>
        <label>Filtrage par Disponibilité  :</label>
        <select name="filter_dispo" class="form-select" onchange="this.form.submit()">
            <option value="">--</option>
            <option value="1" <?= ($filterDispo ?? '') === '1' ? 'selected' : '' ?>>oui</option>
            <option value="0" <?= ($filterDispo ?? '') === '0' ? 'selected' : '' ?>>Non </option>
        </select>
    </div>

    <!-- Bouton pour réinitialiser les filtres -->
    <div>
        <button type="reset" class="btn btn-secondary" onclick="resetFilters()">Réinitialiser</button>
    </div>
</form>



        <div class="table-responsive">
            <table class="transport-table">
                <thead>
                    <tr>
                        <th>ID_vehicule</th>
                        <th>Type</th>
                        <th>model</th>
                        <th>Matricule</th>
                        <th>Capacité</th>
                        <th>Disponibilité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($listeVehicules as $v): ?>
    <tr>
        <?php if ($v['id_vehicule'] == $modifyId): ?>
            <!-- LIGNE EN MODE ÉDITION -->
            <form method="POST">
            <input type="hidden" name="id_vehicule" value="<?= $v['id_vehicule'] ?>">
            <td><?= $v['id_vehicule'] ?></td>
            <td><input name="type"      class="form-control" value="<?= htmlspecialchars($v['type']) ?>"></td>
            <td><input name="model"     class="form-control" value="<?= htmlspecialchars($v['model']) ?>"></td>
            <td><input name="matricule" class="form-control" value="<?= htmlspecialchars($v['matricule']) ?>"></td>
            <td><input name="capacite"  class="form-control" value="<?= htmlspecialchars($v['capacite']) ?>"></td>
            <td>
                <select name="dispo" class="form-select">
                    <option value="1" <?= $v['dispo'] ? 'selected' : '' ?>>Oui</option>
                    <option value="0" <?= !$v['dispo'] ? 'selected' : '' ?>>Non</option>
                </select>
            </td>
            <td class="actions-cell">
                <button type="submit" name="update" class="btn btn-sm btn-success">
                    <i class="fas fa-check"></i>
                </button>
                <a href="consulter_vehicules.php" class="btn btn-sm btn-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </td>
            </form>
        <?php else: ?>
            <!-- LIGNE NORMALE -->
            <td><?= htmlspecialchars($v['id_vehicule']) ?></td>
            <td><?= htmlspecialchars($v['type']) ?></td>
            <td><?= htmlspecialchars($v['model']) ?></td>
            <td><?= htmlspecialchars($v['matricule']) ?></td>
            <td><?= htmlspecialchars($v['capacite']) ?></td>
            <td><?= $v['dispo'] ? 'Oui' : 'Non' ?></td>
            <td class="actions-cell">
                <a href="consulterV.php?modifier=<?= $v['id_vehicule'] ?>" class="edit-btn" title="Modifier">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="consulterV.php?supprimer=<?= $v['id_vehicule'] ?>" class="delete-btn" onclick="return confirmDelete();" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Êtes-vous sûr de vouloir supprimer ce véhicule ?");
    }
    function resetFilters() {
        // Réinitialiser les valeurs de recherche, tri et filtre
        document.querySelector('input[name="search"]').value = '';
        document.querySelector('select[name="sort"]').value = '';
        document.querySelector('select[name="filter_dispo"]').value = '';
        // Soumettre le formulaire pour appliquer la réinitialisation
        document.querySelector('form').submit();
    }
    document.getElementById('toggleSidebar').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.content').classList.toggle('collapsed-content');
        });
</script>

</body>
</html>
