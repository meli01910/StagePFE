
<?php
// Définir le titre de la page et la page active
$pageTitle = 'Saisir le score';
$currentPage = 'matchs';
$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    
// Si non admin, rediriger
if (!$isAdmin) {
    header('Location: index.php');
    exit();
}

// Capturer le contenu
ob_start();
?>

<div class="container mt-4">
    <h1>Classement des groupes - <?= htmlspecialchars($tournoi['nom']) ?></h1>
    
    <?php foreach ($groupes as $groupe): ?>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="h5 mb-0">Groupe <?= htmlspecialchars($groupe['nom']) ?></h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Équipe</th>
                                <th>J</th>
                                <th>G</th>
                                <th>N</th>
                                <th>P</th>
                                <th>BP</th>
                                <th>BC</th>
                                <th>+/-</th>
                                <th>Pts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $classement = $classements[$groupe['id']];
                            foreach ($classement as $i => $equipe): 
                            ?>
                            <tr class="<?= ($i < 2) ? 'table-success' : '' ?>">
                                <td><?= $i+1 ?></td>
                                <td><?= htmlspecialchars($equipe['nom']) ?></td>
                                <td><?= $equipe['joues'] ?></td>
                                <td><?= $equipe['gagnes'] ?></td>
                                <td><?= $equipe['nuls'] ?></td>
                                <td><?= $equipe['perdus'] ?></td>
                                <td><?= $equipe['buts_pour'] ?></td>
                                <td><?= $equipe['buts_contre'] ?></td>
                                <td><?= $equipe['diff_buts'] ?></td>
                                <td><strong><?= $equipe['points'] ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
    <?php if ($tousMatchsTermines): ?>
        <div class="text-center mb-4">
          <div class="alert alert-info">
        <p>Tous les matchs de groupe sont terminés. Vous pouvez maintenant générer la phase finale.</p>
        <a href="index.php?module=tournoi&action=genererPhaseFinale&id=<?= $tournoi['id'] ?>" 
           class="btn btn-primary">
            Générer la phase finale
        </a>
    </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Tous les matchs de groupe ne sont pas encore terminés. La phase finale ne peut pas encore être générée.
        </div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>
