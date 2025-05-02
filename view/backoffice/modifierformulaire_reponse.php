<?php

require_once __DIR__.'/../../model/reponse.php';
require_once __DIR__.'/../../controller/reponsecontroller.php';

// Récupérer l'ID depuis POST
$id_reponse = $_POST['idreponse'];

if (!$id_reponse) {
    header("Location: index.php?error=ID+reponse+manquant");
    exit();
}

// Récupérer les données de l'utilisateur
$controller = new ReponseController();
$avis = $controller->getOneWithAvis($id_reponse);

if (!$avis) {
    header("Location: index.php?error=Utilisateur+non+trouvé");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <title>Système de gestion d'avis et réponses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 800px;
      margin: 60px auto;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    input, select, textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }
    textarea {
      resize: vertical;
    }
    .btn {
      padding: 10px 15px;
      background-color: #2196F3;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .btn:hover {
      background-color: #0b7dda;
    }
  </style>
</head>
<body>
<header>
  <div class="top-bar">
    <div class="logo">Bung<span class="off">OFF</span></div>
    <div class="toggle-sidebar-icon me-3" onclick="toggleSidebar()" style="cursor: pointer;">
        <i class="fas fa-bars"></i>
    </div>
  </div>
  <div class="right-icons">
    <form class="d-inline-flex">
      <input type="text" class="form-control" placeholder="Recherche...">
      <button type="submit" class="btn btn-primary ms-2"><i class="fas fa-search"></i></button>
    </form>
    <div class="login-icon d-inline-flex ms-3">
      <i class="fas fa-user"></i>
    </div>
  </div>
</header>
<div class="sidebar">
  <a href="index.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
  <a href="#"><i class="fas fa-user"></i> Utilisateurs</a>
  <a href="#"><i class="fas fa-home"></i> Bungalows</a>
  <a href="#"><i class="fas fa-campground"></i> Activités</a>
  <a href="btransport.html"><i class="fas fa-car"></i> Transports</a>
  <a href="#"><i class="fas fa-credit-card"></i> Paiement</a>
  <a href="#"><i class="fas fa-star"></i> Avis</a>
  <div class="logout">
    <a href="#"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a>
  </div>
</div>
<div class="container">
  <h1>Modifier la Réponse</h1>
  <form method="post" action="modifier.php">
    <input type="hidden" name="id" value="<?= htmlspecialchars($avis['id_reponse']) ?>">
    
    <div class="form-group">
      <label for="poste_admin">Poste Admin:</label>
      <input type="text" id="poste_admin" name="poste_admin" value="<?= htmlspecialchars($avis['poste_admin']) ?>" required>
    </div>
    
    <div class="form-group">
      <label for="reponse_admin">Réponse Admin:</label>
      <input type="text" id="reponse_admin" name="reponse_admin" value="<?= htmlspecialchars($avis['reponse_admin']) ?>" required>
    </div>
    
    <div class="form-group">
      <label for="date_reponse">Date Réponse:</label>
      <input type="date" id="date_reponse" name="date_reponse" value="<?= htmlspecialchars($avis['date_reponse']) ?>" required>
    </div>
    
    <button type="submit" class="btn">Enregistrer</button>
  </form>
</div>
</body>
</html>