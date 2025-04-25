<?php
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../model/Avis.php';
require_once __DIR__.'/../controller/AvisController.php';

// Récupérer l'ID depuis POST
$IDUtilisateur = $_POST['IDUtilisateur'];

if (!$IDUtilisateur) {
    header("Location: index.php?error=ID+utilisateur+manquant");
    exit();
}

// Récupérer les données de l'utilisateur
$controller = new AvisController();
$avis = $controller->getAvis($IDUtilisateur);

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
    <h1>Modifier l'avis <?= htmlspecialchars($avis['Nom']) ?></h1>
    
    <form method="post" action="modifier.php">
    <input type="hidden" name="id" value="<?= $avis['IDUtilisateur'] ?>">
    
    <input type="text" name="nom" value="<?= $avis['Nom'] ?>" required>
    <input type="text" name="lieu" value="<?= $avis['LieuDuBungalow'] ?>" required>
    <input type="text" name="activite" value="<?= $avis['ActivitéUtilisée'] ?>" required>
    
    <select name="note" required>
        <?php for ($i=1; $i<=5; $i++): ?>
            <option value="<?= $i ?>" <?= $i==$avis['Note']?'selected':'' ?>>
                <?= str_repeat('★', $i) ?>
            </option>
        <?php endfor; ?>
    </select>
    
    <textarea name="commentaire" required><?= $avis['Commentaire'] ?></textarea>
    
    <button type="submit">Enregistrer</button>
</form>
</body>
</html>