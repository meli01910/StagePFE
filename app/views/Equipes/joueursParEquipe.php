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
?>

<div class="container mt-5">
    <h1>Liste des Joueurs de l'Équipe <?= htmlspecialchars($equipe['nom']) ?></h1> </h1>

    <table class="table">
        <thead>
            <tr>
                
                <th>Nom</th>
                <th>Prénom</th>
                <th>Poste</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($joueurs)): ?>
                <?php foreach ($joueurs as $joueur): ?>
                    <tr>
                        <td><?= htmlspecialchars($joueur['nom']) ?></td>
                        <td><?= htmlspecialchars($joueur['prenom']) ?></td>
                        <td><?= htmlspecialchars($joueur['poste']) ?></td>
                        <td><?= htmlspecialchars($joueur['email']) ?></td>
                        <td>
                            <a href="index.php?module=joueur&action=edit&id=<?= $joueur['id'] ?>" class="btn btn-warning">Modifier</a>
                            <a href="index.php?module=joueur&action=delete&id=<?= $joueur['id'] ?>" class="btn btn-danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Aucun joueur trouvé pour cette équipe.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="index.php?module=admin&action=list_equipes" class="btn btn-secondary">Retour à la liste des équipes</a>
</div>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../Users/adminl_layout.php';
?>

