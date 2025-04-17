<?php
require_once '../../models/config.php';

try {
    $pdo = config::getConnexion();
    $stmt = $pdo->prepare("SELECT titre, photo FROM activite");
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
  <link rel="stylesheet" href="activite.css" />
  <!-- Ajoutez ces lignes dans la section <head> de votre HTML pour inclure Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
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
      <li><a href="activite.html"><i class="fas fa-bicycle"></i> Activités</a></li>
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
<section class="activities-title">
    <h2>
      <span class="letter">A</span>
      <span class="letter">C</span>
      <span class="letter">T</span>
      <span class="letter">I</span>
      <span class="letter">V</span>
      <span class="letter">I</span>
      <span class="letter">T</span>
      <span class="letter">E</span>
      <span class="letter">S</span>
    </h2>
  </section>
  <section class="activities-map">
    <div id="map" style="height: 400px;"></div>
    <h3>Localisation des Activités</h3>
  </section>
  
  <main class="activities-container">
  <?php foreach ($activites as $activite): ?>
    <div class="activity-card">
      <img src="image/<?php echo htmlspecialchars($activite['photo']); ?>" alt="<?php echo htmlspecialchars($activite['titre']); ?>">
      <div class="info-overlay">
        <h3><?php echo htmlspecialchars($activite['titre']); ?></h3>
        <a class="reserve-btn" href="details.php?titre=<?php echo urlencode($activite['titre']); ?>">plus de détails</a>

      </div>
    </div>
  <?php endforeach; ?>
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
  // Code pour gérer les sliders (si tu en as besoin)
  document.querySelectorAll('.slider').forEach(slider => {
    let slides = slider.querySelectorAll('.slide');
    let index = 0;

    setInterval(() => {
      slides[index].classList.remove('active');
      index = (index + 1) % slides.length;
      slides[index].classList.add('active');
    }, 2000);
  });
</script>

<script src="activite.js"></script>
</body>

</html> 