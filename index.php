<?php
require_once __DIR__.'/../../controller/AvisController.php';
require_once __DIR__.'/../../controller/ReponseController.php';

$avisController = new AvisController();
$reponseController = new ReponseController();

// Traitement du formulaire de réponse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_reponse'])) {
    $result = $reponseController->create([
        'poste_admin' => $_POST['poste_admin'],
        'reponse_admin' => $_POST['reponse_admin'],
        'date_reponse' => $_POST['date_reponse'],
        'IDUtilisateur' => $_POST['IDUtilisateur']
    ]);
    
    if ($result) {
        $success_message = "Réponse ajoutée avec succès!";
        header("Location: index.php"); // Redirection après succès
    } else {
        $error_message = "Erreur lors de l'ajout de la réponse";
    }
}

$avisList = $avisController->listAvis();
$reponses = $reponseController->getAllWithAvis();

// Fetch note distribution using the listAvisrate method
$noteDistribution = [];
try {
    $noteDistribution = $avisController->listAvisrate();
} catch (Exception $e) {
    echo "<script>alert('Erreur: " . htmlspecialchars($e->getMessage()) . "');</script>";
}

// Encode $noteDistribution for JavaScript
$encodedNoteDistribution = json_encode($noteDistribution);
if ($encodedNoteDistribution === false) {
    $encodedNoteDistribution = '[]'; // Fallback to an empty array if encoding fails
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <title>Système de gestion d'avis et réponses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .form-container {
            display: none;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
        .btn-reply {
            margin-left: 5px;
        }
    </style>
  <style>
    .admin-section {
      display: none;
      margin-top: 30px;
    }
  </style>
</head>
<body>

  <!-- Barre du haut -->
   
  <div class="top-bar">
    
    <div class="logo">Bung<span class="off">OFF</span></div>
    <div class="toggle-sidebar-icon me-3" onclick="toggleSidebar()" style="cursor: pointer;">
        <i class="fas fa-bars"></i>
      </div>
      
  </div>
    <div class="right-icons">
      <form class="d-inline-flex">
        <input type="text" class="form-control" placeholder="Recherche...">
        <button type="submit" class="btn btn-primary ms-2"><i class="fas fa-search"></i></button>
      </form>
      <div class="login-icon d-inline-flex ms-3">
        <i class="fas fa-user"></i>
      </div>
    </div>
   

  <!-- Barre latérale -->
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

  <!-- Contenu principal -->

  <div class="container">
        <h1 style="margin-top: 60px;">Gestion des avis et réponses</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Liste des avis
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Commentaire</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($avisList as $avis): ?>
                        <tr>
                            <td><?= $avis['IDUtilisateur'] ?></td>
                            <td><?= htmlspecialchars($avis['Nom']) ?></td>
                            <td><?= htmlspecialchars($avis['Commentaire']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" 
                                        onclick="showReponseForm(<?= $avis['IDUtilisateur'] ?>)">
                                    Répondre
                                </button>
                            </td>
                            <td>
                            <form action="supprimeradmin.php" method="POST" style="display: inline;">
                                <input type="hidden" name="IDUtilisateur" value="<?= $avis['IDUtilisateur'] ?>">
                                <button type="submit" name="action" value="edit" class="btn btn-edit">supprimer</button>
                            </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Formulaire de réponse (caché par défaut) -->
        <div id="reponse-form" class="form-container">
            <h3>Ajouter une réponse</h3>
            <form method="post">
                <input type="hidden" id="id_utilisateur" name="IDUtilisateur">
                
                <div class="mb-3">
                    <label class="form-label">Votre poste</label>
                    <input type="text" class="form-control" name="poste_admin" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Votre réponse</label>
                    <textarea class="form-control" name="reponse_admin" rows="3" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" name="date_reponse" 
                           value="<?= date('Y-m-d') ?>" required>
                </div>
                
                <button type="submit" name="add_reponse" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" onclick="hideReponseForm()">Annuler</button>
            </form>
        </div>

        <div class="card">
            <div class="card-header bg-info text-white">
                Réponses existantes
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>poste Admin</th>
                            <th>Réponse</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reponses as $reponse): ?>
                        <tr>
                            <td><?= $reponse['id_reponse'] ?></td>
                            <td><?= htmlspecialchars($reponse['poste_admin']) ?></td>
                            <td><?= htmlspecialchars($reponse['reponse_admin']) ?></td>
                            <td><?= $reponse['date_reponse'] ?></td>
                            <td>
                            <form action="supprimer.php" method="POST" style="display: inline;">
                                <input type="hidden" name="idreponse" value="<?= $reponse['id_reponse'] ?>">
                                <button type="submit" name="action" value="edit" class="btn btn-edit">supprimer</button>
                            </form>
                            </td>
                            <td>
                            <form action="modifierformulaire_reponse.php" method="POST" style="display: inline;">
                                <input type="hidden" name="idreponse" value="<?= $reponse['id_reponse'] ?>">
                                <button type="submit" name="action" value="edit" class="btn btn-edit">Modifier</button>
                            </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Button to display statistics -->
        <button id="btnStatistiques" class="btn btn-info mb-4">Afficher les Statistiques</button>

        <!-- Canvas for the pie chart -->
        <canvas id="pieChart" style="max-width: 600px; margin: 20px auto; display: none;"></canvas>
 </div>

    <script>
        function showReponseForm(userId) {
            document.getElementById('id_utilisateur').value = userId;
            document.getElementById('reponse-form').style.display = 'block';
            document.getElementById('reponse-form').scrollIntoView({ behavior: 'smooth' });
        }
        
        function hideReponseForm() {
            document.getElementById('reponse-form').style.display = 'none';
        }

        // Extract note distribution from PHP data
        const noteDistribution = <?php echo $encodedNoteDistribution; ?>;

        // Handle statistics button click
        document.getElementById('btnStatistiques').addEventListener('click', function() {
            // Ensure noteDistribution is an array
            if (!Array.isArray(noteDistribution) || noteDistribution.length === 0) {
                alert('Impossible de charger les statistiques. Les données sont invalides ou vides.');
                return;
            }

            const labels = [];
            const data = [];
            const backgroundColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#9966FF'];

            // Prepare data for the pie chart
            noteDistribution.forEach((item, index) => {
                labels.push(`Note ${item.Note} ★`);
                data.push(item.count);
            });

            // Display the pie chart
            const ctx = document.getElementById('pieChart').getContext('2d');
            document.getElementById('pieChart').style.display = 'block'; // Show the canvas
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Statistiques des Notes',
                        data: data,
                        backgroundColor: backgroundColors.slice(0, labels.length),
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    return `${label}: ${value} avis`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>


</body>
</html>

















