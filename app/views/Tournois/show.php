<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Tournoi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .info-card {
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .info-label {
            font-weight: bold;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                switch ($_GET['success']) {
                    case 'updated': echo "Tournoi mis à jour avec succès"; break;
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Détails du Tournoi</h1>
            <div>
                <a href="index.php?module=tournoi&action=edit&id=<?= $tournoi['id'] ?>" 
                   class="btn btn-warning me-2">
                    Modifier
                </a>
                <a href="index.php?module=tournoi&action=index" class="btn btn-secondary">
                    Retour à la liste
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card info-card">
                    <div class="card-header bg-primary text-white">
                        <h3><?= htmlspecialchars($tournoi['nom']) ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><span class="info-label">Lieu :</span> <?= htmlspecialchars($tournoi['lieu']) ?></p>
                                <p><span class="info-label">Dates :</span> 
                                    Du <?= date('d/m/Y', strtotime($tournoi['date_debut'])) ?>
                                    au <?= date('d/m/Y', strtotime($tournoi['date_fin'])) ?>
                                </p>
                                <a href="index.php?module=match&action=index&tournoi_id=<?= $tournoi['id'] ?>">📅 Voir les matchs</a>
                                <a href="index.php?module=equipe&action=create&tournoi_id=<?= $tournoi['id'] ?>">Ajouter une équipe</a>

                            </div>
                            <div class="col-md-6">
                                <p><span class="info-label">Format :</span> 
                                    <?php
                                    $formats = [
                                        'elimination' => 'Élimination directe',
                                        'poules' => 'Poules + phase finale',
                                        'mixte' => 'Format mixte'
                                    ];
                                    echo $formats[$tournoi['format']] ?? $tournoi['format'];
                                    ?>
                                </p>
                                <p><span class="info-label">Statut :</span>
                                    <span class="badge bg-<?php 
                                        switch($tournoi['statut']) {
                                            case 'planifie': echo 'secondary'; break;
                                            case 'en_cours': echo 'success'; break;
                                            case 'termine': echo 'danger'; break;
                                        }
                                    ?>">
                                        <?php
                                        $statuts = [
                                            'planifie' => 'Planifié',
                                            'en_cours' => 'En cours',
                                            'termine' => 'Terminé'
                                        ];
                                        echo $statuts[$tournoi['statut']] ?? $tournoi['statut'];
                                        ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h4>Équipes participantes</h4>
                            <?php if ($teamCount > 0): ?>
                                <p><?= $teamCount ?> équipe(s) inscrite(s)</p>
                                <a href="index.php?module=equipe&action=listByTournoi&tournoi_id=<?= $tournoi['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    Voir les équipes
                                </a>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    Aucune équipe inscrite pour le moment
                                </div>
                                <a href="index.php?module=equipe&action=create&tournoi_id=<?= $tournoi['id'] ?>" 
                                   class="btn btn-sm btn-primary">
                                    Ajouter une équipe
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($tournoi['reglement'])): ?>
                            <div class="mb-3">
                                <h4>Règlement</h4>
                                <div class="border p-3 bg-light">
                                    <?= nl2br(htmlspecialchars($tournoi['reglement'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card info-card">
                    <div class="card-header bg-info text-white">
                        <h4>Actions rapides</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="index.php?module=match&action=generate&tournoi_id=<?= $tournoi['id'] ?>" 
                               class="btn btn-success">
                                Générer les matchs
                            </a>
                            <a href="index.php?module=tournoi&action=export&id=<?= $tournoi['id'] ?>" 
                               class="btn btn-outline-secondary">
                                Exporter en PDF
                            </a>
                            <form method="POST" 
                                  action="index.php?module=tournoi&action=delete&id=<?= $tournoi['id'] ?>" 
                                  onsubmit="return confirm('Supprimer définitivement ce tournoi ?')">
                                <button type="submit" class="btn btn-danger w-100">Supprimer le tournoi</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>