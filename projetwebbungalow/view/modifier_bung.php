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
    $errors = [];

    // Validation du nom (doit être composé uniquement de lettres)
    if (empty($nom)) {
        $errors[] = "Le nom est requis.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $nom)) {
        $errors[] = "Le nom ne doit contenir que des lettres.";
    }

    // Validation de la capacité (doit être un nombre positif)
    if (empty($capacite)) {
        $errors[] = "Veuillez remplir le champ capacité.";
    } elseif (!is_numeric($capacite) || $capacite <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }

    // Validation du prix (doit être un nombre positif)
    if (empty($prix_nuit)) {
        $errors[] = "Veuillez remplir le champ prix par nuit.";
    } elseif (!is_numeric($prix_nuit) || $prix_nuit <= 0) {
        $errors[] = "Le prix par nuit doit être un nombre positif.";
    }

    // Validation de la localisation
    if (empty($localisation)) {
        $errors[] = "Veuillez remplir le champ localisation.";
    }

    // Validation du type
    if (empty($type)) {
        $errors[] = "Veuillez remplir le champ type.";
    }

    // Validation de la description
    if (empty($description)) {
        $errors[] = "Veuillez remplir le champ description.";
    }

    if (count($errors) === 0) {
        // Tout est OK → modifier le bungalow
        $bungalowC->modifierBungalow($id, $nom, $capacite, $prix_nuit, $localisation, $type, $description, $image);
        header("Location: consulter_bung.php");
        exit();
    } else {
        // Afficher les erreurs
        foreach ($errors as $err) {
            echo "<p style='color:red;'>$err</p>";
        }
    }
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
                <input type="text" name="nom" id="nom" class="form-control" value="<?php echo htmlspecialchars($bungalow['nom']); ?>">
            </div>

            <div class="mb-3">
                <label for="capacite" class="form-label">Capacité</label>
                <input type="text" name="capacite" id="capacite" class="form-control" value="<?php echo htmlspecialchars($bungalow['capacite']); ?>">
            </div>

            <div class="mb-3">
                <label for="prix_nuit" class="form-label">Prix par nuit</label>
                <input type="text" name="prix_nuit" id="prix_nuit" class="form-control" value="<?php echo htmlspecialchars($bungalow['prix_nuit']); ?>">
            </div>

            <div class="mb-3">
                <label for="localisation" class="form-label">Localisation</label>
                <input type="text" name="localisation" id="localisation" class="form-control" value="<?php echo htmlspecialchars($bungalow['localisation']); ?>">
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <input type="text" name="type" id="type" class="form-control" value="<?php echo htmlspecialchars($bungalow['type']); ?>">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control"><?php echo htmlspecialchars($bungalow['description']); ?></textarea>
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

    <script>
    document.querySelector('form').addEventListener('submit', function(event) {
        let valid = true;

        const nom = document.getElementById('nom').value.trim();
        const capacite = document.getElementById('capacite').value;
        const prix = document.getElementById('prix_nuit').value;
        const localisation = document.getElementById('localisation').value.trim();
        const type = document.getElementById('type').value.trim();
        const description = document.getElementById('description').value.trim();

        // Validation du nom (uniquement des lettres et espaces)
        if (nom === '') {
            alert("Le nom est requis.");
            valid = false;
        } else if (!/^[a-zA-Z\s]+$/.test(nom)) {
            alert("Le nom ne doit contenir que des lettres.");
            valid = false;
        }

        // Validation de la capacité (doit être un nombre positif)
        if (capacite === '') {
            alert("Veuillez remplir le champ capacité.");
            valid = false;
        } else if (isNaN(capacite) || capacite <= 0) {
            alert("La capacité doit être un nombre positif.");
            valid = false;
        }

        // Validation du prix (doit être un nombre positif)
        if (prix === '') {
            alert("Veuillez remplir le champ prix par nuit.");
            valid = false;
        } else if (isNaN(prix) || prix <= 0) {
            alert("Le prix par nuit doit être un nombre positif.");
            valid = false;
        }

        // Validation de la localisation
        if (localisation === '') {
            alert("Veuillez remplir le champ localisation.");
            valid = false;
        }

        // Validation du type
        if (type === '') {
            alert("Veuillez remplir le champ type.");
            valid = false;
        }

        // Validation de la description
        if (description === '') {
            alert("Veuillez remplir le champ description.");
            valid = false;
        }

        if (!valid) {
            event.preventDefault(); // Empêche l'envoi du formulaire
        }
    });
    </script>

</body>
</html>
