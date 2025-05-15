<?php
include_once __DIR__ . '/../../../config.php';
include_once "../../../controller/userlistC.php";
$UserC = new userlistC();
$var = $UserC->allusers();

session_start();
$username = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : 'Guest';
$email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : 'No Email';
$image = isset($_SESSION['user']['image']) ? $_SESSION['user']['image'] : 'No image';
try {
    $pdo = config::getConnexion();
    
    if (isset($_POST['search']) && !empty($_POST['search'])) {
        $search = $_POST['search'];
        $stmt = $pdo->prepare("SELECT titre, photo FROM activite WHERE titre LIKE :search");
        $stmt->execute(['search' => "%$search%"]);
    } else {
        $stmt = $pdo->prepare("SELECT titre, photo FROM activite");
        $stmt->execute();
    }

    $activites = $stmt->fetchAll();

    // >>> AJOUT : Charger les lieux pour la carte <<< 
    $lieuStmt = $pdo->prepare("SELECT p.lieu, a.titre, a.photo
                               FROM planification p
                               JOIN activite a ON p.nom_activite = a.titre");
    $lieuStmt->execute();
    $localisations = $lieuStmt->fetchAll(PDO::FETCH_ASSOC);

    // Organiser les activités par lieu
    $groupedByLieu = [];
    foreach ($localisations as $localisation) {
        $lieu = $localisation['lieu'];
        if (!isset($groupedByLieu[$lieu])) {
            $groupedByLieu[$lieu] = [];
        }
        $groupedByLieu[$lieu][] = ['titre' => $localisation['titre'], 'photo' => $localisation['photo']];
    }

    // Retourner le résultat sous forme de JSON
    $localisationsJSON = json_encode($groupedByLieu);

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
  <style>
  .dropdown {
  position: relative;
}

.dropdown-menu {
  position: absolute;
  top: 60px;
  right: 0;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  list-style: none;
  padding: 10px 0;
  display: none;
  min-width: 180px;
  z-index: 1000;
}



.dropdown-menu li {
  padding: 10px 20px;
}

.dropdown-menu li a {
  color: #333;
  text-decoration: none;
  display: block;
  font-weight: bold;
}

.dropdown-menu li a:hover {
  background-color: #f0f0f0;
}

.dropdown-header {
  padding: 10px 20px;
  background-color: #f7f7f7;
  font-size: 14px;
  color: #555;
  border-bottom: 1px solid #ddd;
  text-align: center;
}

.dropdown-divider {
  border-top: 1px solid #eee;
  margin: 5px 0;
}
.dropdown-menu {
  z-index: 9999;
}

</style>

</head>
<body>

<header>
  <div class="logo">
    <img src="image/maison.jpg" alt="Logo BungOFF" class="logo-img">
    Bung<span class="off">OFF</span>
  </div>
  <nav>
    <ul>
      <li><a href="../../User/frontoffice/homePage.php">Accueil</a></li>
       <li class="active"><a href="../../bungalow/frontoffice/bungalow_front.php"><i class="fas fa-home"></i> Bungalows</a></li>
      <li><a href="activite.php"><i class="fas fa-bicycle"></i> Activités</a></li>
      <li><a href="#"><i class="fas fa-car"></i> Transports</a></li>
      <li><a href="../../Compagne/frontoffice/promotions_front.php"><i class="fas fa-credit-card"></i> Promotions</a></li>
      <li><a href="../../Avis/frontoffice/index.php"><i class="fas fa-comments"></i> Avis</a></li>
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
   <img id="profileDropdown"
     src="../../User/frontoffice/user_images/<?php echo htmlspecialchars($image); ?>" 
     alt="Profile" 
     style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; cursor: pointer;">


            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li class="dropdown-header text-center">
                    <strong><?php echo htmlspecialchars($username); ?></strong><br>
                    <small><?php echo htmlspecialchars($email); ?></small>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="../../User/frontoffice/editProfile.php">Edit Profile</a></li>
                <li><a class="dropdown-item" href="../../User/frontoffice/logout.php">Log Out</a></li>
            </ul>
        </div>
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
  <form method="POST" action="#resultats" style="margin-top: 20px; text-align: center;">
    <input type="text" name="search" placeholder="Rechercher une activité..." style="padding: 8px; width: 200px;">
    <button type="submit" style="padding: 8px 12px; background-color: #3498db; color: white; border: none; cursor: pointer;">Rechercher</button>
    <button type="button" onclick="window.location.href='activite.php';" style="padding: 8px 12px; background-color: #2ecc71; color: white; border: none; cursor: pointer; margin-left:10px;">Réinitialiser</button>
  </form>
</section>

<main id="resultats" class="activities-container">
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

<script>
  var map = L.map('map').setView([34.0, 9.0], 6); // Centrage sur la Tunisie
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  var activities = <?php echo $localisationsJSON; ?>;

  // Parcourir les lieux et regrouper les activités sous un seul marqueur par lieu
  Object.entries(activities).forEach(([lieu, activities]) => {
    //Fait une requête vers l’API Nominatim (OpenStreetMap) pour convertir le nom du lieu (ex : "Tozeur") en coordonnées GPS (latitude, longitude).
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(lieu)}`)
      .then(response => response.json())
      .then(data => {
        if (data && data[0]) {
          const lat = data[0].lat;
          const lon = data[0].lon;

          let popupContent = "";
          activities.forEach(activity => {
            popupContent += `<strong>${activity.titre}</strong><br><img src="image/${activity.photo}" width="100"><br><hr>`;
          });

          // Ajouter le marqueur au lieu
          const marker = L.marker([lat, lon]).addTo(map);
          marker.bindPopup(popupContent);
        } else {
          console.warn(`Lieu introuvable : ${lieu}`);
        }
      })
      .catch(error => console.error("Erreur géocodage :", error));
  });
  //pour session
   const profile = document.getElementById('profileDropdown');
  const dropdownMenu = document.querySelector('.dropdown-menu');

  profile.addEventListener('click', function (e) {
    e.stopPropagation(); // empêche la fermeture immédiate
    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
  });

  // Ferme le menu quand on clique en dehors
  window.addEventListener('click', function () {
    dropdownMenu.style.display = 'none';
  });
</script>

<script src="activite.js"></script>

</body>
</html>
