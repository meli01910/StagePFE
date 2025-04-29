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

<!-- Fichier: /app/views/Equipes/show.php -->
<div class="container mt-5">
    <h1 class="mb-4">Détails de l'Équipe : <?= htmlspecialchars($equipe['nom']) ?></h1>

    <div class="card mb-4">
        <img src="<?= htmlspecialchars($equipe['logo']) ?>" class="card-img-top" alt="Logo de l'équipe">
        <div class="card-body">
            <h5 class="card-title">Informations de l'Équipe</h5>
            <p class="card-text"><strong>ID:</strong> <?= htmlspecialchars($equipe['id']) ?></p>
            <p class="card-text"><strong>Email de Contact:</strong> <?= htmlspecialchars($equipe['contact_email']) ?></p>
        </div>
    </div>

    <h2 class="mb-3">Joueurs dans cette Équipe :</h2>
    <?php if (!empty($joueurs)): ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Poste</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($joueurs as $index => $joueur): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($joueur['nom']) ?></td>
                        <td><?= htmlspecialchars($joueur['prenom']) ?></td>
                        <td><?= htmlspecialchars($joueur['poste']) ?></td>
                        <td>
                            <a href="index.php?module=player&action=show&id=<?= $joueur['id'] ?>" class="btn btn-info btn-sm">Voir</a>
                            <a href="index.php?module=player&action=edit&id=<?= $joueur['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">Aucun joueur dans cette équipe.</div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="index.php?module=admin&action=list_equipes" class="btn btn-secondary">Retour à la liste des équipes</a>
        <a href="index.php?module=equipe&action=addPlayer&equipe_id=<?= $equipe['id'] ?>" class="btn btn-primary">Ajouter un Joueur</a>
    </div>
</div>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../Users/adminl_layout.php';
?>

