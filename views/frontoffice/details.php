<?php
require_once '../../models/config.php';

try {
    $pdo = config::getConnexion();
    $stmt = $pdo->prepare("
    SELECT a.IDA, a.titre, a.description, a.NBp, a.prix, a.photo,
           p.date, p.heure_debut, p.heure_fin, p.capacite, p.lieu
    FROM activite a
    LEFT JOIN planification p ON a.titre = p.nom_activite
    ORDER BY a.IDA, p.date, p.heure_debut
");

    $stmt->execute();
    $rows = $stmt->fetchAll();

    $activites = [];
    foreach ($rows as $row) {
        $titre = $row['titre'];
        if (!isset($activites[$titre])) {
            $activites[$titre] = [
                'titre' => $titre, // Ajouté ici pour corriger l'erreur
                'description' => $row['description'],
                'NBp' => $row['NBp'],
                'prix' => $row['prix'],
                'photo' => $row['photo'],
                'planifications' => []
            ];
        }

        if ($row['date'] !== null && $row['heure_debut'] !== null && $row['heure_fin'] !== null) {
            $activites[$titre]['planifications'][] = [
                'date' => $row['date'],
                'heure_debut' => $row['heure_debut'],
                'heure_fin' => $row['heure_fin'],
                'capacite' => $row['capacite'],
                'lieu' => $row['lieu']
            ];
        }
    }

} catch (Exception $e) {
    die("Erreur lors de la récupération des activités : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Activités - BungOFF</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <link rel="stylesheet" href="details.css">
</head>
<body>

<header>
  <div class="logo">
    <img src="image/maison.jpg" alt="Logo BungOFF" class="logo-img">
    Bung<span class="off">OFF</span>
  </div>
  <nav>
    <ul>
      <li><a href="index.html">Accueil</a></li>
      <li><a href="#"><i class="fas fa-home"></i> Bungalows</a></li>
      <li><a href="activite.php"><i class="fas fa-bicycle"></i> Activités</a></li>
      <li><a href="#"><i class="fas fa-car"></i> Transports</a></li>
      <li><a href="#"><i class="fas fa-credit-card"></i> Promotions</a></li>
      <li><a href="#"><i class="fas fa-comments"></i> Avis</a></li>
    </ul>
  </nav>
  <div class="extra-info">
    <div class="weather">
      <i class="fas fa-cloud weather-icon"></i>
      <div class="weather-info">
        <div class="ville">Tunis</div>
        <div class="temperature">22°C</div>
      </div>
    </div>
    <i class="fas fa-search search-icon"></i>
    <i class="fas fa-user login-icon"></i>
  </div>
</header>

<main class="all-activities-container">
  <h1 class="main-title">Découvrez Nos Activités</h1>
  
  <div class="activities-grid">
  <?php foreach ($activites as $activite): ?>
  <div class="activity-card">
    <div class="activity-badge">
      <span class="places"><?php echo htmlspecialchars($activite['NBp']); ?> places</span>
      <span class="price"><?php echo htmlspecialchars($activite['prix']); ?> DT</span>
    </div>
    <img src="image/<?php echo htmlspecialchars($activite['photo']); ?>" alt="<?php echo htmlspecialchars($activite['titre']); ?>">
    <div class="activity-content">
      <h3><?php echo htmlspecialchars($activite['titre']); ?></h3>
      
      <div class="availability-toggle">
        <button class="availability-btn pulse">
          <i class="fas fa-calendar-alt"></i> Voir planification
        </button>
      </div>

      <div class="availability-dates">
        <?php if (!empty($activite['planifications'])): ?>
          <?php foreach ($activite['planifications'] as $p): ?>
            <div class="time-slot">
              <div class="time-card">
                <div class="time-content">
                <div class="time-group" style="display: flex; flex-direction: column; gap: 8px;">
                  <div class="time-item">
                    <i class="fas fa-calendar-day icon"></i>
                    <span class="time-label">Date: </span>
                    <span class="time-value"><?php echo htmlspecialchars($p['date']); ?></span>
                  </div>
                  <div class="time-item">
                    <i class="fas fa-clock icon"></i>
                    <span class="time-label">Horaire: </span>
                    <span class="time-value"><?php echo htmlspecialchars($p['heure_debut']); ?>-<?php echo htmlspecialchars($p['heure_fin']); ?></span>
                  </div>
                  <div class="time-item">
                    <i class="fas fa-map-marker-alt icon"></i>
                      <span class="time-label">Lieu: </span>
                      <span class="time-value"><?php echo htmlspecialchars($p['lieu']); ?></span>
                  </div>
                </div>
                <span class="remaining">(<?php echo htmlspecialchars($p['capacite']); ?> places restantes)</span>

                <button class="register-btn"
                  data-titre="<?php echo htmlspecialchars($activite['titre']); ?>"
                  data-date="<?php echo htmlspecialchars($p['date']); ?>"
                  data-heure="<?php echo htmlspecialchars($p['heure_debut']); ?>">
                  <i class="fas fa-user-plus"></i> S'inscrire
                </button>

                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="no-planning" style="color: #FF5733;">Aucune planification disponible pour cette activité.</p>
        <?php endif; ?>
      </div>

      <p class="activity-desc"><?php echo htmlspecialchars($activite['description']); ?></p>
    </div>
  </div>
  <?php endforeach; ?>
</div>

</main>

<section class="blue-section">
  <div class="info">
    <div class="services-info">
      <h3>Nos Services</h3>
      <p>Location de bungalows</p>
      <p>Activités de groupe</p>
      <p>Transports privés</p>
    </div>
    <div class="contact-info">
      <h3>Contactez-nous</h3>
      <p>Email : contact@bungoff.com</p>
      <p>Téléphone : +216 94245514</p>
      <p>Adresse : Ariana, Tunisie</p>
    </div>
    <div class="social-info">
      <h3>Suivez-nous</h3>
      <p>Facebook</p>
      <p>Instagram</p>
      <p>Twitter</p>
    </div>
    <div class="reserve-now">
      <p>Réservez dès maintenant !</p>
    </div>
  </div>
</section>

<script>
// Animation des boutons de disponibilité
document.querySelectorAll('.availability-btn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.stopPropagation();
    const datesContainer = this.closest('.availability-toggle').nextElementSibling;
    const isHidden = datesContainer.style.display === 'none' || !datesContainer.style.display;

    // Fermer tous les autres
    document.querySelectorAll('.availability-dates').forEach(container => {
      if (container !== datesContainer) {
        container.style.display = 'none';
        container.previousElementSibling.querySelector('.availability-btn').classList.remove('active');
      }
    });

    // Basculer l'état actuel
    if (isHidden) {
      datesContainer.style.display = 'block';
      this.classList.add('active');
      datesContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
      datesContainer.style.display = 'none';
      this.classList.remove('active');
    }
  });
});

