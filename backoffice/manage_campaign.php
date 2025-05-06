<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../Controllers/controller.php';
require_once '../lib/pdf/fpdf.php'; // Inclure FPDF

$controller = new CompagneController();
$compagnes = $controller->handleRequest();
$editCompagne = null;

// Action pour générer le PDF
if (isset($_GET['action']) && $_GET['action'] == 'generate_pdf') {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Liste des Campagnes', 0, 1, 'C');
    $pdf->Ln(10);

    // En-têtes du tableau
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(20, 10, 'ID', 1);
    $pdf->Cell(40, 10, 'Nom', 1);
    $pdf->Cell(60, 10, 'Description', 1);
    $pdf->Cell(30, 10, 'Date Debut', 1);
    $pdf->Cell(30, 10, 'Date Fin', 1);
    $pdf->Ln();

    // Données des campagnes
    $pdf->SetFont('Arial', '', 12);
    foreach ($compagnes as $compagne) {
        $pdf->Cell(20, 10, $compagne['id'], 1);
        $pdf->Cell(40, 10, $compagne['nom'], 1);
        $pdf->Cell(60, 10, substr($compagne['description'], 0, 30) . '...', 1); // Limiter la longueur
        $pdf->Cell(30, 10, $compagne['date_debut'], 1);
        $pdf->Cell(30, 10, $compagne['date_fin'], 1);
        $pdf->Ln();
    }

    $pdf->Output('D', 'campagnes.pdf'); // Télécharge le PDF
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $editCompagne = $controller->getCompagne($_GET['id']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Campagnes - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Barre en haut -->
    <div class="top-bar">
        <div class="logo">Bung<span class="off">OFF</span></div>
        <div class="right-icons">
            <form class="search-form d-inline-flex" action="manage_campaign.php" method="GET">
                <input type="text" class="form-control" name="search" placeholder="Rechercher par nom..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
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
        <a href="index.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="#"><i class="fas fa-user"></i> Utilisateurs</a>
        <a href="#"><i class="fas fa-home"></i> Bungalows</a>
        <a href="#"><i class="fas fa-campground"></i> Activités</a>
        <a href="btransport.html"><i class="fas fa-car"></i> Transports</a>
        <a href="promotion.php"><i class="fas fa-credit-card"></i> Promotions</a>
        <a href="#"><i class="fas fa-star"></i> Avis</a>
        <div class="logout">
            <a href="#"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="content">
        <h1>Gestion des Campagnes</h1>

        <!-- Messages de succès ou d'erreur -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <!-- Formulaire pour créer/modifier une campagne -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?php echo $editCompagne ? 'Modifier la Campagne' : 'Créer une Nouvelle Campagne'; ?></h5>
                <form action="manage_campaign.php?action=<?php echo $editCompagne ? 'edit&id=' . $editCompagne['id'] : 'create'; ?>" method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $editCompagne ? htmlspecialchars($editCompagne['nom']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" required><?php echo $editCompagne ? htmlspecialchars($editCompagne['description']) : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="date_debut" class="form-label">Date de Début</label>
                        <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?php echo $editCompagne ? $editCompagne['date_debut'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_fin" class="form-label">Date de Fin</label>
                        <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?php echo $editCompagne ? $editCompagne['date_fin'] : ''; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo $editCompagne ? 'Mettre à jour' : 'Créer'; ?></button>
                </form>
            </div>
        </div>

        <!-- Bouton pour générer le PDF -->
        <div class="mb-4">
            <a href="manage_campaign.php?action=generate_pdf" class="btn btn-success"><i class="fas fa-file-pdf"></i> Générer PDF</a>
        </div>

        <!-- Tableau des campagnes -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Liste des Campagnes</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><a href="?sort=id&order=<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'id' && isset($_GET['order']) && $_GET['order'] == 'asc') ? 'desc' : 'asc'; ?>">ID</a></th>
                            <th><a href="?sort=nom&order=<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'nom' && isset($_GET['order']) && $_GET['order'] == 'asc') ? 'desc' : 'asc'; ?>">Nom</a></th>
                            <th>Description</th>
                            <th><a href="?sort=date_debut&order=<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date_debut' && isset($_GET['order']) && $_GET['order'] == 'asc') ? 'desc' : 'asc'; ?>">Date Début</a></th>
                            <th><a href="?sort=date_fin&order=<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date_fin' && isset($_GET['order']) && $_GET['order'] == 'asc') ? 'desc' : 'asc'; ?>">Date Fin</a></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($compagnes as $compagne): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($compagne['id']); ?></td>
                                <td><?php echo htmlspecialchars($compagne['nom']); ?></td>
                                <td><?php echo htmlspecialchars($compagne['description']); ?></td>
                                <td><?php echo htmlspecialchars($compagne['date_debut']); ?></td>
                                <td><?php echo htmlspecialchars($compagne['date_fin']); ?></td>
                                <td>
                                    <a href="manage_campaign.php?action=edit&id=<?php echo $compagne['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                                    <a href="manage_campaign.php?action=delete&id=<?php echo $compagne['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer cette campagne ?');"><i class="fas fa-trash"></i> Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="../assets/js/validation.js"></script>
</body>
</html>