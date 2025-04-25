<?php
require_once '../../controller/reservationC.php';
$reservationC = new ReservationC();
$reservations = $reservationC->getAllReservations(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes réservations</title>
    <link rel="stylesheet" href="reservationF.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">

    <a href="bungalow_front.php" class="btn-mes-reservations">← Retour aux bungalows</a>

    <h2 class="mb-4">Mes réservations</h2>

    <?php if (count($reservations) > 0): ?>
        <table class="table-reservations">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date d'arrivée</th>
                    <th>Date de départ</th>
                    <th>Nb personnes</th>
                    <th>Prix total</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo $reservation['IDR']; ?></td>
                        <td><?php echo $reservation['date_arrive']; ?></td>
                        <td><?php echo $reservation['date_depart']; ?></td>
                        <td><?php echo $reservation['nbp']; ?></td>
                        <td><?php echo $reservation['prix_total']; ?> TND</td>
                        <td>
                            <a href="modifier_reservation.php?idr=<?php echo $reservation['IDR']; ?>" class="btn-modifier">Modifier</a>
                        </td>
                        <td>
                            <a href="supprimer_reservation.php?idr=<?php echo $reservation['IDR']; ?>" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
   

        </table>
    <?php else: ?>
        <p class="alert alert-info">Aucune réservation trouvée.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
