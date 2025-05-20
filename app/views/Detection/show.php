<?php
ob_start();
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-search me-2"></i><?= htmlspecialchars($detection['name'] ?? 'Détection de football') ?></h1>
                      </p>
                </h1>
        <p class="lead">Une opportunité unique de montrer votre talent et d'être repéré par des clubs professionnels.
        </p>
    </div>
  
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="?module=home" class="text-white-50">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="?module=detection&action=index" class="text-white-50">Détections</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Détails</li>
                    </ol>
                </nav>
               
                <?php
                // Définir la classe CSS et l'icône en fonction du statut
                $statusClass = '';
                $statusIcon = '';
                $statusText = '';
                
                switch ($detection['status'] ?? 'planned') {
                    case 'planned':
                        $statusClass = 'status-planned';
                        $statusIcon = 'fa-calendar-alt';
                        $statusText = 'Programmée';
                        break;
                    case 'ongoing':
                        $statusClass = 'status-ongoing';
                        $statusIcon = 'fa-play-circle';
                        $statusText = 'En cours';
                        break;
                    case 'completed':
                        $statusClass = 'status-completed';
                        $statusIcon = 'fa-check-circle';
                        $statusText = 'Terminée';
                        break;
                    case 'cancelled':
                        $statusClass = 'status-cancelled';
                        $statusIcon = 'fa-times-circle';
                        $statusText = 'Annulée';
                        break;
                }
                ?>
                
                <span class="status-badge <?= $statusClass ?>">
                    <i class="fas <?= $statusIcon ?>"></i> <?= $statusText ?>
                </span>
            </div>
            
<div class="action-btns">
    <?php if ($isAdmin): ?>
        <!-- Actions administrateur -->
        <div class="btn-group">
            <a href="?module=detection&action=edit&id=<?= $detection['id'] ?? 0 ?>" class="btn btn-light btn-action">
                <i class="fas fa-edit me-2"></i> Modifier
            </a>
            <button type="button" class="btn btn-outline-light btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash-alt me-2"></i> Supprimer
            </button>
            <div class="btn-group">
                <a href="?module=detection&action=participants&id=<?= $detection['id'] ?? 0 ?>" class="btn btn-info btn-action">
                    <i class="fas fa-users me-2"></i> Inscrits (<?= $currentParticipants ?? 0 ?>)
                </a>
                <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?module=detection&action=export&id=<?= $detection['id'] ?? 0 ?>&format=excel">
                        <i class="fas fa-file-excel me-2"></i> Exporter en Excel
                    </a></li>
                    <li><a class="dropdown-item" href="?module=detection&action=export&id=<?= $detection['id'] ?? 0 ?>&format=pdf">
                        <i class="fas fa-file-pdf me-2"></i> Exporter en PDF
                    </a></li>
               
                </ul>
            </div>
        </div>
    <?php elseif ($isJoueur): ?>
        <?php if ($inscription): ?>
            <!-- Joueur déjà inscrit -->
            <div class="btn-group">
                <a href="?module=detection&action=invoice&id=<?= $detection['id'] ?? 0 ?>" class="btn btn-success btn-action">
                    <i class="fas fa-check-circle me-2"></i> Inscrit
                </a>
                <button type="button" class="btn btn-danger btn-action" data-bs-toggle="modal" data-bs-target="#cancelInscriptionModal">
                    <i class="fas fa-times-circle me-2"></i> Annuler
                </button>
            </div>
        <?php else: ?>
            <!-- Joueur non inscrit -->
            <a href="?module=detection&action=register&id=<?= $detection['id'] ?? 0 ?>" class="btn btn-light btn-action">
                <i class="fas fa-user-plus me-2"></i> S'inscrire
            </a>
        <?php endif; ?>
    <?php else: ?>
        <!-- Utilisateur non connecté -->
        <a href="?module=auth&action=login" class="btn btn-light btn-action">
            <i class="fas fa-sign-in-alt me-2"></i> Se connecter pour s'inscrire
        </a>
    <?php endif; ?>
    
    <!-- Toujours montrer le bouton partager -->
    <button class="btn btn-outline-light btn-action btn-share">
        <i class="fas fa-share-alt me-2"></i> Partager
    </button>
</div>


        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="btn-group">
                    <a href="#details" class="btn btn-sm btn-outline-light">Détails</a>
                    <a href="#location" class="btn btn-sm btn-outline-light">Lieu</a>
                    <a href="#registration" class="btn btn-sm btn-outline-light">Inscriptions</a>
                    <a href="#coaches" class="btn btn-sm btn-outline-light">Encadrement</a>
                </div>
            </div>
            
            <?php if (isset($_SESSION['admin']) && $_SESSION['admin']): ?>
            <div class="col-md-6 text-end">
                <div class="btn-group">
                    <a href="?module=detection&action=edit&id=<?= $detection['id'] ?? 0 ?>" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-light text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</header>
