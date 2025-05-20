<?php

// Capturer le contenu
ob_start();
?>



<div class="page-header">
    <div class="container">
        <h1> <i class="fas fa-users "></i> Équipes disponibles</h1>
        <p class="lead">Consultez toutes les équipes </p>
    </div>
</div>

<div class="container">
    <div class="section-header">
        
        <h2 class="section-title">Liste des Équipes</h2>
        <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="?module=equipe&action=create" class="btn-primary">
                <i class="fas fa-plus"></i> Ajouter une équipe
            </a>
        <?php endif; ?>
    </div>


    <!-- Messages flash pour les retours utilisateur -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="flash-message <?= $_SESSION['flash_type'] ?>" role="alert">
            <?= $_SESSION['flash_message'] ?>
            <button type="button" class="btn-close" onclick="this.parentElement.style.display='none';">×</button>
        </div>
        <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <!-- Grille d'équipes -->
    <div class="equipes-grid">
        <?php if (empty($equipes)): ?>
            <div style="grid-column: 1 / -1;">
                <div class="info-message">
                    <i class="fas fa-info-circle"></i>
                    Aucune équipe n'a été créée. Cliquez sur "Nouvelle équipe" pour commencer.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($equipes as $equipe): ?>
                <div class="equipe-card">
                    <div class="equipe-header">
                        <!-- Logo de l'équipe -->
                        <?php if (!empty($equipe['logo'])): ?>
                            <div class="team-logo-container">
                                <img src="<?= htmlspecialchars($equipe['logo']) ?>" 
                                     alt="Logo <?= htmlspecialchars($equipe['nom']) ?>" 
                                     class="team-logo">
                            </div>
                        <?php else: ?>
                            <div class="team-logo-placeholder">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="equipe-body">
                        <h5 class="equipe-title">
                            <?= htmlspecialchars($equipe['nom']) ?>
                        </h5>
                        
                        <div class="equipe-details">
                            <p>
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:<?= htmlspecialchars($equipe['contact_email']) ?>">
                                    <?= htmlspecialchars($equipe['contact_email']) ?>
                                </a>
                            </p>
                            
                            <!-- Nombre de joueurs si disponible -->
                            <?php if (isset($equipe['nb_joueurs'])): ?>
                                <p>
                                    <i class="fas fa-user"></i>
                                    <?= $equipe['nb_joueurs'] ?> joueur<?= $equipe['nb_joueurs'] > 1 ? 's' : '' ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="equipe-footer">
                        <a href="index.php?module=equipe&action=show&id=<?= $equipe['id'] ?>" 
                           class="btn-primary">
                            <i class="fas fa-eye"></i> Détails
                        </a>
                        
                    
                    </div>
                </div>
                
                <!-- Modal de confirmation pour la suppression -->
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <div class="modal" id="deleteModal<?= $equipe['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        Confirmer la suppression
                                    </h5>
                                    <button type="button" class="btn-close" onclick="closeModal('deleteModal<?= $equipe['id'] ?>')">×</button>
                                </div>
                                <div class="modal-body">
                                    <p>Êtes-vous sûr de vouloir supprimer l'équipe <strong><?= htmlspecialchars($equipe['nom']) ?></strong> ?</p>
                                    <p class="text-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Cette action est irréversible et supprimera tous les joueurs associés.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="closeModal('deleteModal<?= $equipe['id'] ?>')">Annuler</button>
                                    <a href="index.php?module=equipe&action=delete&id=<?= $equipe['id'] ?>" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    // Fonctions pour les modals
    function openModal(id) {
        document.getElementById(id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        document.body.style.overflow = '';
    }
    
    // Fermer les modals quand on clique en dehors
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal(event.target.id);
        }
    }
    
    // Auto-fermeture des messages flash après 5 secondes
    setTimeout(function() {
        var flashMessages = document.querySelectorAll('.flash-message');
        flashMessages.forEach(function(message) {
            message.style.opacity = '0';
            setTimeout(function() {
                message.style.display = 'none';
            }, 500);
        });
    }, 5000);
</script>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>
