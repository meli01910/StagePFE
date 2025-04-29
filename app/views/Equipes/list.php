
<?php
// Fichier: /app/views/Users/dashboard_admin.php
// S'assurer que l'utilisateur est un admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?module=auth&action=login');
    exit;
}



 include __DIR__ . '/../templates/header.php';

// Définir le titre de la page et la page active
$pageTitle = 'Gestion des Joueurs';
$currentPage = 'equipes';

// Capturer le contenu
ob_start();
?>

<div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Gestion des Équipes</h1>
                <p class="text-muted">Liste des Equipes</p>
            </div>
            <div>
                
            </div>
        </div>

        <div class="container">
 
    <a href="index.php?module=admin&action=equipe_add" class="btn btn-primary mb-3">Créer une équipe</a>
    
    <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success">Opération réussie !</div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Logo</th>
                <th>Nom</th>
                <th>Adresse mail</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipes as $equipe): ?>
                <tr>
                       <td><img src="<?= htmlspecialchars($equipe['logo']) ?>" alt="Logo" style="width: 100px; height: auto;"></td>
                     
                       <td><?= htmlspecialchars($equipe['nom']) ?></td>
                
                       <td><?= htmlspecialchars($equipe['contact_email']) ?></td>
                       <td>
                        <a href="index.php?module=admin&action=equipe_view&id=<?= $equipe['id'] ?>" class="btn btn-info">Voir</a>
                        <a href="index.php?module=admin&action=equipe_edit&id=<?= $equipe['id'] ?>" class="btn btn-warning">Modifier</a>
                        <a href="index.php?module=admin&action=ajout_joueur&equipe_id=<?= $equipe['id'] ?>" class="btn btn-danger" >Ajouter un joueur</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>  
</div>

</div>


<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../Users/adminl_layout.php';
?>
































