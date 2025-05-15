<?php
// Connexion à la base de données
require_once '../../config.php';

if (!isset($_GET['id'])) {
    die('ID de planification manquant.');
}

$idp = $_GET['id'];

try {
    $pdo = config::getConnexion();

    $query = $pdo->prepare("
    SELECT 
        i.user_id,
        u.fullname,
        u.email,
        p.*,
        a.titre AS nom_activite,
        a.photo
    FROM inscription i
    JOIN userlist u ON i.user_id = u.id
    JOIN planification p ON i.IDP = p.IDP
    JOIN activite a ON p.nom_activite = a.titre
    ORDER BY a.IDA, p.date, p.heure_debut
");
$query->execute();
$participants = $query->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Erreur de base de données : ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter Planification - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="activity.css">
    <link rel="stylesheet" href="consulter_act.css">
</head>
<body>

<!-- Barre en haut (identique) -->
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

<!-- Barre latérale (identique) -->
<div class="sidebar">
    <a href="activity.html"><i class="fas fa-tachometer-alt"></i> dashboard</a>
    <a href="#"><i class="fas fa-user"></i> Utilisateurs</a>
    <a href="#"><i class="fas fa-home"></i> Bungalows</a>
    <a href="new_act.php"><i class="fas fa-campground"></i> Activités</a>
    <a href="#"><i class="fas fa-car"></i> Transport</a>
    <a href="#"><i class="fas fa-credit-card"></i> Paiement</a>
    <a href="#"><i class="fas fa-star"></i> Avis</a>
    <div class="logout">
        <a href="#"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a>
    </div>
</div>

<!-- Contenu principal amélioré -->
<div class="content">
    <div class="activity-header">
        <a href="consulter_plan.php" class="add-activity-link">
            ⬅ Retour à planification
        </a>
    </div>
    <div class="activities-container">
        <div class="activities-header">
            <h1><i class="fas fa-list-alt"></i> Liste des Participants</h1>
            <div class="table-footer">
                <div class="export-btns">
                    <a href="export_pdf.php?id=<?= $idp ?>" class="export-btn" target="_blank">
                        <i class="fas fa-file-pdf"></i> exporter en PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <?php
            // Regrouper les participants par activité
            $grouped = [];
            foreach ($participants as $p) {
                $nomActivite = $p['nom_activite'];
                
                if (!isset($grouped[$nomActivite])) {
                    $grouped[$nomActivite] = [
                        'photo' => $p['photo'],
                        'sessions' => []
                    ];
                }
                
                // Créer une clé unique pour chaque session
                $sessionKey = $p['lieu'] . '|' . $p['date'] . '|' . $p['heure_debut'] . '|' . $p['heure_fin'];
                
                if (!isset($grouped[$nomActivite]['sessions'][$sessionKey])) {
                    $grouped[$nomActivite]['sessions'][$sessionKey] = [
                        'lieu' => $p['lieu'],
                        'date' => $p['date'],
                        'heure_debut' => $p['heure_debut'],
                        'heure_fin' => $p['heure_fin'],
                        'participants' => []
                    ];
                }
                
                $grouped[$nomActivite]['sessions'][$sessionKey]['participants'][] = [
                    'fullname' => $p['fullname'],
                    'email' => $p['email']
                ];
            }
            ?>

            <?php foreach ($grouped as $nomActivite => $data): ?>
                <div class="col-12 mb-4">
                    <div class="card shadow rounded p-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="frontoffice/image/<?= htmlspecialchars($data['photo']) ?>" alt="<?= htmlspecialchars($nomActivite) ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                            <h3 class="mb-0"><?= htmlspecialchars($nomActivite) ?></h3>
                        </div>
                        <hr>
                        
                        <?php foreach ($data['sessions'] as $session): ?>
                            <div class="ps-4 mb-3">
                                <div class="d-flex flex-column flex-md-row gap-3 mb-2">
                                    <span><i class="fas fa-map-marker-alt text-danger me-2"></i><strong><?= htmlspecialchars($session['lieu']) ?></strong></span>
                                    <span><i class="fas fa-calendar-day text-primary me-2"></i><?= date('d M Y', strtotime($session['date'])) ?></span>
                                    <span><i class="fas fa-clock text-warning me-2"></i><?= htmlspecialchars($session['heure_debut']) ?> - <?= htmlspecialchars($session['heure_fin']) ?></span>
                                </div>

                                <ul>
                                    <?php foreach ($session['participants'] as $participant): ?>
                                        <li><?= htmlspecialchars($participant['fullname']) ?> (<a href="mailto:<?= htmlspecialchars($participant['email']) ?>"><?= htmlspecialchars($participant['email']) ?></a>)</li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    // Script pour la sidebar
    document.getElementById('toggleSidebar').addEventListener('click', function () {
        document.querySelector('.sidebar').classList.toggle('collapsed');
        document.querySelector('.content').classList.toggle('collapsed-content');
    });

    function confirmEdit() {
        return confirm("Voulez-vous vraiment modifier cette planification");
    }

    function confirmDelete() {
        return confirm("Êtes-vous sûr de vouloir supprimer cette planification ?");
    }
</script>

<style>
    body {
        background: #f4f7fb;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .activities-header h1 {
        font-size: 2rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 20px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }

    .card {
        background: linear-gradient(145deg, #ffffff, #f0f0f0);
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: scale(1.01);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .card h3 {
        color: #1abc9c;
        font-size: 1.5rem;
    }

    .card img {
        border: 3px solid #1abc9c;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    ul {
        list-style: none;
        padding-left: 20px;
    }

    ul li {
        position: relative;
        padding-left: 25px;
        margin-bottom: 8px;
    }

    ul li::before {
        content: "👤";
        position: absolute;
        left: 0;
    }

    .add-activity-link {
        display: inline-block;
        margin-bottom: 15px;
        text-decoration: none;
        color: #1abc9c;
        font-weight: 600;
    }

    .add-activity-link:hover {
        text-decoration: underline;
        color: #16a085;
    }

    .export-btn {
        background: #1abc9c;
        color: #fff;
        padding: 8px 16px;
        border-radius: 30px;
        text-decoration: none;
        transition: background 0.3s ease;
    }

    .export-btn:hover {
        background: #16a085;
        color: #fff;
    }
</style>
</body>
</html>