<?php
// Capturer le contenu
ob_start();
?>

<div class="match-details-container">
    <div class="match-header">
        <div class="match-title">
            <h1>
                <?php if (isset($match['est_amical']) && $match['est_amical'] == 1): ?>
                    <i class="fas fa-handshake"></i> Match amical
                <?php else: ?>
                    <i class="fas fa-trophy"></i> Match de tournoi
                <?php endif; ?>
            </h1>
            
            <span class="match-status <?= strtolower($match['statut']) ?>">
                <?php if ($match['statut'] === 'terminé'): ?>
                    Terminé
                <?php elseif ($match['statut'] === 'en_cours'): ?>
                    En cours
                <?php else: ?>
                    À venir
                <?php endif; ?>
            </span>
        </div>
        
        <div class="match-navigation">
            <a href="index.php?module=match&action=index" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                <div class="admin-actions">
                    <a href="index.php?module=match&action=edit&id=<?= $match['id'] ?>" class="btn btn-edit">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="index.php?module=match&action=delete&id=<?= $match['id'] ?>" 
                       class="btn btn-delete" 
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce match?');">
                        <i class="fas fa-trash"></i> Supprimer
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Affichage du score/opposition équipes -->
    <div class="teams-scoreboard">
        <div class="team team-left">
            <div class="team-name"><?= htmlspecialchars($match['equipe1_nom']) ?></div>
            <?php if (!empty($match['equipe1_logo'])): ?>
                <div class="team-logo">
                    <img src="<?= htmlspecialchars($match['equipe1_logo']) ?>" alt="Logo <?= htmlspecialchars($match['equipe1_nom']) ?>">
                </div>
            <?php endif; ?>
        </div>
        
        <div class="match-score">
            <?php if ($match['statut'] === 'terminé'): ?>
                <div class="score-display">
                    <span class="score"><?= $match['score1'] ?? 0 ?></span>
                    <span class="score-separator">-</span>
                    <span class="score"><?= $match['score2'] ?? 0 ?></span>
                </div>
            <?php else: ?>
                <div class="versus-display">VS</div>
            <?php endif; ?>
            
            <div class="match-time">
                <?= date('d/m/Y', strtotime($match['date_match'])) ?>
                <span class="time"><?= date('H:i', strtotime($match['date_match'])) ?></span>
            </div>
        </div>
        
        <div class="team team-right">
            <div class="team-name"><?= htmlspecialchars($match['equipe2_nom']) ?></div>
            <?php if (!empty($match['equipe2_logo'])): ?>
                <div class="team-logo">
                    <img src="<?= htmlspecialchars($match['equipe2_logo']) ?>" alt="Logo <?= htmlspecialchars($match['equipe2_nom']) ?>">
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="match-details-grid">
        <div class="match-info-card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Informations du match
            </div>
            <div class="card-content">
                <ul class="info-list">
                    <li>
                        <i class="far fa-calendar-alt"></i>
                        <div class="info-content">
                            <span class="info-label">Date</span>
                            <span class="info-value"><?= date('d/m/Y à H:i', strtotime($match['date_match'])) ?></span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="info-content">
                            <span class="info-label">Lieu</span>
                            <span class="info-value"><?= htmlspecialchars($match['lieu_match']) ?></span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-info-circle"></i>
                        <div class="info-content">
                            <span class="info-label">Type</span>
                            <span class="info-value">
                                <?php if (isset($match['est_amical']) && $match['est_amical'] == 1): ?>
                                    <span class="badge amical">Match amical</span>
                                <?php elseif (!empty($match['tournoi_nom'])): ?>
                                    <a href="index.php?module=tournoi&action=show&id=<?= $match['tournoi_id'] ?>" class="tournoi-link">
                                        <i class="fas fa-trophy"></i> <?= htmlspecialchars($match['tournoi_nom']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="badge non-assigne">Non assigné</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </li>
                    <?php if (!empty($match['phase'])): ?>
                        <li>
                            <i class="fas fa-layer-group"></i>
                            <div class="info-content">
                                <span class="info-label">Phase</span>
                                <span class="info-value"><?= htmlspecialchars($match['phase']) ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        
        <div class="match-status-card">
            <div class="card-header">
                <i class="fas fa-flag"></i> Statut du match
            </div>
            <div class="card-content">
                <?php if ($match['statut'] === 'terminé'): ?>
                    <div class="status-alert termine">
                        <i class="fas fa-flag-checkered"></i>
                        <div>
                            Match terminé
                            <?php if ($match['score1'] > $match['score2']): ?>
                                <span class="result">Victoire de <?= htmlspecialchars($match['equipe1_nom']) ?></span>
                            <?php elseif ($match['score1'] < $match['score2']): ?>
                                <span class="result">Victoire de <?= htmlspecialchars($match['equipe2_nom']) ?></span>
                            <?php else: ?>
                                <span class="result">Match nul</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php elseif ($match['statut'] === 'en_cours'): ?>
                    <div class="status-alert en-cours">
                        <i class="fas fa-play-circle"></i>
                        <div>Le match est en cours</div>
                    </div>
                <?php else: ?>
                    <div class="status-alert a-venir">
                        <i class="fas fa-hourglass-start"></i>
                        <div>Le match n'a pas encore commencé</div>
                    </div>
                <?php endif; ?>
                
                <!-- Informations temporelles -->
                <?php
                $now = time();
                $match_time = strtotime($match['date_match']);
                $time_diff = $match_time - $now;
                
                if ($match['statut'] === 'à_venir' && $time_diff > 0):
                    $days = floor($time_diff / (60 * 60 * 24));
                    $hours = floor(($time_diff % (60 * 60 * 24)) / (60 * 60));
                    $minutes = floor(($time_diff % (60 * 60)) / 60);
                ?>
                    <div class="countdown-container">
                        <h3>Temps restant avant le match</h3>
                        <div class="countdown">
                            <div class="countdown-item">
                                <div class="countdown-value"><?= $days ?></div>
                                <div class="countdown-label">Jours</div>
                            </div>
                            <div class="countdown-item">
                                <div class="countdown-value"><?= $hours ?></div>
                                <div class="countdown-label">Heures</div>
                            </div>
                            <div class="countdown-item">
                                <div class="countdown-value"><?= $minutes ?></div>
                                <div class="countdown-label">Minutes</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if (!empty($match['commentaires'])): ?>
    <div class="comments-card">
        <div class="card-header">
            <i class="fas fa-comments"></i> Commentaires
        </div>
        <div class="card-content">
            <div class="comments-text">
                <?= nl2br(htmlspecialchars($match['commentaires'])) ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>
<style>
    /* Match Details View - CSS Pur */

/* Container principal */
.match-details-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;

  color: #333;
}

