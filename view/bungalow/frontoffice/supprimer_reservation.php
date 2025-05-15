<?php
require_once '../../controller/reservationC.php';
$reservationC = new ReservationC();

// Vérifier si l'ID de la réservation est passé en paramètre dans l'URL
if (isset($_GET['idr']) && !empty($_GET['idr'])) {
    $idr = $_GET['idr'];  // Récupère l'ID de la réservation

    // Appeler la méthode pour supprimer la réservation
    $reservationC->supprimerReservation($idr);

    // Rediriger vers la page des réservations après suppression
    header("Location: mes_reservations.php");
    exit();
} else {
    // Si l'ID est manquant
    echo "ID de réservation manquant.";
    exit();
}
?>
