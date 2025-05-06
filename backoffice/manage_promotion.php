<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../Controllers/promotion_controller.php';
require_once '../Controllers/controller.php';
require_once '../lib/pdf/fpdf.php'; // Inclure FPDF

$controller = new PromotionController();
$promotions = $controller->handleRequest();
$editPromotion = null;

// Action pour générer le PDF
if (isset($_GET['action']) && $_GET['action'] == 'generate_pdf') {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Liste des Promotions', 0, 1, 'C');
    $pdf->Ln(10);

    // En-têtes du tableau
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(15, 10, 'ID', 1);
    $pdf->Cell(30, 10, 'Titre', 1);
    $pdf->Cell(40, 10, 'Description', 1);
    $pdf->Cell(20, 10, 'Pourcentage', 1);
    $pdf->Cell(25, 10, 'Code Promo', 1);
    $pdf->Cell(25, 10, 'Date Debut', 1);
    $pdf->Cell(25, 10, 'Date Fin', 1);
    $pdf->Cell(30, 10, 'Campagne', 1);
    $pdf->Ln();

    // Données des promotions
    $pdf->SetFont('Arial', '', 12);
    foreach ($promotions as $promotion) {
        $pdf->Cell(15, 10, $promotion['idP'], 1);
        $pdf->Cell(30, 10, substr($promotion['titreP'], 0, 15) . '...', 1);
        $pdf->Cell(40, 10, substr($promotion['descriptionP'], 0, 20) . '...', 1);
        $pdf->Cell(20, 10, $promotion['pourcentage'] . '%', 1);
        $pdf->Cell(25, 10, $promotion['codePromo'] ?: 'N/A', 1);
        $pdf->Cell(25, 10, $promotion['date_debutP'], 1);
        $pdf->Cell(25, 10, $promotion['date_finP'], 1);
        $pdf->Cell(30, 10, substr($promotion['campaign_name'], 0, 15) . '...', 1);
        $pdf->Ln();
    }

    $pdf->Output('D', 'promotions.pdf'); // Télécharge le PDF
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['idP'])) {
    $editPromotion = $controller->getPromotion($_GET['idP']);
}

// Fetch all campaigns for the dropdown
$campaignController = new CompagneController();
$campaigns = $campaignController->handleRequest();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Promotions - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Barre en haut -->
    <div class="top-bar">
        <div class="logo">Bung<span class="off">OFF</span></div>
        <div class="right-icons">
            <form class="search-form d-inline-flex" action="manage_promotion.php" method="GET">
                <input type="text" class="form-control" name="search" placeholder="Rechercher par titre ou code..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
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
        <h1>Gestion des Promotions</h1>

        <!-- Messages de succès ou d'erreur -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <!-- Formulaire pour créer/modifier une promotion -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?php echo $editPromotion ? 'Modifier la Promotion' : 'Créer une Nouvelle Promotion'; ?></h5>
                <form action="manage_promotion.php?action=<?php echo $editPromotion ? 'edit&idP=' . $editPromotion['idP'] : 'create'; ?>" method="POST">
                    <div class="mb-3">
                        <label for="titreP" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="titreP" name="titreP" value="<?php echo $editPromotion ? htmlspecialchars($editPromotion['titreP']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descriptionP" class="form-label">Description</label>
                        <textarea class="form-control" id="descriptionP" name="descriptionP" required><?php echo $editPromotion ? htmlspecialchars($editPromotion['descriptionP']) : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="pourcentage" class="form-label">Pourcentage (%)</label>
                        <input type="number" class="form-control" id="pourcentage" name="pourcentage" value="<?php echo $editPromotion ? htmlspecialchars($editPromotion['pourcentage']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="codePromo" class="form-label">Code Promo</label>
                        <input type="text" class="form-control" id="codePromo" name="codePromo" value="<?php echo $editPromotion ? htmlspecialchars($editPromotion['codePromo']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="date_debutP" class="form-label">Date de Début</label>
                        <input type="date" class="form-control" id="date_debutP" name="date_debutP" value="<?php echo $editPromotion ? $editPromotion['date_debutP'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_finP" class="form-label">Date de Fin</label>
                        <input type="date" class="form-control" id="date_finP" name="date_finP" value="<?php echo $editPromotion ? $editPromotion['date_finP'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="idC" class="form-label">Campagne Associée</label>
                        <select class="form-control" id="idC" name="idC" required>
                            <option value="">Sélectionnez une campagne</option>
                            <?php foreach ($campaigns as $campaign): ?>
                                <option value="<?php echo $campaign['id']; ?>" <?php echo $editPromotion && $editPromotion['idC'] == $campaign['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($campaign['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo $editPromotion ? 'Mettre à jour' : 'Créer'; ?></button>
                </form>
            </div>
        </div>

        <!-- Bouton pour générer le PDF -->
        <div class="mb-4">
            <a href="manage_promotion.php?action=generate_pdf" class="btn btn-success"><i class="fas fa-file-pdf"></i> Générer PDF</a>
        </div>

        <!-- Tableau des promotions -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Liste des Promotions</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><a href="?sort=idP&order=<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'idP' && isset($_GET['order']) && $_GET['order'] == 'asc') ? 'desc' : 'asc'; ?>">ID</a></th>
                            <th><a href="?sort=titreP&order=<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'titreP' && isset($_GET['order']) && $_GET['order'] == 'asc') ? 'desc' : 'asc'; ?>">Titre</a></th>
                            <th>Description</th>
                            <th><a href="?sort=pourcentage&order=<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'pourcentage' && isset($_GET['order']) && $_GET['order'] == 'asc') ? 'desc' : 'asc'; ?>">Pourcentage</a></th>
                            <th>Code Promo</th>
                            <th><a href="?sort=date_debutP&order=<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date_debutP' && isset($_GET['order']) && $_GET['order'] == 'asc') ? 'desc' : 'asc'; ?>">Date Début</a></th>
                            <th><a href="?sort=date_finP&order=<?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date_finP' && isset($_GET['order']) && $_GET['order'] == 'asc') ? 'desc' : 'asc'; ?>">Date Fin</a></th>
                            <th>Campagne</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promotions as $promotion): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($promotion['idP']); ?></td>
                                <td><?php echo htmlspecialchars($promotion['titreP']); ?></td>
                                <td><?php echo htmlspecialchars($promotion['descriptionP']); ?></td>
                                <td><?php echo htmlspecialchars($promotion['pourcentage']); ?>%</td>
                                <td><?php echo htmlspecialchars($promotion['codePromo'] ?: 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($promotion['date_debutP']); ?></td>
                                <td><?php echo htmlspecialchars($promotion['date_finP']); ?></td>
                                <td><?php echo htmlspecialchars($promotion['campaign_name']); ?></td>
                                <td>
                                    <a href="manage_promotion.php?action=edit&idP=<?php echo $promotion['idP']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Modifier</a>
                                    <a href="manage_promotion.php?action=delete&idP=<?php echo $promotion['idP']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer cette promotion ?');"><i class="fas fa-trash"></i> Supprimer</a>
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