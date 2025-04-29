

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../Users/adminl_layout.php';
?>

<?php
// Définir le titre et la page active
$pageTitle = "Détails de la détection";
$currentPage = "detections";

// Capturer le contenu
ob_start();
?>

<div class="container-fluid px-4">
    <!-- En-tête avec titre et boutons d'action -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="?module=detection&action=list">Détections</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Détails</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800"><?= htmlspecialchars($detection['name']) ?></h1>
        </div>
        <div class="d-flex gap-1">
            <a href="?module=detection&action=edit&id=<?= $detection['id'] ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i> Modifier
            </a>
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-cog me-1"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="?module=detection&action=participants&id=<?= $detection['id'] ?>">
                            <i class="fas fa-users me-2 text-primary"></i>Gérer les participants
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="?module=detection&action=export&id=<?= $detection['id'] ?>">
                            <i class="fas fa-file-export me-2 text-success"></i>Exporter les données
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="?module=detection&action=duplicate&id=<?= $detection['id'] ?>">
                            <i class="fas fa-copy me-2 text-secondary"></i>Dupliquer
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <?php if ($detection['status'] !== 'cancelled'): ?>
                    <li>
                        <a class="dropdown-item" href="?module=detection&action=cancel&id=<?= $detection['id'] ?>" 
                            onclick="return confirm('Êtes-vous sûr de vouloir annuler cette détection ?')">
                            <i class="fas fa-ban me-2 text-warning"></i>Annuler
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a class="dropdown-item text-danger delete-detection" href="javascript:void(0);" data-id="<?= $detection['id'] ?>">
                            <i class="fas fa-trash-alt me-2"></i>Supprimer
                        </a>
                    </li>
                </ul>
            </div>
            <a href="?module=detection&action=list" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informations générales -->
        <div class="col-xl-8 col-lg-7">
            
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informations générales</h6>
                    <span class="badge bg-<?= getStatusColor($detection['status']) ?>"><?= getStatusLabel($detection['status']) ?></span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Nom de la détection</label>
                                <p class="mb-0 fw-medium"><?= htmlspecialchars($detection['name']) ?></p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small">Date et heure</label>
                                <p class="mb-0 fw-medium">
                                    <?= date('d/m/Y à H:i', strtotime($detection['date'])) ?>
                                    <small class="ms-2 text-muted">
                                        <?= getRelativeDate(strtotime($detection['date'])) ?>
                                    </small>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small">Catégorie d'âge</label>
                                <p class="mb-0 fw-medium">
                                    <span class="badge bg-info text-dark"><?= htmlspecialchars($detection['age_category']) ?></span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Lieu</label>
                                <p class="mb-0 fw-medium"><?= htmlspecialchars($detection['location']) ?></p>
                                <a href="https://maps.google.com/?q=<?= urlencode($detection['location']) ?>" 
                                   target="_blank" class="small text-primary">
                                    <i class="fas fa-map-marker-alt"></i> Voir sur la carte
                                </a>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small">Club partenaire</label>
                                <p class="mb-0 fw-medium">
                                    <?= !empty($detection['partner_club']) ? htmlspecialchars($detection['partner_club']) : '<span class="text-muted fst-italic">Aucun club partenaire</span>' ?>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small">Participants</label>
                                <p class="mb-0 fw-medium">
                                    <?php 

                                    $inscritCount = $detection['participant_count'] ?? 0;
                                    $maxParticipants = $detection['max_participants'];
                                    $inscritPercent = min(100, ($inscritCount / $maxParticipants) * 100);
                                    $progressClass = $inscritPercent >= 90 ? 'danger' : ($inscritPercent >= 60 ? 'warning' : 'success');
                                    ?>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar bg-<?= $progressClass ?>" role="progressbar" 
                                                style="width: <?= $inscritPercent ?>%" 
                                                aria-valuenow="<?= $inscritPercent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="fw-bold"><?= $inscritCount ?>/<?= $maxParticipants ?></span>
                                    </div>
                                    <?php if ($inscritPercent >= 90): ?>
                                    <small class="text-danger">Presque complet !</small>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($detection['description'])): ?>
                    <div>
                        <label class="form-label text-muted small">Description</label>
                        <div class="p-3 bg-light rounded">
                            <?= nl2br(htmlspecialchars($detection['description'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            ID: #<?= $detection['id'] ?>
                        </div>
                        <div class="small text-muted">
                            Créé le: <?= date('d/m/Y', strtotime($detection['created_at'])) ?>
                            <?php if (!empty($detection['updated_at']) && $detection['updated_at'] != $detection['created_at']): ?>
                            | Modifié le: <?= date('d/m/Y', strtotime($detection['updated_at'])) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des participants -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Participants</h6>
                    <a href="?module=detection&action=participants&id=<?= $detection['id'] ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-users me-1"></i> Gérer les participants
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($participants)): ?>
                    <div class="text-center py-5">
                        <img src="assets/images/empty-participants.svg" alt="Aucun participant" class="mb-3" width="120">
                        <h5>Aucun participant inscrit</h5>
                        <p class="text-muted">Aucun joueur n'est encore inscrit à cette détection</p>
                        <a href="?module=detection&action=register&id=<?= $detection['id'] ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Ajouter un participant
                        </a>
                        
                    </div>

                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Âge</th>
                                    <th>Position</th>
                                    <th>Contact</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($participants as $participant): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($participant['photo'])): ?>
                                            <img src="uploads/participants/<?= $participant['photo'] ?>" alt="Photo" class="rounded-circle me-2" width="36" height="36">
                                            <?php else: ?>
                                            <div class="bg-secondary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <?php endif; ?>
                                            <div>
                                                <?= htmlspecialchars($participant['first_name']) ?> <?= htmlspecialchars($participant['last_name']) ?>
                                                <br>
                                                <small class="text-muted">Inscrit le <?= date('d/m/Y', strtotime($participant['registration_date'])) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= $participant['age'] ?> ans</td>
                                    <td><?= htmlspecialchars($participant['position']) ?></td>
                                    <td>
                                        <?= htmlspecialchars($participant['email']) ?>
                                        <br>
                                        <small><?= htmlspecialchars($participant['phone']) ?></small>
                                    </td>
                                    <td>
                                        <?php 
                                        switch($participant['status']): 
                                            case 'confirmed': ?>
                                                <span class="badge bg-success">Confirmé</span>
                                                <?php break; 
                                            case 'pending': ?>
                                                <span class="badge bg-warning text-dark">En attente</span>
                                                <?php break; 
                                            case 'rejected': ?>
                                                <span class="badge bg-danger">Refusé</span>
                                                <?php break; 
                                            case 'canceled': ?>
                                                <span class="badge bg-secondary">Annulé</span>
                                                <?php break; 
                                            default: ?>
                                                <span class="badge bg-info"><?= $participant['status'] ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="?module=detection&action=participant_details&detection_id=<?= $detection['id'] ?>&participant_id=<?= $participant['id'] ?>" class="btn btn-sm btn-icon">
                                            <i class="fas fa-eye text-info"></i>
                                        </a>
                                        <a href="?module=detection&action=edit_participant&detection_id=<?= $detection['id'] ?>&participant_id=<?= $participant['id'] ?>" class="btn btn-sm btn-icon">
                                            <i class="fas fa-edit text-warning"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (count($participants) > 5): ?>
                    <div class="p-3 text-center border-top">
                        <a href="?module=detection&action=participants&id=<?= $detection['id'] ?>" class="btn btn-outline-primary btn-sm">
                            Voir tous les participants (<?= count($participants) ?>)
                        </a>
                      



                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar d'informations -->
        <div class="col-xl-4 col-lg-5">
            <!-- Statistiques -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistiques</h6>
                </div>
                <div class="card-body">
                    <div class="row row-cols-2">
                        <div class="col mb-4">
                            <div class="d-flex align-items-center">
                                <div class="me-3 bg-info text-white rounded p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="fas fa-users fa-fw"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Participants</div>
                                    <div class="fs-4 fw-bold"><?= $inscritCount ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-4">
                            <div class="d-flex align-items-center">
                                <div class="me-3 bg-success text-white rounded p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="fas fa-check-circle fa-fw"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Confirmés</div>
                                    <div class="fs-4 fw-bold"><?= $confirmedCount ?? 0 ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-4">
                            <div class="d-flex align-items-center">
                                <div class="me-3 bg-warning text-white rounded p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="fas fa-clock fa-fw"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">En attente</div>
                                    <div class="fs-4 fw-bold"><?= $pendingCount ?? 0 ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-4">
                            <div class="d-flex align-items-center">
                                <div class="me-3 bg-secondary text-white rounded p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="fas fa-ban fa-fw"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Places restantes</div>
                                    <div class="fs-4 fw-bold"><?= $maxParticipants - $inscritCount ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($inscritCount > 0): ?>
                    <div class="my-3">
                        <h6 class="text-muted small">Répartition par positions</h6>
                        <canvas id="positionsChart" width="100%" height="100"></canvas>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Actions rapides -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="?module=detection&action=add_participant&id=<?= $detection['id'] ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-user-plus text-success me-2"></i>
                                    Ajouter un participant
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </div>
                        </a>
                        <a href="?module=detection&action=send_email&id=<?= $detection['id'] ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    Envoyer un email aux participants
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </div>
                        </a>
                        <a href="?module=detection&action=export&id=<?= $detection['id'] ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file-export text-info me-2"></i>
                                    Exporter les données
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </div>
                        </a>
                        <a href="?module=detection&action=print_list&id=<?= $detection['id'] ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-print text-secondary me-2"></i>
                                    Imprimer la liste des participants
                                </div>
                                <i class="fas fa-chevron-right text-muted"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Lien public -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lien d'inscription</h6>
                </div>
                <div class="card-body">
                    <p class="mb-3 small">Partagez ce lien pour permettre l'inscription à cette journée de détection :</p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="<?= BASE_URL ?>?page=detection&id=<?= $detection['id'] ?>" id="publicLink" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyLink()">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#qrCodeModal">
                            <i class="fas fa-qrcode me-2"></i> Afficher QR Code
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal QR Code -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">QR Code d'inscription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode(BASE_URL . '?page=detection&id=' . $detection['id']) ?>" alt="QR Code" class="img-fluid">
                <p class="mt-3 mb-0 small">Scannez ce code pour accéder au formulaire d'inscription</p>
            </div>
            <div class="modal-footer">
                <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<?= urlencode(BASE_URL . '?page=detection&id=' . $detection['id']) ?>" download="qrcode_detection_<?= $detection['id'] ?>.png" class="btn btn-primary">Télécharger</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Attention ! Cette action est irréversible.</p>
                <p>Êtes-vous sûr de vouloir supprimer définitivement cette journée de détection et toutes ses données associées ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Supprimer définitivement</a>
            </div>
        </div>
    </div>
</div>

<!-- Fonctions utilitaires -->
<?php
function getStatusColor($status) {
    switch($status) {
        case 'upcoming': return 'primary';
        case 'ongoing': return 'success';
        case 'completed': return 'secondary';
        case 'cancelled': return 'danger';
        default: return 'primary';
    }
}

function getStatusLabel($status) {
    switch($status) {
        case 'upcoming': return 'À venir';
        case 'ongoing': return 'En cours';
        case 'completed': return 'Terminée';
        case 'cancelled': return 'Annulée';
        default: return ucfirst($status);
    }
}

function getRelativeDate($timestamp) {
    $now = time();
    $diff = $timestamp - $now;
    
    if ($diff < 0) {
        return 'Passé';
    }
    
    $day = 86400; // 24 * 60 * 60
    
    if ($diff < $day) {
        return 'Aujourd\'hui';
    } elseif ($diff < (2 * $day)) {
        return 'Demain';
    } elseif ($diff < (7 * $day)) {
        return 'Dans ' . ceil($diff / $day) . ' jours';
    } elseif ($diff < (30 * $day)) {
        return 'Dans ' . ceil($diff / (7 * $day)) . ' semaine(s)';
    } else {
        return 'Dans ' . ceil($diff / (30 * $day)) . ' mois';
    }
}
?>

<!-- Scripts spécifiques à cette page -->
<script>
// Copier le lien public
function copyLink() {
    var copyText = document.getElementById("publicLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    // Notification toast (à implémenter)
    alert('Lien copié dans le presse-papier');
}

document.addEventListener('DOMContentLoaded', function() {
    // Script pour la confirmation de suppression
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    
    document.querySelectorAll('.delete-detection').forEach(button => {
        button.addEventListener('click', function() {
            const detectionId = this.getAttribute('data-id');
            confirmDeleteBtn.href = `?module=detection&action=delete&id=${detectionId}`;
            deleteModal.show();
        });
    });

    <?php if ($inscritCount > 0): ?>
    // Graphique de répartition par positions
    const posCtx = document.getElementById('positionsChart').getContext('2d');
    new Chart(posCtx, {
        type: 'doughnut',
        data: {
            labels: [
                <?php
                // Simuler des données pour le graphique (à remplacer par les vraies données)
                $positions = [
                    'Gardien' => $gardienCount ?? 2,
                    'Défenseur' => $defenseCount ?? 8,
                    'Milieu' => $milieuCount ?? 12, 
                    'Attaquant' => $attaquantCount ?? 6
                ];
                
                echo "'" . implode("', '", array_keys($positions)) . "'";
                ?>
            ],
            datasets: [{
                data: [
                    <?= implode(", ", array_values($positions)) ?>
                ],
                backgroundColor: [
                    '#36a2eb', '#ff6384', '#4bc0c0', '#ffcd56', '#9966ff'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 10
                    }
                }
            }
        }
    });
    <?php endif; ?>
});
</script>


