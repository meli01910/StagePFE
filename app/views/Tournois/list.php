

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

    <div class="container mt-4">
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php 
                    switch($_GET['success']) {
                        case 'created': echo "Le tournoi a été créé avec succès"; break;
                        case 'deleted': echo "Le tournoi a été supprimé avec succès"; break;
                        default: echo "Opération réussie"; break;
                    }
                ?>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Liste des Tournois</h1>
            <a href="index.php?module=admin&action=tournoi_add" class="btn btn-primary">Ajouter un tournoi</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Lieu</th>
                        <th>Dates</th>
                        <th>Format</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($tournois)): ?>
                        <tr>
                            <td colspan="9" class="text-center">Aucun tournoi disponible</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($tournois as $tournoi): ?>
                            <tr>
                                <td><?= htmlspecialchars($tournoi['id']) ?></td>
                                <td><?= htmlspecialchars($tournoi['nom']) ?></td>
                                <td><?= htmlspecialchars($tournoi['lieu']) ?></td>
                                <td>
                                    Du <?= date('d/m/Y', strtotime($tournoi['date_debut'])) ?> 
                                    au <?= date('d/m/Y', strtotime($tournoi['date_fin'])) ?>
                                </td>
                                <td><?= htmlspecialchars($tournoi['format']) ?></td>
                                <td><?= htmlspecialchars($tournoi['categorie']) ?></td>    <td>
                                    <span class="badge bg-<?= $tournoi['statut'] === 'À venir' ? 'warning' : 
                                                           ($tournoi['statut'] === 'En cours' ? 'success' : 'secondary') ?>">
                                        <?= htmlspecialchars($tournoi['statut']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="index.php?module=admin&action=tournoi_view&id=<?= $tournoi['id'] ?>" 
                                           class="btn btn-sm btn-info">Voir</a>
                                        <a href="index.php?module=tournoi&action=edit&id=<?= $tournoi['id'] ?>" 
                                           class="btn btn-sm btn-warning">Modifier</a>
                                        <a href="index.php?cmodule=admin&action=delete&id=<?= $tournoi['id'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce tournoi?')">Supprimer</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>



<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../Users/adminl_layout.php';
?>
































