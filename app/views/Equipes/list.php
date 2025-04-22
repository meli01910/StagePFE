<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-4">
    <h1>Liste des Équipes</h1>
    
    <?php if (isset($tournoiId)): ?>
    <div class="mb-3">
        <a href="index.php?module=equipe&action=create&tournoi_id=<?= $tournoiId ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter une équipe
        </a>
        <a href="index.php?module=tournoi&action=show&id=<?= $tournoiId ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour au tournoi
        </a>
    </div>
    <?php endif; ?>
    
    <?php if (empty($equipes)): ?>
        <div class="alert alert-info">Aucune équipe trouvée.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($equipes as $equipe): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if (!empty($equipe['logo'])): ?>
                            <img src="<?= htmlspecialchars($equipe['logo']) ?>" class="card-img-top" alt="Logo de <?= htmlspecialchars($equipe['nom']) ?>">
                        <?php else: ?>
                            <div class="card-img-top bg-light text-center py-5">
                                <i class="fas fa-users fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($equipe['nom']) ?></h5>
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="fas fa-envelope"></i> <?= htmlspecialchars($equipe['contact_email']) ?>
                                </small>
                            </p>
                            
                            <div class="btn-group">
                                <a href="index.php?module=equipe&action=edit&id=<?= $equipe['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <a href="index.php?module=equipe&action=delete&id=<?= $equipe['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe?')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
