<?php
require_once '../controller/bungalowC.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $bungalowC = new BungalowC();

    // Appel de la méthode pour afficher les détails du bungalow en fonction de l'ID
    $bungalow = $bungalowC->afficherBungalowById($id); // Utilisez afficherBungalowById

    // Si aucun bungalow n'est trouvé avec cet ID
    if (!$bungalow) {
        echo "Bungalow introuvable.";
        exit();
    }
}

// Vérifiez si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $capacite = $_POST['capacite'];
    $prix_nuit = $_POST['prix_nuit'];
    $localisation = $_POST['localisation'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    // Traiter l'upload d'image si elle est modifiée
    if (!empty($image)) {
        move_uploaded_file($_FILES['image']['tmp_name'], 'frontoffice/image_video1/' . $image);
    } else {
        // Utiliser l'image actuelle si elle n'a pas été changée
        $image = $_POST['old_image'];
    }

    // Appeler la méthode pour mettre à jour les données du bungalow
    $bungalowC->modifierBungalow($id, $nom, $capacite, $prix_nuit, $localisation, $type, $description, $image);

    // Rediriger vers la page de consultation après la mise à jour
    header("Location: consulter_bung.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Bungalow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="back.css">
</head>
<body>

    <div class="container">
        <h1>Modifier Bungalow</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($bungalow['IDB']); ?>">

            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" id="nom" class="form-control" value="<?php echo htmlspecialchars($bungalow['nom']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="capacite" class="form-label">Capacité</label>
                <input type="number" name="capacite" id="capacite" class="form-control" value="<?php echo htmlspecialchars($bungalow['capacite']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="prix_nuit" class="form-label">Prix par nuit</label>
                <input type="number" name="prix_nuit" id="prix_nuit" class="form-control" value="<?php echo htmlspecialchars($bungalow['prix_nuit']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="localisation" class="form-label">Localisation</label>
                <input type="text" name="localisation" id="localisation" class="form-control" value="<?php echo htmlspecialchars($bungalow['localisation']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <input type="text" name="type" id="type" class="form-control" value="<?php echo htmlspecialchars($bungalow['type']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($bungalow['description']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-control">
                <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($bungalow['image']); ?>">
                <img src="frontoffice/image_video1/<?php echo htmlspecialchars($bungalow['image']); ?>" alt="Image" width="100">
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>

</body>
</html>
