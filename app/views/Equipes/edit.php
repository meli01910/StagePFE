
<?php
ob_start();
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-futbol me-2"></i> modification de l'equipe  : <?= htmlspecialchars($equipe['nom'] ?? '') ?></h1>
        <p class="lead">Complétez ce formulaire pour modifier une détection de nouveaux talents</p>
    </div>
</div>

<div class="container ">
    
    <div class="card">
        <div class="card-header-form">
            <h2 class="card-title"><i class="fas fa-clipboard-list"></i> Formulaire de modification</h2>
        </div>
        <div class="card-body">
                 <form method="POST" action="index.php?module=equipe&action=edit&id=<?= $equipe['id'] ?>" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $equipe['id'] ?>">

                <!-- Informations générales -->
                <div class="form-section">
                    <h4><i class="fas fa-info-circle me-2"></i>Informations générales</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nom" class="form-label-required">Nom de l'equipe'</label>
                                <input type="text" id="nom" name="nom" class="form-control" required 
                                       placeholder="Ex: Paris saint Germain" 
                                       value="<?= htmlspecialchars($equipe['nom'] ?? '') ?>">
                                <small class="help-text">Donnez un nom clair </small>
                            </div>
                        </div>

                        <div class="col-md-6">
                             <div class="form-group">
                                <label for="contact_email" class="form-label-required">l'adresse mail  de l'equipe'</label>
                                <input type="text" id="contact_email" name="contact_email" class="form-control" required 
                                       placeholder="Ex: psg@gmail.com" 
                                       value="<?= htmlspecialchars($equipe['contact_email'] ?? '') ?>">
                                <small class="help-text">Donnez une adresse mail correcte </small>
                            </div>
                        </div>
                    </div>
                    
                           <div class="form-group mt-3">
                        <label for="logo">Logo du club partenaire</label>
                        
                        <?php if (isset($equipe['logo']) && !empty($equipe['logo'])): ?>
                        <div class="mb-3">
                            <p><strong>Logo actuel :</strong></p>
                            <img src="<?= $equipe['logo'] ?>" alt="Logo club" style="max-height: 100px; max-width: 200px;">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo" value="1">
                                <label class="form-check-label" for="remove_logo">
                                    Supprimer ce logo
                                </label>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="file-upload-container" onclick="document.getElementById('logo').click();">
                            <div class="file-upload-message">
                                <i class="fas fa-upload me-2"></i>Cliquez ou glissez-déposez une image pour <?= isset($equipe['logo']) ? 'remplacer' : 'ajouter' ?> le logo
                            </div>
                            <input type="file" id="logo" name="logo" class="form-control" accept="image/*" style="display: none;">
                            <div id="file-name"></div>
                        </div>
                        <small class="help-text">Format recommandé : JPG, PNG ou GIF (max 2Mo)</small>
                    </div>
                </div>
                
               
                                    
                       
                <!-- Statistiques d'inscription (lecture seule) -->
                       
                <div class="action-buttons">
                    <a href="?module=equipe&action=show&id=<?= $equipe['id'] ?>" class="btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Afficher le nom du fichier lors de la sélection
    document.getElementById('logo').addEventListener('change', function() {
        const fileNameDiv = document.getElementById('file-name');
        if (this.files.length > 0) {
            fileNameDiv.innerHTML = '<div class="mt-2 alert alert-success p-2"><i class="fas fa-check-circle me-2"></i>' + this.files[0].name + '</div>';
            document.querySelector('.file-upload-container').style.borderColor = '#28a745';
        } else {
            fileNameDiv.innerHTML = '';
            document.querySelector('.file-upload-container').style.borderColor = '#ced4da';
        }
    });
    
    // Permettre le glisser-déposer pour l'upload du logo
    const dropArea = document.querySelector('.file-upload-container');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropArea.style.borderColor = '#0056b3';
        dropArea.style.backgroundColor = '#f0f7ff';
    }
    
    function unhighlight() {
        dropArea.style.borderColor = '#ced4da';
        dropArea.style.backgroundColor = '#fff';
    }
    
    dropArea.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        const fileInput = document.getElementById('logo');
        
        fileInput.files = files;
        
        // Déclencher l'événement change manuellement
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    }
</script>

<?php
$content = ob_get_clean();
$title = "Modification d'une détection | Football Academy";
require_once(__DIR__ . '/..//layout.php');
?>
