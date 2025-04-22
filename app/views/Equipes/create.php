<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-4">
    <h1>Ajouter une équipe</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="post" action="index.php?module=equipe&action=create&tournoi_id=<?= $tournoiId ?>">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom de l'équipe</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        
        <div class="mb-3">
            <label for="logo" class="form-label">URL du logo</label>
            <input type="url" class="form-control" id="logo" name="logo" placeholder="http://exemple.com/logo.png">
            <small class="form-text text-muted">Entrez l'URL d'une image pour le logo de l'équipe (optionnel)</small>
        </div>
        
        <div class="mb-3">
            <label for="contact_email" class="form-label">Email de contact</label>
            <input type="email" class="form-control" id="contact_email" name="contact_email" required>
        </div>
        
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="index.php?module=equipe&action=listByTournoi&tournoi_id=<?= $tournoiId ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
