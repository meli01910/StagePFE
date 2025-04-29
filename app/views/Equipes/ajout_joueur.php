<?php


// Fichier: /app/views/Users/dashboard_admin.php
// S'assurer que l'utilisateur est un admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?module=auth&action=login');
    exit;
}



 include __DIR__ . '/../templates/header.php';
// Définir le titre et la page active
// Définir le titre de la page et la page active
$pageTitle = 'Gestion des Joueurs';
$currentPage = 'players';

// Capturer le contenu
ob_start();

$message = '';
if (isset($_GET['success'])) {
    $message = "Le joueur a été ajouté avec succès !";
} elseif (isset($_GET['error'])) {
    $message = "Une erreur est survenue lors de l'ajout du joueur.";
}
?>


<div class="container mt-5">
    <h1>Ajouter un Joueur à l'Équipe</h1>

    <?php if ($message): ?>
        <div class="alert alert-info" role="alert">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="player_id" class="form-label">Choisir un joueur :</label>
            <select name="player_id" class="form-select" required>
                <?php foreach ($players as $player): ?>
                    <option value="<?= htmlspecialchars($player['id']) ?>">
                        <?= htmlspecialchars($player['nom'] . ' ' . $player['prenom'] ) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Ajouter le joueur</button>
        <a href="index.php?module=admin&action=equipe_view&id=<?= $equipeId ?>" class="btn btn-secondary">Retour à la liste des joueurs</a>
    </form>
</div>


<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../Users/adminl_layout.php';
?>

