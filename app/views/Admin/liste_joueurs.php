<?php
// Fichier: /app/views/Users/dashboard_admin.php
// S'assurer que l'utilisateur est un admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?module=auth&action=login');
    exit;
}



 include __DIR__ . '/../templates/header.php';
// Définir le titre et la page active
// Définir le titre de la page et la page active
$pageTitle = 'Gestion des Joueurs';
$currentPage = 'players';

// Capturer le contenu
ob_start();
?>

<div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Gestion des Joueurs</h1>
                <p class="text-muted">Liste et recherche des joueurs</p>
            </div>
    
        </div>
        
        <!-- Filtres et recherche -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchPlayer" placeholder="Rechercher un joueur...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterPosition">
                            <option value="">Toutes les positions</option>
                            <option value="Attaquant">attaquant</option>
                            <option value="Milieu">Milieu</option>
                            <option value="Défenseur">Défenseur</option>
                            <option value="Gardien">Gardien</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterStatus">
                            <option value="">Tous les statuts</option>
                            <option value="Approuve">Approuve</option>
                            <option value="En attente">En attente</option>
                            <option value="Refusé">Refusé</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" id="resetFilters">
                            <i class="fas fa-sync-alt"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des joueurs -->
        <div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Joueur</th>
                        <th scope="col">Poste</th>
                        <th scope="col">Niveau</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Poids</th>
                        <th scope="col">Taille</th>
                        <th scope="col">Nationalité</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($joueurs)) : ?>
                        <tr>
                            <td colspan="10" class="text-center">Aucun joueur trouvé</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($joueurs as $index => $joueur) : ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <!-- Avatar par défaut avec initiale -->
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                            <?= strtoupper(substr($joueur['prenom'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?= htmlspecialchars($joueur['prenom'] . ' ' . $joueur['nom']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($joueur['email']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($joueur['position'] ?? 'Non défini') ?></td>
                                <td><?= htmlspecialchars($joueur['niveau_jeu']) ?></td>
                                <td>
                                    <small><i class="fas fa-phone me-1"></i> <?= htmlspecialchars($joueur['telephone']) ?></small>
                                </td>
                                <td>
                                    <small> <?= htmlspecialchars($joueur['poids'])."Kg" ?></small>
                                </td>
                                <td>
                                    <small><?= htmlspecialchars($joueur['taille'])." cm" ?></small>
                                </td>
                                <td>
                                    <small><?= htmlspecialchars($joueur['nationalite']) ?></small>
                                </td>
                                <td>
                                    <span class="badge <?php 
                                        if ($joueur['statut'] === 'approuve') echo 'bg-success';
                                        elseif ($joueur['statut'] === 'en_attente') echo 'bg-warning';
                                        else echo 'bg-danger';
                                    ?>">
                                        <?= ucfirst(str_replace('_', ' ', $joueur['statut'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="index.php?module=utilisateur&action=show&id=<?= $joueur['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="index.php?module=utilisateur&action=edit&id=<?= $joueur['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-player-btn" 
        data-bs-toggle="modal" data-bs-target="#deletePlayerModal" 
        data-id="<?= $joueur['id'] ?>">
    <i class="fas fa-trash"></i>
</button>
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
                <nav aria-label="Page navigation example" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Suivant</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>



<!-- Modal Supprimer un joueur -->
<div class="modal fade" id="deletePlayerModal" tabindex="-1" aria-labelledby="deletePlayerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePlayerModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce joueur ? Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deletePlayerForm" method="post" action="index.php?module=utilisateur&action=delete">
                    <input type="hidden" name="player_id" id="deletePlayerId">
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>


<?php
// Récupérer le contenu et l'assigner à la variable $content
$content = ob_get_clean();

// Inclure le layout
require_once 'adminl_layout.php';
?>






































<script>
document.addEventListener('DOMContentLoaded', function() {
    // Récupération des éléments du DOM
    const searchPlayer = document.getElementById('searchPlayer');
    const filterPosition = document.getElementById('filterPosition');
    const filterStatus = document.getElementById('filterStatus');
    const resetFilters = document.getElementById('resetFilters');
    const playerRows = document.querySelectorAll('table tbody tr'); 
    
    // Fonction pour appliquer les filtres
    function applyFilters() {
        const searchTerm = searchPlayer.value.toLowerCase();
        const positionFilter = filterPosition.value;
        const statusFilter = filterStatus.value;
        
        console.log("Filtre par statut:", statusFilter); // Debugging
        
        playerRows.forEach(row => {
            // Ignore la ligne "Aucun joueur trouvé"
            if (row.querySelector('td[colspan="10"]')) {
                return;
            }
            
            // Récupération des données
            const playerNameElement = row.querySelector('td:nth-child(2) .fw-bold');
            const positionElement = row.querySelector('td:nth-child(3)');
            const statusElement = row.querySelector('td:nth-child(9) .badge');
            
            if (!playerNameElement || !positionElement || !statusElement) {
                return;
            }
            
            // Extraction des valeurs
            const playerName = playerNameElement.textContent.toLowerCase();
            const playerPosition = positionElement.textContent.trim();
            const playerStatus = statusElement.textContent.trim();
            
            console.log("Statut du joueur:", playerStatus); // Debugging
            
            // Mappage des statuts de la base de données aux libellés d'affichage
            let statusForFilter;
            if (playerStatus.toLowerCase().includes('approuve') || playerStatus.toLowerCase().includes('approuvé')) {
                statusForFilter = 'Approuvé';
            } else if (playerStatus.toLowerCase().includes('en attente')) {
                statusForFilter = 'En attente';
            } else {
                statusForFilter = 'Refusé';
            }
            
            console.log("Statut pour filtre:", statusForFilter); // Debugging
            
            // Application des filtres
            const matchesSearch = playerName.includes(searchTerm);
            const matchesPosition = positionFilter === '' || playerPosition === positionFilter;
            const matchesStatus = statusFilter === '' || statusForFilter === statusFilter;
            
            // Affichage ou masquage de la ligne
            if (matchesSearch && matchesPosition && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Réinitialisation des filtres
    function resetAllFilters() {
        searchPlayer.value = '';
        filterPosition.value = '';
        filterStatus.value = '';
        
        // Afficher toutes les lignes de joueurs
        playerRows.forEach(row => {
            row.style.display = '';
        });
    }
    
    // Ajout des écouteurs d'événements
    searchPlayer.addEventListener('input', applyFilters);
    filterPosition.addEventListener('change', applyFilters);
    filterStatus.addEventListener('change', applyFilters);
    resetFilters.addEventListener('click', resetAllFilters);
    
    // Mettre à jour les options du filtre de position en fonction des valeurs réelles du tableau
    function updatePositionOptions() {
        const positions = new Set();
        
        // Collecter toutes les positions uniques
        playerRows.forEach(row => {
            if (!row.querySelector('td[colspan="10"]')) {
                const positionElement = row.querySelector('td:nth-child(3)');
                if (positionElement) {
                    const position = positionElement.textContent.trim();
                    if (position && position !== 'Non défini') {
                        positions.add(position);
                    }
                }
            }
        });
        
        // Recréer les options du filtre de position
        filterPosition.innerHTML = '<option value="">Toutes les positions</option>';
        positions.forEach(position => {
            const option = document.createElement('option');
            option.value = position;
            option.textContent = position;
            filterPosition.appendChild(option);
        });
    }
    
    // Mettre à jour les options du filtre de statut
    function correctFilterStatus() {
        // Les options du filtre de statut doivent correspondre aux statuts mappés dans applyFilters
        filterStatus.innerHTML = `
            <option value="">Tous les statuts</option>
            <option value="Approuvé">Approuvé</option>
            <option value="En attente">En attente</option>
            <option value="Refusé">Refusé</option>
        `;
    }
    
    // Initialiser les filtres au chargement de la page
    updatePositionOptions();
    correctFilterStatus();
});




document.addEventListener('DOMContentLoaded', function() {
    // Gestion du modal de suppression
    const deleteModal = document.getElementById('deletePlayerModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            // Bouton qui a déclenché le modal
            const button = event.relatedTarget;
            // Récupérer l'ID du joueur depuis l'attribut data-id
            const playerId = button.getAttribute('data-id');
            // Mettre à jour le champ caché dans le formulaire
            document.getElementById('deletePlayerId').value = playerId;
            
            // Mettre à jour l'action du formulaire avec l'ID
            const form = document.getElementById('deletePlayerForm');
            form.action = `index.php?module=utilisateur&action=delete&id=${playerId}`;
        });
    }
});
</script>

