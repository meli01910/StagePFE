<?php include __DIR__ . '/../templates/header.php'; ?>
<?php 
// Au début de votre vue
$message = '';
$messageType = 'danger';
if (isset($_SESSION['flash'])) {
    $message = $_SESSION['flash']['message'];
    $messageType = $_SESSION['flash']['type'];
    unset($_SESSION['flash']); // Effacer après utilisation
}
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Connexion</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-<?= $messageType ?>"><?= $message ?></div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="mot_de_passe" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <p>Pas encore inscrit? <a href="index.php?module=utilisateur&action=inscription">S'inscrire</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
