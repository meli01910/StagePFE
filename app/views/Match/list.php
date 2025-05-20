<?php
$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
ob_start();
?>



<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-futbol me-2"></i> Listes des matches </h1>
                      </p>
                </h1>
        <p class="lead">Consultez  tous les matches et les résultats récents.
        </p>
    </div>
    </div>
<div class="container">
   

    <!-- Filtres -->
    <div class="filter-section">
        <form action="index.php" method="GET" class="filter-form">
            <input type="hidden" name="module" value="match">
            <input type="hidden" name="action" value="index">
            
            <div class="filter-group">
                <label for="tournoi_id">Tournoi</label>
                <select name="tournoi_id" id="tournoi_id">
                    <option value="">Tous les tournois</option>
                    <?php foreach($tournois as $tournoi): ?>
                        <option value="<?= $tournoi['id'] ?>" <?= (isset($_GET['tournoi_id']) && $_GET['tournoi_id'] == $tournoi['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tournoi['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="status">Statut</label>
                <select name="status" id="status">
                    <option value="">Tous</option>
                    <option value="played" <?= (isset($_GET['status']) && $_GET['status'] === 'played') ? 'selected' : '' ?>>Matchs terminés</option>
                    <option value="coming" <?= (isset($_GET['status']) && $_GET['status'] === 'coming') ? 'selected' : '' ?>>Matchs à venir</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="type">Type de match</label>
                <select name="type" id="type">
                    <option value="all">Tous les types</option>
                    <option value="tournament" <?= (isset($_GET['type']) && $_GET['type'] === 'tournament') ? 'selected' : '' ?>>Matchs de tournoi</option>
                    <option value="friendly" <?= (isset($_GET['type']) && $_GET['type'] === 'friendly') ? 'selected' : '' ?>>Matchs amicaux</option>
                </select>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="primary-btn">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
                <a href="index.php?module=match&action=index" class="secondary-btn">
                    <i class="fas fa-redo"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
 <?php if ($isAdmin): ?>
        <div class="admin-actions">
            <a href="index.php?module=match&action=create" class="primary-btn">
                <i class="fas fa-plus"></i> Créer un match
            </a>
        </div>
    <?php endif; ?>
    <?php if (empty($matchs)): ?>
        <div class="empty-message">Aucun match ne correspond à ces critères.</div>
    <?php else: ?>
        <!-- Matches à venir -->
        <?php 
            $comingMatches = array_filter($matchs, function($match) {
                return $match['statut'] === 'à_venir';
            });
            
            if (!empty($comingMatches)):
        ?>
            <div class="match-category">
                <h2><i class="fas fa-calendar-alt"></i> Prochains matchs</h2>
                <div class="match-cards">
                    <?php foreach ($comingMatches as $match): ?>
                        <div class="match-card">
                            <div class="match-date">
                                <div class="date-day"><?= date('d', strtotime($match['date_match'])) ?></div>
                                <div class="date-month"><?= date('M', strtotime($match['date_match'])) ?></div>
                                <div class="date-time"><?= date('H:i', strtotime($match['date_match'])) ?></div>
                            </div>
                            
                            <div class="match-content">
                                <div class="match-teams">
                                    <div class="team team1"><?= htmlspecialchars($match['equipe1_nom']) ?></div>
                                    <div class="versus">VS</div>
                                    <div class="team team2"><?= htmlspecialchars($match['equipe2_nom']) ?></div>
                                </div>
                                
                                <div class="match-info">
                                    <div class="match-location">
                                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($match['lieu_match']) ?>
                                    </div>
                                    <div class="match-type">
                                        <?php if (isset($match['est_amical']) && $match['est_amical'] == 1): ?>
                                            <span class="badge friendly">Amical</span>
                                        <?php elseif (!empty($match['tournoi_id'])): ?>
                                            <span class="badge tournament"><?= htmlspecialchars($match['tournoi_nom']) ?></span>
                                            <?php if (!empty($match['phase'])): ?>
                                                <span class="badge phase"><?= htmlspecialchars($match['phase']) ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="match-actions">
                                <a href="index.php?module=match&action=show&id=<?= $match['id'] ?>" class="action-btn view-btn">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($isAdmin): ?>
                                    <a href="index.php?module=match&action=edit&id=<?= $match['id'] ?>" class="action-btn edit-btn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?module=match&action=delete&id=<?= $match['id'] ?>" 
                                       class="action-btn delete-btn" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce match?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Matches en cours -->
        <?php 
            $ongoingMatches = array_filter($matchs, function($match) {
                return $match['statut'] === 'en_cours';
            });
            
            if (!empty($ongoingMatches)):
        ?>
            <div class="match-category highlight">
                <h2><i class="fas fa-play-circle"></i> Matches en cours</h2>
                <div class="match-cards">
                    <?php foreach ($ongoingMatches as $match): ?>
                        <div class="match-card ongoing">
                            <div class="live-badge">LIVE</div>
                            
                            <div class="match-content">
                                <div class="match-teams">
                                    <div class="team team1"><?= htmlspecialchars($match['equipe1_nom']) ?></div>
                                    <div class="match-score">
                                        <span class="score"><?= $match['score1'] ?? '0' ?></span>
                                        <span>-</span>
                                        <span class="score"><?= $match['score2'] ?? '0' ?></span>
                                    </div>
                                    <div class="team team2"><?= htmlspecialchars($match['equipe2_nom']) ?></div>
                                </div>
                                
                                <div class="match-info">
                                    <div class="match-location">
                                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($match['lieu_match']) ?>
                                    </div>
                                    <div class="match-type">
                                        <?php if (isset($match['est_amical']) && $match['est_amical'] == 1): ?>
                                            <span class="badge friendly">Amical</span>
                                        <?php elseif (!empty($match['tournoi_id'])): ?>
                                            <span class="badge tournament"><?= htmlspecialchars($match['tournoi_nom']) ?></span>
                                            <?php if (!empty($match['phase'])): ?>
                                                <span class="badge phase"><?= htmlspecialchars($match['phase']) ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="match-actions">
                                <a href="index.php?module=match&action=show&id=<?= $match['id'] ?>" class="action-btn view-btn">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($isAdmin): ?>
                                    <a href="index.php?module=match&action=edit&id=<?= $match['id'] ?>" class="action-btn edit-btn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Matches terminés -->
        <?php 
            $finishedMatches = array_filter($matchs, function($match) {
                return $match['statut'] === 'terminé';
            });
            
            if (!empty($finishedMatches)):
        ?>
            <div class="match-category">
                <h2><i class="fas fa-flag-checkered"></i> Résultats récents</h2>
                <div class="match-cards results">
                    <?php foreach ($finishedMatches as $match): ?>
                        <div class="match-card result">
                            <div class="match-date">
                                <div class="date-day"><?= date('d', strtotime($match['date_match'])) ?></div>
                                <div class="date-month"><?= date('M', strtotime($match['date_match'])) ?></div>
                            </div>
                            
                            <div class="match-content">
                                <div class="match-teams">
                                    <div class="team team1 <?= $match['score1'] > $match['score2'] ? 'winner' : '' ?>">
                                        <?= htmlspecialchars($match['equipe1_nom']) ?>
                                    </div>
                                    <div class="match-score final">
                                        <span class="score"><?= $match['score1'] ?></span>
                                        <span>-</span>
                                        <span class="score"><?= $match['score2'] ?></span>
                                    </div>
                                    <div class="team team2 <?= $match['score2'] > $match['score1'] ? 'winner' : '' ?>">
                                        <?= htmlspecialchars($match['equipe2_nom']) ?>
                                    </div>
                                </div>
                                
                                <div class="match-info">
                                    <div class="match-type">
                                        <?php if (isset($match['est_amical']) && $match['est_amical'] == 1): ?>
                                            <span class="badge friendly">Amical</span>
                                        <?php elseif (!empty($match['tournoi_id'])): ?>
                                            <span class="badge tournament"><?= htmlspecialchars($match['tournoi_nom']) ?></span>
                                            <?php if (!empty($match['phase'])): ?>
                                                <span class="badge phase"><?= htmlspecialchars($match['phase']) ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="match-actions">
                                <a href="index.php?module=match&action=show&id=<?= $match['id'] ?>" class="action-btn view-btn">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($isAdmin): ?>
                                    <a href="index.php?module=match&action=edit&id=<?= $match['id'] ?>" class="action-btn edit-btn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?module=match&action=delete&id=<?= $match['id'] ?>" 
                                       class="action-btn delete-btn" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce match?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    
   
</div>

<!-- CSS pour cette page -->
<style>

    

    

    
    /* Section filtres */
    .filter-section {
        background-color: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }
    
    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }
    
    .filter-group {
        flex: 1 1 200px;
        margin-bottom: 1rem;
    }
    
    .filter-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--gray-700);
    }
    
    .filter-group select {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        border: 1px solid var(--gray-300);
        background-color: white;
        font-size: 1rem;
        transition: border-color 0.2s;
    }
    
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(28, 63, 170, 0.2);
    }
    
    .filter-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
        flex: 1 0 100%;
    }
    
    /* Message vide */
    .empty-message {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        font-size: 1rem;
        color: var(--gray-600);
    }
    
    /* Catégories de match */
    .match-category {
        margin-bottom: 2.5rem;
    }
    
    .match-category h2 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .match-category.highlight h2 {
        color: var(--accent);
    }
    
    /* Cards de match */
    .match-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }
    
    .match-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        display: flex;
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
    }
    
    .match-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }
    
    .match-card.ongoing {
        background-color: rgba(247, 250, 252, 1);
        border-left: 4px solid var(--warning);
    }
    
    .live-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: var(--danger);
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.6; }
        100% { opacity: 1; }
    }
    
    /* Date du match */
    .match-date {
        background-color: var(--primary-dark);
        color: white;
        padding: 0.75rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 80px;
    }
    
    .date-day {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .date-month {
        font-size: 0.75rem;
        text-transform: uppercase;
    }
    
    .date-time {
        font-size: 0.875rem;
        margin-top: 0.5rem;
        font-weight: 600;
    }
    
    /* Contenu du match */
    .match-content {
        flex: 1;
        padding: 1rem;
    }
    
    .match-teams {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    
    .team {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--gray-800);
        text-align: center;
        flex: 1;
    }
    
    .team.winner {
        color: var(--success);
        font-weight: 700;
    }
    
    .versus {
        margin: 0 0.5rem;
        font-weight: 700;
        color: var(--gray-400);
        font-size: 0.875rem;
    }
    
    .match-score {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 0.5rem;
    }
    
    .score {
        font-weight: 700;
        font-size: 1.25rem;
        min-width: 1.5rem;
        text-align: center;
    }
    
    .match-score.final {
        background-color: var(--primary-dark);
        color: white;
        border-radius: 6px;
        padding: 0.25rem 0.75rem;
    }
    
    /* Informations du match */
    .match-info {
        margin-top: 0.75rem;
        font-size: 0.875rem;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    
    .match-location {
        color: var(--gray-600);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .match-type {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
    }
    
    .badge.friendly {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .badge.tournament {
        background-color: #e0e7ff;
        color: #4338ca;
    }
    
    .badge.phase {
        background-color: #f3f4f6;
        color: #4b5563;
    }
    
    /* Actions */
    .match-actions {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0.75rem;
        gap: 0.5rem;
        border-left: 1px solid var(--gray-200);
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 6px;
        color: white;
        transition: background-color 0.2s;
    }
    
    .action-btn:hover {
        opacity: 0.9;
    }
    
    .view-btn {
         background-color: none;
        color : black;
        border: solid 1px black;
    }
    
    .edit-btn {
        background-color: none;
        color : black;
        border: solid 1px black;
    }
    
    .delete-btn {
        background-color: var(--danger);
    }
    
    /* Boutons */
    .primary-btn, .secondary-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 6px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
        font-size: 1rem;
    }
    
    .primary-btn {
        background-color: var(--primary);
        color: white;
    }
    
    .primary-btn:hover {
        background-color: var(--primary-dark);
    }
    
    .secondary-btn {
        background-color: var(--gray-200);
        color: var(--gray-700);
    }
    
    .secondary-btn:hover {
        background-color: var(--gray-300);
    }
    
    /* Actions admin */
    .admin-actions {
        margin-top: 2rem;
        text-align: center;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .match-cards {
            grid-template-columns: 1fr;
        }
        
        .filter-form {
            flex-direction: column;
        }
        
        .filter-group {
            flex: 1 1 100%;
        }
        
        .filter-actions {
            justify-content: center;
        }
    }
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
