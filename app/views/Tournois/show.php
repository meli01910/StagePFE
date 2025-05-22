<?php
$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
ob_start();
?>

<div class="tournament-container">
    <!-- En-tête du tournoi avec informations principales -->
    <div class="tournament-header" style="background-image: url('<?= !empty($tournoi['image']) ? 'uploads/' . $tournoi['image'] : 'assets/img/default-tournament-bg.jpg' ?>')">
        <div class="tournament-header-overlay">
            <div class="tournament-header-content">
                <div class="tournament-header-main">
                    <?php if (!empty($tournoi['logo'])): ?>
                        <div class="tournament-logo">
                            <img src="uploads/<?= $tournoi['logo'] ?>" alt="Logo <?= htmlspecialchars($tournoi['nom']) ?>">
                        </div>
                    <?php endif; ?>
                    
                    <div class="tournament-title">
                        <h1><?= htmlspecialchars($tournoi['nom']) ?></h1>
                        
                        <div class="tournament-meta">
                            <div class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>
                                    <?= date('d/m/Y', strtotime($tournoi['date_debut'])) ?> - 
                                    <?= date('d/m/Y', strtotime($tournoi['date_fin'])) ?>
                                </span>
                            </div>
                            
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= htmlspecialchars($tournoi['lieu']) ?></span>
                            </div>
                            
                            <div class="meta-item">
                                <i class="fas fa-trophy"></i>
                                <span><?= htmlspecialchars($tournoi['categorie']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tournament-status">
                    <?php if (strtotime($tournoi['date_debut']) > time()): ?>
                        <span class="status upcoming">À venir</span>
                    <?php elseif (strtotime($tournoi['date_fin']) < time()): ?>
                        <span class="status completed">Terminé</span>
                    <?php else: ?>
                        <span class="status ongoing">En cours</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Navigation du tournoi -->
    <div class="tournament-nav">
        <div class="nav-tabs">
            <a href="#overview" class="nav-tab active" data-target="overview">Vue d'ensemble</a>
            <a href="#teams" class="nav-tab" data-target="teams">Équipes</a>
            <a href="#matches" class="nav-tab" data-target="matches">Matchs</a>
            <a href="#standings" class="nav-tab" data-target="standings">Classements</a>
            <a href="#stats" class="nav-tab" data-target="stats">Statistiques</a>
        </div>
        
        <?php if ($isAdmin): ?>
            <div class="admin-controls">
                <a href="index.php?module=tournoi&action=edit&id=<?= $tournoi['id'] ?>" class="admin-btn edit">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                  <a href="index.php?module=tournoi&action=organiser&id=<?= $tournoi['id'] ?>" class="admin-btn edit">
                    <i class="fas fa-edit"></i> organiser
                </a>
                <a href="index.php?module=tournoi&action=delete&id=<?= $tournoi['id'] ?>" 
                   class="admin-btn delete"
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce tournoi?');">
                    <i class="fas fa-trash"></i> Supprimer
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Contenu principal du tournoi -->
    <div class="tournament-content">
        <!-- Section vue d'ensemble -->
        <section id="overview" class="content-section active">
            <div class="section-grid">
                <!-- Colonne principale -->
                <div class="main-column">
                    <!-- Description du tournoi -->
                    <div class="tournament-section">
                        <div class="section-header">
                            <h2>À propos du tournoi</h2>
                        </div>
                        <div class="section-body">
                            <div class="tournament-description">
                                <?php if (!empty($tournoi['description'])): ?>
                                    <?= nl2br(htmlspecialchars($tournoi['description'])) ?>
                                <?php else: ?>
                                    <p class="empty-text">Aucune description disponible pour ce tournoi.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Phases du tournoi -->
                    <div class="tournament-section">
                        <div class="section-header">
                            <h2>Format & Phases</h2>
                            <?php if ($isAdmin): ?>
                                <a href="index.php?module=phase&action=create&tournoi_id=<?= $tournoi['id'] ?>" class="section-action">
                                    <i class="fas fa-plus"></i> Ajouter une phase
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="section-body">
                            <?php if (!empty($phases)): ?>
                                <div class="phases-timeline">
                                    <?php foreach ($phases as $index => $phase): ?>
                                        <div class="phase-item <?= strtotime($phase['date_debut']) <= time() && strtotime($phase['date_fin']) >= time() ? 'active' : '' ?>">
                                            <div class="phase-dot"></div>
                                            <div class="phase-content">
                                                <h3><?= htmlspecialchars($phase['nom']) ?></h3>
                                                <div class="phase-dates">
                                                    <i class="fas fa-calendar-day"></i>
                                                    <?= date('d/m/Y', strtotime($phase['date_debut'])) ?> - 
                                                    <?= date('d/m/Y', strtotime($phase['date_fin'])) ?>
                                                </div>
                                                <p><?= htmlspecialchars($phase['description']) ?></p>
                                                
                                                <?php if ($isAdmin): ?>
                                                    <div class="phase-controls">
                                                        <a href="index.php?module=phase&action=edit&id=<?= $phase['id'] ?>" class="action-btn">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="index.php?module=phase&action=delete&id=<?= $phase['id'] ?>" 
                                                           class="action-btn delete"
                                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette phase?');">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="empty-text">Aucune phase définie pour ce tournoi.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Matchs à venir -->
                    <div class="tournament-section">
                        <div class="section-header">
                            <h2>Prochains matchs</h2>
                            <a href="#matches" class="section-action" data-toggle-tab="matches">
                                Voir tous les matchs <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="section-body">
                            <?php
                            $upcomingMatches = array_filter($matchs ?? [], function($match) {
                                return $match['statut'] === 'à_venir';
                            });
                            
                            if (!empty($upcomingMatches)): 
                                $upcomingMatches = array_slice($upcomingMatches, 0, 3); // Limiter à 3 matchs
                            ?>
                                <div class="upcoming-matches">
                                    <?php foreach ($upcomingMatches as $match): ?>
                                        <div class="match-card">
                                            <div class="match-date">
                                                <div class="date-day"><?= date('d', strtotime($match['date_match'])) ?></div>
                                                <div class="date-month"><?= date('M', strtotime($match['date_match'])) ?></div>
                                                <div class="date-time"><?= date('H:i', strtotime($match['date_match'])) ?></div>
                                            </div>
                                            
                                            <div class="match-teams">
                                                <div class="team team1"><?= htmlspecialchars($match['equipe1_nom']) ?></div>
                                                <div class="versus">VS</div>
                                                <div class="team team2"><?= htmlspecialchars($match['equipe2_nom']) ?></div>
                                            </div>
                                            
                                            <div class="match-info">
                                                <div class="match-phase">
                                                    <span class="badge phase"><?= htmlspecialchars($match['phase_nom'] ?? 'Match') ?></span>
                                                </div>
                                                <div class="match-location">
                                                    <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($match['lieu_match']) ?>
                                                </div>
                                            </div>
                                            
                                            <a href="index.php?module=match&action=show&id=<?= $match['id'] ?>" class="match-link">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="empty-text">Aucun match à venir n'est programmé.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Colonne latérale -->
                <div class="side-column">
                    <!-- Informations clés -->
                    <div class="tournament-section">
                        <div class="section-header">
                            <h2>Informations clés</h2>
                        </div>
                        <div class="section-body">
                            <div class="info-list">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-users"></i>
                                        Participants
                                    </div>
                                    <div class="info-value"><?= count($equipes ?? []) ?> équipes</div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-futbol"></i>
                                        Matchs
                                    </div>
                                    <div class="info-value"><?= count($matchs ?? []) ?> matchs</div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-flag"></i>
                                        Organisateur
                                    </div>
                                    <div class="info-value"><?= htmlspecialchars($tournoi['organisateur'] ?? 'Non spécifié') ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-globe"></i>
                                        Site Web
                                    </div>
                                    <div class="info-value">
                                        <?php if (!empty($tournoi['site_web'])): ?>
                                            <a href="<?= htmlspecialchars($tournoi['site_web']) ?>" target="_blank"><?= htmlspecialchars($tournoi['site_web']) ?></a>
                                        <?php else: ?>
                                            Non spécifié
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-phone"></i>
                                        Contact
                                    </div>
                                    <div class="info-value">
                                        <?= !empty($tournoi['contact']) ? htmlspecialchars($tournoi['contact']) : 'Non spécifié' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Meilleurs buteurs -->
                    <div class="tournament-section">
                        <div class="section-header">
                            <h2>Meilleurs buteurs</h2>
                        </div>
                        <div class="section-body">
                            <?php if(!empty($buteurs)): ?>
                                <ol class="top-scorers">
                                    <?php foreach($buteurs as $buteur): ?>
                                        <li class="scorer-item">
                                            <div class="scorer-rank"><?= $buteur['rang'] ?></div>
                                            <div class="scorer-info">
                                                <div class="scorer-name"><?= htmlspecialchars($buteur['nom']) ?></div>
                                                <div class="scorer-team"><?= htmlspecialchars($buteur['equipe']) ?></div>
                                            </div>
                                            <div class="scorer-goals"><?= $buteur['buts'] ?></div>
                                        </li>
                                    <?php endforeach; ?>
                                </ol>
                            <?php else: ?>
                                <p class="empty-text">Aucune statistique disponible pour l'instant.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Documents et ressources -->
                    <div class="tournament-section">
                        <div class="section-header">
                            <h2>Documents & Ressources</h2>
                        </div>
                        <div class="section-body">
                            <?php if(!empty($documents)): ?>
                                <ul class="document-list">
                                    <?php foreach($documents as $doc): ?>
                                        <li class="document-item">
                                            <a href="uploads/<?= $doc['fichier'] ?>" target="_blank" class="document-link">
                                                <i class="fas fa-file-pdf"></i>
                                                <span><?= htmlspecialchars($doc['titre']) ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="empty-text">Aucun document disponible pour ce tournoi.</p>
                            <?php endif; ?>
                            
                            <?php if ($isAdmin): ?>
                                <div class="document-upload">
                                    <a href="index.php?module=document&action=upload&tournoi_id=<?= $tournoi['id'] ?>" class="upload-btn">
                                        <i class="fas fa-upload"></i> Ajouter un document
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Section équipes -->
        <section id="teams" class="content-section">
            
            
            <?php if(!empty($equipes)): ?>
                <div class="teams-grid">
                    <?php foreach($equipes as $equipe): ?>
                        <div class="team-card">
                            <div class="team-header">
                                <?php if(!empty($equipe['logo'])): ?>
                                    <img src="uploads/<?= $equipe['logo'] ?>" alt="Logo <?= htmlspecialchars($equipe['nom']) ?>" class="team-logo">
                                <?php else: ?>
                                    <div class="team-logo-placeholder">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <h3 class="team-name"><?= htmlspecialchars($equipe['nom']) ?></h3>
                            </div>
                            
                            <div class="team-content">
                                <!-- Statistiques de l'équipe dans ce tournoi -->
                                <div class="team-stats">
                                    <div class="stat-item">
                                        <span class="stat-value">
                                            <?= $equipe['matchs_joues'] ?? 0 ?>
                                        </span>
                                        <span class="stat-label">Joués</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value">
                                            <?= $equipe['victoires'] ?? 0 ?>
                                        </span>
                                        <span class="stat-label">Victoires</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value">
                                            <?= $equipe['nuls'] ?? 0 ?>
                                        </span>
                                        <span class="stat-label">Nuls</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value">
                                            <?= $equipe['defaites'] ?? 0 ?>
                                        </span>
                                        <span class="stat-label">Défaites</span>
                                    </div>
                                </div>
                                
                                <!-- Score actuel si phase de groupes -->
                                <?php if(isset($equipe['points'])): ?>
                                    <div class="team-group-position">
                                        <span class="group-label">Groupe <?= htmlspecialchars($equipe['groupe'] ?? '-') ?></span>
                                        <span class="points-label">
                                            <strong><?= $equipe['points'] ?></strong> points
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="team-footer">
                                <a href="index.php?module=equipe&action=show&id=<?= $equipe['id'] ?>" class="team-link">
                                    Voir l'équipe
                                </a>
                                
                                <?php if ($isAdmin): ?>
                                    <div class="team-admin-actions">
                                        <a href="index.php?module=tournoi&action=removeTeam&id=<?= $tournoi['id'] ?>&equipe_id=<?= $equipe['id'] ?>" 
                                           class="remove-team-btn"
                                           onclick="return confirm('Êtes-vous sûr de vouloir retirer cette équipe du tournoi?');">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="empty-text center">Aucune équipe n'a encore été ajoutée à ce tournoi.</p>
            <?php endif; ?>
        </section>
        
        <!-- Section matchs -->
        <section id="matches" class="content-section">
           
            
            <div class="matches-filters">
                <div class="filters-row">
                    <div class="filter-group">
                        <label for="match-phase-filter">Phase</label>
                        <select id="match-phase-filter">
                            <option value="all">Toutes les phases</option>
                            <?php foreach($phases ?? [] as $phase): ?>
                                <option value="<?= $phase['id'] ?>"><?= htmlspecialchars($phase['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="match-status-filter">Statut</label>
                        <select id="match-status-filter">
                            <option value="all">Tous les statuts</option>
                            <option value="à_venir">À venir</option>
                            <option value="en_cours">En cours</option>
                            <option value="terminé">Terminés</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="match-team-filter">Équipe</label>
                        <select id="match-team-filter">
                            <option value="all">Toutes les équipes</option>
                            <?php foreach($equipes ?? [] as $equipe): ?>
                                <option value="<?= $equipe['id'] ?>"><?= htmlspecialchars($equipe['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <?php if(!empty($matchs)): ?>
                <!-- Grouper les matchs par date -->
                <?php
                $matchsByDate = [];
                foreach($matchs as $match) {
                    $dateKey = date('Y-m-d', strtotime($match['date_match']));
                    if(!isset($matchsByDate[$dateKey])) {
                        $matchsByDate[$dateKey] = [];
                    }
                    $matchsByDate[$dateKey][] = $match;
                }
                
                // Trier par date (plus récent d'abord)
                ksort($matchsByDate);
                ?>
                
                <div class="matches-by-date">
                    <?php foreach($matchsByDate as $date => $dayMatches): ?>
                        <div class="match-day">
                            <div class="day-header">
                                <h3>
                                    <?php
                                    $timestamp = strtotime($date);
                                    $today = date('Y-m-d');
                                    $tomorrow = date('Y-m-d', strtotime('+1 day'));
                                    
                                    if($date === $today) {
                                        echo "Aujourd'hui";
                                    } elseif($date === $tomorrow) {
                                        echo "Demain";
                                    } else {
                                        echo date('l d F Y', $timestamp); // Localisez selon votre préférence
                                    }
                                    ?>
                                </h3>
                            </div>
                            
                            <div class="day-matches">
                                <?php foreach($dayMatches as $match): ?>
                                    <div class="match-row" 
                                         data-phase="<?= $match['phase_id'] ?? 'all' ?>"
                                         data-status="<?= $match['statut'] ?>"
                                         data-team1="<?= $match['equipe1_id'] ?>"
                                         data-team2="<?= $match['equipe2_id'] ?>">
                                        
                                        <div class="match-time">
                                            <?= date('H:i', strtotime($match['date_match'])) ?>
                                        </div>
                                        
                                        <div class="match-teams-container">
                                            <div class="match-team team1">
                                                <span class="team-name"><?= htmlspecialchars($match['equipe1_nom']) ?></span>
                                            </div>
                                            
                                            <div class="match-result">
                                                <?php if($match['statut'] === 'terminé'): ?>
                                                    <span class="score"><?= $match['score1'] ?></span>
                                                    <span class="separator">-</span>
                                                    <span class="score"><?= $match['score2'] ?></span>
                                                <?php elseif($match['statut'] === 'en_cours'): ?>
                                                    <span class="live-indicator">EN DIRECT</span>
                                                <?php else: ?>
                                                    <span class="vs">VS</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="match-team team2">
                                                <span class="team-name"><?= htmlspecialchars($match['equipe2_nom']) ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="match-details">
                                            <div class="match-phase-badge">
                                                <?= htmlspecialchars($match['phase_nom'] ?? 'Match') ?>
                                            </div>
                                            
                                            <div class="match-location-slim">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <?= htmlspecialchars($match['lieu_match'] ?? '-') ?>
                                            </div>
                                        </div>
                                        
                                        <div class="match-actions">
                                            <a href="index.php?module=match&action=show&id=<?= $match['id'] ?>" class="match-view-btn">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="empty-text center">Aucun match n'a encore été programmé pour ce tournoi.</p>
            <?php endif; ?>
        </section>
        
        <!-- Section classements -->
        <section id="standings" class="content-section">
           
            
            <?php if(!empty($groupes)): ?>
                <div class="groups-container">
                    <?php foreach($groupes as $groupe): ?>
                        <div class="group-section">
                            <h3 class="group-title">Groupe <?= htmlspecialchars($groupe['nom']) ?></h3>
                            
                            <div class="standings-table-container">
                                <table class="standings-table">
                                    <thead>
                                        <tr>
                                            <th>Pos</th>
                                            <th class="team-cell">Équipe</th>
                                            <th>J</th>
                                            <th>V</th>
                                            <th>N</th>
                                            <th>D</th>
                                            <th>BP</th>
                                            <th>BC</th>
                                            <th>Diff</th>
                                            <th>Pts</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($groupe['equipes'] as $index => $equipe): ?>
                                            <tr class="<?= $index < 2 ? 'qualified' : '' ?>">
                                                <td class="position"><?= $index + 1 ?></td>
                                                <td class="team-cell">
                                                    <a href="index.php?module=equipe&action=show&id=<?= $equipe['id'] ?>" class="team-link-small">
                                                        <?php if(!empty($equipe['logo'])): ?>
                                                            <img src="uploads/<?= $equipe['logo'] ?>" alt="Logo" class="team-logo-small">
                                                        <?php endif; ?>
                                                        <?= htmlspecialchars($equipe['nom']) ?>
                                                    </a>
                                                </td>
                                                <td><?= $equipe['matchs_joues'] ?></td>
                                                <td><?= $equipe['victoires'] ?></td>
                                                <td><?= $equipe['nuls'] ?></td>
                                                <td><?= $equipe['defaites'] ?></td>
                                                <td><?= $equipe['buts_pour'] ?></td>
                                                <td><?= $equipe['buts_contre'] ?></td>
                                                <td class="<?= $equipe['difference'] > 0 ? 'positive' : ($equipe['difference'] < 0 ? 'negative' : '') ?>">
                                                    <?= $equipe['difference'] > 0 ? '+' . $equipe['difference'] : $equipe['difference'] ?>
                                                </td>
                                                <td class="points"><?= $equipe['points'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif(!empty($tableau_final)): ?>
                <!-- Affichage du tableau à élimination directe -->
                <div class="knockout-bracket">
                    <div class="bracket-rounds">
                        <?php foreach($tableau_final as $round => $matches): ?>
                            <div class="bracket-round">
                                <h3 class="round-title"><?= htmlspecialchars($round) ?></h3>
  
                                <div class="bracket-matches">
                                    <?php foreach($matches as $match): ?>
                                        <div class="bracket-match">
                                            <div class="bracket-team <?= isset($match['winner']) && $match['winner'] == $match['equipe1_id'] ? 'winner' : '' ?>">
                                                <span class="team-name"><?= htmlspecialchars($match['equipe1_nom'] ?? 'À déterminer') ?></span>
                                                <span class="team-score"><?= $match['score1'] ?? '-' ?></span>
                                            </div>
                                            
                                            <div class="bracket-team <?= isset($match['winner']) && $match['winner'] == $match['equipe2_id'] ? 'winner' : '' ?>">
                                                <span class="team-name"><?= htmlspecialchars($match['equipe2_nom'] ?? 'À déterminer') ?></span>
                                                <span class="team-score"><?= $match['score2'] ?? '-' ?></span>
                                            </div>
                                            
                                            <?php if(isset($match['id'])): ?>
                                                <a href="index.php?module=match&action=show&id=<?= $match['id'] ?>" class="bracket-match-link">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <p class="empty-text center">Aucun classement ou tableau disponible pour ce tournoi.</p>
                
                <?php if($isAdmin): ?>
                    <div class="admin-action-center">
                        <a href="index.php?module=groupe&action=create&tournoi_id=<?= $tournoi['id'] ?>" class="action-btn">
                            <i class="fas fa-table"></i> Configurer les groupes
                        </a>
                        <a href="index.php?module=bracket&action=create&tournoi_id=<?= $tournoi['id'] ?>" class="action-btn">
                            <i class="fas fa-sitemap"></i> Créer le tableau final
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </section>
        
        <!-- Section statistiques -->
        <section id="stats" class="content-section">
            <div class="stats-grid">
                <!-- Meilleurs buteurs -->
                <div class="stats-card">
                    <div class="card-header">
                        <h3><i class="fas fa-futbol"></i> Meilleurs buteurs</h3>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($buteurs)): ?>
                            <div class="stats-table-container">
                                <table class="stats-table">
                                    <thead>
                                        <tr>
                                            <th>Rang</th>
                                            <th>Joueur</th>
                                            <th>Équipe</th>
                                            <th>Buts</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($buteurs as $index => $buteur): ?>
                                            <tr>
                                                <td class="rank"><?= $index + 1 ?></td>
                                                <td>
                                                    <a href="index.php?module=joueur&action=show&id=<?= $buteur['joueur_id'] ?>" class="player-link">
                                                        <?= htmlspecialchars($buteur['nom']) ?>
                                                    </a>
                                                </td>
                                                <td><?= htmlspecialchars($buteur['equipe']) ?></td>
                                                <td class="goals"><?= $buteur['buts'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="empty-text">Aucune donnée disponible</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Passes décisives -->
                <div class="stats-card">
                    <div class="card-header">
                        <h3><i class="fas fa-shoe-prints"></i> Passes décisives</h3>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($passeurs)): ?>
                            <div class="stats-table-container">
                                <table class="stats-table">
                                    <thead>
                                        <tr>
                                            <th>Rang</th>
                                            <th>Joueur</th>
                                            <th>Équipe</th>
                                            <th>Passes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($passeurs as $index => $passeur): ?>
                                            <tr>
                                                <td class="rank"><?= $index + 1 ?></td>
                                                <td>
                                                    <a href="index.php?module=joueur&action=show&id=<?= $passeur['joueur_id'] ?>" class="player-link">
                                                        <?= htmlspecialchars($passeur['nom']) ?>
                                                    </a>
                                                </td>
                                                <td><?= htmlspecialchars($passeur['equipe']) ?></td>
                                                <td class="assists"><?= $passeur['passes'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="empty-text">Aucune donnée disponible</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Équipe offensive -->
                <div class="stats-card">
                    <div class="card-header">
                        <h3><i class="fas fa-bullseye"></i> Meilleures attaques</h3>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($attaques)): ?>
                            <div class="stats-chart">
                                <div class="chart-bars">
                                    <?php foreach($attaques as $equipe): ?>
                                        <div class="chart-item">
                                            <div class="team-info">
                                                <?php if(!empty($equipe['logo'])): ?>
                                                    <img src="uploads/<?= $equipe['logo'] ?>" alt="Logo" class="team-logo-small">
                                                <?php endif; ?>
                                                <span><?= htmlspecialchars($equipe['nom']) ?></span>
                                            </div>
                                            <div class="chart-bar-container">
                                                <div class="chart-bar" style="width: <?= min(100, ($equipe['buts_pour'] / max(array_column($attaques, 'buts_pour'))) * 100) ?>%;">
                                                    <span class="bar-value"><?= $equipe['buts_pour'] ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="empty-text">Aucune donnée disponible</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Équipes défensives -->
                <div class="stats-card">
                    <div class="card-header">
                        <h3><i class="fas fa-shield-alt"></i> Meilleures défenses</h3>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($defenses)): ?>
                            <div class="stats-chart">
                                <div class="chart-bars">
                                    <?php foreach($defenses as $equipe): ?>
                                        <div class="chart-item">
                                            <div class="team-info">
                                                <?php if(!empty($equipe['logo'])): ?>
                                                    <img src="uploads/<?= $equipe['logo'] ?>" alt="Logo" class="team-logo-small">
                                                <?php endif; ?>
                                                <span><?= htmlspecialchars($equipe['nom']) ?></span>
                                            </div>
                                            <div class="chart-bar-container">
                                                <div class="chart-bar defense" style="width: <?= min(100, (1 - ($equipe['buts_contre'] / max(1, max(array_column($defenses, 'buts_contre'))))) * 100) ?>%;">
                                                    <span class="bar-value"><?= $equipe['buts_contre'] ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="empty-text">Aucune donnée disponible</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<style>

    
 
    
    .tournament-container {
        max-width: 1200px;
        margin: 0 auto;
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    /* En-tête du tournoi */
    .tournament-header {
        position: relative;
        background-color: var(--primary);
        background-size: cover;
        background-position: center;
        color: white;
        min-height: 200px;
    }
    
    .tournament-header-overlay {
        background: linear-gradient(rgba(0, 51, 102, 0.8), rgba(0, 34, 68, 0.9));
        padding: 2rem;
    }
    
    .tournament-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .tournament-header-main {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    
    .tournament-logo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    .tournament-logo img {
        width: 90%;
        height: 90%;
        object-fit: contain;
    }
    
    .tournament-title {
        flex: 1;
    }
    
    .tournament-title h1 {
        font-size: 2.2rem;
        margin-bottom: 0.5rem;
        font-weight: 700;
    }
    
    .tournament-meta {
        display: flex;
        gap: 1.5rem;
        margin-top: 0.75rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }
    
    .tournament-status {
        display: flex;
        align-items: center;
    }
    
    .status {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
    }
    
    .status.upcoming {
        background-color: var(--info);
    }
    
    .status.ongoing {
        background-color: var(--accent);
        color: var(--text-dark);
        animation: pulse 2s infinite;
    }
    
    .status.completed {
        background-color: var(--success);
    }
    
    @keyframes pulse {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
        100% {
            opacity: 1;
        }
    }
    
    /* Navigation du tournoi */
    .tournament-nav {
        display: flex;
        justify-content: space-between;
        background-color: white;
        border-bottom: 1px solid var(--gray-300);
        padding: 0 1rem;
    }
    
    .nav-tabs {
        display: flex;
    }
    
    .nav-tab {
        padding: 1rem 1.25rem;
        font-weight: 600;
        color: var(--gray-700);
        border-bottom: 3px solid transparent;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .nav-tab:hover {
        color: var(--primary);
        background-color: var(--gray-100);
    }
    
    .nav-tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }
    
    .admin-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .admin-btn {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .admin-btn.edit {
        background-color: var(--warning);
        color: white;
    }
    
    .admin-btn.delete {
        background-color: var(--danger);
        color: white;
    }
    
    /* Contenu principal */
    .tournament-content {
        padding: 0;
    }
    
    .content-section {
        display: none;
        padding: 2rem;
    }
    
    .content-section.active {
        display: block;
    }
    
    .section-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }
    
    /* Section et cards */
    .tournament-section {
        background-color: white;
        border-radius: var(--border-radius);
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .section-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--gray-300);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .main-header {
        background-color: var(--primary);
        color: white;
    }
    
    .section-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .section-action {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        background-color: var(--primary-light);
        color: white;
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: background-color 0.2s;
    }
    
    .section-action:hover {
        background-color: var(--primary);
    }
    
    .section-body {
        padding: 1.25rem;
    }
    
    /* Vue d'ensemble */
    .tournament-description {
        line-height: 1.7;
    }
    
    /* Timeline des phases */
    .phases-timeline {
        position: relative;
        padding: 0.5rem 0;
    }
    
    .phases-timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 15px;
        width: 2px;
        height: 100%;
        background-color: var(--gray-300);
    }
    
    .phase-item {
        position: relative;
        padding-left: 40px;
        margin-bottom: 1.5rem;
    }
    
    .phase-item:last-child {
        margin-bottom: 0;
    }
    
    .phase-dot {
        position: absolute;
        left: 8px;
        top: 6px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background-color: var(--gray-500);
        z-index: 1;
    }
    
    .phase-item.active .phase-dot {
        background-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(0, 51, 102, 0.2);
    }
    
    .phase-content {
        background-color: var(--gray-100);
        padding: 1rem;
        border-radius: var(--border-radius);
        transition: all 0.2s;
    }
    
    .phase-item.active .phase-content {
        background-color: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-left: 3px solid var(--primary);
    }
    
    .phase-content h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.1rem;
    }
    
    .phase-dates {
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        color: var(--gray-600);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .phase-controls {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 4px;
        background-color: var(--gray-200);
        color: var(--gray-700);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .action-btn:hover {
        background-color: var(--gray-300);
    }
    
    .action-btn.delete:hover {
        background-color: var(--danger);
        color: white;
    }
    
    /* Matchs à venir */
    .upcoming-matches {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .match-card {
        display: flex;
        align-items: center;
        background-color: var(--gray-100);
        border-radius: var(--border-radius);
        overflow: hidden;
        transition: transform 0.2s;
    }
    
    .match-card:hover {
        transform: translateY(-2px);
    }
    
    .match-date {
        background-color: var(--primary);
        color: white;
        padding: 1rem;
        text-align: center;
        min-width: 80px;
    }
    
    .date-day {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }
    
    .date-month {
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    
    .date-time {
        margin-top: 0.25rem;
        font-weight: 600;
    }
    
    .match-teams {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
    }
    
    .team {
        font-weight: 600;
    }
    
    .versus {
        padding: 0 0.75rem;
        color: var(--gray-600);
        font-size: 0.85rem;
    }
    
    .match-info {
        padding: 0 1rem;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
    }
    
    .match-phase {
        display: flex;
        gap: 0.5rem;
    }
    
    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .badge.phase {
        background-color: rgba(0, 51, 102, 0.1);
        color: var(--primary);
    }
    
    .match-location {
        font-size: 0.85rem;
        color: var(--gray-500);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .match-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        text-decoration: none;
        color: var(--primary);
        transition: background-color 0.2s;
    }
    
    .match-link:hover {
        background-color: var(--gray-200);
    }
    
    /* Informations */
    .info-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        color: var(--gray-700);
    }
    
    .info-value {
        font-weight: 600;
    }
    
    /* Meilleurs buteurs */
    .top-scorers {
        list-style-type: none;
        counter-reset: scorer-counter;
    }
    
    .scorer-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .scorer-item:last-child {
        border-bottom: none;
    }
    
    .scorer-rank {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
    }
    
    .scorer-item:nth-child(1) .scorer-rank {
        color: gold;
    }
    
    .scorer-item:nth-child(2) .scorer-rank {
        color: silver;
    }
    
    .scorer-item:nth-child(3) .scorer-rank {
        color: #cd7f32; /* bronze */
    }
    
    .scorer-info {
        flex: 1;
    }
    
    .scorer-name {
        font-weight: 600;
    }
    
    .scorer-team {
        font-size: 0.85rem;
        color: var(--gray-600);
    }
    
    .scorer-goals {
        font-weight: 700;
        padding: 0.25rem 0.5rem;
        background-color: var(--gray-200);
        border-radius: 4px;
        min-width: 28px;
        text-align: center;
    }
    
    /* Documents */
    .document-list {
        list-style-type: none;
    }
    
    .document-item {
        margin-bottom: 0.5rem;
    }
    
    .document-item:last-child {
        margin-bottom: 0;
    }
    
    .document-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        border-radius: 4px;
        text-decoration: none;
        color: var(--primary);
        transition: background-color 0.2s;
    }
    
    .document-link:hover {
        background-color: var(--gray-200);
    }
    
    .document-upload {
        margin-top: 1rem;
        text-align: center;
    }
    
    .upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        background-color: var(--primary);
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.2s;
    }
    
    .upload-btn:hover {
        background-color: var(--primary-dark);
    }
    
    /* Équipes */
    .teams-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        padding: 1rem;
    }
    
    .team-card {
        border-radius: var(--border-radius);
        background-color: white;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.2s;
    }
    
    .team-card:hover {
        transform: translateY(-5px);
    }
    
    .team-header {
        padding: 1.5rem;
        text-align: center;
        background-color: var(--primary);
        color: white;
    }
    
    .team-logo, .team-logo-placeholder {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        background-color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .team-logo img {
        width: 90%;
        height: 90%;
        object-fit: contain;
    }
    
    .team-logo-placeholder i {
        font-size: 2.5rem;
        color: var(--gray-400);
    }
    
    .team-name {
        font-size: 1.25rem;
        margin: 0;
    }
    
    .team-content {
        padding: 1.25rem;
    }
    
    .team-stats {
        display: flex;
        justify-content: space-between;
        text-align: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
    }
    
    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: var(--gray-600);
    }
    
    .team-group-position {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .group-label {
        font-weight: 600;
        color: var(--primary);
    }
    
    .points-label {
        padding: 0.25rem 0.5rem;
        background-color: var(--gray-200);
        border-radius: 4px;
        font-size: 0.9rem;
    }
    
    .team-footer {
        padding: 0.75rem 1.25rem;
        border-top: 1px solid var(--gray-200);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .team-link {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        background-color: var(--primary);
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.2s;
        font-size: 0.9rem;
    }
    
    .team-link:hover {
        background-color: var(--primary-dark);
    }
    
    .team-admin-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .remove-team-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-
    }
        .team-admin-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .remove-team-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        background-color: var(--gray-200);
        color: var(--danger);
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .remove-team-btn:hover {
        background-color: var(--danger);
        color: white;
    }
    
    /* Classements et groupes */
    .groups-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    
    .group-card {
        background-color: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }
    
    .group-header {
        background-color: var(--primary-light);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .group-content {
        padding: 1.25rem;
    }
    
    .standings-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }
    
    .standings-table th {
        text-align: left;
        padding: 0.75rem;
        background-color: var(--gray-100);
        border-bottom: 2px solid var(--gray-300);
        font-weight: 600;
    }
    
    .standings-table td {
        padding: 0.75rem;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .standings-table tr:last-child td {
        border-bottom: none;
    }
    
    .standings-table tr:hover {
        background-color: var(--gray-100);
    }
    
    .rank-cell {
        width: 40px;
        font-weight: 600;
        color: var(--gray-700);
    }
    
    .team-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .cell-logo {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .cell-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .points {
        font-weight: 700;
        color: var(--primary);
    }
    
    .positive {
        color: var(--success);
    }
    
    .negative {
        color: var(--danger);
    }
    
    /* Tableau final */
    .knockout-bracket {
        overflow-x: auto;
        padding: 1.5rem 0;
    }
    
    .bracket-rounds {
        display: flex;
        min-width: max-content;
    }
    
    .bracket-round {
        display: flex;
        flex-direction: column;
        min-width: 220px;
        margin-right: 40px;
    }
    
    .bracket-round:last-child {
        margin-right: 0;
    }
    
    .round-title {
        text-align: center;
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
        color: var(--gray-700);
        position: relative;
    }
    
    .round-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background-color: var(--primary);
        border-radius: 3px;
    }
    
    .bracket-matches {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        flex: 1;
        gap: 2rem;
    }
    
    .bracket-match {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
    }
    
    .bracket-team {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .bracket-team:last-child {
        border-bottom: none;
    }
    
    .bracket-team.winner {
        background-color: rgba(76, 175, 80, 0.1);
    }
    
    .team-name {
        font-weight: 500;
    }
    
    .team-score {
        font-weight: 700;
    }
    
    .bracket-team.winner .team-score {
        color: var(--success);
    }
    
    .bracket-match-link {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: var(--info);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 0.8rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s;
    }
    
    .bracket-match-link:hover {
        transform: scale(1.1);
    }
    
    /* Statistiques */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    .stats-card {
        background-color: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        background-color: var(--primary);
        color: white;
        padding: 1rem 1.5rem;
    }
    
    .card-header h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .card-body {
        padding: 1.25rem;
    }
    
    .stats-table-container {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .stats-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }
    
    .stats-table th {
        text-align: left;
        padding: 0.75rem;
        background-color: var(--gray-100);
        border-bottom: 2px solid var(--gray-300);
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .stats-table td {
        padding: 0.75rem;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .stats-table tr:last-child td {
        border-bottom: none;
    }
    
    .stats-table tr:hover {
        background-color: var(--gray-100);
    }
    
    .rank {
        font-weight: 700;
        color: var(--gray-700);
        width: 40px;
        text-align: center;
    }
    
    .player-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }
    
    .player-link:hover {
        text-decoration: underline;
    }
    
    .goals, .assists {
        font-weight: 700;
        color: var(--primary);
        text-align: center;
    }
    
    /* Chart */
    .stats-chart {
        margin-top: 0.5rem;
    }
    
    .chart-bars {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .chart-item {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    
    .team-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .team-logo-small {
        width: 20px;
        height: 20px;
        object-fit: contain;
    }
    
    .chart-bar-container {
        height: 12px;
        background-color: var(--gray-200);
        border-radius: 6px;
        overflow: hidden;
    }
    
    .chart-bar {
        height: 100%;
        background-color: var(--primary);
        border-radius: 6px;
        position: relative;
        min-width: 30px;
    }
    
    .chart-bar.defense {
        background-color: var(--success);
    }
    
    .bar-value {
        position: absolute;
        right: 6px;
        top: -16px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    /* Message vide */
    .empty-text {
        text-align: center;
        padding: 2rem;
        color: var(--gray-500);
        font-style: italic;
    }
    
    .center {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .admin-action-center {
        display: flex;
        justify-content: center;
        gap: 1rem;
        padding: 1rem 0;
    }
    
    /* Responsive design */
    @media (max-width: 992px) {
        .section-grid {
            grid-template-columns: 1fr;
        }
        
        .tournament-header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .tournament-status {
            margin-top: 1rem;
            align-self: flex-start;
        }
        
        .match-teams {
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 768px) {
        .tournament-nav {
            flex-direction: column;
        }
        
        .nav-tabs {
            flex-wrap: wrap;
        }
        
        .nav-tab {
            flex: 1;
            text-align: center;
            padding: 0.75rem;
        }
        
        .admin-controls {
            padding: 0.75rem;
            justify-content: center;
        }
        
        .tournament-meta {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .match-card {
            flex-direction: column;
            text-align: center;
        }
        
        .match-date {
            width: 100%;
            padding: 0.5rem;
        }
        
        .date-day, .date-month {
            display: inline;
        }
        
        .match-teams {
            padding: 0.75rem;
        }
        
        .match-info {
            padding: 0 0.75rem 0.75rem;
            align-items: center;
        }
    }
    
    @media (max-width: 576px) {
        .tournament-header-main {
            flex-direction: column;
            text-align: center;
            align-items: center;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .teams-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    // Navigation des onglets de tournoi
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.nav-tab');
        const sections = document.querySelectorAll('.content-section');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('href').substring(1);
                
                // Désactiver tous les onglets et sections
                tabs.forEach(t => t.classList.remove('active'));
                sections.forEach(s => s.classList.remove('active'));
                
                // Activer l'onglet et la section cliqués
                this.classList.add('active');
                document.getElementById(target).classList.add('active');
            });
        });
        
        // Activer le premier onglet par défaut s'il existe
        if (tabs.length > 0) {
            tabs[0].click();
        }
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>

    </style>