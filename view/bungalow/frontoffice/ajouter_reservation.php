<?php
require_once '../model/reservation.php';
require_once '../controller/reservationC.php';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $date_arrive = $_POST['date_arrive'];
    $date_depart = $_POST['date_depart'];
    $nbp = $_POST['nbp'];
    $prix_total = $_POST['prix_total'];

    // Contrôle de saisie simple
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

    // Si aucun problème, on ajoute la réservation en base de données
    if (empty($errors)) {
        // Création de l’objet Reservation
        $reservation = new Reservation($date_arrive, $date_depart, $nbp, $prix_total);
        
        // Insertion en base de données
        $reservationC = new ReservationC();
        $reservationC->ajouterReservation($reservation);

        // Redirection ou message de confirmation
        header("Location: ajouter_reservation.php?success=1");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="ajouter_reservation.css">
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
        <a href="bungalow_list.php"><i class="fas fa-home"></i> Bungalows</a>
        <a href="reservation_list.php"><i class="fas fa-calendar-check"></i> Réservations</a>
        <div class="logout">
            <a href="#"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="content">
        <a href="reservation_list.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour à la liste des réservations
        </a>
        
        <div class="form-container">
            <h2>Formulaire de réservation</h2>
            <form action="ajouter_reservation.php" method="POST" id="form-reservation">

                <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                    <div class="alert alert-success">La réservation a été ajoutée avec succès !</div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="date_arrive" class="form-label">Date d'arrivée</label>
                    <input type="date" class="form-control" id="date_arrive" name="date_arrive" required>
                </div>
                <div class="mb-3">
                    <label for="date_depart" class="form-label">Date de départ</label>
                    <input type="date" class="form-control" id="date_depart" name="date_depart" required>
                </div>
                <div class="mb-3">
                    <label for="nbp" class="form-label">Nombre de personnes</label>
                    <input type="number" class="form-control" id="nbp" name="nbp" required>
                </div>
                <div class="mb-3">
                    <label for="prix_total" class="form-label">Prix total</label>
                    <input type="number" class="form-control" id="prix_total" name="prix_total" required>
                </div>
                <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
            </form>
        </div>
    </div>
    
    <script>
        document.getElementById('form-reservation').addEventListener('submit', function (event) {
        let valid = true;

        // Vérification des dates
        const dateArrive = document.getElementById('date_arrive').value;
        const dateDepart = document.getElementById('date_depart').value;

        if (dateArrive === '' || dateDepart === '') {
            alert('Les dates d\'arrivée et de départ sont requises.');
            valid = false;
        }

        // Vérification du nombre de personnes
        const nbp = document.getElementById('nbp').value;
        if (nbp <= 0 || isNaN(nbp)) {
            alert('Le nombre de personnes doit être un nombre positif.');
            valid = false;
        }

        // Vérification du prix total
        const prixTotal = document.getElementById('prix_total').value;
        if (prixTotal <= 0 || isNaN(prixTotal)) {
            alert('Le prix total doit être un nombre positif.');
            valid = false;
        }

        if (!valid) {
            event.preventDefault(); // Bloque l'envoi du formulaire
        }
    });
    </script>
</body>
</html>
