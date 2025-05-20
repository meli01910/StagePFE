<?php
// Définir le titre et la page active
$pageTitle = 'Ajouter des Joueurs à l\'Équipe';
$currentPage = 'players';

// Capturer le contenu
ob_start();

// Gérer les messages flash
$message = '';
$alertClass = 'info';
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    $alertClass = $_SESSION['flash_type'] ?? 'info';
    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
}
?>

<div class="container mt-4">
    <!-- En-tête de page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="fas fa-users-cog me-2"></i>
            Ajouter des Joueurs à l'Équipe: <?= htmlspecialchars($equipe['nom'] ?? '') ?>
        </h1>
        <a href="index.php?module=equipe&action=show&id=<?= $equipeId ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour à l'équipe
        </a>
    </div>
    
    <!-- Affichage des messages -->
    <?php if ($message): ?>
        <div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-plus-circle me-2"></i> 
            Sélectionnez les joueurs à ajouter
        </div>
        <div class="card-body">
            <?php if (empty($players)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Tous les joueurs sont déjà dans une équipe ou aucun joueur n'est disponible.
                </div>
            <?php else: ?>
                <form action="index.php?module=equipe&action=addPlayer&id=<?= $equipeId ?>" method="post" id="addPlayersForm">
                    <!-- Barre de recherche et sélection globale -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" id="searchPlayers" class="form-control" placeholder="Rechercher un joueur...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-check-inline float-md-end">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">Sélectionner tous les joueurs</label>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des joueurs -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;"></th>
                                    <th style="width: 80px;">Photo</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Position</th>
                                    <th>Numéro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($players as $player): ?>
                                    <tr class="player-row">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input player-checkbox" type="checkbox" 
                                                    name="player_ids[]" value="<?= $player['id'] ?>" 
                                                    id="player<?= $player['id'] ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($player['photo'])): ?>
                                                <img src="<?= htmlspecialchars($player['photo']) ?>" alt="Photo de <?= htmlspecialchars($player['prenom']) ?>" 
                                                    class="img-thumbnail rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" 
                                                    style="width: 50px; height: 50px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($player['nom'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($player['prenom'] ?? '') ?></td>
                                        <td>
                                            <?php if (!empty($player['poste'])): ?>
                                                <span class="badge bg-<?= getPositionColor($player['position']) ?>">
                                                    <?= htmlspecialchars($player['poste']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Non défini</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($player['numero'])): ?>
                                                <span class="badge bg-dark"><?= htmlspecialchars($player['numero']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="mt-3 d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" onclick="window.location.href='index.php?module=equipe&action=show&id=<?= $equipeId ?>'">
                            <i class="fas fa-times me-1"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-primary" id="addPlayersButton" disabled>
                            <i class="fas fa-plus-circle me-1"></i> Ajouter les joueurs sélectionnés
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// JavaScript pour améliorer l'interaction
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const searchInput = document.getElementById('searchPlayers');
    const selectAllCheckbox = document.getElementById('selectAll');
    const playerCheckboxes = document.querySelectorAll('.player-checkbox');
    const playerRows = document.querySelectorAll('.player-row');
    const addButton = document.getElementById('addPlayersButton');
    
    // Fonction pour mettre à jour l'état du bouton d'ajout
    function updateAddButton() {
        const anyChecked = Array.from(playerCheckboxes).some(checkbox => checkbox.checked && !checkbox.closest('tr').classList.contains('d-none'));
        addButton.disabled = !anyChecked;
    }
    
    // Écouteurs d'événements pour les cases à cocher
    playerCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateAddButton);
    });
    
    // Recherche en temps réel
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        playerRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.classList.remove('d-none');
            } else {
                row.classList.add('d-none');
                // Décocher les cases à cocher des lignes masquées si "Sélectionner tout" est activé
                if (selectAllCheckbox.checked) {
                    const checkbox = row.querySelector('.player-checkbox');
                    checkbox.checked = false;
                }
            }
        });
        
        // Mettre à jour l'état du bouton d'ajout
        updateAddButton();
    });
    
    // Fonctionnalité "Sélectionner tout"
    selectAllCheckbox.addEventListener('change', function() {
        const visibleCheckboxes = Array.from(playerCheckboxes)
            .filter(checkbox => !checkbox.closest('tr').classList.contains('d-none'));
            
        visibleCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        
        updateAddButton();
    });
});

// Fonction pour déterminer la couleur de fond du badge selon la position
function getPositionColor(position) {
    switch(position) {
        case 'Gardien': return 'warning';
        case 'Défenseur': return 'danger';
        case 'Milieu': return 'info';
        case 'Attaquant': return 'success';
        default: return 'secondary';
    }
}
</script>

<?php
// Fonction helper pour obtenir une couleur selon la position (côté serveur)
function getPositionColor($position) {
    switch($position) {
        case 'Gardien': return 'warning';
        case 'Défenseur': return 'danger';
        case 'Milieu': return 'info';
        case 'Attaquant': return 'success';
        default: return 'secondary';
    }
}

$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>
