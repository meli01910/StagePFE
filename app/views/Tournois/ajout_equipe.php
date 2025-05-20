<!-- views/Tournois/ajouterEquipe.php -->


<?php
// Fichier: /app/views/Users/dashboard_admin.php
// S'assurer que l'utilisateur est un admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?module=auth&action=login');
    exit;
}

// Définir le titre et la page active
// Définir le titre de la page et la page active
$pageTitle = 'Gestion des Joueurs';
$currentPage = 'players';

// Capturer le contenu
ob_start();
?>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Ajouter des équipes au tournoi: <?= htmlspecialchars($tournoi['nom']) ?></h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['flash'])): ?>
                            <div class="alert alert-<?= $_SESSION['flash']['type'] ?>" role="alert">
                                <?= $_SESSION['flash']['message'] ?>
                            </div>
                            <?php unset($_SESSION['flash']); ?>
                        <?php endif; ?>

                        <p>Sélectionnez une équipe à ajouter au tournoi.</p>
                        
                        <?php if (empty($equipes)): ?>
                            <div class="alert alert-info">
                                Aucune équipe n'est disponible pour l'inscription.
                            </div>
                        <?php else: ?>
                            <form action="index.php?module=tournoi&action=ajouterEquipe&id=<?= $tournoi_id ?>" method="POST">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Logo</th>
                                                <th>Nom de l'équipe</th>
                                                <th>Statut</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($equipes as $equipe): 
                                                $estInscrite = in_array($equipe['id'], $equipesInscritesIds);
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php if (!empty($equipe['logo'])): ?>
                                                        <img src="<?= htmlspecialchars($equipe['logo']) ?>" alt="Logo <?= htmlspecialchars($equipe['nom']) ?>" height="40">
                                                    <?php else: ?>
                                                        <img src="/FootballProject/uploads/logos/default-team.png" alt="Logo par défaut" height="40">
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($equipe['nom']) ?></td>
                                                <td>
                                                    <?php if ($estInscrite): ?>
                                                        <span class="badge bg-success">Déjà inscrite</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Non inscrite</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($estInscrite): ?>
                                                        <a href="index.php?module=tournoi&action=retirerEquipe&tournoi_id=<?= $tournoi_id ?>&equipe_id=<?= $equipe['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir retirer cette équipe du tournoi?')">
                                                            Retirer
                                                        </a>
                                                    <?php else: ?>
                                                        <button type="submit" name="equipe_id" value="<?= $equipe['id'] ?>" class="btn btn-sm btn-success">
                                                            Ajouter
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        <?php endif; ?>
                        
                        <div class="mt-4">
                            <a href="index.php?module=tournoi&action=show&id=<?= $tournoi_id ?>" class="btn btn-secondary">
                                Retour au tournoi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>
