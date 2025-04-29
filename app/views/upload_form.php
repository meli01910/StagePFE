<?php
// Exemple de code pour afficher l'équipe avec son logo

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=nom_de_la_base_de_donnees', 'utilisateur', 'motdepasse');

$sql = "SELECT * FROM equipes WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => 1]); // Remplacez par l'ID de l'équipe
$equipe = $stmt->fetch();

if ($equipe) {
    echo '<h2>' . htmlspecialchars($equipe['nom']) . '</h2>';
    if (!empty($equipe['logo'])) {
        echo '<img src="' . htmlspecialchars($equipe['logo']) . '" alt="Logo de l\'équipe">';
    } else {
        echo 'Pas de logo disponible.';
    }
} else {
    echo 'Équipe non trouvée.';
}
?>
