<?php

// Capturer le contenu
ob_start();
?>



  <div class="container">
    <header class="header">
        <h1>Gestion des Joueurs</h1>
        <p class="subtitle">Liste et recherche des joueurs</p>
    </header>
     <!-- Fil d'Ariane -->
  <nav class="breadcrumb">
    <a href="index.php?module=equipe&action=index" class="breadcrumb-link">
      <i class="fas fa-users"></i> Équipes 
    </a>
    <i class="fas fa-angle-right"></i>
    <span class="current-page"><?= htmlspecialchars($equipe['nom']) ?></span>
  </nav>

  <!-- Layout principal -->
  <div class="equipe-layout">
    <!-- Colonne gauche: Détails de l'équipe -->
    <div class="equipe-details">
      <div class="equipe-card">
        <div class="card-header">
          <h2><i class="fas fa-shield-alt"></i> Détails de l'équipe</h2>
          <span class="badge badge-secondary">ID: <?= htmlspecialchars($equipe['id']) ?></span>
        </div>
        
        <div class="logo-container">
          <?php if (!empty($equipe['logo'])): ?>
            <img src="<?= htmlspecialchars($equipe['logo']) ?>" class="team-logo" alt="Logo de <?= htmlspecialchars($equipe['nom']) ?>">
          <?php else: ?>
            <div class="default-logo">
              <i class="fas fa-users"></i>
            </div>
          <?php endif; ?>
        </div>
        
        <h3 class="team-name"><?= htmlspecialchars($equipe['nom']) ?></h3>
        
        <ul class="details-list">
          <!-- Date de création -->
          <li class="detail-item">
            <span class="detail-label"><i class="far fa-calendar-alt"></i> Date de création</span>
            <span class="detail-value"><?= !empty($equipe['date_creation']) ? date('d/m/Y', strtotime($equipe['date_creation'])) : 'Non spécifiée' ?></span>
          </li>
          
          <!-- Email de contact -->
          <li class="detail-item">
            <span class="detail-label"><i class="fas fa-envelope"></i> Contact</span>
            <a href="mailto:<?= htmlspecialchars($equipe['contact_email']) ?>" class="email-link">
              <?= htmlspecialchars($equipe['contact_email']) ?>
            </a>
          </li>
          
          <!-- Entraîneur -->
          <li class="detail-item">
            <span class="detail-label"><i class="fas fa-user-tie"></i> Entraîneur</span>
            <span class="detail-value"><?= !empty($equipe['entraineur']) ? htmlspecialchars($equipe['entraineur']) : 'Non spécifié' ?></span>
          </li>
          
          <!-- Catégorie -->
          <li class="detail-item">
            <span class="detail-label"><i class="fas fa-tag"></i> Catégorie</span>
            <span class="detail-value"><?= !empty($equipe['categorie']) ? htmlspecialchars($equipe['categorie']) : 'Non spécifiée' ?></span>
          </li>
          
          <!-- Nombre de joueurs -->
          <li class="detail-item">
            <span class="detail-label"><i class="fas fa-users"></i> Effectif</span>
            <span class="badge <?= count($joueurs) > 0 ? 'badge-success' : 'badge-secondary' ?>"><?= count($joueurs) ?> joueurs</span>
          </li>
        </ul>
        
        <div class="card-footer">
          <div class="button-group">
            <a href="index.php?module=equipe&action=edit&id=<?= $equipe['id'] ?>" class="btn-outline-primary">
              <i class="fas fa-edit"></i> Modifier l'équipe
            </a>
            <a href="index.php?module=equipe&action=addPlayer&id=<?= $equipe['id'] ?>" class="btn-primary">
              <i class="fas fa-user-plus"></i> Ajouter un joueur
            </a>
          </div>
        </div>
      </div>
      
     
    </div>
    
    <!-- Colonne droite: Liste des joueurs -->
    <div class="equipe-roster">
      <div class="roster-card">
        <div class="card-header">
          <div class="roster-header-info">
            <h2><i class="fas fa-user-friends"></i> Effectif</h2>
            <span class="badge badge-primary"><?= count($joueurs) ?> joueurs</span>
          </div>
        </div>
        
        <?php if (!empty($joueurs)): ?>
          <div class="table-container" style="overflow-x: auto;">
            <table class="players-table">
              <thead>
                <tr>
                  <th width="5%"></th>
                  <th width="25%">Nom</th>
                  <th width="25%">Prénom</th>
                  <th width="20%">Poste</th>
                  <th width="25%" style="text-align:center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($joueurs as $joueur): ?>
                  <tr>
                    <td>
                      <?php if (!empty($joueur['photo'])): ?>
                        <img src="<?= htmlspecialchars($joueur['photo']) ?>" alt="Photo de <?= htmlspecialchars($joueur['prenom']) ?>" 
                            class="player-photo">
                      <?php else: ?>
                        <div class="photo-placeholder">
                          <i class="fas fa-user"></i>
                        </div>
                      <?php endif; ?>
                    </td>
                    <td class="player-name"><?= htmlspecialchars($joueur['nom']) ?></td>
                    <td class="player-firstname"><?= htmlspecialchars($joueur['prenom']) ?></td>
                    <td>
                      <?php 
                        $posteClass = '';
                        $posteIcon = '';
                        
                        switch(strtolower($joueur['poste'] ?? '')) {
                            case 'gardien':
                                $posteClass = 'poste-gardien';
                                $posteIcon = 'fas fa-hand-paper';
                                break;
                            case 'défenseur':
                            case 'defenseur':  
                                $posteClass = 'poste-defenseur';
                                $posteIcon = 'fas fa-shield-alt';
                                break;
                            case 'milieu':
                                $posteClass = 'poste-milieu';
                                $posteIcon = 'fas fa-sync-alt';
                                break;
                            case 'attaquant':
                                $posteClass = 'poste-attaquant';
                                $posteIcon = 'fas fa-futbol';
                                break;
                            default:
                                $posteClass = '';
                                $posteIcon = 'fas fa-user';
                        }
                      ?>
                      <span class="poste-badge <?= $posteClass ?>">
                        <i class="<?= $posteIcon ?>"></i> <?= htmlspecialchars($joueur['poste'] ?? 'Non défini') ?>
                      </span>
                    </td>
                    <td>
                      <div class="player-actions">
                        <a href="index.php?module=utilisateur&action=show&id=<?= $joueur['id'] ?>" class="action-btn action-btn-view" title="Voir le profil">
                          <i class="fas fa-eye"></i>
                        </a>
                        <a href="index.php?module=player&action=delete&id=<?= $joueur['id'] ?>" 
                           class="action-btn action-btn-delete"
                           title="Retirer de l'équipe"
                           onclick="return confirm('Êtes-vous sûr de vouloir retirer ce joueur de l\'équipe ?')">
                          <i class="fas fa-trash-alt"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="empty-roster">
            <i class="fas fa-users-slash"></i>
            <p>Aucun joueur n'est enregistré dans cette équipe.</p>
            <a href="index.php?module=equipe&action=selectPlayers&id=<?= $equipe['id'] ?>" class="btn-primary">
               Ajouter un premier joueur
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  
 </div>

<script>
  function toggleDropdown() {
    document.getElementById('optionsDropdown').classList.toggle('show');
  }
  
  // Fermer le dropdown lorsque l'on clique ailleurs dans la page
  window.addEventListener('click', function(event) {
    if (!event.target.matches('.dropdown-toggle') && !event.target.closest('.dropdown-toggle')) {
      var dropdowns = document.getElementsByClassName('options-dropdown');
      for (var i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }
  });
</script>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>
