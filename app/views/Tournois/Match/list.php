<?php include __DIR__ . '/../../templates/header.php'; ?>

<div class="container mt-4">
    <h1>Matchs du tournoi</h1>
    
    <div class="mb-3">
        <a href="index.php?module=match&action=create&tournoi_id=<?= $tournoiId ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un match
        </a>
        <a href="index.php?module=match&action=genererMatchsPoules&tournoi_id=<?= $tournoiId ?>" class="btn btn-success">
            <i class="fas fa-random"></i> Générer les matchs de poules
        </a>
        <a href="index.php?module=tournoi&action=show&id=<?= $tournoiId ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au tournoi
        </a>
    </div>
    
    <?php if (empty($matchs)): ?>
        <div class="alert alert-info">Aucun match programmé pour ce tournoi.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Équipe 1</th>
                        <th>Score</th>
                        <th>Équipe 2</th>
                        <th>Terrain</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matchs as $match): ?>
                        <tr>
                            <td><?= (new DateTime($match['date_match']))->format('d/m/Y H:i') ?></td>
                            <td><?= htmlspecialchars($match['equipe1_nom']) ?></td>
                            <td>
                                <?php if ($match['score_equipe1'] !== null && $match['score_equipe2'] !== null): ?>
                                    <span class="badge bg-primary"><?= $match['score_equipe1'] ?> - <?= $match['score_equipe2'] ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Non joué</span>
                                <?php endif; ?>
                                
                                <button type="button" class="btn btn-sm btn-outline-primary ms-1" data-bs-toggle="modal" data-bs-target="#scoreModal<?= $match['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <!-- Modal pour éditer le score -->
                                <div class="modal fade" id="scoreModal<?= $match['id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Modifier le score</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="index.php?module=match&action=updateScore&id=<?= $match['id'] ?>" method="post">
                                                <div class="modal-body">
                                                    <div class="row align-items-center">
                                                        <div class="col text-end">
                                                            <label class="form-label"><?= htmlspecialchars($match['equipe1_nom']) ?></label>
                                                        </div>
                                                        <div class="col-3">
                                                            <input type="number" min="0" class="form-control" name="score_equipe1" value="<?= $match['score_equipe1'] ?? 0 ?>">
                                                        </div>
                                                        <div class="col-1 text-center">-</div>
                                                        <div class="col-3">
                                                            <input type="number" min="0" class="form-control" name="score_equipe2" value="<?= $match['score_equipe2'] ?? 0 ?>">
                                                        </div>
                                                        <div class="col">
                                                            <label class="form-label"><?= htmlspecialchars($match['equipe2_nom']) ?></label>
                                                        </div>
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
                            </td>
                            <td><?= htmlspecialchars($match['equipe2_nom']) ?></td>
                            <td><?= htmlspecialchars($match['terrain']) ?></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-danger" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer ce match?')) location.href='index.php?module=match&action=delete&id=<?= $match['id'] ?>'">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
