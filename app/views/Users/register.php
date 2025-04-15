<!-- register.php -->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Inscription</h2>
    <form method="POST" action="register_process.php">
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" class="form-control" required>
        </div>
        <div class="form-check">
            <input type="checkbox" name="is_admin" value="1" class="form-check-input" id="adminCheck">
            <label for="adminCheck" class="form-check-label">S'inscrire en tant qu'admin</label>
        </div>
        <button type="submit" class="btn btn-primary mt-3">S'inscrire</button>
    </form>
    <p class="mt-3">Déjà inscrit ? <a href="login.php">Connexion</a></p>
</div>
</body>
</html>
