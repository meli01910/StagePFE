<?php
// Vérifier si $currentPage est défini, sinon définir une valeur par défaut
if (!isset($currentPage)) {
    $currentPage = '';
}

// Fonction pour vérifier si un menu est actif
function isMenuActive($menuName) {
    global $currentPage;
    return $currentPage === $menuName ? 'text-primary fw-bold' : 'text-dark';
}
?>

<div class="admin-sidebar">
    <!-- En-tête -->
    <div class="p-3 border-bottom d-flex align-items-center">
        <div class="bg-primary text-white d-flex align-items-center justify-content-center" 
             style="width: 30px; height: 30px; border-radius: 6px; margin-right: 10px;">
            <span>A</span>
        </div>
        <span class="fw-bold">Admin Arena</span>
    </div>
    
    <!-- Menu principal -->
    <div class="p-3">
        <small class="text-muted text-uppercase">Main Menu</small>
        
        <ul class="nav flex-column mt-2">
            <li class="nav-item">
                <a href="index.php?module=utilisateur&action=dashboard" class="nav-link px-0 py-2 <?= isMenuActive('dashboard') ?>">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?module=utilisateur&action=index" class="nav-link px-0 py-2 <?= isMenuActive('players') ?>">
                    <i class="fas fa-users me-2"></i> Joueurs
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?module=utilisateur&action=liste_equipes" class="nav-link px-0 py-2 <?= isMenuActive('equipes') ?>">
                    <i class="fas fa-users me-2"></i> Equipes
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?module=utilisateur&action=liste_matches" class="nav-link px-0 py-2 <?= isMenuActive('matches') ?>">
                    <i class="fas fa-futbol me-2"></i> Matches
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?module=admin&action=liste_tournois" class="nav-link px-0 py-2 <?= isMenuActive('tournaments') ?>">
                    <i class="fas fa-trophy me-2"></i> Tournois
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?module=admin&action=detections" class="nav-link px-0 py-2 <?= isMenuActive('detections') ?>">
                    <i class="fas fa-search me-2"></i> Détections
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Menu compte -->
    <div class="p-3 mt-3">
        <small class="text-muted text-uppercase">Account</small>
        
        <ul class="nav flex-column mt-2">
            <li class="nav-item">
                <a href="index.php?module=users&action=settings" class="nav-link px-0 py-2 <?= isMenuActive('settings') ?>">
                    <i class="fas fa-cog me-2"></i> Settings
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?module=auth&action=logout" class="nav-link px-0 py-2 text-dark">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>
