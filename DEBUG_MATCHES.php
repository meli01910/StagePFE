<?php
require_once 'config/db.php';
require_once 'app/models/Matchs.php';

// ID du tournoi à tester
$tournoiId = $_GET['id'] ?? 1; // Remplacez 1 par l'ID que vous souhaitez tester

echo "<h1>Débogage de genererMatchsPoules pour tournoi ID: $tournoiId</h1>";

$matchModel = new App\models\Matchs($pdo);

try {
    // Test étape par étape
    echo "<h2>Vérification des poules</h2>";
    $stmt = $pdo->prepare("SELECT id FROM poules WHERE tournoi_id = ?");
    $stmt->execute([$tournoiId]);
    $poules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Nombre de poules trouvées: " . count($poules) . "<br>";
    if (empty($poules)) {
        echo "<div style='color:red'>ERREUR: Aucune poule trouvée pour ce tournoi!</div>";
        exit;
    }
    
    foreach ($poules as $poule) {
        $pouleId = $poule['id'];
        echo "<h3>Poule ID: $pouleId</h3>";
        
        // Vérifier les équipes dans cette poule
        $stmt = $pdo->prepare("
            SELECT e.id, e.nom 
            FROM equipes e
            JOIN equipe_poule ep ON e.id = ep.equipe_id
            WHERE ep.poule_id = ?
        ");
        $stmt->execute([$pouleId]);
        $equipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Nombre d'équipes dans cette poule: " . count($equipes) . "<br>";
        if (count($equipes) < 2) {
            echo "<div style='color:red'>ERREUR: Cette poule a moins de 2 équipes!</div>";
            continue;
        }
        
        echo "<ul>";
        foreach ($equipes as $equipe) {
            echo "<li>ID: {$equipe['id']} - Nom: {$equipe['nom']}</li>";
        }
        echo "</ul>";
    }
    
    echo "<h2>Structure de la table matchs</h2>";
    $stmt = $pdo->prepare("DESCRIBE matchs");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
    echo "<h2>Test de la génération</h2>";
    echo "<a href='DEBUG_MATCHES.php?id=$tournoiId&action=generate'>Cliquer ici pour tester la génération</a>";
    
    if (isset($_GET['action']) && $_GET['action'] === 'generate') {
        $result = $matchModel->genererMatchsPoules($tournoiId);
        if ($result) {
            echo "<div style='color:green'>Génération réussie!</div>";
        } else {
            echo "<div style='color:red'>Échec de la génération!</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div style='color:red; font-weight:bold;'>Exception: " . $e->getMessage() . "</div>";
}
?>
