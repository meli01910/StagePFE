<?php
ob_start();
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-users me-2"></i> Joueurs disponibles</h1>
        <p class="lead">Consultez tous les joueurs inscrits dans notre base de données</p>
    </div>
</div>

<div class="container">
    <div class="section-header">
        <h2 class="section-title">Liste des joueurs</h2>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="?module=joueur&action=create" class="btn-primary">
                <i class="fas fa-plus"></i> Ajouter un joueur
            </a>
        <?php endif; ?>
    </div>


<div class="container">
    

    <div class="filters">
        <input type="text" id="searchPlayer" placeholder="Rechercher un joueur..." />
        <select id="filterPosition">
            <option value="">Toutes les positions</option>
            <option value="Attaquant">Attaquant</option>
            <option value="Milieu">Milieu</option>
            <option value="Défenseur">Défenseur</option>
            <option value="Gardien">Gardien</option>
        </select>
        <select id="filterStatus">
            <option value="">Tous les statuts</option>
            <option value="Approuvé">Approuvé</option>
            <option value="En attente">En attente</option>
            <option value="Refusé">Refusé</option>
        </select>
        <button id="resetFilters">Reset</button>
    </div>

<div class="player-cards" id="playerList">
    <?php if (empty($joueurs)) : ?>
        <div class="no-players">Aucun joueur trouvé</div>
    <?php else : ?>
        <?php foreach ($joueurs as $joueur) : ?>
            <div class="card-joueurs" data-position="<?= htmlspecialchars($joueur['poste']) ?>" data-status="<?= htmlspecialchars($joueur['statut']) ?>">
                <div class="player-info">
                    <?php if (!empty($joueur['photo'])) : ?>
                       <img src="<?= htmlspecialchars( $joueur['photo']) ?>" alt="<?= htmlspecialchars($joueur['prenom'] . ' ' . $joueur['nom']) ?>">
     <?php else : ?>
                        <div class="initial"><?= strtoupper(substr($joueur['prenom'], 0, 1)) ?></div>
                    <?php endif; ?>
                    <div>
                        <h2><?= htmlspecialchars($joueur['prenom'] . ' ' . $joueur['nom']) ?></h2>
                        <p class="email"><?= htmlspecialchars($joueur['email']) ?></p>
                    </div>
                </div>
                <p><strong>Poste:</strong> <?= htmlspecialchars($joueur['poste']) ?></p>
                <p><strong>Age:</strong> <?= htmlspecialchars($joueur['age']) ?> </p>
                <a href="index.php?module=utilisateur&action=show&id=<?= $joueur['id'] ?>" class="btn-primary">Voir Détails</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</div>

<!-- Détails du joueur Modale -->
<div id="playerDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Détails du Joueur</h2>
        <div id="playerDetails"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchPlayer = document.getElementById('searchPlayer');
    const filterPosition = document.getElementById('filterPosition');
    const filterStatus = document.getElementById('filterStatus');
    const playerCards = document.querySelectorAll('.card');

    function applyFilters() {
        const searchTerm = searchPlayer.value.toLowerCase();
        const positionFilter = filterPosition.value;
        const statusFilter = filterStatus.value;

        playerCards.forEach(card => {
            const playerName = card.querySelector('h2').innerText.toLowerCase();
            const playerPosition = card.getAttribute('data-position');
            const playerStatus = card.getAttribute('data-status');

            const matchesSearch = playerName.includes(searchTerm);
            const matchesPosition = positionFilter === '' || playerPosition === positionFilter;
            const matchesStatus = statusFilter === '' || playerStatus === statusFilter;

            if (matchesSearch && matchesPosition && matchesStatus) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function resetAllFilters() {
        searchPlayer.value = '';
        filterPosition.value = '';
        filterStatus.value = '';

        playerCards.forEach(card => {
            card.style.display = '';
        });
    }

    searchPlayer.addEventListener('input', applyFilters);
    filterPosition.addEventListener('change', applyFilters);
    filterStatus.addEventListener('change', applyFilters);
    document.getElementById('resetFilters').addEventListener('click', resetAllFilters);

    document.querySelectorAll('.details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const playerId = this.getAttribute('data-id');
            // Ici, vous pouvez récupérer et afficher les détails du joueur
            document.getElementById('playerDetails').innerText = `Détails pour le joueur ID: ${playerId}`;
            const modal = document.getElementById('playerDetailsModal');
            modal.style.display = "block";
        });
    });

    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('playerDetailsModal').style.display = "none";
    });
});
</script>



<?php
$content = ob_get_clean();
$title = "Joueurs | Football Academy";
require_once(__DIR__ . '/../layout.php');
?>


