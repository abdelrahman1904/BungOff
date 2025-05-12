<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../../model/newsletter_model.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$newsletterModel = new NewsletterModel();
$subscribers = $newsletterModel->getSubscribers();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        if ($newsletterModel->deleteSubscriber($id)) {
            $message = "<div class='alert alert-success'>Abonné supprimé avec succès.</div>";
            header("Refresh:0");
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de la suppression.</div>";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'send_email') {
        $subject = htmlspecialchars($_POST['subject'], ENT_QUOTES, 'UTF-8');
        $body = $_POST['body'];

        $mail = new PHPMailer(true);
        //$mail->SMTPDebug = 2; // Active le débogage temporairement
        //$mail->Debugoutput = 'html'; // Affiche les messages de débogage en HTML
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'arijbensalem901@gmail.com'; // Remplacez par votre e-mail
            $mail->Password = 'prjismkfpkxkavzl'; // Remplacez par votre mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('arijbensalem901@gmail.com', 'BungOFF');
            $mail->isHTML(true);
            $mail->Subject = $subject;

            $success_count = 0;
            foreach ($subscribers as $subscriber) {
                $mail->addAddress($subscriber['email']);
                $mail->Body = $body;
                if ($mail->send()) {
                    $success_count++;
                }
                $mail->clearAddresses();
            }
            $message = "<div class='alert alert-success'>E-mail envoyé à $success_count abonné(s) avec succès.</div>";
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger'>Erreur lors de l'envoi : {$mail->ErrorInfo}</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de la Newsletter - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="top-bar">
        <div class="logo">Bung<span class="off">OFF</span></div>
        <div class="right-icons">
            <div class="login-icon d-inline-flex ms-3">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

    <div class="sidebar">
  <a href="../../User/backoffice/homePage.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
  <a href="../../User/backoffice/userlist.php"><i class="fas fa-user"></i> Utilisateurs</a>
  <a href="#"><i class="fas fa-home"></i> Bungalows</a>
  <a href="#"><i class="fas fa-campground"></i> Activités</a>
  <a href="#"><i class="fas fa-car"></i> Transports</a>
  <a href="promotion.php"><i class="fas fa-credit-card"></i> Promotion</a>
  <a href="../../Avis/backoffice/index.php"><i class="fas fa-star"></i> Avis</a>
  <div class="logout">
    <a href="#"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a>
  </div>
</div>

    <div class="content">
        <h1>Gestion de la Newsletter</h1>
        <?php echo $message; ?>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Envoyer un e-mail promotionnel</h5>
                <form action="manage_newsletter.php" method="POST">
                    <input type="hidden" name="action" value="send_email">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Sujet</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="body" class="form-label">Contenu (HTML accepté)</label>
                        <textarea class="form-control" id="body" name="body" rows="5" required>Bonjour,<br><br>Découvrez nos dernières promotions ! Cliquez <a href="http://192.168.60.1/web10-Copie/frontoffice/promotions_front.php">ici</a> pour en profiter.<br><br>BungOFF</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer à tous les abonnés</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Liste des abonnés</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>E-mail</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subscribers as $subscriber): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($subscriber['id']); ?></td>
                                <td><?php echo htmlspecialchars($subscriber['email']); ?></td>
                                <td><?php echo htmlspecialchars($subscriber['subscribed_at']); ?></td>
                                <td>
                                    <form action="manage_newsletter.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $subscriber['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer cet abonné ?');">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>