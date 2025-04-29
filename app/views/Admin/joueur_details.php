
<?php include __DIR__ . '/../templates/header.php'; ?>
<?php
// Vérifier que $joueur existe
if (empty($joueur)) {
    echo '<div class="alert alert-danger">Joueur non trouvé</div>';
    exit;
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Profil du joueur</h1>
        <div>
            <a href="index.php?module=utilisateur&action=index" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
            <a href="index.php?module=utilisateur&action=edit&id=<?= $joueur['id'] ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Modifier
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?> alert-dismissible fade show">
        <?= $_SESSION['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php 
        unset($_SESSION['message']); 
        unset($_SESSION['message_type']);
    endif; 
    ?>

    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-center">
                        <?php if (!empty($joueur['photo'])): ?>
                            <img src="<?= htmlspecialchars($joueur['photo']) ?>" alt="Photo de profil" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 150px; height: 150px;">
                                <i class="fas fa-user fa-4x"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row" width="40%">Nom:</th>
                                <td><?= htmlspecialchars($joueur['nom']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Prénom:</th>
                                <td><?= htmlspecialchars($joueur['prenom']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Email:</th>
                                <td><?= htmlspecialchars($joueur['email']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Téléphone:</th>
                                <td><?= htmlspecialchars($joueur['telephone'] ?? 'Non renseigné') ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Date de naissance:</th>
                                
                                <td><?= htmlspecialchars($joueur['date_naissance'] ?? 'Non renseigné') ?></td>
                        
                                
                            </tr>
                            <tr>
                                <th scope="row">Nationalité:</th>
                                <td><?= htmlspecialchars($joueur['nationalite'] ?? 'Non renseignée') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Caractéristiques sportives -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Profil sportif</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row" width="40%">Poste:</th>
                                <td><?= htmlspecialchars($joueur['poste'] ?? 'Non renseigné') ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Niveau de jeu:</th>
                                <td><?= htmlspecialchars($joueur['niveau_jeu'] ?? 'Non renseigné') ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Taille:</th>
                                <td>
                                    <?= !empty($joueur['taille']) ? htmlspecialchars($joueur['taille']) . ' cm' : 'Non renseignée' ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Poids:</th>
                                <td>
                                    <?= !empty($joueur['poids']) ? htmlspecialchars($joueur['poids']) . ' kg' : 'Non renseigné' ?>
                                </td>
                            </tr>
                          
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Statut du compte -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Statut du compte</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row" width="40%">Statut:</th>
                                <td>
                                    <?php 
                                    $statut_color = [
                                        'en_attente' => 'warning',
                                        'approuve' => 'success',
                                        'refuse' => 'danger'
                                    ];
                                    $statut_label = [
                                        'en_attente' => 'En attente',
                                        'approuve' => 'Approuvé',
                                        'refuse' => 'Refusé'
                                    ];
                                    $color = $statut_color[$joueur['statut']] ?? 'secondary';
                                    $label = $statut_label[$joueur['statut']] ?? $joueur['statut'];
                                    ?>
                                    <span class="badge bg-<?= $color ?>"><?= $label ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Inscription le:</th>
                                <td><?= date('d/m/Y à H:i', strtotime($joueur['date_creation'])) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Justificatif:</th>
                                <td>
                                    <?php if (!empty($joueur['justificatif'])): ?>
                                        <a href="index.php?module=utilisateur&action=afficherJustificatif&id=<?= $joueur['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf"></i> Voir le document
                                        </a>
                                       
                                    <?php else: ?>
                                        <span class="text-muted">Aucun justificatif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if ($joueur['statut'] == 'en_attente'): ?>
                            <tr>
                                <th scope="row">Actions:</th>
                                <td>
                                    <div class="btn-group">
                                        <a href="index.php?module=utilisateur&action=approver_joueur&id=<?= $joueur['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Confirmer l\'approbation de ce joueur?')">
                                            <i class="fas fa-check"></i> Approuver
                                        </a>
                                        <a href="index.php?module=utilisateur&action=refuser_joueur&id=<?= $joueur['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer le refus de ce joueur?')">
                                            <i class="fas fa-times"></i> Refuser
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Boutons d'action -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                     <a href="index.php?module=utilisateur&action=delete&id=<?= $joueur['id'] ?>" class="btn btn-warning">
                       <i class="fas fa-trash"></i> Supprimer ce joueur
                    </a>
                    
                    <?php if ($joueur['statut'] == 'approuve'): ?>
                    <a href="index.php?module=utilisateur&action=approuver_joueur&id=<?= $joueur['id'] ?>" class="btn btn-warning">
                        <i class="fas fa-envelope"></i> Renvoyer les identifiants
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deletePlayerModal" tabindex="-1" aria-labelledby="deletePlayerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deletePlayerModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer définitivement le joueur <strong><?= htmlspecialchars($joueur['prenom'] . ' ' . $joueur['nom']) ?></strong> ?</p>
                <p class="text-danger"><strong>Attention:</strong> Cette action est irréversible et supprimera toutes les données associées à ce joueur.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="index.php?module=utilisateur&action=delete&id=<?= $joueur['id'] ?>" class="btn btn-danger">Confirmer la suppression</a>
            </div>
        </div>
    </div>
</div>
