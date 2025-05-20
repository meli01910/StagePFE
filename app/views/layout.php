<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Football Academy' ?></title>
    
     
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/FootballProject/public/assets/css/layout.css">
<!-- Styles spécifiques aux pages -->
    <link rel="stylesheet" href="/FootballProject/public/assets/css/style.css"> 

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    

</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-left">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <img src="/FootballProject/public/assets/images/logo_academie.png" alt="Football Academy" class="brand-logo">
            <h1 class="brand-text">Football Academy</h1>
        </div>
        
        <div class="header-right">
            <div class="dropdown">
                <button class="dropdown-toggle" id="notificationsDropdown" onclick="toggleDropdown('notificationsMenu')">
                    <i class="fas fa-bell"></i>
                    <span class="badge badge-danger">3</span>
                </button>
                <div class="dropdown-menu" id="notificationsMenu">
                    <div class="dropdown-header">Notifications</div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-calendar-check me-2 text-success"></i>
                        Nouvelle détection programmée
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user-plus me-2 text-primary"></i>
                        5 nouveaux joueurs inscrits
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-exclamation-circle me-2 text-warning"></i>
                        Rappel: détection demain à 14h
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-center" href="#">Voir toutes les notifications</a>
                </div>
            </div>
            
            <div class="dropdown">
                <button class="dropdown-toggle" id="userDropdown" onclick="toggleDropdown('userMenu')">
                    <?php if(isset($_SESSION['user'])): ?>
                        <span><?= htmlspecialchars($_SESSION['user']['prenom'] ?? 'Utilisateur') ?></span>
                    <?php else: ?>
                        <span>Invité</span>
                    <?php endif; ?>
                    <i class="fas fa-chevron-down ms-1" style="font-size: 0.8rem;"></i>
                </button>
                <div class="dropdown-menu" id="userMenu">
                    <?php if(isset($_SESSION['user'])): ?>
                        <a class="dropdown-item" href="?module=user&action=profile">
                            <i class="fas fa-user me-2"></i>Mon profil
                        </a>
                        <a class="dropdown-item" href="?module=user&action=settings">
                            <i class="fas fa-cog me-2"></i>Paramètres
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="?module=auth&action=logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                        </a>
                    <?php else: ?>
                        <a class="dropdown-item" href="?module=auth&action=login">
                            <i class="fas fa-sign-in-alt me-2"></i>Connexion
                        </a>
                        <a class="dropdown-item" href="?module=auth&action=register">
                            <i class="fas fa-user-plus me-2"></i>Inscription
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Sidebar overlay (for mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <?php if(isset($_SESSION['user']) && isset($_SESSION['user']['photo'])): ?>
                <img src="<?= $_SESSION['user']['photo'] ?>" alt="Photo de profil" class="user-img">
            <?php else: ?>
                <div class="user-profile">
                    <?php if(isset($_SESSION['user'])): ?>
                        <?= strtoupper(substr($_SESSION['user']['prenom'] ?? 'U', 0, 1)) ?>
                    <?php else: ?>
                        <i class="fas fa-user"></i>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <h5 class="user-name">
                <?php if(isset($_SESSION['user'])): ?>
                    <?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?>
                <?php else: ?>
                    Invité
                <?php endif; ?>
            </h5>
            
            <p class="user-role">
                <?php 
                    $role = '';
                    if(isset($_SESSION['user'])) {
                        $role = $_SESSION['user']['role'] === 'admin' ? 'Administrateur' : 
                               ($_SESSION['user']['role'] === 'coach' ? 'Coach' : 'Joueur');
                    } else {
                        $role = 'Non connecté';
                    }
                    echo $role;
                ?>
            </p>
        </div>
        
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link <?= (($_GET['module'] ?? '') === 'dashboard') ? 'active' : '' ?>" href="?module=dashboard&action=index">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (($_GET['module'] ?? '') === 'detection') ? 'active' : '' ?>" href="?module=detection&action=index">
                    <i class="fas fa-search"></i>
                    <span>Détections</span>
                </a>
            </li>
              <li class="nav-item">
                <a class="nav-link <?= (($_GET['module'] ?? '') === 'tournoi') ? 'active' : '' ?>" href="?module=tournoi&action=index">
                    <i class="fas fa-trophy"></i>
                    <span>Tournois</span>
                </a>
            </li>
             <li class="nav-item">
                <a class="nav-link <?= (($_GET['module'] ?? '') === 'match') ? 'active' : '' ?>" href="?module=match&action=index">
                    <i class="fas fa-futbol"></i>
                    <span>Matchs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (($_GET['module'] ?? '') === 'equipe') ? 'active' : '' ?>" href="?module=equipe&action=index">
                    <i class="fas fa-users"></i>
                    <span>Équipes</span>
                </a>
            </li>
           
            <li class="nav-item">
                <a class="nav-link <?= (($_GET['module'] ?? '') === 'joueur') ? 'active' : '' ?>" href="?module=utilisateur&action=index">
                    <i class="fas fa-running"></i>
                    <span>Joueurs</span>
                </a>
            </li>
            
             <?php if(isset($_SESSION['user']) && ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'coach')): ?>
          
            <li class="nav-item">
                <a class="nav-link <?= (($_GET['module'] ?? '') === 'statistique') ? 'active' : '' ?>" href="?module=statistique&action=index">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistiques</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (($_GET['module'] ?? '') === 'rapport') ? 'active' : '' ?>" href="?module=rapport&action=index">
                    <i class="fas fa-file-alt"></i>
                    <span>Rapports</span>
                </a>
            </li>
            <?php endif; ?>
              <li class="nav-item">
                <a class="nav-link <?= (($_GET['module'] ?? '') === 'parametre') ? 'active' : '' ?>" href="?module=parametre&action=index">
                    <i class="fas fa-cogs"></i>
                    <span>Paramètres</span>
                </a>
            </li>
              <li class="nav-item">
                <a class="nav-link <?= (($_GET['module'] ?? '') === 'parametre') ? 'active' : '' ?>" href="?module=auth&action=logout">
                    <i class="fas fa-cogs"></i>
                    <span>Déconnexion</span>
                </a>
            </li>
          
        </ul>
        
        <!-- Si vous voulez un pied de page de la sidebar -->
        <!-- 
        <div class="sidebar-footer">
            <p>Version 1.0.0</p>
        </div>
        -->
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Contenu dynamique à injecter ici -->
        <?= $content ?? '' ?>
        
        <!-- Footer -->
        <footer class="footer mt-4">
            <div class="container">
                <p class="mb-0">&copy; <?= date('Y') ?> Football Academy - Tous droits réservés</p>
            </div>
        </footer>
    </main>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Toggle sidebar on button click
            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth > 992) {
                    // Desktop behavior
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                } else {
                    // Mobile behavior
                    sidebar.classList.toggle('mobile-visible');
                    sidebarOverlay.classList.toggle('show');
                }
            });
            
            // Close sidebar when clicking on overlay (mobile)
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-visible');
                sidebarOverlay.classList.remove('show');
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    sidebar.classList.remove('mobile-visible');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });
        
        // Function to toggle dropdowns
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            
            // Close all other open dropdowns
            document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                if (menu.id !== id) {
                    menu.classList.remove('show');
                }
            });
            
            // Toggle the specified dropdown
            dropdown.classList.toggle('show');
        }
        
        // Close dropdowns when clicking outside
        window.addEventListener('click', function(event) {
            if (!event.target.matches('.dropdown-toggle') && !event.target.closest('.dropdown-menu')) {
                document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                    menu.classList.remove('show');
                });
            }
        });
    </script>
    
    <!-- Script spécifique à la page si nécessaire -->
    <?= $scripts ?? '' ?>
</body>
</html>
