<?php require __DIR__ . '/../templates/header.php'; ?>

<body>


<div class="container mt-4">
    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert <?= $_SESSION['message_class'] ?? 'alert-info' ?> alert-dismissible fade show">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php 
        unset($_SESSION['message']);
        unset($_SESSION['message_class']);
        ?>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= htmlspecialchars($tournoi['nom']) ?></h1>
        <a href="index.php?controller=tournoi" class="btn btn-secondary">Retour à la liste</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Informations sur le tournoi -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informations générales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Lieu:</strong> <?= htmlspecialchars($tournoi['lieu']) ?></p>
                            <p><strong>Dates:</strong> Du <?= date('d/m/Y', strtotime($tournoi['date_debut'])) ?> au <?= date('d/m/Y', strtotime($tournoi['date_fin'])) ?></p>
                            <p><strong>Format:</strong> <?= htmlspecialchars($tournoi['format']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Catégorie:</strong> <?= htmlspecialchars($tournoi['categorie']) ?></p>
                            <p><strong>Équipes max:</strong> <?= htmlspecialchars($tournoi['nb_equipes_max']) ?></p>
                            <p><strong>Statut:</strong> <span class="badge bg-<?= $tournoi['statut'] === 'en_attente' ? 'warning' : ($tournoi['statut'] === 'en_cours' ? 'primary' : 'success') ?>"><?= str_replace('_', ' ', ucfirst($tournoi['statut'])) ?></span></p>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <hr>
                        <div class="d-flex justify-content-end">
                            <a href="index.php?controller=tournoi&action=edit&id=<?= $tournoi['id'] ?>" class="btn btn-primary me-2">Modifier</a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Supprimer</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Liste des équipes -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Équipes inscrites (<?= $nombreEquipes ?> / <?= $tournoi['nb_equipes_max'] ?>)</h5>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' && $nombreEquipes < $tournoi['nb_equipes_max']): ?>
                        <a href="index.php?controller=equipe&action=create&tournoi_id=<?= $tournoi['id'] ?>" class="btn btn-success btn-sm">Ajouter une équipe</a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (empty($equipes)): ?>
                        <p class="text-muted">Aucune équipe inscrite pour le moment.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($equipes as $equipe): ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <?php if (!empty($equipe['logo'])): ?>
                                                    <img src="<?= htmlspecialchars($equipe['logo']) ?>" alt="Logo <?= htmlspecialchars($equipe['nom']) ?>" class="img-fluid me-2" style="max-width: 50px; max-height: 50px;">
                                                <?php else: ?>
                                                    <div class="bg-light me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        <i class="bi bi-shield-fill" style="font-size: 24px;"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <h6 class="card-title mb-0"><?= htmlspecialchars($equipe['nom']) ?></h6>
                                            </div>
                                            <p class="card-text small text-muted">Contact: <?= htmlspecialchars($equipe['contact_email'] ?? 'Non spécifié') ?></p>
                                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                                <div class="d-flex justify-content-end">
                                                    <a href="index.php?controller=equipe&action=edit&id=<?= $equipe['id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="index.php?controller=equipe&action=delete&id=<?= $equipe['id'] ?>&tournoi_id=<?= $tournoi['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Gestion des poules -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Gestion des poules</h5>
                </div>
                <div class="card-body">
                    <?php if (!$tournoi['poules_generees']): ?>
                        <p>Aucune poule n'a encore été générée pour ce tournoi.</p>
                        
                        <?php if ($nombreEquipes >= 4): ?>
                            <form action="index.php?controller=tournoi&action=genererPoules" method="post" class="mb-3">
                                <input type="hidden" name="tournoi_id" value="<?= $tournoi['id'] ?>">
                                <div class="form-group">
                                    <label for="nb_poules">Nombre de poules</label>
                                    <select name="nb_poules" id="nb_poules" class="form-control">
                                        <?php if ($nombreEquipes >= 4): ?><option value="2">2 poules</option><?php endif; ?>
                                        <?php if ($nombreEquipes >= 8): ?><option value="4">4 poules</option><?php endif; ?>
                                        <?php if ($nombreEquipes >= 16): ?><option value="8">8 poules</option><?php endif; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Générer les poules</button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                Vous avez besoin d'au moins 4 équipes pour pouvoir générer des poules.
                            </div>
                        <?php endif; ?>
                        
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($poules as $poule): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5><?= htmlspecialchars($poule['nom']) ?></h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group">
                                                <?php foreach ($poule['equipes'] as $equipe): ?>
                                                    <li class="list-group-item">
                                                        <?= htmlspecialchars($equipe['nom']) ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <form action="index.php?controller=tournoi&action=genererPoules" method="post" class="mt-3">
                            <input type="hidden" name="tournoi_id" value="<?= $tournoi['id'] ?>">
                            <div class="form-group">
                                <label for="nb_poules">Regénérer les poules</label>
                                <select name="nb_poules" id="nb_poules" class="form-control">
                                    <?php if ($nombreEquipes >= 4): ?><option value="2">2 poules</option><?php endif; ?>
                                    <?php if ($nombreEquipes >= 8): ?><option value="4">4 poules</option><?php endif; ?>
                                    <?php if ($nombreEquipes >= 16): ?><option value="8">8 poules</option><?php endif; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-warning mt-2">Regénérer les poules</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Gestion des matchs (à ajouter plus tard) -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Matchs</h5>
                </div>
                <div class="card-body">
                <?php if ($tournoi['poules_generees'] == 1 && empty($tournoi['matchs_poule_generes'])): ?>
    <div class="card mt-3">
        <div class="card-header">Génération des matchs</div>
        <div class="card-body">
            <p>Les poules ont été créées. Vous pouvez maintenant générer les matchs pour ces poules.</p>
            <form action="index.php?module=match&action=genererMatchsPoules" method="post">
                <input type="hidden" name="tournoi_id" value="<?= $tournoi['id'] ?>">
                <button type="submit" class="btn btn-primary">Générer les matchs de poules</button>
            </form>
        </div>
    </div>
<?php endif; ?>
                    <p class="text-muted">Aucun match programmé pour le moment.</p>
                    <!-- Vous pourrez ajouter la gestion des matchs ici plus tard -->
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Informations supplémentaires, sidebar, etc. -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Statistiques</h5>
                </div>
                <div class="card-body">
                    <p><strong>Équipes inscrites:</strong> <?= $nombreEquipes ?> / <?= $tournoi['nb_equipes_max'] ?></p>
                    <p><strong>Poules générées:</strong> <?= $tournoi['poules_generees'] ? 'Oui' : 'Non' ?></p>
                    <p><strong>Matchs programmés:</strong> 0</p>
                    <!-- Plus de statistiques ici -->
                </div>
            </div>
            
            <!-- Autres widgets, informations, etc. -->
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce tournoi ? Cette action est irréversible et supprimera également toutes les équipes et matchs associés.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="index.php?controller=tournoi&action=delete&id=<?= $tournoi['id'] ?>" class="btn btn-danger">Supprimer</a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

