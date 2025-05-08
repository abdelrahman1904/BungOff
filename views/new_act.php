<?php
require_once '../models/config.php';

// Marquer une notification comme lue
if (isset($_POST['mark_as_seen']) && isset($_POST['IDI'])) {
    try {
        $pdo = config::getConnexion();
        $stmt = $pdo->prepare("UPDATE inscription SET is_seen = 1 WHERE IDI = :IDI");
        $stmt->bindParam(':IDI', $_POST['IDI'], PDO::PARAM_INT);
        $stmt->execute();
        echo "Notification mise à jour avec succès.";
    } catch (Exception $e) {
        http_response_code(500);
        echo 'Erreur lors de la mise à jour de la notification : ' . $e->getMessage();
    }
    exit;
}

try {
    $pdo = config::getConnexion();
    $stmtNotif = $pdo->prepare("
    SELECT i.IDI, u.fullname, u.email, a.titre AS activite, p.date, i.is_seen,p.lieu
        FROM inscription i
        JOIN userlist u ON i.user_id = u.id
        JOIN planification p ON i.IDP = p.IDP
        JOIN activite a ON i.IDA = a.IDA
        WHERE i.is_seen = 0
        ORDER BY i.IDI DESC
        LIMIT 5

");

    $stmtNotif->execute();
    $notifications = $stmtNotif->fetchAll(PDO::FETCH_ASSOC);
    $hasNotif = !empty($notifications); // Déterminer s'il y a des notifications
} catch (Exception $e) {
    die('Erreur lors de la récupération des notifications : ' . $e->getMessage());
}

try {
    $pdo = config::getConnexion();
    $stmt = $pdo->prepare("
        SELECT a.titre AS nom_activite, COUNT(i.IDI) AS nb_inscriptions
        FROM planification p
        LEFT JOIN inscription i ON i.IDP = p.IDP
        JOIN activite a ON p.nom_activite = a.titre
        GROUP BY a.titre
        ORDER BY nb_inscriptions DESC
    ");
    $stmt->execute();
    $statistiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Erreur lors de la récupération des statistiques : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="activity.css">
    <style>
        .notifications-dropdown {
            display: none;
            position: absolute;
            top: 40px;
            right: 0;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
        }

        .notification-item {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-content {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 10px;
            margin-top: 5px;
        }

        .notification-item strong {
            color: #333;
        }

        .notification-item em {
            color: #6c757d;
            font-style: normal;
        }

        .notif-counter {
            font-size: 0.7rem;
            padding: 3px 6px;
        }

        .login-icon {
            cursor: pointer;
            position: relative;
        }
        .notification-item {
    padding: 15px;
    background: #ffffff;
    border-radius: 10px;
    margin-bottom: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border-left: 4px solid #6e8efb;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    position: relative;
    overflow: hidden;
}

.notification-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    background: linear-gradient(to right, #f8faff, #ffffff);
}

.notification-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(to bottom, #6e8efb, #a777e3);
}

.notification-content {
    padding-left: 8px;
}

.notification-content p {
    margin: 8px 0;
    font-size: 0.9rem;
    color: #4a5568;
    display: flex;
    align-items: center;
    line-height: 1.4;
}

.notification-content i {
    width: 20px;
    color: #6e8efb;
    margin-right: 10px;
    text-align: center;
}

.notification-content em {
    color: #2d3748;
    font-style: normal;
    font-weight: 500;
    margin-left: 5px;
}

.notification-time {
    font-size: 0.75rem;
    color: #718096;
    display: flex;
    align-items: center;
    margin-top: 10px;
}

.notification-time i {
    margin-right: 5px;
}

.badge-new {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #e53e3e;
    color: white;
    font-size: 0.7rem;
    padding: 3px 8px;
    border-radius: 10px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
    </style>

</head>
<body>

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

        <div class="login-icon d-inline-flex ms-3 position-relative">
            <i class="fas fa-bell fa-2x" onclick="toggleNotifications()"></i>
            <!--hasNotif wakteli yabda andi ken notification non lu yani is_seen=0 -->
            <?php if ($hasNotif): ?> 
                <div class="notif-counter position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <!-- count bech yehseb les notifications non lue -->
                    <?php echo count($notifications); ?>
                </div>
            <?php endif; ?>
            <!--menu ili yethal mta3 notification -->
            <div class="notifications-dropdown">
                <div class="dropdown-header p-2 border-bottom">
                    <h6 class="m-0">Notifications récentes</h6>
                </div>
                <?php foreach ($notifications as $notif): ?>
                    <div class="notification-item" data-id="<?php echo $notif['IDI']; ?>" onclick="handleNotificationClick(<?php echo $notif['IDI']; ?>)">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong><?php echo $notif['fullname']; ?></strong>
                                    <div class="text-muted small"><?php echo $notif['date']; ?></div>
                                </div>
                                <?php if ($notif['is_seen'] == 0): ?>
                                    <span class="badge bg-primary">Nouveau</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Lu</span>
                                <?php endif; ?>
                            </div>
                            <div class="notification-content mt-2">
                                <p style="margin: 0;"><i class="fas fa-campground"></i> Activité : <em><?php echo $notif['activite']; ?></em></p>
                                <p style="margin: 0;"><i class="fas fa-map-marker-alt"></i> Lieu : <em><?php echo $notif['lieu']; ?></em></p>
                            </div>
                        </div>

                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

    <!-- Barre latérale -->
    
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
       
       
        <div class="logout">
            <a href="#"><i class="fas fa-sign-in-alt"></i> Se Déconnecter</a>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="content">
        <h1>Activités et Planification</h1>
        <div class="row mt-4 d-flex justify-content-between">
            <!-- Ajouter Activité -->
            <div class="col-md-3">
                <div class="card bg-success mb-3">
                    <div class="card-body text-white text-center">
                        <h5 class="card-title"><i class="fas fa-calendar-plus"></i> Ajouter Activité</h5>
                        <a href="ajouter_act.php">Voir →</a>
                    </div>
                </div>
            </div>
    
            <!-- Consulter Activité -->
            <div class="col-md-3">
                <div class="card bg-primary mb-3">
                    <div class="card-body text-white text-center">
                        <h5 class="card-title"><i class="fas fa-calendar-alt"></i> Consulter Activité</h5>
                        <a href="consulter_act.php">Voir →</a>
                    </div>
                </div>
            </div>
    
            <!-- Ajouter Planification -->
            <div class="col-md-3">
                <div class="card bg-warning mb-3">
                    <div class="card-body text-white text-center">
                        <h5 class="card-title"><i class="fas fa-calendar-plus"></i> Ajouter Planification</h5>
                        <a href="ajouter_plan.php">Voir →</a>
                    </div>
                </div>
            </div>
    
            <!-- Consulter Planification -->
            <div class="col-md-3">
                <div class="card bg-danger mb-3">
                    <div class="card-body text-white text-center">
                        <h5 class="card-title"><i class="fas fa-calendar-alt"></i> Consulter Planification</h5>
                        <a href="consulter_plan.php">Voir →</a>
                    </div>
                </div>
            </div>


        </div>
       <!-- Contenu principal -->
    <!-- Section Statistiques modernisée -->
    <div class="statistique-section mt-5">
        <div class="statistique-card" style="
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: none;
            position: relative;
            overflow: hidden;
            
        ">
            <!-- Effet décoratif -->
            <div style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 8px;
                background: linear-gradient(90deg, #6e8efb, #a777e3);
            "></div>
            
            <div class="statistique-header d-flex align-items-center justify-content-center mb-4" style="
                padding-bottom: 1rem;
                border-bottom: 1px solid rgba(0,0,0,0.1);
            ">
                <i class="fas fa-chart-bar fa-2x me-3" style="
                    background: linear-gradient(135deg, #6e8efb, #a777e3);
                    -webkit-background-clip: text;
                    background-clip: text;
                    -webkit-text-fill-color: transparent;
                "></i>
                <h3 class="m-0" style="
                    font-weight: 700;
                    color: #2c3e50;
                    font-size: 1.5rem;
                ">Statistiques des Activités</h3>
            </div>
            
            <div class="chart-container" style="
                position: relative;
                height: 350px;
                width: 100%;
            ">
                <canvas id="statistiquesChart"></canvas>
            </div>
            
            <!-- Résumé des stats -->
            <div class="stats-summary mt-4 pt-3" style="
                display: flex;
                justify-content: space-around;
                flex-wrap: wrap;
                gap: 1rem;
            ">
                <div class="stat-item text-center" style="
                    background: rgba(110, 142, 251, 0.1);
                    padding: 1rem;
                    border-radius: 12px;
                    min-width: 150px;
                    flex: 1;
                ">
                    <div class="stat-value fw-bold" style="
                        font-size: 1.5rem;
                        color: #6e8efb;
                    " id="totalInscriptions">0</div>
                    <div class="stat-label" style="
                        color: #7f8c8d;
                        font-size: 0.9rem;
                    ">Total inscriptions</div>
                </div>
                
                <div class="stat-item text-center" style="
                    background: rgba(167, 119, 227, 0.1);
                    padding: 1rem;
                    border-radius: 12px;
                    min-width: 150px;
                    flex: 1;
                ">
                    <div class="stat-value fw-bold" style="
                        font-size: 1.5rem;
                        color: #a777e3;
                    " id="averageInscriptions">0</div>
                    <div class="stat-label" style="
                        color: #7f8c8d;
                        font-size: 0.9rem;
                    ">Moyenne</div>
                </div>
                
                <div class="stat-item text-center" style="
                    background: rgba(76, 175, 80, 0.1);
                    padding: 1rem;
                    border-radius: 12px;
                    min-width: 150px;
                    flex: 1;
                ">
                    <div class="stat-value fw-bold" style="
                        font-size: 1.5rem;
                        color: #4CAF50;
                    " id="mostPopularCount">0</div>
                    <div class="stat-label" style="
                        color: #7f8c8d;
                        font-size: 0.9rem;
                    ">Max inscriptions</div>
                </div>
                
                <div class="stat-item text-center" style="
                    background: rgba(255, 152, 0, 0.1);
                    padding: 1rem;
                    border-radius: 12px;
                    min-width: 150px;
                    flex: 1;
                ">
                    <div class="stat-value fw-bold" style="
                        font-size: 1.2rem;
                        color: #FF9800;
                        word-break: break-word;
                    " id="mostPopularActivity">-</div>
                    <div class="stat-label" style="
                        color: #7f8c8d;
                        font-size: 0.9rem;
                    ">Activité populaire</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.getElementById('toggleSidebar').addEventListener('click', function () {
        document.querySelector('.sidebar').classList.toggle('collapsed');
        document.querySelector('.content').classList.toggle('collapsed-content');
    });

    // Récupération des données PHP dans JavaScript
    const stats = <?php echo json_encode($statistiques); ?>;
    const labels = stats.map(item => item.nom_activite);
    const data = stats.map(item => item.nb_inscriptions);

    // Calcul des métriques
    const totalInscriptions = data.reduce((a, b) => a + b, 0);
    const averageInscriptions = (totalInscriptions / data.length).toFixed(1);
    const maxInscriptions = Math.max(...data);
    const mostPopularIndex = data.indexOf(maxInscriptions);
    const mostPopularActivity = labels[mostPopularIndex];

    // Mise à jour des métriques
    document.getElementById('totalInscriptions').textContent = totalInscriptions;
    document.getElementById('averageInscriptions').textContent = averageInscriptions;
    document.getElementById('mostPopularCount').textContent = maxInscriptions;
    document.getElementById('mostPopularActivity').textContent = mostPopularActivity;

    // Couleurs dynamiques avec dégradé
    const backgroundColors = labels.map((_, i) => {
        const hue = (i * 30) % 360;
        return `hsl(${hue}, 70%, 65%)`;
    });

    const ctx = document.getElementById('statistiquesChart').getContext('2d');
    
    // Création du graphique moderne
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: "Inscriptions",
                data: data,
                backgroundColor: backgroundColors,
                borderColor: backgroundColors.map(c => c.replace('65%)', '55%)')),
                borderWidth: 2,
                borderRadius: 8,
                hoverBackgroundColor: backgroundColors.map(c => c.replace('65%)', '50%)')),
                hoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Nombre d\'inscriptions par activité',
                    color: '#2c3e50',
                    font: {
                        size: 18,
                        weight: 'bold',
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                    },
                    padding: { top: 10, bottom: 20 }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.85)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#6e8efb',
                    borderWidth: 1,
                    cornerRadius: 10,
                    padding: 12,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return `${context.parsed.y} inscription(s)`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 1,
                        color: '#7f8c8d'
                    },
                    suggestedMax: maxInscriptions + 1
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#7f8c8d',
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeOutQuart'
            }
        }
    });
    document.querySelector('.login-icon').addEventListener('click', function() {
    const notifDropdown = document.querySelector('.notifications-dropdown');
    notifDropdown.style.display = notifDropdown.style.display === 'none' ? 'block' : 'none';
});

