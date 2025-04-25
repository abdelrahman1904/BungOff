<?php
// Inclure le modèle et le contrôleur pour la réservation
require_once '../../controller/reservationC.php';
require_once '../../model/reservation.php';
session_start();

// Si l'identifiant utilisateur n'existe pas en session, on le crée
if (!isset($_SESSION['reservation_user_token'])) {
    $_SESSION['reservation_user_token'] = uniqid('user_', true);
}

$user_token = $_SESSION['reservation_user_token'];

// Initialiser le contrôleur de réservation
$reservationC = new ReservationC();

// Vérifier que l'ID du bungalow est passé dans l'URL
if (isset($_GET['id_bungalow'])) {
    $id_bungalow = $_GET['id_bungalow'];
} else {
    echo "L'ID du bungalow est manquant.";
    exit;
}

// Récupérer le prix du bungalow depuis la base de données
$prix_nuit = $reservationC->getPrixBungalowById($id_bungalow); // Cette méthode doit être implémentée dans ReservationC

// Initialiser la variable pour les erreurs
$errors = [];

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $date_arrive = $_POST['date_arrive'];
    $date_depart = $_POST['date_depart'];
    $nbp = $_POST['nbp'];
    $prix_total = $_POST['prix_total'];  // Ce sera calculé automatiquement

    // Calcul du prix total si nécessaire
    if (empty($prix_total)) {
        $date1 = new DateTime($date_arrive);
        $date2 = new DateTime($date_depart);
        $diff = $date2->diff($date1);
        $nights = $diff->days;

        // Calculer le prix total basé sur le nombre de nuits, le prix par nuit et le nombre de personnes
        $prix_total = $prix_nuit * $nights ;
    }

    // Validation des données
    if (empty($date_arrive)) {
        $errors[] = "La date d'arrivée est requise.";
    }
    if (empty($date_depart)) {
        $errors[] = "La date de départ est requise.";
    }
    if (empty($nbp) || !is_numeric($nbp) || $nbp <= 0) {
        $errors[] = "Le nombre de personnes doit être un nombre positif.";
    }
    if (empty($prix_total) || !is_numeric($prix_total) || $prix_total <= 0) {
        $errors[] = "Le prix total doit être un nombre positif.";
    }

    // Si aucune erreur, enregistrer la réservation
    if (empty($errors)) {
        $reservation = new Reservation($id_bungalow, $date_arrive, $date_depart, $nbp, $prix_nuit);

        $reservationC->ajouterReservation($reservation);

        // Redirection ou message de confirmation
        header("Location: reservationFront.php?success=1&id_bungalow=" . $id_bungalow);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- Bootstrap (optionnel si tu veux un joli style) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            padding: 50px;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Formulaire de réservation pour le Bungalow</h2>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success">La réservation a été ajoutée avec succès !</div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="reservationFront.php?id_bungalow=<?php echo $id_bungalow; ?>" method="POST" onsubmit="return validerFormulaire();">
        <div class="mb-3">
            <label for="date_arrive" class="form-label">Date d'arrivée</label>
            <input type="text" class="form-control" id="date_arrive" name="date_arrive" placeholder="Choisissez une date">
        </div>

        <div class="mb-3">
            <label for="date_depart" class="form-label">Date de départ</label>
            <input type="text" class="form-control" id="date_depart" name="date_depart" placeholder="Choisissez une date">
        </div>

        <div class="mb-3">
            <label for="nbp" class="form-label">Nombre de personnes</label>
            <input type="text" class="form-control" id="nbp" name="nbp" placeholder="Ex: 2">
        </div>

        <button type="submit" class="btn btn-primary">Réserver</button>
 
        </form>
    </div>
    <script>
    // Initialisation du datepicker jQuery UI
    $(function () {
        $("#date_arrive, #date_depart").datepicker({
            dateFormat: "yy-mm-dd",
            minDate: 0
        });
    });

    // Validation personnalisée en JavaScript
    function validerFormulaire() {
        const dateArrive = document.getElementById('date_arrive').value.trim();
        const dateDepart = document.getElementById('date_depart').value.trim();
        const nbp = document.getElementById('nbp').value.trim();

        if (!dateArrive || !dateDepart || !nbp) {
            alert("Tous les champs sont obligatoires.");
            return false;
        }

        if (isNaN(nbp) || parseInt(nbp) <= 0) {
            alert("Le nombre de personnes doit être un nombre positif.");
            return false;
        }

        if (new Date(dateArrive) >= new Date(dateDepart)) {
            alert("La date de départ doit être après la date d'arrivée.");
            return false;
        }

        return true;
    }
</script>
</body>
</html>
