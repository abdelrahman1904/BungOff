<?php
require_once '../../controller/bungalowC.php';
// Logic PHP avant tout contenu HTML

// Vérification de la méthode d'envoi du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        $image = $_POST['old_image'];
    }

    $bungalowC = new BungalowC();
    $bungalowC->modifierBungalow($id, $nom, $capacite, $prix_nuit, $localisation, $type, $description, $image);
    header("Location: consulter_bung.php");
    exit();
}

if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $bungalowC = new BungalowC();
    $bungalowC->supprimerBungalow($id);
    header("Location: consulter_bung.php");
    exit();
}

$bungalowC = new BungalowC();

// Gestion de la recherche
$critere = $_GET['critere'] ?? null;
$valeur = $_GET['valeur'] ?? null;

// Gestion du tri
$tri_colonne = $_GET['tri_colonne'] ?? null;
$tri_ordre = $_GET['tri_ordre'] ?? null;

if (!empty($critere) && !empty($valeur)) {
    $bungalows = $bungalowC->rechercherBungalows($critere, $valeur);
} elseif (!empty($tri_colonne) && !empty($tri_ordre)) {
    $bungalows = $bungalowC->trierBungalows($tri_colonne, $tri_ordre);
} else {
    $bungalows = $bungalowC->afficherBungalows();
}

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
    <style>
        .bungalow-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: #f8f9fa;
            margin-bottom: 1.5rem;
            border-radius: 0.25rem;
        }

        .bungalow-header h1 {
            color: #0d6efd;
            margin-bottom: 0;
        }

        .search-sort-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            background-color: #fff;
            padding: 1rem;
            border-radius: 0.25rem;
            align-items: center;
        }

        .search-group, .sort-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .sort-group label, .search-group label {
            margin-bottom: 0;
            white-space: nowrap;
        }

        .search-actions {
            display: flex;
            gap: 0.5rem;
            margin-left: auto;
        }

        .sort-container { /* Nouveau conteneur pour "Trier par" et "Ordre" */
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .sort-button-container {
            display: flex;
            justify-content: flex-start;
            margin-top: 0.75rem;
            flex-basis: 100%; /* Prend toute la largeur par défaut */
        }

        @media (min-width: 768px) {
            .sort-button-container {
                flex-basis: auto;
                margin-top: 0;
                margin-left: 1rem;
            }
            .search-actions {
                margin-left: 1rem;
            }
            .sort-container { /* Permet au conteneur de tri de rester sur la même ligne sur les écrans plus grands */
                flex-basis: auto;
            }
        }

        .table-responsive {
            background-color: #fff;
            border-radius: 0.25rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table-dark th {
            background-color: #0d6efd;
            color: white;
            border-color: #0a58ca;
            cursor: pointer;
        }

        .table-dark th i {
            margin-left: 5px;
        }

        .table-bordered {
            border: 1px solid #28a745;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #28a745;
        }

        .btn-modifier {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }

        .btn-modifier:hover {
            background-color: #138496;
            border-color: #117a8b;
            color: white;
        }

        .btn-supprimer {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .btn-supprimer:hover {
            background-color: #218838;
            border-color: #1e7e34;
            color: white;
        }
    </style>
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
        <a href="newback_bung.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
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
        <div class="bungalow-header">
            <a href="newback_bung.html" class="add-activity-link">
                ⬅ Retour
            </a>
            <h1><i class="fas fa-home"></i> Liste des Bungalows</h1>
        </div>

        <form method="get" class="p-4 rounded shadow bg-white mb-4">
            <div class="search-sort-container">
                <div class="search-group">
                    <label for="critere" class="form-label">Rechercher par :</label>
                    <select name="critere" id="critere" class="form-select">
                        <option value="">-- Choisir un critère --</option>
                        <option value="nom" <?= $critere == 'nom' ? 'selected' : '' ?>>Nom</option>
                        <option value="localisation" <?= $critere == 'localisation' ? 'selected' : '' ?>>Localisation</option>
                        <option value="type" <?= $critere == 'type' ? 'selected' : '' ?>>Type</option>
                    </select>
                </div>

                <div class="search-group">
                    <label for="valeur" class="form-label">Mot-clé :</label>
                    <input type="text" name="valeur" id="valeur" value="<?= htmlspecialchars($valeur ?? '') ?>" class="form-control">
                </div>

                <div class="search-actions">
                    <button type="submit" class="btn btn-primary">🔍 Rechercher</button>
                    <a href="consulter_bung.php" class="btn btn-secondary">❌ Réinitialiser</a>
                </div>

                <div class="sort-container">
                    <div class="sort-group">
                        <label for="tri_colonne" class="form-label">Trier par :</label>
                        <select name="tri_colonne" id="tri_colonne" class="form-select">
                            <option value="">-- Critère de tri --</option>
                            <option value="nom" <?= $tri_colonne == 'nom' ? 'selected' : '' ?>>Nom</option>
                            <option value="capacite" <?= $tri_colonne == 'capacite' ? 'selected' : '' ?>>Capacité</option>
                            <option value="prix_nuit" <?= $tri_colonne == 'prix_nuit' ? 'selected' : '' ?>>Prix</option>
                        </select>
                    </div>

                    <div class="sort-group">
                        <label for="tri_ordre" class="form-label">Ordre :</label>
                        <select name="tri_ordre" id="tri_ordre" class="form-select">
                            <option value="">--</option>
                            <option value="ASC" <?= $tri_ordre == 'ASC' ? 'selected' : '' ?>>↑</option>
                            <option value="DESC" <?= $tri_ordre == 'DESC' ? 'selected' : '' ?>>↓</option>
                        </select>
                    </div>
                </div>

                <div class="sort-button-container">
                    <button type="submit" class="btn btn-primary">Trier</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
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
                    <?php foreach ($bungalows as $bungalow): ?>
                        <tr>
                            <td><?= htmlspecialchars($bungalow['nom']) ?></td>
                            <td><?= htmlspecialchars($bungalow['capacite']) ?> personnes</td>
                            <td><?= htmlspecialchars($bungalow['prix_nuit']) ?> DT</td>
                            <td><?= htmlspecialchars($bungalow['localisation']) ?></td>
                            <td><?= htmlspecialchars($bungalow['type']) ?></td>
                            <td><?= htmlspecialchars($bungalow['description']) ?></td>
                            <td><img src="frontoffice/image_video1/<?= htmlspecialchars($bungalow['image']) ?>" alt="Image Bungalow" style="width: 100px; height: auto;"></td>
                            <td>
                                <a href="modifier_bung.php?id=<?= htmlspecialchars($bungalow['IDB']) ?>" class="btn btn-sm btn-modifier">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <a href="?supprimer=<?= htmlspecialchars($bungalow['IDB']) ?>" class="btn btn-sm btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bungalow ?');">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
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