/* En-tête du match */
.match-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding-bottom: 15px;
  border-bottom: 1px solid #eaeaea;
}

.match-title {
  display: flex;
  align-items: center;
  gap: 15px;
}

.match-title h1 {
  font-size: 24px;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.match-status {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 600;
  text-transform: uppercase;
}

.match-status.terminé {
  background-color: #4caf50;
  color: white;
}

.match-status.en_cours {
  background-color:rgb(141, 207, 144);
  color: white;
}

.match-status.à_venir {
  background-color:rgb(106, 153, 200);
  color: white;
}

.match-navigation {
  display: flex;
  gap: 10px;
}

.admin-actions {
  display: flex;
  gap: 10px;
}

/* Boutons */
.btn {
  padding: 8px 16px;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
  border: none;
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}



.btn-edit {
  background-color:none;
  border: solid 1px black;
  color: black;
 
}

.btn-delete {
  background-color: #ef5350;
  color: white;
}

/* Tableau d'affichage des équipes et scores */
.teams-scoreboard {
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(to right, #0a1435, #1a2d6e);
  color: white;
  border-radius: 8px;
  padding: 30px;
  margin-bottom: 30px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.team {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 40%;
  padding: 0 20px;
}

.team-left {
  align-items: flex-end;
  text-align: right;
}

.team-right {
  align-items: flex-start;
  text-align: left;
}

.team-name {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 15px;
}

.team-logo {
  width: 80px;
  height: 80px;
  background-color: white;
  border-radius: 50%;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 0 0 4px rgba(255,255,255,0.2);
}

.team-logo img {
  max-width: 80%;
  max-height: 80%;
  object-fit: contain;
}

.match-score {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 20%;
  text-align: center;
  position: relative;
}

.score-display {
  display: flex;
  align-items: center;
  font-size: 40px;
  font-weight: 700;
  margin-bottom: 10px;
}

.score {
  width: 50px;
  text-align: center;
}

.score-separator {
  margin: 0 10px;
  opacity: 0.7;
}

.versus-display {
  font-size: 28px;
  font-weight: 700;
  letter-spacing: 2px;
  margin-bottom: 10px;
}

.match-time {
  font-size: 14px;
  opacity: 0.9;
}

.match-time .time {
  font-weight: 700;
  margin-left: 5px;
}

/* Grille d'informations du match */
.match-details-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
  margin-bottom: 30px;
}

/* Cartes d'information */
.match-info-card, .match-status-card, .comments-card {
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  background-color: white;
}

.card-header {
  padding: 15px 20px;
  background-color: #0a1435;
  color: white;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 16px;
}

.card-content {
  padding: 20px;
}

/* Liste d'informations */
.info-list {
  list-style: none;
  margin: 0;
  padding: 0;
}

.info-list li {
  display: flex;
  align-items: center;
  padding: 12px 0;
  border-bottom: 1px solid #f0f0f0;
  gap: 15px;
}

.info-list li:last-child {
  border-bottom: none;
}

.info-list li i {
  font-size: 18px;
  color:rgb(17, 25, 60);
  width: 20px;
  text-align: center;
}

.info-content {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.info-label {
  font-size: 12px;
  color: #666;
  margin-bottom: 4px;
}

.info-value {
  font-weight: 500;
}

.badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 15px;
  font-size: 12px;
  font-weight: 500;
}

.badge.amical {
  background-color: #81d4fa;
  color: #01579b;
}

.badge.non-assigne {
  background-color: #ffecb3;
  color: #ff8f00;
}

.tournoi-link {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  color:rgb(11, 11, 11);
  text-decoration: none;
  font-weight: 500;
}

.tournoi-link:hover {
  text-decoration: underline;
}

/* Alertes de statut */
.status-alert {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 15px;
  border-radius: 5px;
  margin-bottom: 20px;
}

.status-alert i {
  font-size: 24px;
}

.status-alert.termine {
  background-color: #e8f5e9;
  color: #2e7d32;
}

.status-alert.en-cours {
  background-color: #fff8e1;
  color: #ff8f00;
}

.status-alert.a-venir {
  background-color: #e3f2fd;
  color: #1565c0;
}

.result {
  display: block;
  font-weight: 600;
  margin-top: 5px;
}

/* Compte à rebours */
.countdown-container {
  background-color: #f5f5f5;
  border-radius: 5px;
  padding: 15px;
  text-align: center;
}

.countdown-container h3 {
  font-size: 16px;
  margin: 0 0 15px 0;
  color: #333;
}

.countdown {
  display: flex;
  justify-content: center;
  gap: 20px;
}

.countdown-item {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.countdown-value {
  font-size: 28px;
  font-weight: 700;
  color: #1a2d6e;
  padding: 10px 15px;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  min-width: 60px;
  text-align: center;
}

.countdown-label {
  font-size: 12px;
  color: #666;
  margin-top: 5px;
  text-transform: uppercase;
}

/* Commentaires */
.comments-card {
  margin-bottom: 30px;
}

.comments-text {
  line-height: 1.6;
  white-space: pre-line;
  background-color: #f9f9f9;
  padding: 15px;
  border-radius: 5px;
  font-size: 14px;
}

/* Responsive */
@media (max-width: 768px) {
  .match-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .match-navigation {
    width: 100%;
    justify-content: space-between;
  }
  
  .teams-scoreboard {
    flex-direction: column;
    padding: 20px;
  }
  
  .team {
    width: 100%;
    margin: 10px 0;
  }
  
  .team-left, .team-right {
    text-align: center;
    align-items: center;
  }
  
  .match-score {
    width: 100%;
    margin: 20px 0;
    order: 3;
  }
  
  .match-details-grid {
    grid-template-columns: 1fr;
  }
  
  .countdown {
    gap: 10px;
  }
  
  .countdown-value {
    font-size: 20px;
    min-width: 40px;
  }
}

</style>