// Gestion du bouton "S'inscrire"
document.querySelectorAll('.register-btn').forEach(btn => {
  // Vérifier l'état initial du bouton lorsque la page est chargée
  const remainingSpan = btn.closest('.time-slot').querySelector('.remaining');
  const placesText = remainingSpan.textContent.match(/\d+/);
  if (placesText) {
    let places = parseInt(placesText[0]);
    if (places === 0) {
      btn.classList.add('disabled');
      btn.style.backgroundColor = 'gray';  
      btn.innerHTML = `<i class="fas fa-user-slash"></i> Complet`;
    }
  }

  // Ajouter l'écouteur d'événements pour la réservation
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    const remainingSpan = this.closest('.time-slot').querySelector('.remaining');
    const placesText = remainingSpan.textContent.match(/\d+/);

    if (placesText) {
      let places = parseInt(placesText[0]);
      if (places > 0) {
        places--;
        remainingSpan.textContent = `(${places} places restantes)`; 

        if (places === 0) {
          this.classList.add('disabled');
          this.style.backgroundColor = 'gray';
          this.innerHTML = `<i class="fas fa-user-slash"></i> Complet`;
        }

        // Maintenant l'envoi de l'inscription avec fetch ili fi boutton s'incrire ili hatithom
        // this heya boutton clique 
        const titre = this.dataset.titre;
        const date = this.dataset.date;
        const heure = this.dataset.heure;

        fetch('inscrire.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `titre=${encodeURIComponent(titre)}&date=${encodeURIComponent(date)}&heure=${encodeURIComponent(heure)}`
        })
        // mbaed houni bech yakra reponse ili jetou min inscrire.php w ykharejha fi chakel alerte
        .then(response => response.text())
        .then(data => {
          alert(data); // Message que tu peux personnaliser selon ce que retourne inscrire.php
        })
        .catch(error => {
          console.error('Erreur:', error);
        });
      }
    }
  });
});
</script>


</body>
</html>
