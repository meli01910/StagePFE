<?php
// views/detection/list.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des journées de détection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <div class="container">
        <h1>Liste des journées de détection</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                $message = '';
                switch ($_GET['success']) {
                    case 'detection_created':
                        $message = 'La journée de détection a été créée avec succès.';
                        break;
                    case 'detection_updated':
                        $message = 'La journée de détection a été mise à jour avec succès.';
                        break;
                    case 'detection_deleted':
                        $message = 'La journée de détection a été supprimée avec succès.';
                        break;
                }
                echo $message;
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php
                $message = '';
                switch ($_GET['error']) {
                    case 'detection_not_found':
                        $message = 'La journée de détection n\'existe pas.';
                        break;
                    case 'deletion_failed':
                        $message = 'Échec de la suppression de la journée de détection.';
                        break;
                    case 'missing_id':
                        $message = 'ID de la journée de détection manquant.';
                        break;
                }
                echo $message;
                ?>
            </div>
        <?php endif; ?>
        
        <div class="actions">
            <a href="?action=create" class="btn btn-primary">Ajouter une journée de détection</a>
        </div>
        
        <div class="filter">
            <form action="index.php" method="get">
                <input type="hidden" name="action" value="list">
                <label for="status-filter">Filtrer par statut:</label>
                <select id="status-filter" name="status" onchange="this.form.submit()">
                    <option value="">Tous</option>
                    <option value="planned" <?= (isset($_GET['status']) && $_GET['status'] === 'planned') ? 'selected' : '' ?>>Planifié</option>
                    <option value="ongoing" <?= (isset($_GET['status']) && $_GET['status'] === 'ongoing') ? 'selected' : '' ?>>En cours</option>
                    <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] === 'completed') ? 'selected' : '' ?>>Terminé</option>
                    <option value="annulé" <?= (isset($_GET['status']) && $_GET['status'] === 'annulé') ? 'selected' : '' ?>>Annulé</option>
                </select>
            </form>
        </div>
        
        <?php if (empty($detections)): ?>
            <p>Aucune journée de détection trouvée.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Date</th>
                        <th>Lieu</th>
                        <th>Club partenaire</th>
                        <th>Catégorie</th>
                        <th>Max participants</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detections as $detection): ?>
                        <tr>
                            <td><?= htmlspecialchars($detection['name']) ?></td>
                            <td><?= date('d/m/Y', strtotime($detection['date'])) ?></td>
                            <td><?= htmlspecialchars($detection['location']) ?></td>
                            <td><?= htmlspecialchars($detection['partner_club']) ?></td>
                            <td><?= htmlspecialchars($detection['age_category']) ?></td>
                            <td><?= htmlspecialchars($detection['max_participants']) ?></td>
                            <td><span class="status status-<?= strtolower($detection['status']) ?>"><?= htmlspecialchars($detection['status']) ?></span></td>
                            <td>
                                <a href="?action=show&id=<?= $detection['id'] ?>" class="btn btn-sm btn-info">Voir</a>
                                <a href="?action=edit&id=<?= $detection['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                <a href="?action=delete&id=<?= $detection['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette journée de détection ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
