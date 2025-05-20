<?php
// Définir le titre de la page et la page active
$pageTitle = 'Créer un match';
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
            <h2 class="mb-0">Créer un match</h2>
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

            <form action="index.php?module=match&action=create" method="post" class="needs-validation" novalidate>
                
                <!-- Type de match -->
                <?php if (!isset($tournoiId)): ?>
                <div class="mb-3">
                    <label for="type_match" class="form-label">Type de match</label>
                    <select class="form-select" id="type_match" name="type_match">
                        <option value="tournoi" <?= ($typeMatch === 'tournoi') ? 'selected' : ''; ?>>Match de tournoi</option>
                        <option value="amical" <?= ($typeMatch === 'amical') ? 'selected' : ''; ?>>Match amical</option>
                    </select>
                </div>
                <?php else: ?>
                <input type="hidden" name="type_match" value="tournoi">
                <?php endif; ?>
                
                <!-- Tournoi - visible uniquement pour les matchs de tournoi -->
                <div class="mb-3" id="tournoi-container" style="<?= ($typeMatch === 'amical') ? 'display: none;' : ''; ?>">
                    <label for="tournoi_id" class="form-label">Tournoi</label>
                    <select class="form-select" id="tournoi_id" name="tournoi_id" <?= ($typeMatch === 'tournoi') ? 'required' : ''; ?> 
                            <?= isset($tournoiId) ? 'disabled' : ''; ?>>
                        <option value="">Sélectionner un tournoi</option>
                        <?php foreach ($tournois as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= (isset($tournoiId) && $tournoiId == $t['id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($t['nom']) ?> (<?= date('d/m/Y', strtotime($t['date_debut'])) ?> - <?= date('d/m/Y', strtotime($t['date_fin'])) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <?php if(isset($tournoiId)): ?>
                    <input type="hidden" name="tournoi_id" value="<?= $tournoiId ?>">
                    <?php endif; ?>
                    
                    <div class="invalid-feedback">Veuillez sélectionner un tournoi</div>
                </div>
                
                <div class="row">
                    <!-- Équipe 1 -->
                    <div class="col-md-6 mb-3">
                        <label for="equipe1_id" class="form-label">Équipe 1</label>
                        <select class="form-select" id="equipe1_id" name="equipe1_id" required>
                            <option value="">Sélectionner une équipe</option>
                            <?php foreach ($equipes as $equipe): ?>
                                <option value="<?= $equipe['id'] ?>"><?= htmlspecialchars($equipe['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner l'équipe 1</div>
                    </div>
                    
                    <!-- Équipe 2 -->
                    <div class="col-md-6 mb-3">
                        <label for="equipe2_id" class="form-label">Équipe 2</label>
                        <select class="form-select" id="equipe2_id" name="equipe2_id" required>
                            <option value="">Sélectionner une équipe</option>
                            <?php foreach ($equipes as $equipe): ?>
                                <option value="<?= $equipe['id'] ?>"><?= htmlspecialchars($equipe['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner l'équipe 2</div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Date du match -->
                    <div class="col-md-6 mb-3">
                        <label for="date_match" class="form-label">Date et heure du match</label>
                        <input type="datetime-local" class="form-control" id="date_match" name="date_match" required
                            <?php if(isset($tournoi)): ?>
                            min="<?= date('Y-m-d\TH:i', strtotime($tournoi['date_debut'])) ?>" 
                            max="<?= date('Y-m-d\TH:i', strtotime($tournoi['date_fin'])) ?>"
                            <?php endif; ?>>
                        <div class="invalid-feedback">Veuillez sélectionner la date et l'heure du match</div>
                    </div>
                    
                    <!-- Lieu -->
                    <div class="col-md-6 mb-3">
                        <label for="lieu_match" class="form-label">Lieu du match</label>
                        <input type="text" class="form-control" id="lieu_match" name="lieu_match" required>
                        <div class="invalid-feedback">Veuillez indiquer le lieu du match</div>
                    </div>
                </div>
                
                <!-- Phase - visible uniquement pour les matchs de tournoi -->
                <div class="mb-3" id="phase-container" style="<?= ($typeMatch === 'amical') ? 'display: none;' : ''; ?>">
                    <label for="phase" class="form-label">Phase</label>
                    <select class="form-select" id="phase" name="phase" <?= ($typeMatch === 'tournoi') ? 'required' : ''; ?>>
                        <option value="Phase de groupes">Phase de groupes</option>
                        <option value="Huitièmes de finale">Huitièmes de finale</option>
                        <option value="Quarts de finale">Quarts de finale</option>
                        <option value="Demi-finales">Demi-finales</option>
                        <option value="Petite finale">Petite finale</option>
                        <option value="Finale">Finale</option>
                    </select>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <?php if(isset($tournoiId)): ?>
                    <a href="index.php?module=tournoi&action=show&id=<?= $tournoiId ?>" class="btn btn-outline-secondary me-md-2">Annuler</a>
                    <?php else: ?>
                    <a href="index.php?module=match&action=index" class="btn btn-outline-secondary me-md-2">Annuler</a>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary">Créer le match</button>
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

// Empêcher de sélectionner la même équipe
document.addEventListener('DOMContentLoaded', function() {
    const equipe1Select = document.getElementById('equipe1_id');
    const equipe2Select = document.getElementById('equipe2_id');
    
    equipe1Select.addEventListener('change', function() {
        Array.from(equipe2Select.options).forEach(option => {
            if(option.value === equipe1Select.value && option.value !== '') {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });
    });
    
    equipe2Select.addEventListener('change', function() {
        Array.from(equipe1Select.options).forEach(option => {
            if(option.value === equipe2Select.value && option.value !== '') {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });
    });
    
    // Gérer l'affichage des champs en fonction du type de match
    const typeMatchSelect = document.getElementById('type_match');
    const tournoiContainer = document.getElementById('tournoi-container');
    const phaseContainer = document.getElementById('phase-container'); 
    const tournoiSelect = document.getElementById('tournoi_id');
    const phaseSelect = document.getElementById('phase');
    
    if(typeMatchSelect) {
        typeMatchSelect.addEventListener('change', function() {
            if(this.value === 'amical') {
                tournoiContainer.style.display = 'none';
                phaseContainer.style.display = 'none';
                tournoiSelect.removeAttribute('required');
                phaseSelect.removeAttribute('required');
            } else {
                tournoiContainer.style.display = 'block';
                phaseContainer.style.display = 'block';
                tournoiSelect.setAttribute('required', 'required');
                phaseSelect.setAttribute('required', 'required');
            }
        });
    }
});
</script>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>
