<?php
require_once '../../controller/reservationC.php';
$reservationC = new ReservationC();

// Vérifier si l'ID de la réservation est passé en paramètre
if (isset($_GET['idr'])) {  // Vérifie si 'idr' est passé dans l'URL
    $idr = $_GET['idr'];  // Récupère l'ID de réservation
    
    // Récupérer les détails de la réservation à modifier
    $reservation = $reservationC->getReservationById($idr);
    
    if (!$reservation) {
        echo "Réservation introuvable.";
        exit();
    }
} else {
    echo "ID de réservation manquant.";
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $date_arrive = $_POST['date_arrive'];
    $date_depart = $_POST['date_depart'];
    $nbp = $_POST['nbp'];
    $prix_total = $_POST['prix_total']; // Récupère le prix total calculé
    $IDB = $_POST['IDB']; // Récupère l'ID du bungalow

    // Validation de la date et du prix
    if (!strtotime($date_arrive) || !strtotime($date_depart)) {
        echo "<div class='alert alert-warning'>Les dates ne sont pas valides.</div>";
    } elseif (!is_numeric($prix_total)) {
        echo "<div class='alert alert-warning'>Le prix total doit être un nombre valide.</div>";
    } else {
        // Mettre à jour la réservation
        $reservationC->modifierReservation($idr, $date_arrive, $date_depart, $nbp, $prix_total, $IDB);
        
        // Rediriger vers la page des réservations après modification
        header("Location: mes_reservations.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Fonction pour recalculer le prix total
        function calculerPrix() {
            var dateArrive = new Date(document.getElementById("date_arrive").value);
            var dateDepart = new Date(document.getElementById("date_depart").value);
            var nbp = parseInt(document.getElementById("nbp").value);

            // Calcul du nombre de jours entre les deux dates
            var diffTime = dateDepart - dateArrive;
            var diffDays = diffTime / (1000 * 3600 * 24); // Conversion du temps en jours

            if (!isNaN(diffDays) && diffDays > 0 && !isNaN(nbp) && nbp > 0) {
                // Calcul du prix total
                var prixParPersonne = 50; // Exemple de prix par personne par jour
                var prixTotal = prixParPersonne * nbp * diffDays;
                document.getElementById("prix_total").value = prixTotal.toFixed(2); // Mise à jour du prix
            } else {
                document.getElementById("prix_total").value = "0.00"; // Si les dates ou le nombre de personnes sont invalides
            }
        }

        // Ajout des événements pour recalculer le prix
        window.onload = function() {
            document.getElementById("date_arrive").addEventListener("change", calculerPrix);
            document.getElementById("date_depart").addEventListener("change", calculerPrix);
            document.getElementById("nbp").addEventListener("input", calculerPrix);
        };
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>Modifier la réservation</h2>

    <?php if (isset($reservation)): ?>
        <form method="POST" action="modifier_reservation.php?idr=<?php echo $idr; ?>">
            <div class="mb-3">
                <label for="date_arrive" class="form-label">Date d'arrivée</label>
                <!-- Supprimer l'attribut "required" et "type=date" -->
                <input type="text" class="form-control" id="date_arrive" name="date_arrive" value="<?php echo $reservation['date_arrive']; ?>">
            </div>

            <div class="mb-3">
                <label for="date_depart" class="form-label">Date de départ</label>
                <!-- Supprimer l'attribut "required" et "type=date" -->
                <input type="text" class="form-control" id="date_depart" name="date_depart" value="<?php echo $reservation['date_depart']; ?>">
            </div>

            <div class="mb-3">
                <label for="nbp" class="form-label">Nombre de personnes</label>
                <input type="number" class="form-control" id="nbp" name="nbp" value="<?php echo $reservation['nbp']; ?>">
            </div>

            <!-- Champ caché pour le prix total -->
            <input type="hidden" id="prix_total" name="prix_total" value="<?php echo $reservation['prix_total']; ?>">

            <input type="hidden" name="IDB" value="<?php echo $reservation['IDB']; ?>"> <!-- Champ caché pour l'ID du bungalow -->

            <button type="submit" class="btn btn-primary">Modifier</button>
        </form>
    <?php else: ?>
        <p>La réservation que vous souhaitez modifier n'a pas été trouvée.</p>
    <?php endif; ?>
</div>
<script>
      var prixParNuit = <?php echo $reservation['prix_nuit']; ?>;

function calculerPrix() {
    // Récupérer les dates du formulaire
    var dateArrive = new Date(document.getElementById("date_arrive").value);
    var dateDepart = new Date(document.getElementById("date_depart").value);
    var nbp = parseInt(document.getElementById("nbp").value);

    // Calcul de la différence en millisecondes entre les dates
    var diffTime = dateDepart - dateArrive;

    // Convertir la différence en jours (millisecondes / 1000 / 3600 / 24)
    var diffDays = diffTime / (1000 * 3600 * 24);

    // Vérifier que les dates sont valides et que la différence est positive
    if (!isNaN(diffDays) && diffDays > 0 ) {
        // Calculer le prix total
        var prixTotal = prixParNuit *  diffDays;

        // Mettre à jour le champ prix total avec le résultat
        document.getElementById("prix_total").value = prixTotal.toFixed(2);
    } else {
        // Si les dates ou le nombre de personnes sont invalides, afficher 0
        document.getElementById("prix_total").value = "0.00";
    }
}

// Ajouter des événements pour recalculer le prix lors du changement des champs
window.onload = function() {
    document.getElementById("date_arrive").addEventListener("change", calculerPrix);
    document.getElementById("date_depart").addEventListener("change", calculerPrix);
    document.getElementById("nbp").addEventListener("input", calculerPrix);
};
flatpickr("#date_arrive", {
    dateFormat: "Y-m-d"
});

flatpickr("#date_depart", {
    dateFormat: "Y-m-d"
});
</script>
</body>
</html>
