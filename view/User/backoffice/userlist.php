<?php
// Include the controller
include_once '../../../controller/userlistC.php';
include_once '../../../model/userlist.php';

// Fetch all users
$userController = new userlistC();
$users = $userController->allusers();
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
<<<<<<< HEAD
  <a href="#"><i class="fas fa-campground"></i> Activités</a>
=======
  <a href="../../views/new_act.php"><i class="fas fa-campground"></i> Activités</a>
>>>>>>> 77c66e1 (Integration+bungalow)
  <a href="#"><i class="fas fa-car"></i> Transports</a>
  <a href="../../Compagne/backoffice/promotion.php"><i class="fas fa-credit-card"></i> Promotion</a>
  <a href="../../Avis/backoffice/index.php"><i class="fas fa-star"></i> Avis</a>
  <div class="logout">
    <a href="#"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a>
  </div>
</div>


    <!-- Contenu principal -->
    <div class="container mt-4">
        <h1>Liste des Utilisateurs</h1>
        <table class="table table-bordered mt-5">
            <thead>
                <tr>
                    <th>Nom Complet</th>
                    <th>Nom d'utilisateur</th>
                    <th>Email</th>
                    <th>Âge</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['age']); ?></td>
                <td>
                    <img src="<?php echo htmlspecialchars($user['image']); ?>" alt="User Image" style="width: 50px; height: 50px;">
                </td>
                <td>
                    
                    <a href="edituser.php?id=<?php echo htmlspecialchars($user['id']); ?>" class="btn btn-warning btn-sm">Modifier</a>
                    
                    <form action="deleteuser.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</button>
                    </form>
                </td>
            </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Aucun utilisateur trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function toggleDashboard() {
            let dashboardItem = document.getElementById("dashboard-item");
            dashboardItem.classList.toggle("active");
        }
    </script>

</body>
</html>