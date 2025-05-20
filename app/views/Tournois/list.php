<?php
$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
ob_start();
?>

<div class="tournaments-container">
    <div class="tournaments-header">
        <div class="tournaments-title">
            <h1><i class="fas fa-trophy"></i> Tournois</h1>
            <p class="subtitle">Découvrez tous les tournois organisés par notre club</p>
        </div>

        <?php if($isAdmin): ?>
        <div class="admin-actions">
            <a href="index.php?module=tournoi&action=create" class="btn-primary">
                <i class="fas fa-plus"></i> Créer un tournoi
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Filtres et recherche -->
    <div class="filters-section">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchTournament" placeholder="Rechercher un tournoi...">
        </div>

        <div class="filters-container">
            <div class="filter-group">
                <select id="statusFilter" class="filter-select">
                    <option value="">Tous les statuts</option>
                    <option value="upcoming">À venir</option>
                    <option value="ongoing">En cours</option>
                    <option value="completed">Terminés</option>
                </select>
            </div>

            <div class="filter-group">
                <select id="seasonFilter" class="filter-select">
                    <option value="">Toutes les saisons</option>
                    <option value="2023-2024">Saison 2023-2024</option>
                    <option value="2022-2023">Saison 2022-2023</option>
                    <option value="2021-2022">Saison 2021-2022</option>
                </select>
            </div>

            <div class="filter-group">
                <select id="categoryFilter" class="filter-select">
                    <option value="">Toutes les catégories</option>
                    <option value="senior">Seniors</option>
                    <option value="u19">U19</option>
                    <option value="u17">U17</option>
                    <option value="u15">U15</option>
                </select>
            </div>

            <button id="resetFilters" class="reset-btn">
                <i class="fas fa-sync-alt"></i> Réinitialiser
            </button>
        </div>
    </div>

    <!-- Affichage des tournois -->
    <div class="tournaments-layout-control">
        <div class="results-count"><span id="tournamentsCount"><?= count($tournois) ?></span> tournois trouvés</div>
        <div class="view-toggle">
            <button class="view-btn active" data-view="grid"><i class="fas fa-th-large"></i></button>
            <button class="view-btn" data-view="list"><i class="fas fa-list"></i></button>
        </div>
    </div>

    <div id="tournamentsGrid" class="tournaments-grid view-active">
        <?php if (empty($tournois)): ?>
            <div class="empty-state">
                <i class="far fa-calendar-times empty-icon"></i>
                <p>Aucun tournoi disponible pour le moment.</p>
                <?php if($isAdmin): ?>
                    <a href="index.php?module=tournois&action=create" class="btn primary-btn">Créer un nouveau tournoi</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php foreach ($tournois as $tournoi): ?>
                <?php 
                    $dateDebut = strtotime($tournoi['date_debut']);
                    $dateFin = strtotime($tournoi['date_fin']);
                    $today = time();
                    
                    if ($today < $dateDebut) {
                        $status = 'upcoming';
                        $statusLabel = 'À venir';
                    } elseif ($today <= $dateFin) {
                        $status = 'ongoing';
                        $statusLabel = 'En cours';
                    } else {
                        $status = 'completed';
                        $statusLabel = 'Terminé';
                    }
                ?>
                
                <div class="tournament-card" data-status="<?= $status ?>" data-season="<?= htmlspecialchars($tournoi['saison']) ?>" data-category="<?= htmlspecialchars($tournoi['categorie']) ?>">
                    <div class="tournament-card-banner" style="background-image: url('<?= !empty($tournoi['image']) ? 'uploads/' . $tournoi['image'] : 'assets/img/default-tournament-bg.jpg' ?>')">
                        <div class="tournament-status <?= $status ?>">
                            <?= $statusLabel ?>
                        </div>
                    </div>
                    
                    <div class="tournament-card-content">
                        <div class="tournament-card-top">
                            <?php if (!empty($tournoi['logo'])): ?>
                                <div class="tournament-logo">
                                    <img src="uploads/<?= $tournoi['logo'] ?>" alt="Logo <?= htmlspecialchars($tournoi['nom']) ?>">
                                </div>
                            <?php else: ?>
                                <div class="tournament-logo-placeholder">
                                    <i class="fas fa-trophy"></i>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="tournament-name"><?= htmlspecialchars($tournoi['nom']) ?></h3>
                            
                            <div class="tournament-dates">
                                <i class="fas fa-calendar-alt"></i>
                                <span>
                                    <?= date('d/m/Y', $dateDebut) ?> 
                                    <?php if (date('d/m/Y', $dateDebut) !== date('d/m/Y', $dateFin)): ?>
                                        - <?= date('d/m/Y', $dateFin) ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="tournament-card-meta">
                            <?php if (!empty($tournoi['localisation'])): ?>
                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?= htmlspecialchars($tournoi['localisation']) ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($tournoi['categorie'])): ?>
                                <div class="meta-item">
                                    <i class="fas fa-users"></i>
                                    <span><?= htmlspecialchars($tournoi['categorie']) ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($tournoi['teams_count']) && $tournoi['teams_count'] > 0): ?>
                                <div class="meta-item">
                                    <i class="fas fa-shield-alt"></i>
                                    <span><?= $tournoi['teams_count'] ?> équipe(s)</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($tournoi['description'])): ?>
                            <div class="tournament-description">
                                <?= substr(htmlspecialchars($tournoi['description']), 0, 120) . (strlen($tournoi['description']) > 120 ? '...' : '') ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="tournament-card-buttons">
                            <a href="index.php?module=tournoi&action=show&id=<?= $tournoi['id'] ?>" class="btn details-btn">
                                <i class="fas fa-eye"></i> Détails
                            </a>
                            
                            <?php if($isAdmin): ?>
                                <div class="admin-card-actions">
                                    <a href="index.php?module=tournoi&action=edit&id=<?= $tournoi['id'] ?>" class="btn edit-btn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?module=tournoi&action=delete&id=<?= $tournoi['id'] ?>" 
                                       class="btn delete-btn" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce tournoi ?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="tournamentsList" class="tournaments-list">
        <?php if (empty($tournois)): ?>
            <div class="empty-state">
                <i class="far fa-calendar-times empty-icon"></i>
                <p>Aucun tournoi disponible pour le moment.</p>
                <?php if($isAdmin): ?>
                    <a href="index.php?module=tournoi&action=create" class="btn primary-btn">Créer un nouveau tournoi</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <table class="tournaments-table">
                <thead>
                    <tr>
                        <th class="th-name">Nom</th>
                        <th class="th-dates">Dates</th>
                        <th class="th-category">Catégorie</th>
                        <th class="th-status">Statut</th>
                        <th class="th-teams">Équipes</th>
                        <th class="th-location">Lieu</th>
                        <th class="th-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tournois as $tournoi): ?>
                        <?php 
                            $dateDebut = strtotime($tournoi['date_debut']);
                            $dateFin = strtotime($tournoi['date_fin']);
                            $today = time();
                            
                            if ($today < $dateDebut) {
                                $status = 'upcoming';
                                $statusLabel = 'À venir';
                            } elseif ($today <= $dateFin) {
                                $status = 'ongoing';
                                $statusLabel = 'En cours';
                            } else {
                                $status = 'completed';
                                $statusLabel = 'Terminé';
                            }
                        ?>
                        
                        <tr data-status="<?= $status ?>" data-season="<?= htmlspecialchars($tournoi['saison']) ?>" data-category="<?= htmlspecialchars($tournoi['categorie']) ?>">
                            <td class="td-name">
                                <div class="tournament-list-name">
                                    <?php if (!empty($tournoi['logo'])): ?>
                                        <img src="uploads/<?= $tournoi['logo'] ?>" alt="Logo" class="list-logo">
                                    <?php else: ?>
                                        <div class="list-logo-placeholder">
                                            <i class="fas fa-trophy"></i>
                                        </div>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($tournoi['nom']) ?>
                                </div>
                            </td>
                            <td class="td-dates">
                                <?= date('d/m/Y', $dateDebut) ?> 
                                <?php if (date('d/m/Y', $dateDebut) !== date('d/m/Y', $dateFin)): ?>
                                    <br><span class="date-separator">au</span> <?= date('d/m/Y', $dateFin) ?>
                                <?php endif; ?>
                            </td>
                            <td class="td-category"><?= htmlspecialchars($tournoi['categorie'] ?? '-') ?></td>
                            <td class="td-status">
                                <span class="status-badge <?= $status ?>"><?= $statusLabel ?></span>
                            </td>
                            <td class="td-teams"><?= isset($tournoi['teams_count']) ? $tournoi['teams_count'] : '-' ?></td>
                            <td class="td-location"><?= htmlspecialchars($tournoi['localisation'] ?? '-') ?></td>
                            <td class="td-actions">
                                <div class="list-actions">
                                    <a href="index.php?module=tournament&action=show&id=<?= $tournoi['id'] ?>" class="action-btn view-btn" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if($isAdmin): ?>
                                        <a href="index.php?module=tournament&action=edit&id=<?= $tournoi['id'] ?>" class="action-btn edit-btn" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?module=tournament&action=delete&id=<?= $tournoi['id'] ?>" 
                                           class="action-btn delete-btn" 
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce tournoi ?')"
                                           title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>

