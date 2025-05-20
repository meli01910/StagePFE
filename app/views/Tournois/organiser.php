

<?php
ob_start();
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">Organisation du tournoi "<?= htmlspecialchars($tournoi['nom']) ?>"</h1>
                    <a href="index.php?module=tournoi&action=show&id=<?= $tournoi['id'] ?>" class="btn btn-sm btn-light">
                        <i class="fas fa-eye me-1"></i> Voir le tournoi
                    </a>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Cette page vous permet d'organiser votre tournoi en créant des groupes, des phases et des matchs.
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-secondary text-white">
                                    <h2 class="h5 mb-0">Informations du tournoi</h2>
                                </div>
                                <div class="card-body">
                                    <p><strong>Format:</strong> <?= htmlspecialchars($tournoi['format'] ?? 'Non spécifié') ?></p>
                                    <p><strong>Dates:</strong> Du <?= date('d/m/Y', strtotime($tournoi['date_debut'])) ?> au <?= date('d/m/Y', strtotime($tournoi['date_fin'])) ?></p>
                                    <p><strong>Lieu:</strong> <?= htmlspecialchars($tournoi['lieu'] ?? 'Non spécifié') ?></p>
                                    <p><strong>Équipes:</strong> <?= count($equipes) ?> équipes inscrites</p>
                                    <p><strong>Statut:</strong> 
                                        <span class="badge bg-<?= $tournoi['statut'] === 'en_cours' ? 'success' : ($tournoi['statut'] === 'termine' ? 'secondary' : 'warning') ?>">
                                            <?= $tournoi['statut'] === 'en_cours' ? 'En cours' : ($tournoi['statut'] === 'termine' ? 'Terminé' : 'À venir') ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-secondary text-white">
                                    <h2 class="h5 mb-0">Actions rapides</h2>
                                </div>
                                <div class="card-body d-grid gap-2">
                                    <a href="index.php?module=tournoi&action=ajouterEquipe&id=<?= $tournoi['id'] ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-users me-1"></i> Ajouter une équipe
                                    </a>
                                    <a href="index.php?module=groupe&action=create&tournoi_id=<?= $tournoi['id'] ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-layer-group me-1"></i> Créer un groupe
                                    </a>
                                    <a href="index.php?module=match&action=create&tournoi_id=<?= $tournoi['id'] ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-futbol me-1"></i> Créer un match
                                    </a>
                                    <a href="index.php?module=tournoi&action=generateMatches&id=<?= $tournoi['id'] ?>" class="btn btn-outline-danger" onclick="return confirm('Attention ! Cela va générer automatiquement tous les matchs du tournoi. Les matchs existants ne seront pas affectés. Continuer ?')">
                                        <i class="fas fa-magic me-1"></i> Générer tous les matchs automatiquement
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Groupes du tournoi -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">Groupes du tournoi</h2>
                            <a href="index.php?module=groupe&action=create&tournoi_id=<?= $tournoi['id'] ?>" class="btn btn-sm btn-light">
                                <i class="fas fa-plus-circle me-1"></i> Créer un groupe
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($groupes)): ?>
                            <div class="alert alert-warning m-3">
                                <i class="fas fa-exclamation-triangle me-2"></i> Aucun groupe n'a été créé pour ce tournoi.
                                <a href="index.php?module=groupe&action=create&tournoi_id=<?= $tournoi['id'] ?>" class="alert-link">Créer un groupe</a>.
                            </div>
                            <?php else: ?>
                 <div class="row p-3">
    <?php foreach ($groupesComplets as $groupeData): ?>
        <?php 
        $groupe = $groupeData['groupe'];
        $equipesGroupe = $groupeData['equipes'];
        $matchsGroupe = $groupeData['matchs'];
        $matchsTermines = $groupeData['matchsTermines'];
        $progression = $groupeData['progression'];
        ?>
        <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h3 class="h6 mb-0">Groupe <?= htmlspecialchars($groupe['nom']) ?></h3>
                </div>
                <div class="card-body small">
                    <p class="mb-2">
                        <i class="fas fa-users me-1"></i> 
                        <strong><?= count($equipesGroupe) ?></strong> équipes
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-futbol me-1"></i> 
                        <strong><?= count($matchsGroupe) ?></strong> matchs 
                        (<?= $matchsTermines ?> terminés)
                    </p>
                    <div class="progress mb-2" style="height: 5px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progression ?>%"></div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between bg-white">
                    <a href="index.php?module=groupe&action=show&id=<?= $groupe['id'] ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i> Voir
                    </a>
                    <a href="index.php?module=groupe&action=edit&id=<?= $groupe['id'] ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit me-1"></i> Modifier
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Phases du tournoi -->
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h4>Phases du tournoi</h4>
    </div>
    <div class="card-body">
        <?php if (empty($phases)): ?>
            <p>Aucune phase définie pour ce tournoi.</p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($phases as $phase): ?>
                    <?php $nomPhase = $phase['phase']; ?>
                    <?php $progressionInfo = $progressionPhases[$nomPhase] ?? ['total' => 0, 'termines' => 0, 'progression' => 0]; ?>
                    
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-1"><?= htmlspecialchars($nomPhase) ?></h5>
                            <span class="badge bg-primary rounded-pill">
                                <?= $progressionInfo['termines'] ?>/<?= $progressionInfo['total'] ?> matchs
                            </span>
                        </div>
                        
                        <div class="progress mt-2">
                            <div class="progress-bar" role="progressbar" style="width: <?= $progressionInfo['progression'] ?>%"
                                aria-valuenow="<?= $progressionInfo['progression'] ?>" aria-valuemin="0" aria-valuemax="100">
                                <?= round($progressionInfo['progression']) ?>%
                            </div>
                        </div>
                        
                        <?php if (isset($matchsParPhase[$nomPhase]) && !empty($matchsParPhase[$nomPhase])): ?>
                            <div class="mt-3">
                                <h6>Matchs de cette phase:</h6>
                                <ul class="list-group">
                                    <?php foreach ($matchsParPhase[$nomPhase] as $match): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= htmlspecialchars($match['equipe1_nom'] ?? 'Équipe 1') ?> 
                                            vs 
                                            <?= htmlspecialchars($match['equipe2_nom'] ?? 'Équipe 2') ?>
                                            
                                            <span class="badge <?= $match['statut'] === 'terminé' ? 'bg-success' : 'bg-secondary' ?> rounded-pill">
                                                <?= ucfirst(htmlspecialchars($match['statut'])) ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mt-2">Aucun match programmé pour cette phase.</p>
                        <?php endif; ?>
                        
                        <!-- Bouton pour créer un match dans cette phase -->
                        <a href="index.php?module=match&action=createForPhase&tournoi_id=<?= $id ?>&phase=<?= urlencode($nomPhase) ?>" 
                           class="btn btn-sm btn-outline-primary mt-2">
                            Ajouter un match à cette phase
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <!-- Bouton pour ajouter une nouvelle phase -->
            <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#addPhaseModal">
                Ajouter une nouvelle phase
            </button>
        <?php endif; ?>
    </div>
