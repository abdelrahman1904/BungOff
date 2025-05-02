<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../../vendor/PHPMailer-master/src/Exception.php';
require_once '../../vendor/PHPMailer-master/src/PHPMailer.php';
require_once '../../vendor/PHPMailer-master/src/SMTP.php';


require_once '../../controller/reservationC.php';
require_once '../../model/reservation.php';
session_start();

// Génération d'un token pour l'utilisateur si non existant
if (!isset($_SESSION['reservation_user_token'])) {
    $_SESSION['reservation_user_token'] = uniqid('user_', true);
}
$user_token = $_SESSION['reservation_user_token'];

$reservationC = new ReservationC();

// Vérification de l'id_bungalow
if (!isset($_GET['id_bungalow'])) {
    echo "L'ID du bungalow est manquant.";
    exit;
}
$id_bungalow = $_GET['id_bungalow'];

// Récupération des infos bungalow
$prix_nuit = $reservationC->getPrixBungalowById($id_bungalow);
$nom_bungalow = $reservationC->getNomBungalowById($id_bungalow);

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_arrive = $_POST['date_arrive'];
    $date_depart = $_POST['date_depart'];
    $nbp = $_POST['nbp'];
    $prix_total = $_POST['prix_total'];

    // Calcul automatique si prix vide
    if (empty($prix_total)) {
        $date1 = new DateTime($date_arrive);
        $date2 = new DateTime($date_depart);
        $diff = $date2->diff($date1);
        $nights = $diff->days;
        $prix_total = $prix_nuit * $nights;
    }

    // Validation
    if (empty($date_arrive)) $errors[] = "La date d'arrivée est requise.";
    if (empty($date_depart)) $errors[] = "La date de départ est requise.";
    if (empty($nbp) || !is_numeric($nbp) || $nbp <= 0) $errors[] = "Le nombre de personnes est invalide.";
    if (empty($prix_total) || !is_numeric($prix_total) || $prix_total <= 0) $errors[] = "Le prix total est invalide.";

    if (empty($errors)) {
        $reservation = new Reservation($id_bungalow, $date_arrive, $date_depart, $nbp, $prix_total);
        $reservationC->ajouterReservation($reservation);

        // ENVOI D'EMAIL
        $email_sent = gestimail([
            'date_arrive' => $date_arrive,
            'date_depart' => $date_depart,
            'nbp' => $nbp,
            'prix_total' => $prix_total,
            'nom_bungalow' => $nom_bungalow,
        ]);

        if ($email_sent) {
            header("Location: reservationFront.php?success=1&id_bungalow=" . $id_bungalow);
            exit();
        } else {
            echo "La réservation est faite, mais l'email n'a pas pu être envoyé.";
        }
    }
}

// Fonction d'envoi d'email
function gestimail($data) {
    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bnrranim141@gmail.com'; // votre email
        $mail->Password = 'bglg avde jgzj cpyw';    // mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Détails de l'email
        $mail->setFrom('bnrranim141@gmail.com', 'BungOFF'); 
        $mail->addAddress('bnrranim141@gmail.com'); // destinataire
        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de réservation BungOFF';
        $mail->Body = generateEmailBody($data);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur d'envoi d'email : {$mail->ErrorInfo}");
        return false;
    }
}

// Fonction pour générer le contenu HTML de l'email
function generateEmailBody($data) {
    return "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; }
            h2 { color: #0066cc; }
            ul { list-style: none; padding: 0; }
            li { margin-bottom: 10px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Confirmation de votre réservation</h2>
            <p>Merci d'avoir réservé chez <strong>BungOFF</strong>. Voici les détails :</p>
            <ul>
                <li><strong>Bungalow :</strong> {$data['nom_bungalow']}</li>
                <li><strong>Date d'arrivée :</strong> {$data['date_arrive']}</li>
                <li><strong>Date de départ :</strong> {$data['date_depart']}</li>
                <li><strong>Nombre de personnes :</strong> {$data['nbp']}</li>
                <li><strong>Prix total :</strong> {$data['prix_total']} dt</li>
            </ul>
            <p>Nous avons hâte de vous accueillir !</p>
            <p>L'équipe BungOFF</p>
        </div>
    </body>
    </html>";
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
        <h2>Formulaire de réservation pour le Bungalow: <?php echo htmlspecialchars($nom_bungalow); ?></h2>

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
