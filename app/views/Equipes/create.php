<?php
// Définir le titre et la page active
$pageTitle = 'Créer une nouvelle équipe';
$currentPage = 'equipes';

// Capturer le contenu
ob_start();
?>

<!-- Fichier: /app/views/equipe/create.php -->
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Créer une nouvelle équipe</h3>
                </div>
                
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?module=equipe&action=store" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom de l'équipe <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nom" name="nom" required 
                                   value="<?= isset($formData['nom']) ? htmlspecialchars($formData['nom']) : '' ?>">
                            <div class="form-text">Entrez le nom officiel de l'équipe.</div>
                        </div>

                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Email de contact <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" required
                                   value="<?= isset($formData['contact_email']) ? htmlspecialchars($formData['contact_email']) : '' ?>">
                            <div class="form-text">Cet email sera utilisé pour les communications concernant l'équipe.</div>
                        </div>

                        <div class="mb-4">
                            <label for="logo" class="form-label">Logo de l'équipe</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                <label class="input-group-text" for="logo">Choisir</label>
                            </div>
                            <div class="form-text">Formats acceptés : JPG, PNG, GIF. Taille maximale : 2 Mo.</div>
                        </div>
                        
                        <div id="logoPreview" class="text-center mb-3 d-none">
                            <p>Aperçu du logo :</p>
                            <img id="imagePreview" src="#" alt="Aperçu du logo" class="img-thumbnail" style="max-height: 200px">
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="index.php?module=equipe&action=index" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer l'équipe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Script pour prévisualiser l'image avant upload
document.getElementById('logo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Vérifier si le fichier est une image
        if (!file.type.match('image.*')) {
            alert('Veuillez sélectionner une image.');
            return;
        }
        
        // Vérifier la taille (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('La taille du fichier doit être inférieure à 2 Mo.');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewContainer = document.getElementById('logoPreview');
            const preview = document.getElementById('imagePreview');
            preview.src = e.target.result;
            previewContainer.classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    }
});
</script>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>
