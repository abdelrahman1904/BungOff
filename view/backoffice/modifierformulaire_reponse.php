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
    <title>Modifier Avis</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select, textarea { 
            width: 100%; 
            padding: 8px; 
            box-sizing: border-box;
        }
        .btn { 
            padding: 10px 15px; 
            background: #4CAF50; 
            color: white; 
            border: none; 
            cursor: pointer; 
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Modifier l'reponse <?= htmlspecialchars($avis['id_reponse']) ?></h1>
    
    <form method="post" action="modifier.php">
    <input type="hidden" name="id" value="<?= $avis['id_reponse'] ?>">
    
    <input type="text" name="poste_admin" value="<?= $avis['poste_admin'] ?>" required>
    <input type="text" name="reponse_admin" value="<?= $avis['reponse_admin'] ?>" required>
    <input type="date" name="date_reponse" value="<?= $avis['date_reponse'] ?>" required>
    
    <button type="submit">Enregistrer</button>
</form>
</body>
</html>