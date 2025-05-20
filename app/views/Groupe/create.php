
<?php

// Capturer le contenu
ob_start();
?>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h1 class="h3 mb-0">Créer un groupe pour le tournoi "<?= htmlspecialchars($tournoi['nom']) ?>"</h1>
        </div>
        
        <div class="card-body">
            <form method="post" action="">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom du groupe</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Ex: Groupe A, Groupe B, etc." required>
                    <div class="form-text">Nommez votre groupe de façon claire (ex: "Groupe A", "Poule 1", etc.)</div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description (optionnel)</label>
                    <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Équipes du groupe</label>
                    <?php if (empty($equipes)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> Toutes les équipes du tournoi sont déjà attribuées à des groupes.
                        </div>
                    <?php else: ?>
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text mb-3">Sélectionnez les équipes qui feront partie de ce groupe :</p>
                                
                                <div class="row">
                                    <?php foreach ($equipes as $equipe): ?>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="equipes[]" id="equipe-<?= $equipe['id'] ?>" value="<?= $equipe['id'] ?>">
                                                <label class="form-check-label" for="equipe-<?= $equipe['id'] ?>">
                                                <label class="form-check-label" for="equipe-<?= $equipe['id'] ?>">
                                                    <?= htmlspecialchars($equipe['nom']) ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="index.php?module=tournoi&action=organiser&id=<?= $tournoi['id'] ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                    
                    <button type="submit" class="btn btn-success" <?= empty($equipes) ? 'disabled' : '' ?>>
                        <i class="fas fa-plus-circle me-1"></i> Créer le groupe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>