</div>

<div class="container">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.3/dist/leaflet.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<?php 
$isUserLoggedIn = isset($_SESSION['user']);
$isAdmin = $isUserLoggedIn && $_SESSION['user']['role'] === 'admin';
$isJoueur = $isUserLoggedIn && $_SESSION['user']['role'] === 'joueur';
?>
<!-- Toast notifications -->
<div class="toast-container">
    <?php if (isset($_SESSION['success'])): ?>
    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Succès</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong class="me-auto">Erreur</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Header section with detection title and main actions -->


<!-- Main content section -->
<div class="container my-5">
    <div class="row">
        <!-- Left column -->
        <div class="col-lg-8">
            <!-- Main information card -->
            <div class="detection-info-card" id="details">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> Informations principales
                </div>
                <div class="detection-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Date</div>
                                    <div class="info-text">
                                        <?php
                                        $date = new DateTime($detection['date'] ?? 'now');
                                        echo $date->format('d F Y');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Heure</div>
                                    <div class="info-text"><?= $detection['heure'] ?? '14:00' ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Lieu</div>
                                    <div class="info-text"><?= htmlspecialchars($detection['lieu'] ?? 'Stade Municipal') ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Catégorie</div>
                                    <div class="info-text"><?= htmlspecialchars($detection['categorie'] ?? 'U15-U17') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    // Calculer le nombre de jours restants avant la détection
                    $dateDetection = new DateTime($detection['date'] ?? 'now');
                    $today = new DateTime();
                    $interval = $today->diff($dateDetection);
                    $joursRestants = $interval->days;
                    $finInscription = new DateTime($detection['date_fin_inscription'] ?? 'now');
                    $intervalInscription = $today->diff($finInscription);
                    $joursRestantsInscription = $intervalInscription->days;
                    
                    // Ne montrer le compte à rebours que si la détection est dans le futur
                    if ($dateDetection > $today && ($detection['status'] ?? '') != 'cancelled'):
                    ?>
                    <div class="countdown">
                        <div class="countdown-item">
                            <div class="countdown-number"><?= $joursRestants ?></div>
                            <div class="countdown-label">Jours</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-number"><?= $interval->h ?></div>
                            <div class="countdown-label">Heures</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-number"><?= $interval->i ?></div>
                            <div class="countdown-label">Minutes</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-number"><?= $interval->s ?></div>
                            <div class="countdown-label">Secondes</div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Il vous reste <strong><?= $joursRestantsInscription ?> jours</strong> pour vous inscrire à cette détection.
                    </div>
                    <?php endif; ?>
                    
                    <?php if (($detection['status'] ?? '') == 'cancelled'): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i> Cette détection a été annulée. Pour plus d'informations, veuillez contacter l'organisation.
                    </div>
                    <?php endif; ?>
                    
                    <?php if (($detection['status'] ?? '') == 'completed'): ?>
                    <div class="alert alert-secondary">
                        <i class="fas fa-check-circle me-2"></i> Cette détection est terminée. Les résultats ont été communiqués aux participants.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Map location card -->
            <div class="detection-info-card" id="location">
                <div class="card-header">
                    <i class="fas fa-map-marked-alt"></i> Lieu de la détection
                </div>
                <div class="detection-card-body">
                    <div id="map"></div>
                    <div class="mt-3">
                        <p><strong>Adresse:</strong> <?= htmlspecialchars($detection['lieu'] ?? 'Stade Municipal, 75001 Paris') ?></p>
                        <p><small class="text-muted">Pour toute question concernant l'accès au lieu, veuillez contacter l'organisation au 01 23 45 67 89.</small></p>
                    </div>
                    <div class="mt-3">
                        <a href="https://www.google.com/maps/search/<?= urlencode($detection['lieu'] ?? 'Stade Municipal') ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-directions"></i> Itinéraire
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Registration stats card -->
            <div class="detection-info-card" id="registration">
                <div class="card-header">
                    <i class="fas fa-clipboard-list"></i> Inscriptions
                </div>
                <div class="detection-card-body">
                    <div class="progress-container">
                        <?php
                        // Calculer le pourcentage d'inscriptions
                        $maxParticipants = $detection['max_participants'] ?? 30;
                        
                        $percentage = min(round(($currentParticipants  / $maxParticipants) * 100), 100);
                        ?>
                        <div class="progress-label">
                            <div>Participants inscrits (<?= $currentParticipants ?>/<?= $maxParticipants ?>)</div>
                            <div><?= $percentage ?>%</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <?php if ($currentParticipants >= $maxParticipants): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i> Les inscriptions sont complètes. Vous pouvez toujours vous inscrire sur liste d'attente.
                    </div>
                    <?php else: ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> Il reste <strong><?= $maxParticipants - $currentParticipants ?> places</strong> disponibles pour cette détection.
                    </div>
                    <?php endif; ?>
                    
                    <div class="mt-4">
                        <h5 class="mb-3">Frais d'inscription</h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Inscription standard
                                <span class="badge bg-primary rounded-pill">0€</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Avec option vidéo
                                <span class="badge bg-primary rounded-pill">35€</span>
                            </li>
                        </ul>
                    </div>
                    <?php if(!$isAdmin): ?>
                    <div class="mt-4">
                        <a href="?module=detection&action=inscrire&id=<?= $detection['id'] ?? 0 ?>" class="btn btn-primary btn
                        <a href="?module=detection&action=inscrire&id=<?= $detection['id'] ?? 0 ?>" class="btn btn-primary btn-action px-4">
                            <i class="fas fa-plus-circle me-2"></i> S'inscrire maintenant
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Requirements and recommendations -->
            <div class="detection-info-card">
                <div class="card-header">
                    <i class="fas fa-clipboard-check"></i> Exigences et recommandations
                </div>
                <div class="detection-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Documents à apporter</h5>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <i class="fas fa-id-card text-primary me-2"></i> Pièce d'identité
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-file-medical text-primary me-2"></i> Certificat médical récent
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-file-signature text-primary me-2"></i> Autorisation parentale pour les mineurs
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-id-badge text-primary me-2"></i> Licence sportive (si disponible)
                                </li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="mb-3">Équipement</h5>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <i class="fas fa-tshirt text-primary me-2"></i> Tenue de football complète
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-shoe-prints text-primary me-2"></i> Chaussures adaptées au terrain
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-wine-bottle text-primary me-2"></i> Bouteille d'eau personnelle
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-shower text-primary me-2"></i> Kit de douche & serviette
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="alert alert-primary mt-4">
                        <i class="fas fa-info-circle me-2"></i> Il est recommandé d'arriver 45 minutes avant le début de la détection pour l'enregistrement et l'échauffement.
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right column -->
        <div class="col-lg-4">
            <!-- Club partenaire -->
            <div class="detection-info-card partner-club">
                <h5 class="mb-3">Club partenaire</h5>
                <?php if (!empty($detection['logo_club'])): ?>
                    <img src="<?= htmlspecialchars($detection['logo_club']) ?>" alt="Logo <?= htmlspecialchars($detection['partnerClub'] ?? 'Club partenaire') ?>" class="partner-logo">
                <?php else: ?>
                    <img src="/FootballProject/assets/images/default-club-logo.png" alt="Logo club par défaut" class="partner-logo">
                <?php endif; ?>
                <h6><?= htmlspecialchars($detection['partnerClub'] ?? 'Club Sportif') ?></h6>
                <p class="text-muted small">Un club professionnel à la recherche des meilleurs talents.</p>
            </div>
            
            <!-- Staff et coaches -->
            <div class="detection-info-card" id="coaches">
                <div class="card-header">
                    <i class="fas fa-user-tie"></i> Encadrement technique
                </div>
                <div class="detection-card-body">
                    <div class="coaches-container">
                        <div class="coach-card">
                            <img src="/FootballProject/assets/images/coach1.jpg" alt="Coach" class="coach-avatar">
                            <h6 class="coach-name">Jean Dupont</h6>
                            <p class="coach-role">Directeur technique</p>
                        </div>
                        <div class="coach-card">
                            <img src="/FootballProject/assets/images/coach2.jpg" alt="Coach" class="coach-avatar">
                            <h6 class="coach-name">Marie Leblanc</h6>
                            <p class="coach-role">Analyste vidéo</p>
                        </div>
                        <div class="coach-card">
                            <img src="/FootballProject/assets/images/coach3.jpg" alt="Coach" class="coach-avatar">
                            <h6 class="coach-name">Pierre Martin</h6>
                            <p class="coach-role">Scout</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Programme de la journée -->
            <div class="detection-info-card">
                <div class="card-header">
                    <i class="fas fa-tasks"></i> Programme de la journée
                </div>
                <div class="detection-card-body">
                    <div class="d-flex mb-3">
                        <div class="me-3 text-primary"><i class="fas fa-clipboard-check fa-lg"></i></div>
                        <div>
                            <h6 class="mb-1">09:00 - 09:45</h6>
                            <p class="mb-0 text-muted">Accueil et enregistrement</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="me-3 text-primary"><i class="fas fa-running fa-lg"></i></div>
                        <div>
                            <h6 class="mb-1">10:00 - 10:30</h6>
                            <p class="mb-0 text-muted">Échauffement et préparation</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="me-3 text-primary"><i class="fas fa-futbol fa-lg"></i></div>
                        <div>
                            <h6 class="mb-1">10:30 - 12:00</h6>
                            <p class="mb-0 text-muted">Tests techniques individuels</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="me-3 text-primary"><i class="fas fa-utensils fa-lg"></i></div>
                        <div>
                            <h6 class="mb-1">12:00 - 13:00</h6>
                            <p class="mb-0 text-muted">Pause déjeuner</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="me-3 text-primary"><i class="fas fa-users fa-lg"></i></div>
                        <div>
                            <h6 class="mb-1">13:00 - 14:30</h6>
                            <p class="mb-0 text-muted">Matchs à effectif réduit</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="me-3 text-primary"><i class="fas fa-trophy fa-lg"></i></div>
                        <div>
                            <h6 class="mb-1">14:30 - 16:00</h6>
                            <p class="mb-0 text-muted">Match complet</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3 text-primary"><i class="fas fa-comments fa-lg"></i></div>
                        <div>
                            <h6 class="mb-1">16:00 - 17:00</h6>
                            <p class="mb-0 text-muted">Debriefing et conclusion</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Témoignages -->
            <div class="detection-info-card">
                <div class="card-header">
                    <i class="fas fa-quote-left"></i> Témoignages
                </div>
                <div class="testimonials">
                    <div class="testimonial-item">
                        <p class="testimonial-text">"Cette détection a été un vrai tournant dans ma carrière. J'ai signé mon premier contrat pro suite à cet événement."</p>
                        <p class="testimonial-author">— Lucas M., 19 ans</p>
                    </div>
                    <div class="testimonial-item">
                        <p class="testimonial-text">"L'organisation était parfaite et les retours des coachs très constructifs, même si je n'ai pas été retenu."</p>
                        <p class="testimonial-author">— Emma D., 17 ans</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>



