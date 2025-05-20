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
?><div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h1 class="h3 mb-0">Créer un match pour le tournoi "<?= htmlspecialchars($tournoi['nom']) ?>"</h1>
        </div>
        
        <div class="card-body">
            <?php if (count($equipes) < 2): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> Il faut au moins 2 équipes pour créer un match.
                </div>
                <a href="index.php?module=tournoi&action=addEquipe&id=<?= $tournoi['id'] ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter des équipes
                </a>
            <?php else: ?>
                <form method="post" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="equipe1_id" class="form-label">Équipe 1</label>
                                <select name="equipe1_id" id="equipe1_id" class="form-select" required>
                                    <option value="">Sélectionner une équipe</option>
                                    <?php foreach ($equipes as $equipe): ?>
                                        <option value="<?= $equipe['id'] ?>">
                                            <?= htmlspecialchars($equipe['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="equipe2_id" class="form-label">Équipe 2</label>
                                <select name="equipe2_id" id="equipe2_id" class="form-select" required>
                                    <option value="">Sélectionner une équipe</option>
                                    <?php foreach ($equipes as $equipe): ?>
                                        <option value="<?= $equipe['id'] ?>">
                                            <?= htmlspecialchars($equipe['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_match" class="form-label">Date et heure du match</label>
                                <input type="datetime-local" name="date_match" id="date_match" 
                                       class="form-control" required
                                       min="<?= date('Y-m-d\TH:i', strtotime($tournoi['date_debut'])) ?>"
                                       max="<?= date('Y-m-d\TH:i', strtotime($tournoi['date_fin'])) ?>">
                                <div class="form-text">
                                    Le match doit se dérouler pendant la période du tournoi 
                                    (<?= date('d/m/Y', strtotime($tournoi['date_debut'])) ?> au 
                                    <?= date('d/m/Y', strtotime($tournoi['date_fin'])) ?>)
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lieu_match" class="form-label">Lieu du match</label>
                                <input type="text" name="lieu_match" id="lieu_match" 
                                       class="form-control" 
                                       value="<?= htmlspecialchars($tournoi['lieu']) ?>">
                                <div class="form-text">Si différent du lieu du tournoi</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phase" class="form-label">Phase du tournoi</label>
                        <input type="text" name="phase" id="phase" class="form-control" 
                               placeholder="Ex: Phase de groupes, Quarts de finale, etc.">
                        <div class="form-text">
                            Indiquez la phase du tournoi pour mieux organiser les matchs
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus-circle me-1"></i> Créer le match
                        </button>
                        <a href="index.php?module=tournoi&action=organiser&id=<?= $tournoi['id'] ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Annuler
                        </a>
                    </div>
                </form>
                
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Empêcher de sélectionner la même équipe deux fois
                    const equipe1Select = document.getElementById('equipe1_id');
                    const equipe2Select = document.getElementById('equipe2_id');
                    
                    equipe1Select.addEventListener('change', function() {
                        const selectedValue = this.value;
                        
                        // Réactiver toutes les options dans equipe2
                        Array.from(equipe2Select.options).forEach(opt => {
                            opt.disabled = false;
                        });
                        
                        // Désactiver l'option sélectionnée dans equipe1
                        if (selectedValue) {
                            const optionToDisable = equipe2Select.querySelector(`option[value="${selectedValue}"]`);
                            if (optionToDisable) {
                                optionToDisable.disabled = true;
                            }
                            
                            // Si l'équipe2 a la même valeur, la réinitialiser
                            if (equipe2Select.value === selectedValue) {
                                equipe2Select.value = '';
                            }
                        }
                    });
                    
                    equipe2Select.addEventListener('change', function() {
                        const selectedValue = this.value;
                        
                        // Réactiver toutes les options dans equipe1
                        Array.from(equipe1Select.options).forEach(opt => {
                            opt.disabled = false;
                        });
                        
                        // Désactiver l'option sélectionnée dans equipe2
                        if (selectedValue) {
                            const optionToDisable = equipe1Select.querySelector(`option[value="${selectedValue}"]`);
                            if (optionToDisable) {
                                optionToDisable.disabled = true;
                            }
                            
                            // Si l'équipe1 a la même valeur, la réinitialiser
                            if (equipe1Select.value === selectedValue) {
                                equipe1Select.value = '';
                            }
                        }
                    });
                });
                </script>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>
