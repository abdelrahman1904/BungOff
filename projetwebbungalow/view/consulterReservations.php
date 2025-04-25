<?php
require_once '../controller/reservationC.php';
$reservationC = new ReservationC();

// Gérer la suppression
if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $reservationC->supprimerReservation($id);
    header("Location: consulterReservations.php?deleted=1");
    exit();
}

$listeReservations = $reservationC->afficherReservations();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Consulter Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="back.css">
</head>
<body>

<div class="top-bar">
    <div class="logo">Bung<span class="off">OFF</span></div>
</div>

<div class="sidebar">
    <a href="back.html">Dashboard</a>
    <a href="#">Utilisateurs</a>
    <a href="#">Bungalows</a>
    <a href="#">Activités</a>
    <a href="#">Réservations</a>
</div>

<div class="content">
    <h1>📅 Liste des Réservations</h1>

    <!-- Message après suppression -->
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <div class="alert alert-success text-center">
            ✅ Réservation supprimée avec succès !
        </div>
    <?php endif; ?>

    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>IDR</th>
                    <th>IDB (Bungalow)</th>
                    <th>Date arrivée</th>
                    <th>Date départ</th>
                    <th>Nombre de personnes</th>
                    <th>Prix total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($listeReservations)) {
                    foreach ($listeReservations as $reservation) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($reservation['IDR']) . '</td>';
                        echo '<td>' . htmlspecialchars($reservation['IDB']) . '</td>';
                        echo '<td>' . htmlspecialchars($reservation['date_arrive']) . '</td>';
                        echo '<td>' . htmlspecialchars($reservation['date_depart']) . '</td>';
                        echo '<td>' . htmlspecialchars($reservation['nbp']) . '</td>';
                        echo '<td>' . htmlspecialchars($reservation['prix_total']) . ' DT</td>';
                        echo '<td>
                            <a href="consulterReservations.php?supprimer=' . $reservation['IDR'] . '" 
                                   onclick="return confirm(\'Supprimer cette réservation ?\')">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </button>
                                </a>
                              </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7" class="text-center">Aucune réservation trouvée</td></tr>';
                } ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Bouton retour -->
<div class="text-center mt-4">
        <a href="newback_bung.html" class="btn btn-secondary">Retour </a>
    </div>
</div>
<!-- Icônes FontAwesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>
</html>
