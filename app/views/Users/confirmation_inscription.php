<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h2 class="mb-0">Inscription réussie !</h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h3 class="text-center mb-4">Merci, <?= htmlspecialchars($userData['prenom']) ?> !</h3>
                    
                    <div class="alert alert-info">
                        <p><strong>Votre inscription a bien été prise en compte.</strong></p>
                        <p>Votre compte est en attente de validation par un administrateur. Vous recevrez un email à l'adresse <strong><?= htmlspecialchars($userData['email']) ?></strong> lorsque votre compte sera validé.</p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <p><strong>Important :</strong> Pour compléter votre profil et participer à nos événements, vous devrez attendre la validation de votre compte.</p>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
                        <a href="index.php?module=auth&action=login" class="btn btn-outline-primary ms-2">Se connecter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . '/../templates/footer.php'; ?>
