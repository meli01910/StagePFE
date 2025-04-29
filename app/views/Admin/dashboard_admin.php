<?php

// S'assurer que l'utilisateur est un admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?module=auth&action=login');
    exit;
}





 include __DIR__ . '/../templates/header.php';
// Définir le titre et la page active
$pageTitle = "Dashboard Admin";
$currentPage = "dashboard";

// Capturer le contenu
ob_start();
?>

<div class="container-fluid">
    <h1 class="mb-4">Dashboard Administrateur</h1>

        <p class="text-muted">Bienvenue dans votre espace de gestion sportive</p>
        
        <!-- Statistiques principale -->
        <div class="row mb-4">
    
        <!-- Joueurs en attente -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Joueurs en attente</h6>
                
                            <small class="text-muted">Approbations en attente</small>
                        </div>
                        <div>
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
               <!-- Matchs à venir -->
               <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Matchs à venir</h6>
                            <h2 class="mb-0"><?= $matchsAVenir ?></h2>
                            <small class="text-muted">
                            
                            </small>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tournois actifs -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Tournois actifs</h6>
                                <small class="text-muted">Tournois en cours</small>
                        </div>
                        <div>
                            <i class="fas fa-trophy fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sessions de détection -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Détections à venir</h6>
                            <h2 class="mb-0"><?= $detectionsCount ?></h2>
                            <small class="text-muted">Sessions planifiées</small>
                        </div>
                        <div>
                            <i class="fas fa-search fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenus principaux -->
    <div class="row">
        <!-- Joueurs en attente d'approbation -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Joueurs récents</h5>
                            <small class="text-muted">Gérez les demandes d'inscription et les approbations</small>
                        </div>
                        <a href="index.php?module=admin&action=joueurs_attente" class="btn btn-sm btn-outline-primary">Voir tout</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($joueursEnAttente)): ?>
                        <div class="p-4">
                            <p class="text-muted mb-0">Aucun joueur en attente d'approbation.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($joueursEnAttente as $joueur): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light text-center mr-3" style="width: 40px; height: 40px; line-height: 40px;">
                                                    <?= strtoupper(substr($joueur['prenom'], 0, 1) . substr($joueur['nom'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($joueur['prenom'] . ' ' . $joueur['nom']) ?></h6>
                                                    <small class="text-muted"><?= htmlspecialchars($joueur['poste']) ?></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="badge badge-warning mr-2">En attente</span>
                                            <a href="index.php?module=admin&action=voir_justificatif&id=<?= $joueur['id'] ?>" 
                                               class="btn btn-sm btn-info mr-1" 
                                               title="Voir justificatif">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?module=admin&action=approuver&id=<?= $joueur['id'] ?>" 
                                               class="btn btn-sm btn-success mr-1" 
                                               title="Approuver">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="index.php?module=admin&action=refuser&id=<?= $joueur['id'] ?>" 
                                               class="btn btn-sm btn-danger" 
                                               title="Refuser"
                                               onclick="return confirm('Êtes-vous sûr de vouloir refuser ce joueur ?');">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Matchs à venir -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Prochains matchs</h5>
                            <small class="text-muted">Planifiez et gérez vos matchs</small>
                        </div>
                        <a href="index.php?module=match&action=create" class="btn btn-sm btn-outline-primary">Créer match</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($prochainMatchs)): ?>
                        <div class="p-4">
                            <p class="text-muted mb-0">Aucun match à venir.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($prochainMatchs as $match): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">
                                            <?= htmlspecialchars($match['equipe1_nom']) ?> vs <?= htmlspecialchars($match['equipe2_nom']) ?>
                                        </h6>
                                        <?php 
                                        $now = new DateTime();
                                        $matchDate = new DateTime($match['date_match']);
                                        
                                        if (isset($match['score_equipe1']) && isset($match['score_equipe2'])) {
                                            $status = "Terminé";
                                            $statusClass = "success";
                                        } elseif ($matchDate < $now) {
                                            $status = "En cours";
                                            $statusClass = "warning";
                                        } else {
                                            $status = "À venir";
                                            $statusClass = "primary";
                                        }
                                        ?>
                                        <span class="badge badge-<?= $statusClass ?>"><?= $status ?></span>
                                    </div>
                                    <div>
                                        <small class="text-muted">
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            <?= date('d/m/Y à H:i', strtotime($match['date_match'])) ?>
                                        </small>
                                    </div>
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            <?= htmlspecialchars($match['terrain'] ?? 'Lieu non défini') ?>
                                        </small>
                                    </div>
                                    <?php if ($status == "À venir" || $status == "En cours"): ?>
                                        <div class="mt-2">
                                            <a href="index.php?module=match&action=score&id=<?= $match['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                Saisir le score
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tournois et Sessions de détection -->
    <div class="row">
        <!-- Liste des tournois -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Tournois</h5>
                            <small class="text-muted">Gérez vos tournois et compétitions</small>
                        </div>
                        <a href="index.php?module=tournoi&action=create" class="btn btn-sm btn-outline-primary">Créer tournoi</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($tournoisActifs)): ?>
                        <p class="text-muted">Aucun tournoi actif.</p>
                    <?php else: ?>
                        <?php foreach ($tournoisActifs as $tournoi): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">
                                        <i class="fas fa-trophy text-warning mr-2"></i>
                                        <?= htmlspecialchars($tournoi['nom']) ?>
                                    </h6>
                                    <?php 
                                    $now = new DateTime();
                                    $dateDebut = new DateTime($tournoi['date_debut']);
                                    $dateFin = new DateTime($tournoi['date_fin']);
                                    
                                    if ($now < $dateDebut) {
                                        $status = "À venir";
                                        $statusClass = "primary";
                                        $progress = 0;
                                    } elseif ($now > $dateFin) {
                                        $status = "Terminé";
                                        $statusClass = "secondary";
                                        $progress = 100;
                                    } else {
                                        $status = "En cours";
                                        $statusClass = "success";
                                        
                                        // Calcul du progrès
                                        $totalDays = $dateDebut->diff($dateFin)->days + 1;
                                        $daysPassed = $dateDebut->diff($now)->days + 1;
                                        $progress = min(round(($daysPassed / $totalDays) * 100), 100);
                                    }
                                    ?>
                                    <span class="badge badge-<?= $statusClass ?>"><?= $status ?></span>
                                </div>
                                <div>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        <?= date('d/m/Y', strtotime($tournoi['date_debut'])) ?> - 
                                        <?= date('d/m/Y', strtotime($tournoi['date_fin'])) ?>
                                    </small>
                                </div>
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-users mr-1"></i>
                                        <?= $tournoi['equipes_count'] ?? '?' ?> équipes
                                    </small>
                                </div>
                                
                                <?php if ($status != "À venir"): ?>
                                <div class="progress mt-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" 
                                         role="progressbar" 
                                         style="width: <?= $progress ?>%" 
                                         aria-valuenow="<?= $progress ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted"><?= $progress ?>% complété</small>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
           
   <!-- Sessions de détection -->
<div class="col-lg-6 mb-4">
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Sessions de détection</h5>
                    <small class="text-muted">Gérez vos séances de recrutement</small>
                </div>
                <a href="index.php?module=detection&action=create" class="btn btn-sm btn-outline-primary">Créer session</a>
            </div>
        </div>
        <div class="card-body">
        <?php if (empty($prochainDetections)): ?>
    <p class="text-muted">Aucune session de détection trouvée.</p>
<?php else: ?>
    <div class="list-group">
        <?php foreach ($prochainDetections as $detection): ?>
            <?php
            // Initialiser les styles de badge
            $badgeClass = 'bg-secondary';
            $statusText = 'Indéterminé';
            
            if (isset($detection['status'])) {
                switch ($detection['status']) {
                    case 'planned':
                        $badgeClass = 'bg-primary';
                        $statusText = 'Upcoming';
                        break;
                    case 'ongoing':
                        $badgeClass = 'bg-warning';
                        $statusText = 'En cours';
                        break;
                    case 'completed':
                        $badgeClass = 'bg-success';
                        $statusText = 'Completed';
                        break;
                    default:
                        // Utiliser les valeurs par défaut
                }
            }
            ?>

            <div class="list-group-item border-0 mb-3" style="border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="d-flex justify-content-between align-items-start">
                    <h5 class="mb-2 font-weight-bold">
                        <?= isset($detection['name']) ? htmlspecialchars($detection['name']) : 'Session sans nom' ?>
                    </h5>
                    
                    <span class="badge rounded-pill <?= $badgeClass ?> text-white px-3 py-2" style="border-radius: 20px;">
                        <?= $statusText ?>
                    </span>
                </div>
                
                <div class="mb-1">
                    <i class="far fa-calendar-alt text-muted mr-2"></i>
                    <?php 
                    if (isset($detection['date']) && $detection['date']) {
                        echo date('j M Y, H:i', strtotime($detection['date']));
                    } else {
                        echo "Date non définie";
                    }
                    ?>
                </div>
                
                <div class="mb-1">
                    <i class="fas fa-map-marker-alt text-muted mr-2"></i>
                    <?= isset($detection['location']) ? htmlspecialchars($detection['location']) : 'Lieu non défini' ?>
                </div>

                <?php if(isset($detection['current_participants']) && isset($detection['max_participants'])): ?>
                <div class="mt-2">
                    <i class="fas fa-users text-muted mr-2"></i>
                    <?= $detection['current_participants'] ?>/<?= $detection['max_participants'] ?> 
                    <?php if($detection['current_participants'] >= $detection['max_participants']): ?>
                        <span class="text-danger">(Full)</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


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



