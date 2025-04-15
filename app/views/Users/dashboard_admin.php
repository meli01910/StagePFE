<?php
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
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
    <h2>Bienvenue Admin, <?= htmlspecialchars($_SESSION['nom']) ?> 👑</h2>
    <ul class="list-group mt-4">
        <li class="list-group-item"><a href="index.php?module=tournoi&action=index">Gérer les tournois</a></li>
        <li class="list-group-item"><a href="index.php?module=equipe&action=index">Gérer les équipes</a></li>
        <li class="list-group-item"><a href="logout.php">Déconnexion</a></li>
    </ul>
</div>
</body>
</html>
