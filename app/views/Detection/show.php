<?php
// views/detection/show.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la journée de détection</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Détails de la journée de détection</h1>
        
        <div class="card">
            <div class="card-header">
                <h2><?= htmlspecialchars($detection['name']) ?></h2>
                <span class="badge status-<?= strtolower($detection['status']) ?>"><?= htmlspecialchars($detection['status']) ?></span>
            </div>
            
            <div class="card-body">
                <div class="info-group">
                    <label>Date:</label>
                    <span><?= date('d/m/Y', strtotime($detection['date'])) ?></span>
                </div>
                
                <div class="info-group">
                    <label>Lieu:</label>
                    <span><?= htmlspecialchars($detection['location']) ?></span>
                </div>
                
                <div class="info-group">
                    <label>Club partenaire:</label>
                    <span><?= htmlspecialchars($detection['partner_club']) ?></span>
                </div>
                
                <div class="info-group">
                    <label>Catégorie d'âge:</label>
                    <span><?= htmlspecialchars($detection['age_category']) ?></span>
                </div>
                
                <div class="info-group">
                    <label>Nombre maximum de participants:</label>
                    <span><?= htmlspecialchars($detection['max_participants']) ?></span>
                </div>
                
                <div class="info-group">
                    <label>Créé le:</label>
                    <span><?= date('d/m/Y H:i', strtotime($detection['created_at'])) ?></span>
                </div>
                
                <div class="info-group">
                    <label>Dernière mise à jour:</label>
                    <span><?= date('d/m/Y H:i', strtotime($detection['updated_at'])) ?></span>
                </div>
            </div>
            
            <div class="card-footer">
                <a href="?action=edit&id=<?= $detection['id'] ?>" class="btn btn-warning">Modifier</a>
                <a href="?action=delete&id=<?= $detection['id'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette journée de détection ?')">Supprimer</a>
                <a href="?action=index" class="btn btn-secondary">Retour à la liste</a>
            </div>
        </div>
    </div>
</body>
</html>
