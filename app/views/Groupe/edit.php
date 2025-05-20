
<?php
// Définir le titre et la page active
$pageTitle = 'Équipe : ' . htmlspecialchars($equipe['nom']);
$currentPage = 'equipes';

// Capturer le contenu
ob_start();
?>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Modifier le groupe "<?= htmlspecialchars($groupe['nom']) ?>"</h1>
            
            <div>
                <a href="index.php?module=groupe&action=generateMatches&id=<?= $groupe['id'] ?>" class="btn btn-sm btn-light" onclick="return confirm('Êtes-vous sûr de vouloir générer les matchs pour ce groupe ?')">
                    <i class="fas fa-cogs me-1"></i> Générer les matchs
                </a>
                <a href="index.php?module=groupe&action=delete&id=<?= $groupe['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe et tous ses matchs ?')">
                    <i class="fas fa-trash me-1"></i> Supprimer
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <form method="post" action="">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du groupe</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($groupe['nom']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description (optionnel)</label>
                    <textarea class="form-control" id="description" name="description" rows="2"><?= htmlspecialchars($groupe['description'] ?? '') ?></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Équipes du groupe</label>
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text mb-3">Sélectionnez les équipes qui font partie de ce groupe :</p>
                            
                            <div class="row">
                                <?php 
                                $equipeIds = array_column($equipesGroupe, 'id');
                                foreach ($equipesTournoi as $equipe): 
                                ?>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="equipes[]" 
                                                id="equipe-<?= $equipe['id'] ?>" 
                                                value="<?= $equipe['id'] ?>" 
                                                <?= in_array($equipe['id'], $equipeIds) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="equipe-<?= $equipe['id'] ?>">
                                                <?= htmlspecialchars($equipe['nom']) ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="index.php?module=tournoi&action=organiser&id=<?= $tournoi['id'] ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
        
        <?php if (!empty($matchs)): ?>
        <div class="card-footer">
            <h4 class="h5 mb-3">Matchs du groupe</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Équipes</th>
                            <th>Score</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($matchs as $match): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($match['date_match'])) ?></td>
                            <td>
                                <?= htmlspecialchars($match['equipe1_nom']) ?> vs <?= htmlspecialchars($match['equipe2_nom']) ?>
                            </td>
                            <td>
                                <?php if ($match['statut'] === 'terminé'): ?>
                                    <?= $match['score_equipe1'] ?> - <?= $match['score_equipe2'] ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $match['statut'] === 'terminé' ? 'bg-success' : ($match['statut'] === 'en_cours' ? 'bg-primary' : 'bg-warning') ?>">
                                    <?= str_replace('_', ' ', $match['statut']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="index.php?module=match&action=edit&id=<?= $match['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>
