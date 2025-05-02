<?php
require_once '../controller/ReservationC.php';
$reservationC = new ReservationC();

if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $reservationC->supprimerReservation($id);
    header('Location: reservation_back.php?deleted=1');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter Réservations - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="back.css">
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
            <a href="newback_bung.html" class="add-activity-link">⬅ Retour</a>
        </div>
        <div class="bungalow-container">
            <div class="bungalow-header">
                <h1><i class="fas fa-calendar-alt"></i> Liste des Réservations</h1>
            </div>
            <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
                <div class="alert alert-success text-center" role="alert">
                    ✅ La réservation a été supprimée avec succès !
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>IDR</th>
                            <th>Date Arrivée</th>
                            <th>Date Départ</th>
                            <th>Nb Personnes</th>
                            <th>Prix Total</th>
                            <th>Bungalow ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $reservations = $reservationC->afficherReservations();
                        if ($reservations) {
                            foreach ($reservations as $reservation) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($reservation['IDR']) . '</td>';
                                echo '<td>' . htmlspecialchars($reservation['date_arrivee']) . '</td>';
                                echo '<td>' . htmlspecialchars($reservation['date_depart']) . '</td>';
                                echo '<td>' . htmlspecialchars($reservation['nb_personnes']) . '</td>';
                                echo '<td>' . htmlspecialchars($reservation['prix_total']) . ' DT</td>';
                                echo '<td>' . htmlspecialchars($reservation['IDB']) . '</td>';
                                echo '<td><a href="reservation_back.php?supprimer=' . $reservation['IDR'] . '" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette réservation ?\')">Supprimer</a></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="7" class="text-center">Aucune réservation trouvée</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.content').classList.toggle('collapsed-content');
        });
    </script>
</body>
</html>
