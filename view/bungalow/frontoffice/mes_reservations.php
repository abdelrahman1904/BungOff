<?php
require_once '../../controller/reservationC.php';
$reservationC = new ReservationC();

// Gestion de la recherche
$searchResults = false;

if (isset($_GET['critere']) && isset($_GET['valeur']) && !empty($_GET['valeur']))  //condition de recherche 
 {
    $critere = $_GET['critere']; //recuperation des donnees via url get 
    $valeur = $_GET['valeur'];
    $reservations = $reservationC->searchReservations($critere, $valeur);
    $searchResults = true;
} else {
    // Gestion du tri si pas de recherche
    $triColonne = $_GET['tri_colonne'] ?? null;
    $triOrdre = $_GET['tri_ordre'] ?? null;

    if ($triColonne && $triOrdre) {
        $reservations = $reservationC->getAllReservationsSorted($triColonne, $triOrdre);
    } else {
        $reservations = $reservationC->getAllReservations();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes réservations</title>
    <link rel="stylesheet" href="reservationF.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
    
<div class="container mt-5">

    <!-- Bouton retour -->
    <div class="header-btn-container mb-4">
        <a href="bungalow_front.php" class="btn-mes-reservations">← Retour aux bungalows</a>
    </div>

    <!-- Titre -->
    <h2 class="mb-4 text-center">Mes réservations</h2>

    <!-- Formulaire de recherche -->
    <form method="get" class="row g-3 mb-4">
    <div class="col-md-4">
        
        <select name="critere" class="form-select" onchange="changerTypeInput(this.value)">
            <option value="date_arrive">Date d'arrivée</option>
            <option value="date_depart">Date de départ</option>
            <option value="nom">Nom du bungalow</option>
        </select>
    </div>
    <div class="col-md-4">
    <input type="text" id="valeur" name="valeur" class="form-control" placeholder="Rechercher...">

    </div>
    <div class="col-md-4">
        <button type="submit" class="btn btn-success">Rechercher</button>
    </div>
    
</form>


<!-- Liens de tri -->
<div class="mb-3">
    <strong>Trier par :</strong>
 
    <a href="?tri_colonne=prix_total&tri_ordre=asc" class="btn btn-outline-success btn-sm">Prix ↑</a>
    <a href="?tri_colonne=prix_total&tri_ordre=desc" class="btn btn-outline-success btn-sm">Prix ↓</a>
</div>

<!-- Affichage des résultats de recherche -->
<?php if (count($reservations) > 0): ?>
    <table class="table-reservations table table-bordered table-striped">
        <thead>
            <tr>
                <th>Image</th>
                <th>Nom du bungalow</th>
                <th>Date d'arrivée</th>
                <th>Date de départ</th>
                <th>Nombre de personnes</th>
                <th>Prix total</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $reservation): ?>
            <?php
                // Récupérer les informations du bungalow
                $bungalow = $reservationC->getBungalowById($reservation['IDB']);
                $image_path = 'image_video1/' . $bungalow['image'];
            ?>
            <tr>
                <td><img src="<?= $image_path; ?>" alt="Image Bungalow" class="bungalow-image" style="width: 100%; height: auto;" /></td>
                <td><?= htmlspecialchars($bungalow['nom']); ?></td>
                <td><?= date("d/m/Y", strtotime($reservation['date_arrive'])); ?></td>
                <td><?= date("d/m/Y", strtotime($reservation['date_depart'])); ?></td>
                <td><?= htmlspecialchars($reservation['nbp']); ?></td>
                <td><?= number_format($reservation['prix_total'], 2); ?> TND</td>
                <td><a href="modifier_reservation.php?idr=<?= $reservation['IDR']; ?>" class="btn-modifier">Modifier</a></td>
                <td><a href="supprimer_reservation.php?idr=<?= $reservation['IDR']; ?>" class="btn-supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')">Supprimer</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="alert alert-info text-center">
        <?= $searchResults ? "Aucun résultat pour votre recherche." : "Aucune réservation trouvée." ?>
    </p>
<?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function changerTypeInput(critere) {
    const input = document.getElementById('valeur');
    if (critere === 'date_arrive' || critere === 'date_depart') {
        input.type = 'date';
        input.placeholder = '';
    } else {
        input.type = 'text';
        input.placeholder = 'Rechercher...';
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
function changerTypeInput(critere) {
    const input = document.getElementById('valeur');

    if (critere === 'date_arrive' || critere === 'date_depart') {
        input.type = 'text'; // nécessaire pour flatpickr
        input.placeholder = 'Choisir une date';
        flatpickr(input, {
            dateFormat: "Y-m-d"
        });
    } else {
        input.type = 'text';
        input.placeholder = 'Rechercher...';
        if (input._flatpickr) {
            input._flatpickr.destroy(); // Retirer Flatpickr si déjà appliqué
        }
    }
}
</script>


</body>
</html>
