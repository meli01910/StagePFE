<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une journée de détection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Créer une nouvelle journée de détection</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form action="index.php?module=detection&action=create" method="post">
            <div class="form-group mb-3">
                <label for="nom">Nom de l'événement</label>
                <input type="text" id="nom" name="nom" class="form-control" required>
            </div>
            
            <div class="form-group mb-3">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" class="form-control" required>
            </div>
            
            <div class="form-group mb-3">
                <label for="lieu">Lieu</label>
                <input type="text" id="lieu" name="lieu" class="form-control" required>
            </div>
            
            <div class="form-group mb-3">
                <label for="partnerClub">Club partenaire</label>
                <input type="text" id="partnerClub" name="partnerClub" class="form-control" required>
            </div>
            
            <div class="form-group mb-3">
                <label for="categorie">Catégorie d'âge</label>
                <select id="categorie" name="categorie" class="form-control" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <option value="U13">U13</option>
                    <option value="U15">U15</option>
                    <option value="U17">U17</option>
                    <option value="U19">U19</option>
                    <option value="Senior">Senior</option>
                </select>
            </div>
            
            <div class="form-group mb-3">
                <label for="maxParticipants">Nombre maximum de participants</label>
                <input type="number" id="maxParticipants" name="maxParticipants" class="form-control" min="1" required>
            </div>
            
            <div class="form-group mb-3">
                <label for="status">Statut</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="planned">Planifié</option>
                    <option value="ongoing">En cours</option>
                    <option value="completed">Terminé</option>
                </select>
            </div>
            
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary">Créer la journée de détection</button>
                <a href="index.php?module=detection&action=index" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
