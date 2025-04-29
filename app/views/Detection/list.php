<?php
// Définir le titre et la page active
$pageTitle = "Gestion des détections";
$currentPage = "detections";

// Capturer le contenu
ob_start();
?>

    <!-- En-tête avec titre et bouton d'ajout -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3"> <!-- Ajout de margin-top -->
        <h1 class="h3 mb-0 text-gray-800">Journées de détection</h1>
        <a href="?module=detection&action=create" class="btn btn-primary btn-sm d-flex align-items-center">
            <i class="fas fa-plus me-2"></i> Nouvelle détection
        </a>
    </div>

    <!-- Filtres et recherche -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Filtres</h6>
            <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <i class="fas fa-filter"></i>
            </button>
        </div>
        <div class="collapse" id="filterCollapse">
            <div class="card-body">
                <form action="" method="GET" class="row g-3">
                    <input type="hidden" name="module" value="detection">
                    <input type="hidden" name="action" value="list">
                    
                    <div class="col-md-3">
                        <label for="filterStatus" class="form-label">Statut</label>
                        <select class="form-select form-select-sm" id="filterStatus" name="status">
                            <option value="">Tous les statuts</option>
                            <option value="upcoming">À venir</option>
                            <option value="ongoing">En cours</option>
                            <option value="completed">Terminée</option>
                            <option value="cancelled">Annulée</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="filterCategory" class="form-label">Catégorie d'âge</label>
                        <select class="form-select form-select-sm" id="filterCategory" name="category">
                            <option value="">Toutes les catégories</option>
                            <option value="U10">U10</option>
                            <option value="U12">U12</option>
                            <option value="U14">U14</option>
                            <option value="U16">U16</option>
                            <option value="U18">U18</option>
                            <option value="Senior">Senior</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="filterDate" class="form-label">Date</label>
                        <input type="date" class="form-control form-control-sm" id="filterDate" name="date">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="searchTerm" class="form-label">Recherche</label>
                        <input type="text" class="form-control form-control-sm" id="searchTerm" name="search" 
                               placeholder="Nom, lieu, club partenaire...">
                    </div>
                    
                    <div class="col-12 mt-3 d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary btn-sm me-2">Réinitialiser</button>
                        <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tableau des détections avec scrolling horizontal si nécessaire -->
    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="detectionsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3" style="min-width: 200px">Nom</th>
                            <th style="min-width: 100px">Date</th>
                            <th style="min-width: 150px">Lieu</th>
                            <th style="min-width: 120px">Club partenaire</th>
                            <th style="min-width: 100px">Catégorie</th>
                            <th style="min-width: 100px">Statut</th>
                            <th class="text-end pe-3" style="min-width: 100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($detections)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="py-5">
                                        <img src="assets/images/empty-list.svg" alt="Aucune détection" width="120" class="mb-3">
                                        <h5>Aucune journée de détection</h5>
                                        <p class="text-muted">Commencez par ajouter une nouvelle journée de détection</p>
                                        <a href="?module=detection&action=create" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-2"></i>Ajouter une détection
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            
                        <?php else: ?>
                          
                            <?php foreach ($detections as $detection): ?>
                                

                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center">
                                            <div class="detection-icon bg-<?= getStatusColor($detection['status']) ?> text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                <i class="fas fa-futbol"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold"><?= htmlspecialchars($detection['name']) ?></span>
                                                <br>
<                                              <small class="text-muted">ID: #<?= $detection['id'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($detection['date'])) ?>
                                        <br>
                                        <small class="text-muted"><?= date('H:i', strtotime($detection['date'])) ?></small>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($detection['location']) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($detection['partner_club']) ?: '<span class="text-muted">-</span>' ?>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-info text-dark">
                                            <?= htmlspecialchars($detection['age_category']) ?>
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <?php
                                        $badgeClass = '';
                                        $statusText = '';
                                        switch($detection['status']) {
                                            case 'upcoming':
                                                $badgeClass = 'bg-primary';
                                                $statusText = 'À venir';
                                                break;
                                            case 'ongoing':
                                                $badgeClass = 'bg-success';
                                                $statusText = 'En cours';
                                                break;
                                            case 'completed':
                                                $badgeClass = 'bg-secondary';
                                                $statusText = 'Terminée';
                                                break;
                                            case 'cancelled':
                                                $badgeClass = 'bg-danger';
                                                $statusText = 'Annulée';
                                                break;
                                            default:
                                                $badgeClass = 'bg-info';
                                                $statusText = $detection['status'];
                                        }
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="?module=detection&action=show&id=<?= $detection['id'] ?>">
                                                        <i class="fas fa-eye me-2 text-info"></i>Voir les détails
                                                    </a>
                                                </li>
                                                
                                                <li>
                                                    <a class="dropdown-item" href="?module=detection&action=edit&id=<?= $detection['id'] ?>">
                                                        <i class="fas fa-edit me-2 text-warning"></i>Modifier
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
                                                    <a class="dropdown-item delete-item" href="javascript:void(0);" data-id="<?= $detection['id'] ?>">
                                                        <i class="fas fa-trash-alt me-2 text-danger"></i>Supprimer
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <?php if (!empty($detections) && isset($totalPages) && $totalPages > 1): ?>
    <div class="d-flex justify-content-center mt-4 mb-4">
        <nav aria-label="Navigation des pages">
            <ul class="pagination pagination-sm">
                <?php if ($currentPage > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?module=detection&action=list&page=<?= $currentPage - 1 ?>" aria-label="Précédent">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                    <a class="page-link" href="?module=detection&action=list&page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?module=detection&action=list&page=<?= $currentPage + 1 ?>" aria-label="Suivant">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
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
        <p>Êtes-vous sûr de vouloir supprimer définitivement cette journée de détection ?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
        <a href="#" id="confirmDelete" class="btn btn-danger">Supprimer définitivement</a>
      </div>
    </div>
  </div>
</div>

<!-- Fonctions utilitaires pour l'affichage -->
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
?>

<!-- Script pour la gestion de la suppression -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Script pour la confirmation de suppression
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function() {
            const detectionId = this.getAttribute('data-id');
            confirmDeleteBtn.href = `?module=detection&action=delete&id=${detectionId}`;
            deleteModal.show();
        });
    });
});
</script>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../Admin/adminl_layout.php';
?>
