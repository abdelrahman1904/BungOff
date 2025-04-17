<?php
require_once 'config.php';

try {
    $pdo = config::getConnexion();
    echo "Connexion à la base de données réussie!";
    
    // Test de requête simple
    $stmt = $pdo->query("SELECT COUNT(*) FROM userlist");
    $count = $stmt->fetchColumn();
    echo "<br>Nombre d'utilisateurs: " . $count;
} catch (PDOException $e) {
    die("ERREUR: " . $e->getMessage());
}
?>