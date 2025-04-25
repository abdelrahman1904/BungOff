<?php
// Inclure le contrôleur
require_once __DIR__.'/../Controller/AvisController.php';
require_once __DIR__.'/../config.php';

// Créer une instance du contrôleur
$db = config::getConnexion();
$avisController = new AvisController($db);

// Récupérer la liste des avis
$listeAvis = $avisController->listAvis();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Avis</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
            height: 100px;
            resize: vertical;
        }
        .btn {
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-add {
            background-color: #4CAF50;
        }
        .btn-add:hover {
            background-color: #45a049;
        }
        .btn-edit {
            background-color: #2196F3;
        }
        .btn-edit:hover {
            background-color: #0b7dda;
        }
        .btn-delete {
            background-color: #f44336;
        }
        .btn-delete:hover {
            background-color: #d32f2f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-cell {
            white-space: nowrap;
        }
        .stars {
            color: gold;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Avis</h1>
        
        <!-- Formulaire principal -->
        <form id="avisForm" action="traitement_avis.php" method="POST">
            <input type="hidden" name="IDUtilisateur" value="">
            
            <div class="form-group">
                <label for="Nom">Nom:</label>
                <input type="text" id="Nom" name="Nom" required>
            </div>
            
            <div class="form-group">
                <label for="LieuDuBungalow">Lieu du Bungalow:</label>
                <input type="text" id="LieuDuBungalow" name="LieuDuBungalow" required>
            </div>
            
            <div class="form-group">
                <label for="ActiviteUtilisee">Activité Utilisée:</label>
                <input type="text" id="ActiviteUtilisee" name="ActivitéUtilisée" required>
            </div>
            
            <div class="form-group">
                <label for="Note">Note:</label>
                <select id="Note" name="Note">
                    <option value="">Choisir une note</option>
                    <option value="1">1 ★</option>
                    <option value="2">2 ★★</option>
                    <option value="3">3 ★★★</option>
                    <option value="4">4 ★★★★</option>
                    <option value="5">5 ★★★★★</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="Commentaire">Commentaire:</label>
                <textarea id="Commentaire" name="Commentaire" required></textarea>
            </div>
            
            <button type="button" id="btnAjouter" class="btn btn-add">Ajouter Avis</button>        </form>
        
        <!-- Tableau des avis -->
        <h2>Liste des Avis</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Lieu</th>
                    <th>Activité</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listeAvis as $avis): ?>
                    <tr>
                        <td><?= htmlspecialchars($avis['IDUtilisateur']) ?></td>
                        <td><?= htmlspecialchars($avis['Nom']) ?></td>
                        <td><?= htmlspecialchars($avis['LieuDuBungalow']) ?></td>
                        <td><?= htmlspecialchars($avis['ActivitéUtilisée']) ?></td>
                        <td><?= htmlspecialchars($avis['Note']) ?></td>
                        <td><?= htmlspecialchars($avis['Commentaire']) ?></td>
                        <td class="action-cell">
                            <form action="formulaire_modification.php" method="POST" style="display: inline;">
                                <input type="hidden" name="IDUtilisateur" value="<?= $avis['IDUtilisateur'] ?>">
                                <button type="submit" name="action" value="edit" class="btn btn-edit">Modifier</button>
                            </form>
                            <form action="supprimer.php" method="POST" style="display: inline;">
                                <input type="hidden" name="IDUtilisateur" value="<?= $avis['IDUtilisateur'] ?>">
                                <button type="submit" name="action" value="delete" class="btn btn-delete">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
 document.getElementById('btnAjouter').addEventListener('click', function() {
            // Récupération des valeurs
            const nom = document.getElementById('Nom').value.trim();
            const lieu = document.getElementById('LieuDuBungalow').value.trim();
            const activite = document.getElementById('ActiviteUtilisee').value.trim();
            const note = document.getElementById('Note').value;
            const commentaire = document.getElementById('Commentaire').value.trim();
            
            // Validation
            let erreurs = [];
            
            if (nom.length < 2 || nom.length > 50) {
                erreurs.push("- Le nom doit contenir entre 2 et 50 caractères");
            }
            
            if (lieu.length < 3 || lieu.length > 100) {
                erreurs.push("- Le lieu doit contenir entre 3 et 100 caractères");
            }
            
            if (activite.length < 3 || activite.length > 50) {
                erreurs.push("- L'activité doit contenir entre 3 et 50 caractères");
            }
            
            if (!note || note < 1 || note > 5) {
                erreurs.push("- Veuillez sélectionner une note valide (1 à 5)");
            }
            
            if (commentaire.length < 10 || commentaire.length > 500) {
                erreurs.push("- Le commentaire doit contenir entre 10 et 500 caractères");
            }
            
            // Affichage des erreurs ou soumission
            if (erreurs.length > 0) {
                alert("Veuillez corriger les erreurs suivantes :\n\n" + erreurs.join("\n"));
            } else {
                document.getElementById('avisForm').submit();
            }
        });
        </script>
</body>
</html>