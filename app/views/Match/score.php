<?php
// Définir le titre de la page et la page active
$pageTitle = 'Saisir le score';
$currentPage = 'matchs';
$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    
// Si non admin, rediriger
if (!$isAdmin) {
    header('Location: index.php');
    exit();
}

// Capturer le contenu
ob_start();
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Saisir le score</h2>
        </div>

        <div class="card-body">
            <?php if (isset($_SESSION['form_errors'])): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($_SESSION['form_errors'] as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['form_errors']); ?>
            <?php endif; ?>

            <div class="text-center mb-4">
                <h3 class="text-primary"><?= htmlspecialchars($match['tournoi_nom']) ?></h3>
                <p class="text-muted">
                    <?= date('d/m/Y H:i', strtotime($match['date_match'])) ?> - 
                    <?= htmlspecialchars($match['lieu_match']) ?>
                </p>
            </div>

            <form action="index.php?module=match&action=score&id=<?= $match['id'] ?>" method="post" class="needs-validation" novalidate>
                <input type="hidden" name="redirect_to_tournoi" value="1">
                
                <div class="row align-items-center justify-content-center mb-4">
                    <div class="col-4 col-md-3 text-end">
                        <h5><?= htmlspecialchars($match['equipe1_nom']) ?></h5>
                    </div>
                    
                    <div class="col-3 col-md-2">
                        <input type="number" min="0" class="form-control form-control-lg text-center" 
                               id="score1" name="score1" value="<?= $match['score1'] ?? 0 ?>" required>
                    </div>
                    
                    <div class="col-2 col-md-1 text-center">
                        <span class="h4">-</span>
                    </div>
                    
                    <div class="col-3 col-md-2">
                        <input type="number" min="0" class="form-control form-control-lg text-center" 
                               id="score2" name="score2" value="<?= $match['score2'] ?? 0 ?>" required>
                    </div>
                    
                    <div class="col-4 col-md-3 text-start">
                        <h5><?= htmlspecialchars($match['equipe2_nom']) ?></h5>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="index.php?module=tournoi&action=show&id=<?= $match['tournoi_id'] ?>" class="btn btn-outline-secondary me-md-2">
                        <i class="fas fa-arrow-left"></i> Retour au tournoi
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer le score
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Validation des formulaires Bootstrap
(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
})()
</script>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>