// Gestion des notifications
document.querySelector('.login-icon').addEventListener('click', function(e) {
    // Empêche la propagation pour ne pas fermer immédiatement
    e.stopPropagation();
    
    const notifDropdown = document.querySelector('.notifications-dropdown');
    const isVisible = notifDropdown.style.display === 'block';
    
    // Ferme tous les dropdowns ouverts
    document.querySelectorAll('.notifications-dropdown').forEach(dropdown => {
        dropdown.style.display = 'none';
    });
    
    // Ouvre/ferme le dropdown actuel
    notifDropdown.style.display = isVisible ? 'none' : 'block';
    
    // Si on ouvre les notifications, on supprime le compteur
    if (!isVisible) {
        const counter = document.querySelector('.notif-counter');
        if (counter) counter.remove();
        
       
    }
});

// Ferme le dropdown quand on clique ailleurs
document.addEventListener('click', function() {
    document.querySelectorAll('.notifications-dropdown').forEach(dropdown => {
        dropdown.style.display = 'none';
    });
});

// Empêche la fermeture quand on clique dans le dropdown
document.querySelectorAll('.notifications-dropdown').forEach(dropdown => {
    dropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
document.addEventListener('DOMContentLoaded', function () {
    // Ajouter un événement pour chaque notification
    const notifications = document.querySelectorAll('.notification-item');
    
    notifications.forEach(function (notif) {
        notif.addEventListener('click', function () {
            handleNotificationClick(notif.dataset.id);
        });
    });
});

function toggleNotifications() {
    const dropdown = document.querySelector('.notifications-dropdown');
    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
}

// Fonction pour marquer une notification comme lue
function handleNotificationClick(IDI) {
    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `mark_as_seen=1&IDI=${IDI}`
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Message de succès
        location.reload(); // Recharge pour mettre à jour l'affichage
    })
    .catch(error => {
        console.error("Erreur lors de la mise à jour :", error);
    });
}
</script>

</body>
</html>