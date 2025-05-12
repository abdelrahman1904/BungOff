<?php
include_once '../../../controller/userlistC.php';
include_once '../../../model/userlist.php';

$userController = new userlistC();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $userData = $userController->findusers($id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $updatedUser = new userlist(
            $_POST['fullname'],
            $_POST['username'],
            $_POST['email'],
            $_POST['pass'],
            $_POST['age'],
            $_POST['image'],
            "admin",
            $_POST['is2f'],
            $_POST['is2f_secret']
        );
        $userController->updateuser($updatedUser, $id);
        header('Location: userlist.php');
        exit();
    }
} else {
    header('Location: userlist.php');
    exit();
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
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Barre en haut -->
    <div class="top-bar">
        <div class="logo">Bung<span class="off">OFF</span></div>
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
  <a href="homePage.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
  <a href="userlist.php"><i class="fas fa-user"></i> Utilisateurs</a>
  <a href="#"><i class="fas fa-home"></i> Bungalows</a>
  <a href="#"><i class="fas fa-campground"></i> Activités</a>
  <a href="#"><i class="fas fa-car"></i> Transports</a>
  <a href="../../Compagne/backoffice/promotion.php"><i class="fas fa-credit-card"></i> Promotion</a>
  <a href="../../Avis/backoffice/index.php"><i class="fas fa-star"></i> Avis</a>
  <div class="logout">
    <a href="#"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a>
  </div>
</div>

    <!-- Contenu principal -->
    <div class="container mt-5">
        <h1 style="margin-top: 140px;">Modifier Utilisateur</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="fullname" class="form-label">Nom Complet</label>
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($userData['fullname']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Âge</label>
                <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($userData['age']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="userlist.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <script>
        function toggleDashboard() {
            let dashboardItem = document.getElementById("dashboard-item");
            dashboardItem.classList.toggle("active");
        }
    </script>

</body>
</html>