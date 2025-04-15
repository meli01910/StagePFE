<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Tournois</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .status-planifie { background-color: #6c757d; color: white; }
        .status-en_cours { background-color: #28a745; color: white; }
        .status-termine { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Liste des Tournois</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                switch ($_GET['success']) {
                    case 'created': echo "Tournoi créé avec succès"; break;
                    case 'updated': echo "Tournoi mis à jour avec succès"; break;
                    case 'deleted': echo "Tournoi supprimé avec succès"; break;
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php
                switch ($_GET['error']) {
                    case 'delete_failed': echo "Échec de la suppression du tournoi"; break;
                    case 'not_found': echo "Tournoi introuvable"; break;
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <a href="index.php?module=tournoi&action=create" class="btn btn-primary">
                Créer un nouveau tournoi
            </a>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Tous les tournois</h5>
                <div class="btn-group">
                    <a href="?module=tournoi&action=index" class="btn btn-sm btn-outline-secondary">Tous</a>
                    <a href="?module=tournoi&action=index&status=planifie" class="btn btn-sm btn-outline-secondary">Planifiés</a>
                    <a href="?module=tournoi&action=index&status=en_cours" class="btn btn-sm btn-outline-secondary">En cours</a>
                    <a href="?module=tournoi&action=index&status=termine" class="btn btn-sm btn-outline-secondary">Terminés</a>
                </div>
            </div>
            
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Dates</th>
                            <th>Lieu</th>
                            <th>Format</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tournois as $tournoi): ?>
                        <tr>
                            <td><?= htmlspecialchars($tournoi['nom']) ?></td>
                            <td>
                                <?= date('d/m/Y', strtotime($tournoi['date_debut'])) ?>
                                au
                                <?= date('d/m/Y', strtotime($tournoi['date_fin'])) ?>
                            </td>
                            <td><?= htmlspecialchars($tournoi['lieu']) ?></td>
                            <td>
                                <?php
                                $formats = [
                                    'elimination' => 'Élimination',
                                    'poules' => 'Poules',
                                    'mixte' => 'Mixte'
                                ];
                                echo $formats[$tournoi['format']] ?? $tournoi['format'];
                                ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?= $tournoi['statut'] ?>">
                                    <?php
                                    $statuts = [
                                        'planifie' => 'Planifié',
                                        'en_cours' => 'En cours',
                                        'termine' => 'Terminé'
                                    ];
                                    echo $statuts[$tournoi['statut']] ?? $tournoi['statut'];
                                    ?>
                                </span>
                            </td>
                            <td>
                                <a href="index.php?module=tournoi&action=show&id=<?= $tournoi['id'] ?>" 
                                   class="btn btn-sm btn-info">
                                    Voir
                                </a>
                                <a href="index.php?module=tournoi&action=edit&id=<?= $tournoi['id'] ?>" 
                                   class="btn btn-sm btn-warning">
                                    Modifier
                                </a>
                                <form method="POST" action="index.php?module=tournoi&action=delete&id=<?= $tournoi['id'] ?>" 
                                      style="display: inline-block;" 
                                      onsubmit="return confirm('Supprimer ce tournoi définitivement ?')">
                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>