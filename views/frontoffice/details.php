<?php
require_once '../../models/config.php';

try {
    $pdo = config::getConnexion();
    $stmt = $pdo->prepare("SELECT titre, description,NBp, prix, photo FROM activite");
    $stmt->execute();
    $activites = $stmt->fetchAll();
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
          <span class="price"><?php echo htmlspecialchars($activite['prix']); ?> TND</span>
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
            <div class="time-slot">
              <form class="time-slot-form">
                <select class="date-combo">
                </select>
                <select class="time-combo">
                </select>
                <button class="register-btn shine">
                  <i class="fas fa-user-plus"></i> S'inscrire
                </button>
              </form>
              <span class="remaining">(<?php echo htmlspecialchars($activite['NBp']); ?> places restantes)</span>
            </div>
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

  // Gestion des réservations
  document.querySelectorAll('.register-btn:not(.disabled)').forEach(btn => {
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
          }
        }
      }
    });
  });

  // Fermer les disponibilités en cliquant ailleurs
  document.addEventListener('click', function() {
    document.querySelectorAll('.availability-dates').forEach(container => {
      container.style.display = 'none';
      container.previousElementSibling.querySelector('.availability-btn').classList.remove('active');
    });
  });

  // Empêcher la fermeture quand on clique dans le container
  document.querySelectorAll('.availability-dates, .availability-btn').forEach(el => {
    el.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  });
</script>

</body>
</html>
