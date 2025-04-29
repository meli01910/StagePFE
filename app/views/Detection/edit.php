
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Détection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Modifier la Détection</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?module=admin&action=detection_edit&id=<?= $detection['id'] ?>">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" 
                   value="<?= htmlspecialchars($detection['name'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" 
                   value="<?= htmlspecialchars($detection['date'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="lieu" class="form-label">Lieu</label>
            <input type="text" class="form-control" id="lieu" name="lieu" 
                   value="<?= htmlspecialchars($detection['location'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="partnerClub" class="form-label">Club Partenaire</label>
            <input type="text" class="form-control" id="partnerClub" name="partnerClub" 
                   value="<?= htmlspecialchars($detection['partner_club'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie d'âge</label>
            <input type="text" class="form-control" id="categorie" name="categorie" 
                   value="<?= htmlspecialchars($detection['age_category'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="maxParticipants" class="form-label">Participants Max</label>
            <input type="number" class="form-control" id="maxParticipants" name="maxParticipants" 
                   value="<?= htmlspecialchars($detection['max_participants'] ?? '') ?>" min="1" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Statut</label>
            <select class="form-select" id="status" name="status" required>
                <option value="planned" <?= ($detection['status'] ?? '') === 'planned' ? 'selected' : '' ?>>Planifié</option>
                <option value="ongoing" <?= ($detection['status'] ?? '') === 'ongoing' ? 'selected' : '' ?>>En cours</option>
                <option value="completed" <?= ($detection['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Terminé</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="index.php?module=admin&action=detections" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>