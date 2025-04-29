<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FootballStar - Détections et tournois de football</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  
    
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                       FootballStar
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link <?= !isset($_GET['module']) ? 'active' : '' ?>" href="index.php">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isset($_GET['module']) && $_GET['module'] === 'detection' ? 'active' : '' ?>" href="index.php?module=detection&action=list">Détections</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isset($_GET['module']) && $_GET['module'] === 'tournoi' ? 'active' : '' ?>" href="index.php?module=tournoi&action=list">Tournois</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isset($_GET['module']) && $_GET['module'] === 'tournoi' ? 'active' : '' ?>" href="index.php?module=admin&action=joueur_attente">Joueurs</a>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <?php if(isset($_SESSION['user'])): ?>
                         
                            <a href="index.php?module=auth&action=logout" class="btn btn-danger">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </a>
                        <?php else: ?>
                            <a href="index.php?module=auth&action=login" class="btn btn-outline-light me-2">Connexion</a>
                            <a href="index.php?module=auth&action=register" class="btn btn-warning">Inscription</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
