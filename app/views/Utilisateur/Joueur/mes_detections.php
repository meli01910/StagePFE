<?php include __DIR__ . '/../../templates/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group mb-4">
                <a href="index.php?module=joueur&action=profil" class="list-group-item list-group-item-action">
                    <i class="fas fa-user me-2"></i> Mon profil
                </a>
                <a href="index.php?module=joueur&action=mes_detections" class="list-group-item list-group-item-action active">
                    <i class="fas fa-list-alt me-2"></i> Mes inscriptions
                </a>
                <a href="index.php?module=detection&action=index" class="list-group-item list-group-item-action">
                    <i class="fas fa-search me-2"></i> Voir les détections
                </a>
                <a href="index.php?module=utilisateur&action=deconnexion" class="list-group-item list-group-item-action text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                </a>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Mes inscriptions aux détections</h2>
                </div>
                <div class="card-body">
                    <?php if (empty($inscriptions)): ?>
                        <div class="alert alert-info">
                            Vous n'êtes inscrit à aucune détection pour le moment.
                            <a href="index.php?module=detection&action=index" class="alert-link">Voir les détections disponibles</a>.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Détection</th>
                                        <th>Date</th>
                                        <th>Lieu</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inscriptions as $inscription): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($inscription['titre']) ?></td>
                                        <td><?= date('d/m/Y à H:i', strtotime($inscription['date_heure'])) ?></td>
                                        <td><?= htmlspecialchars($inscription['lieu']) ?></td>
                                        <td>
                                            <?php if ($inscription['statut_inscription'] === 'confirmé'): ?>
                                                <span class="badge bg-success">Confirmé</span>
                                            <?php elseif ($inscription['statut_inscription'] === 'en_attente'): ?>
                                                <span class="badge bg-warning">En attente</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?= htmlspecialchars($inscription['statut_inscription']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="index.php?module=detection&action=show&id=<?= $inscription['detection_id'] ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Détails
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
