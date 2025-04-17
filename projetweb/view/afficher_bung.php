<?php
require_once '../model/bungalow.php';
require_once '../controller/bungalowC.php';

$errors = [];
$imageName = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gestion de l’upload de l’image
    $imageName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Récupérer le nom de l'image
        $imageName = basename($_FILES['image']['name']);
        
        // Définir le répertoire de destination
        $targetDir = "../image_video1/";
        $targetFile = $targetDir . $imageName;
        
        // Déplacer le fichier téléchargé vers le répertoire uploads
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // L'image a été téléchargée avec succès
            echo "L'image a été téléchargée avec succès.";
        } else {
            // L'image n'a pas été téléchargée
            echo "Désolé, une erreur est survenue lors du téléchargement de l'image.";
        }
    }

    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $capacite = $_POST['capacite'];
    $prix_nuit = $_POST['prix_nuit'];
    $localisation = $_POST['localisation'];
    $type = $_POST['type'];
    $description = $_POST['description'];

    // Contrôle de saisie simple
    if (empty($nom)) {
        $errors[] = "Le nom est requis.";
    }
    if (empty($capacite) || !is_numeric($capacite) || $capacite <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }
    if (empty($prix_nuit) || !is_numeric($prix_nuit) || $prix_nuit <= 0) {
        $errors[] = "Le prix par nuit doit être un nombre positif.";
    }
    if (empty($localisation)) {
        $errors[] = "La localisation est requise.";
    }
    if (empty($type)) {
        $errors[] = "Le type est requis.";
    }
    if (empty($description)) {
        $errors[] = "La description est requise.";
    }

    // Si aucun problème, on ajoute le bungalow en base de données
    if (empty($errors)) {
        // Création de l’objet Bungalow
        $bungalow = new Bungalow($nom, $capacite, $prix_nuit, $localisation, $type, $description, $imageName);
        
        // Insertion en base de données
        $bungalowC = new BungalowC();
        $bungalowC->ajouterBungalow($bungalow);

        // Redirection ou message de confirmation
        header("Location: ajouter_bung.php?success=1");
        exit();
    }
}
?>