<style>

    
    .tournaments-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .tournaments-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .tournaments-title h1 {
        margin-bottom: 0.5rem;
        color: var(--primary);
        font-size: 2.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .subtitle {
        color: var(--gray-600);
        font-size: 1rem;
        margin: 0;
    }
    
    .admin-actions {
        display: flex;
        gap: 1rem;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: var(--border-radius);
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }
    

    /* Filtres */
    .filters-section {
        background-color: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .search-box {
        position: relative;
        margin-bottom: 1.25rem;
    }
    
    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-500);
    }
    
    #searchTournament {
        width: 100%;
        padding: 1rem 1rem 1rem 2.5rem;
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius);
        font-size: 1rem;
        transition: border-color 0.2s;
    }
    
    #searchTournament:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(26, 35, 126, 0.1);
    }
    
    .filters-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .filter-group {
        flex: 1;
        min-width: 180px;
    }
    
    .filter-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius);
        font-size: 0.95rem;
        background-color: white;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23616161' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
    }
    
    .filter-select:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(26, 35, 126, 0.1);
    }
    
    .reset-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        background-color: var(--gray-200);
        border: none;
        border-radius: var(--border-radius);
        color: var(--gray-800);
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .reset-btn:hover {
        background-color: var(--gray-300);
    }
    
    /* Contrôles d'affichage */
    .tournaments-layout-control {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .results-count {
        font-size: 0.95rem;
        color: var(--gray-600);
    }
    
    .results-count span {
        font-weight: 700;
        color: var(--primary);
    }
    
    .view-toggle {
        display: flex;
        gap: 0.5rem;
    }
    
    .view-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--gray-200);
        border: none;
        border-radius: var(--border-radius);
        color: var(--gray-600);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .view-btn:hover {
        background-color: var(--gray-300);
        color: var(--gray-900);
    }
    
    .view-btn.active {
        background-color: var(--primary);
        color: white;
    }
    
    /* Vue grille */
    .tournaments-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .tournaments-grid:not(.view-active) {
        display: none;
    }
    
    .tournament-card {
        background-color: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .tournament-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .tournament-card-banner {
        height: 140px;
        background-size: cover;
        background-position: center;
        position: relative;
    }
    
    .tournament-status {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        color: white;
    }
    
    .tournament-status.upcoming {
        background-color: var(--info);
    }
    
    .tournament-status.ongoing {
        background-color: var(--success);
    }
    
    .tournament-status.completed {
        background-color: var(--gray-600);
    }
    
    .tournament-card-content {
        padding: 1.25rem;
    }
    
    .tournament-card-top {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .tournament-logo, .tournament-logo-placeholder {
        width: 70px;
        height: 70px;
        margin-top: -55px;
        margin-bottom: 0.75rem;
        border-radius: 50%;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .tournament-logo img {
        width: 85%;
        height: 85%;
        object-fit: contain;
    }
    
    .tournament-logo-placeholder i {
        font-size: 2rem;
        color: var(--primary);
    }
    
    .tournament-name {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
        color: var(--gray-900);
    }
    
    .tournament-dates {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--gray-700);
        font-size: 0.9rem;
    }
    
    .tournament-card-meta {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--gray-600);
        font-size: 0.85rem;
    }
    
    .tournament-description {
        margin-bottom: 1.25rem;
        font-size: 0.9rem;
        color: var(--gray-700);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .tournament-card-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }
    
    .details-btn {
        padding: 0.65rem 1rem;
        background-color: transparent;
        border: 1px solid var(--primary);
        color: var(--primary);
        border-radius: var(--border-radius);
    }
    
    .details-btn:hover {
        background-color: var(--primary);
        color: white;
    }
    
    .admin-card-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .edit-btn, .delete-btn {
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .edit-btn {
        background-color: var(--gray-200);
        color: var(--gray-700);
    }
    
    .edit-btn:hover {
        background-color: var(--info);
        color: white;
    }
    
    .delete-btn {
        background-color: var(--gray-200);
        color: var(--gray-700);
    }
    
    .delete-btn:hover {
        background-color: var(--danger);
        color: white;
    }
    
    /* Vue liste */
    .tournaments-list {
        display: none;
    }
    
    .tournaments-list.view-active {
        display: block;
    }
    
    .tournaments-table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .tournaments-table th {
        text-align: left;
        padding: 1rem;
        background-color: var(--primary);
        color: white;
        font-weight: 500;
    }
    
    .tournaments-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--gray-200);
        vertical-align: middle;
    }
    
    .tournaments-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .tournaments-table tbody tr:hover {
        background-color: var(--gray-100);
    }
    
    .tournament-list-name {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
        color: var(--primary);
    }
    
    .list-logo, .list-logo-placeholder {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .list-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .list-logo-placeholder {
        background-color: var(--gray-200);
    }
    
    .list-logo-placeholder i {
        color: var(--primary);
        font-size: 0.95rem;
    }
    
    .date-separator {
        color: var(--gray-500);
        font-size: 0.85rem;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        color: white;
    }
    
    .status-badge.upcoming {
        background-color: var(--primary);
    }
    
    .status-badge.ongoing {
        background-color: var(--success);
    }
    
    .status-badge.completed {
        background-color: var(--gray-600);
    }
    
    .list-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .action-btn.view-btn {
        background-color: var(--primary-light);
        color: white;
    }
    
    .action-btn.edit-btn {
        background-color: var(--info);
        color: white;
    }
    
    .action-btn.delete-btn {
        background-color: var(--danger);
        color: white;
    }
    
    .action-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* État vide */
    .empty-state {
        text-align: center;
        padding: 3rem;
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .empty-icon {
        font-size: 3rem;
        color: var(--gray-400);
        margin-bottom: 1rem;
    }
    
    .empty-state p {
        font-size: 1.1rem;
        color: var(--gray-600);
        margin-bottom: 1.5rem;
    }
    
    /* Ajustements responsifs */
    @media (max-width: 992px) {
        .filters-container {
            flex-direction: column;
            gap: 1rem;
        }
        
        .filter-group {
            width: 100%;
        }
        
        .tournaments-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .admin-actions {
            width: 100%;
        }
    }
    
    @media (max-width: 768px) {
        .tournaments-table {
            display: block;
            overflow-x: auto;
        }
        
        .th-category, .th-teams, .td-category, .td-teams {
            display: none;
        }
        
        .tournaments-layout-control {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .view-toggle {
            margin-left: auto;
        }
    }
    
    @media (max-width: 576px) {
        .th-location, .td-location {
            display: none;
        }
        
        .tournament-card {
            min-width: 100%;
        }
        
        .admin-card-actions {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: var(--border-radius);
            padding: 0.25rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables pour les éléments DOM
        const searchInput = document.getElementById('searchTournament');
        const statusFilter = document.getElementById('statusFilter');
        const seasonFilter = document.getElementById('seasonFilter');
        const categoryFilter = document.getElementById('categoryFilter');
        const resetButton = document.getElementById('resetFilters');
        const tournamentsCount = document.getElementById('tournamentsCount');
        const viewButtons = document.querySelectorAll('.view-btn');
        const tournamentsGrid = document.getElementById('tournamentsGrid');
        const tournamentsList = document.getElementById('tournamentsList');
        
        // Tous les tournois (cartes et lignes)
        const tournamentCards = document.querySelectorAll('.tournament-card');
        const tournamentRows = document.querySelectorAll('.tournaments-table tbody tr');
        
        // Fonction pour basculer entre les vues grille et liste
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                viewButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const view = this.getAttribute('data-view');
                if (view === 'grid') {
                    tournamentsGrid.classList.add('view-active');
                    tournamentsList.classList.remove('view-active');
                } else {
                    tournamentsList.classList.add('view-active');
                    tournamentsGrid.classList.remove('view-active');
                }
            });
        });
        
        // Fonction pour filtrer les tournois
        function filterTournaments() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            const seasonValue = seasonFilter.value;
            const categoryValue = categoryFilter.value;
            
            let visibleCount = 0;
            
            // Filtrer les cartes dans la vue grille
            tournamentCards.forEach(card => {
                const name = card.querySelector('.tournament-name').textContent.toLowerCase();
                const status = card.getAttribute('data-status');
                const season = card.getAttribute('data-season');
                const category = card.getAttribute('data-category');
                
                const matchesSearch = name.includes(searchTerm);
                const matchesStatus = statusValue === '' || status === statusValue;
                const matchesSeason = seasonValue === '' || season === seasonValue;
                const matchesCategory = categoryValue === '' || category === categoryValue;
                
                if (matchesSearch && matchesStatus && matchesSeason && matchesCategory) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Filtrer les lignes dans la vue liste
            tournamentRows.forEach(row => {
                const name = row.querySelector('.tournament-list-name').textContent.toLowerCase();
                const status = row.getAttribute('data-status');
                const season = row.getAttribute('data-season');
                const category = row.getAttribute('data-category');
                
                const matchesSearch = name.includes(searchTerm);
                const matchesStatus = statusValue === '' || status === statusValue;
                const matchesSeason = seasonValue === '' || season === seasonValue;
                const matchesCategory = categoryValue === '' || category === categoryValue;
                
                if (matchesSearch && matchesStatus && matchesSeason && matchesCategory) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Mettre à jour le compteur de tournois visibles
            tournamentsCount.textContent = visibleCount;
        }
        
        // Ajouter des écouteurs d'événements pour les filtres
        searchInput.addEventListener('input', filterTournaments);
        statusFilter.addEventListener('change', filterTournaments);
        seasonFilter.addEventListener('change', filterTournaments);
        categoryFilter.addEventListener('change', filterTournaments);
        
        // Réinitialiser les filtres
        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.selectedIndex = 0;
            seasonFilter.selectedIndex = 0;
            categoryFilter.selectedIndex = 0;
            filterTournaments();
        });
    });
</script>

