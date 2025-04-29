<!-- views/Detection/confirmation.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation d'Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Confirmation d'Inscription</h2>

        <div class="alert alert-success" role="alert">
            Vous vous êtes inscrit avec succès pour la détection suivante :
        </div>

        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>Nom :</strong> <?= htmlspecialchars($detection['name']); ?></li>
            <li class="list-group-item"><strong>Date :</strong> <?= htmlspecialchars($detection['date']); ?></li>
            <li class="list-group-item"><strong>Lieu :</strong> <?= htmlspecialchars($detection['location']); ?></li>
            <li class="list-group-item"><strong>Nombre maximum de participants :</strong> <?= htmlspecialchars($detection['max_participants']); ?></li>
            <li class="list-group-item"><strong>Club Partenaire :</strong> <?= htmlspecialchars($detection['partner_club']); ?></li>
            <li class="list-group-item"><strong>Catégorie d'âge :</strong> <?= htmlspecialchars($detection['age_category']); ?></li>
            <li class="list-group-item"><strong>Dernière mise à jour :</strong> <?= htmlspecialchars($detection['updated_at']); ?></li>
        </ul>

       

        <a href="index.php?module=detection&action=list" class="btn btn-secondary mt-3">Retour à la liste des détections</a>
    </div>
</body>
</html>