<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer définitivement cette détection ?</p>
                <p class="text-danger"><strong>Attention :</strong> Cette action est irréversible et supprimera toutes les inscriptions associées.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="?module=detection&action=delete" method="POST">
                    <input type="hidden" name="id" value="<?= $detection['id'] ?? 0 ?>">
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal pour annuler l'inscription -->
<?php if ($isJoueur && $inscription): ?>
<div class="modal fade" id="cancelInscriptionModal" tabindex="-1" aria-labelledby="cancelInscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelInscriptionModalLabel">Confirmer l'annulation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir annuler votre inscription à cette détection ?</p>
                <p class="text-danger"><strong>Attention :</strong> Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, garder mon inscription</button>
                <a href="?module=detection&action=cancel&id=<?= $detection['id'] ?? 0 ?>" class="btn btn-danger">
                    Oui, annuler mon inscription
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    // Initialisation de la carte
    document.addEventListener('DOMContentLoaded', function() {
        // Remplacer ces coordonnées par celles du lieu réel (à récupérer via une API de géocodage)
        const lat = 48.8566;
        const lng = 2.3522;
        
        const map = L.map('map').setView([lat, lng], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Ajouter un marqueur
        const marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup("<?= htmlspecialchars($detection['lieu'] ?? 'Stade Municipal') ?>").openPopup();
        
        // Gestion des toasts
        const toastElements = document.querySelectorAll('.toast');
        toastElements.forEach(toast => {
            setTimeout(() => {
                toast.style.display = 'none';
            }, 5000);
        });
        
        // Compte à rebours
        const updateCountdown = () => {
            const now = new Date();
            <?php if (isset($detection['date'])): ?>
            const targetDate = new Date("<?= $detection['date'] ?>");
            const diff = Math.max(targetDate - now, 0);
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            
            document.querySelectorAll('.countdown-number')[0].textContent = days;
            document.querySelectorAll('.countdown-number')[1].textContent = hours;
            document.querySelectorAll('.countdown-number')[2].textContent = minutes;
            document.querySelectorAll('.countdown-number')[3].textContent = seconds;
            <?php endif; ?>
        };
        
        // Mettre à jour le compte à rebours chaque seconde
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
    
    // Fonctionnalité de partage
    document.querySelector('.btn-share').addEventListener('click', function() {
        if (navigator.share) {
            navigator.share({
                title: '<?= htmlspecialchars($detection['name'] ?? 'Détection de football') ?>',
                text: 'Découvrez cette détection de football : <?= htmlspecialchars($detection['name'] ?? 'Détection de football') ?>',
                url: window.location.href,
            })
            .catch(error => console.log('Erreur lors du partage', error));
        } else {
            // Fallback pour les navigateurs qui ne supportent pas l'API Web Share
            const dummy = document.createElement('input');
            document.body.appendChild(dummy);
            dummy.value = window.location.href;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);
            
            alert('Lien copié dans le presse-papier !');
        }
    });
</script>

<?php
$content = ob_get_clean();
$title = "Détections disponibles | Football Academy";
require_once(__DIR__ . '/..//layout.php');
?>