</div>
         <div class="mt-3">
    <!-- Bouton pour voir les phases finales -->
    <a href="index.php?module=tournoi&action=voirPhasesFinales&id=<?= $tournoi['id'] ?>" class="btn btn-primary">
        Voir les Phases Finales
    </a>
    
    <!-- Bouton pour générer les phases finales (admin seulement) -->
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
        <a href="index.php?module=tournoi&action=genererPhaseFinale&id=<?= $tournoi['id'] ?>" class="btn btn-success">
            Générer les Phases Finales
        </a>
    <?php endif; ?>
</div>           
                    <!-- Derniers matchs créés -->
                    <div class="card">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">Derniers matchs créés</h2>
                            <a href="index.php?module=match&action=index&tournoi_id=<?= $tournoi['id'] ?>" class="btn btn-sm btn-light">
                                <i class="fas fa-search me-1"></i> Voir tous les matchs
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($matchs)): ?>
                            <div class="alert alert-warning m-3">
                                <i class="fas fa-exclamation-triangle me-2"></i> Aucun match n'a été créé pour ce tournoi.
                                <a href="index.php?module=match&action=create&tournoi_id=<?= $tournoi['id'] ?>" class="alert-link">Créer un match</a> ou
                                <a href="index.php?module=tournoi&action=generateMatches&id=<?= $tournoi['id'] ?>" class="alert-link">générer les matchs automatiquement</a>.
                            </div>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Équipes</th>
                                            <th>Phase / Groupe</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($matchs, 0, 20) as $match): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i', strtotime($match['date_match'])) ?></td>
                                            <td>
                                                <?= htmlspecialchars($match['equipe1_nom']) ?> 
                                                <?php if ($match['statut'] === 'terminé'): ?>
                                                    <strong><?= $match['score1'] ?> - <?= $match['score2'] ?></strong>
                                                <?php else: ?>
                                                    vs
                                                <?php endif; ?>
                                                <?= htmlspecialchars($match['equipe2_nom']) ?>
                                            </td>
                                                                                       <td>
                                                <?= htmlspecialchars($match['phase'] ?? 'Non spécifiée') ?>
                                                <?php if (!empty($match['groupe_nom'])): ?>
                                                    <span class="badge bg-info">Groupe <?= htmlspecialchars($match['groupe_nom']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge <?= $match['statut'] === 'terminé' ? 'bg-success' : ($match['statut'] === 'en_cours' ? 'bg-primary' : 'bg-warning') ?>">
                                                    <?= str_replace('_', ' ', $match['statut']) ?>
                                                </span>
                                            </td>
                                            <td>
    <div class="btn-group btn-group-sm">
        <a href="index.php?module=match&action=edit&id=<?= $match['id'] ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-edit"></i>
        </a>
        <!-- Bouton pour mettre à jour le score -->
        <button class="btn btn-sm btn-success update-score" 
                data-bs-toggle="modal" 
                data-bs-target="#scoreModal" 
                data-match-id="<?= $match['id'] ?>"
                data-tournoi-id="<?= $tournoi['id'] ?>"
                data-equipe1="<?= htmlspecialchars($match['equipe1_nom']) ?>"
                data-equipe2="<?= htmlspecialchars($match['equipe2_nom']) ?>">
            <i class="fas fa-marker"></i> Score
        </button>
    </div>
</td>

                                        </tr>
                                        
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <?php if (count($matchs) > 5): ?>
                            <div class="text-center p-3">
                                <a href="index.php?module=match&action=index&tournoi_id=<?= $tournoi['id'] ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-list me-1"></i> Voir tous les matchs (<?= count($matchs) ?>)
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer text-center">
                    <a href="index.php?module=tournoi&action=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste des tournois
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal pour mettre à jour le score -->
<div class="modal fade" id="scoreModal" tabindex="-1" aria-labelledby="scoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scoreModalLabel">Mettre à jour le score</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?module=match&action=updateScore">
                <div class="modal-body">
                    <input type="hidden" name="match_id" id="match_id">
                    <input type="hidden" name="tournoi_id" id="tournoi_id">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" id="equipe1-label">Équipe 1</label>
                            <input type="number" class="form-control" name="score1" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" id="equipe2-label">Équipe 2</label>
                            <input type="number" class="form-control" name="score2" min="0" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Statut du match</label>
                        <select class="form-select" name="statut">
                            <option value="à_venir">À venir</option>
                            <option value="en_cours">En cours</option>
                            <option value="terminé">Terminé</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Script pour mettre à jour le modal avec les bonnes infos
document.addEventListener('DOMContentLoaded', function() {
    const scoreModal = document.getElementById('scoreModal');
    if (scoreModal) {
        scoreModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const matchId = button.getAttribute('data-match-id');
            const tournoiId = button.getAttribute('data-tournoi-id');
            const equipe1 = button.getAttribute('data-equipe1');
            const equipe2 = button.getAttribute('data-equipe2');
            
            document.getElementById('match_id').value = matchId;
            document.getElementById('tournoi_id').value = tournoiId;
            document.getElementById('equipe1-label').textContent = equipe1;
            document.getElementById('equipe2-label').textContent = equipe2;
        });
    }
});
</script>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>
