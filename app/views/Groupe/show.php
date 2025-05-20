<?php

// Capturer le contenu
ob_start();
?>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">
                Groupe <?= htmlspecialchars($groupe['nom']) ?> - 
                <small><?= htmlspecialchars($tournoi['nom']) ?></small>
            </h1>
            
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <div>
                <a href="index.php?module=groupe&action=edit&id=<?= $groupe['id'] ?>" class="btn btn-sm btn-light">
                    <i class="fas fa-edit me-1"></i> Modifier
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="card-body">
            <?php if (!empty($groupe['description'])): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> <?= htmlspecialchars($groupe['description']) ?>
            </div>
            <?php endif; ?>
            
            <div class="row">
                <!-- Classement du groupe -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h2 class="h5 mb-0">Classement</h2>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm mb-0">
                                    <thead>
                                        <tr class="table-dark">
                                            <th>#</th>
                                            <th>Équipe</th>
                                            <th>Pts</th>
                                            <th>J</th>
                                            <th>G</th>
                                            <th>N</th>
                                            <th>P</th>
                                            <th>BP</th>
                                            <th>BC</th>
                                            <th>Diff</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($equipes as $index => $equipe): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($equipe['nom']) ?></td>
                                            <td><strong><?= $equipe['points'] ?></strong></td>
                                            <td><?= $equipe['joues'] ?></td>
                                            <td><?= $equipe['gagnes'] ?></td>
                                            <td><?= $equipe['nuls'] ?></td>
                                            <td><?= $equipe['perdus'] ?></td>
                                            <td><?= $equipe['buts_pour'] ?></td>
                                            <td><?= $equipe['buts_contre'] ?></td>
                                            <td><?= $equipe['diff'] ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistiques du groupe -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h2 class="h5 mb-0">Statistiques du groupe</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="card border-0 mb-3">
                                        <div class="card-body text-center">
                                            <h3 class="display-4 text-primary"><?= count($equipes) ?></h3>
                                            <p class="text-muted mb-0">Équipes</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card border-0 mb-3">
                                        <div class="card-body text-center">
                                            <h3 class="display-4 text-primary"><?= count($matchs) ?></h3>
                                            <p class="text-muted mb-0">Matchs</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="progress mb-2">
                                <?php 
                                $matchsTermines = count(array_filter($matchs, function($m) { return $m['statut'] === 'terminé'; }));
                                $matchsEnCours = count(array_filter($matchs, function($m) { return $m['statut'] === 'en_cours'; }));
                                $matchsAVenir = count($matchs) - $matchsTermines - $matchsEnCours;
                                $pourcentTermines = count($matchs) > 0 ? ($matchsTermines / count($matchs) * 100) : 0;
                                $pourcentEnCours = count($matchs) > 0 ? ($matchsEnCours / count($matchs) * 100) : 0;
                                $pourcentAVenir = count($matchs) > 0 ? ($matchsAVenir / count($matchs) * 100) : 0;
                                ?>
                                <div class="progress-bar bg-success" style="width: <?= $pourcentTermines ?>%" role="progressbar" aria-valuenow="<?= $matchsTermines ?>" aria-valuemin="0" aria-valuemax="<?= count($matchs) ?>">
                                    <?= $matchsTermines ?> terminés
                                </div>
                                <div class="progress-bar bg-primary" style="width: <?= $pourcentEnCours ?>%" role="progressbar" aria-valuenow="<?= $matchsEnCours ?>" aria-valuemin="0" aria-valuemax="<?= count($matchs) ?>">
                                    <?= $matchsEnCours ?> en cours
                                </div>
                                <div class="progress-bar bg-warning" style="width: <?= $pourcentAVenir ?>%" role="progressbar" aria-valuenow="<?= $matchsAVenir ?>" aria-valuemin="0" aria-valuemax="<?= count($matchs) ?>">
                                    <?= $matchsAVenir ?> à venir
                                </div>
                            </div>
                            <div class="small text-muted text-center">Progression des matchs du groupe</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Liste des matchs du groupe -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h2 class="h5 mb-0">Calendrier des matchs</h2>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($matchs)): ?>
                    <div class="alert alert-warning m-3">
                        <i class="fas fa-exclamation-triangle me-2"></i> Aucun match n'a été programmé pour ce groupe.
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                            <a href="index.php?module=groupe&action=generateMatches&id=<?= $groupe['id'] ?>" class="alert-link">Générer les matchs</a>.
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Équipe 1</th>
                                    <th>Score</th>
                                    <th>Équipe 2</th>
                                    <th>Lieu</th>
                                    <th>Statut</th>
                                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                                    <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($matchs as $match): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($match['date_match'])) ?></td>
                                    <td class="text-end"><?= htmlspecialchars($match['equipe1_nom']) ?></td>
                                    <td class="text-center">
                                        <?php if ($match['statut'] === 'terminé'): ?>
                                            <strong><?= $match['score1'] ?> - <?= $match['score2'] ?></strong>
                                        <?php elseif ($match['statut'] === 'en_cours'): ?>
                                            <span class="badge bg-primary">En direct</span>
                                        <?php else: ?>
                                            <span class="text-muted">vs</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($match['equipe2_nom']) ?></td>
                                    <td><?= htmlspecialchars($match['lieu_match']) ?></td>
                                    <td>
                                        <span class="badge <?= $match['statut'] === 'terminé' ? 'bg-success' : ($match['statut'] === 'en_cours' ? 'bg-primary' : 'bg-warning') ?>">
                                            <?= str_replace('_', ' ', $match['statut']) ?>
                                        </span>
                                    </td>
                                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                                    <td>
                                        <a href="index.php?module=match&action=edit&id=<?= $match['id'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="card-footer text-center">
            <a href="index.php?module=tournoi&action=show&id=<?= $tournoi['id'] ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour au tournoi
            </a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>
