<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php?action=login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Bienvenue Admin 👑</h1>
    <p>Tu as accès aux fonctionnalités administratives du site.</p>
    <a href="index.php?action=logout" class="btn btn-danger">Déconnexion</a>
</div>
<div class="card">
    <div class="card-header">Joueurs en attente</div>
    <div class="card-body">
        <h5 class="card-title"><?= $stats['joueurs_attente'] ?> joueurs</h5>
        <p class="card-text">Joueurs en attente d'approbation.</p>
        <a href="index.php?module=admin&action=joueurs_attente" class="btn btn-primary">Voir les joueurs</a>
    </div>
</div>
</body>
</html>
