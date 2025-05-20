
<?php
ob_start();
?>


<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-futbol me-2"></i> Création d'une nouvelle détection</h1>
        <p class="lead">Complétez ce formulaire pour organiser une détection de nouveaux talents</p>
    </div>
</div>

<div class="container">

    
    <?php if (isset($successMessage)): ?>
    <div class="success-message mb-4">
        <i class="fas fa-check-circle"></i>
        <?php echo $successMessage; ?>
    </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="error-message mb-4">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header-form">
            <h2 class="card-title"><i class="fas fa-clipboard-list"></i> Formulaire de création</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="?module=detection&action=create" enctype="multipart/form-data" id="createDetectionForm">
                
                <!-- Informations générales -->
                <div class="form-section">
                    <h4><i class="fas fa-info-circle me-2"></i>Informations générales</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label-required">Nom de la détection</label>
                                <input type="text" id="name" name="name" class="form-control" required 
                                       placeholder="Ex: Détection U15 Paris" 
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                                <small class="help-text">Donnez un titre clair qui indique la nature de l'événement</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categorie" class="form-label-required">Catégorie d'âge</label>
                                <select id="categorie" name="categorie" class="form-select" required>
                                    <option value="" disabled <?php echo !isset($_POST['categorie']) ? 'selected' : ''; ?>>Sélectionner une catégorie</option>
                                    <option value="U11" <?php echo (isset($_POST['categorie']) && $_POST['categorie'] == 'U11') ? 'selected' : ''; ?>>U11 (9-10 ans)</option>
                                    <option value="U13" <?php echo (isset($_POST['categorie']) && $_POST['categorie'] == 'U13') ? 'selected' : ''; ?>>U13 (11-12 ans)</option>
                                    <option value="U15" <?php echo (isset($_POST['categorie']) && $_POST['categorie'] == 'U15') ? 'selected' : ''; ?>>U15 (13-14 ans)</option>
                                    <option value="U17" <?php echo (isset($_POST['categorie']) && $_POST['categorie'] == 'U17') ? 'selected' : ''; ?>>U17 (15-16 ans)</option>
                                    <option value="U19" <?php echo (isset($_POST['categorie']) && $_POST['categorie'] == 'U19') ? 'selected' : ''; ?>>U19 (17-18 ans)</option>
                                    <option value="Seniors" <?php echo (isset($_POST['categorie']) && $_POST['categorie'] == 'Seniors') ? 'selected' : ''; ?>>Seniors (18+ ans)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="description">Description de la détection</label>
                        <textarea id="description" name="description" class="form-control" 
                                  placeholder="Décrivez les objectifs, critères de sélection et déroulement de la détection..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                        <small class="help-text">Informations détaillées sur l'événement, les compétences recherchées, etc.</small>
                    </div>
                </div>
                
                <!-- Date et lieu -->
                <div class="form-section">
                    <h4><i class="fas fa-calendar-alt me-2"></i>Date et lieu</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date" class="form-label-required">Date de la détection</label>
                                <input type="date" id="date" name="date" class="form-control" required
                                       value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : date('Y-m-d', strtotime('+14 days')); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="heure" class="form-label-required">Heure de la détection</label>
                                <input type="time" id="heure" name="heure" class="form-control" required
                                       value="<?php echo isset($_POST['heure']) ? htmlspecialchars($_POST['heure']) : '14:00'; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_fin_inscription">Date limite d'inscription</label>
                                <input type="date" id="date_fin_inscription" name="date_fin_inscription" class="form-control"
                                       value="<?php echo isset($_POST['date_fin_inscription']) ? htmlspecialchars($_POST['date_fin_inscription']) : date('Y-m-d', strtotime('+7 days')); ?>">
                                <small class="help-text">Laissez vide pour permettre les inscriptions jusqu'à la date de l'événement</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="lieu" class="form-label-required">Lieu de la détection</label>
                        <input type="text" id="lieu" name="lieu" class="form-control" required
                               placeholder="Ex: Stade Municipal, 123 Rue du Sport, 75001 Paris"
                               value="<?php echo isset($_POST['lieu']) ? htmlspecialchars($_POST['lieu']) : ''; ?>">
                        <small class="help-text">Adresse complète du lieu où se déroulera la détection</small>
                    </div>
                </div>
                
                <!-- Partenariats et organisation -->
                <div class="form-section">
                    <h4><i class="fas fa-handshake me-2"></i>Partenariats et organisation</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="partnerClub">Club partenaire</label>
                                <input type="text" id="partnerClub" name="partnerClub" class="form-control" 
                                       placeholder="Ex: Paris Saint-Germain, FC Barcelona, etc."
                                       value="<?php echo isset($_POST['partnerClub']) ? htmlspecialchars($_POST['partnerClub']) : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maxParticipants" class="form-label-required">Nombre maximum de participants</label>
                                <input type="number" id="maxParticipants" name="maxParticipants" class="form-control" 
                                       min="1" max="200" required
                                       value="<?php echo isset($_POST['maxParticipants']) ? htmlspecialchars($_POST['maxParticipants']) : '30'; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="logo_club">Logo du club partenaire</label>
                        <div class="file-upload-container" onclick="document.getElementById('logo_club').click();">
                            <div class="file-upload-message">
                                <i class="fas fa-upload me-2"></i>Cliquez ou glissez-déposez une image
                            </div>
                            <input type="file" id="logo_club" name="logo_club" class="form-control" accept="image/*" style="display: none;">
                            <div id="file-name"></div>
                        </div>
                        <small class="help-text">Format recommandé : JPG, PNG ou GIF (max 2Mo)</small>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="status" class="form-label-required">Statut de la détection</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="planned" <?php echo (!isset($_POST['status']) || $_POST['status'] == 'planned') ? 'selected' : ''; ?>>Planifiée</option>
                            <option value="ongoing" <?php echo (isset($_POST['status']) && $_POST['status'] == 'ongoing') ? 'selected' : ''; ?>>En cours</option>
                            <option value="completed" <?php echo (isset($_POST['status']) && $_POST['status'] == 'completed') ? 'selected' : ''; ?>>Terminée</option>
                        </select>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="?module=detection&action=index" class="btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save me-2"></i>Créer la détection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    // Afficher le nom du fichier lors de la sélection
    document.getElementById('logo_club').addEventListener('change', function() {
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
        const fileInput = document.getElementById('logo_club');
        
        fileInput.files = files;
        
        // Déclencher l'événement change manuellement
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    }
</script>



<?php
$content = ob_get_clean();
$title = "Creer une detection | Football Academy";
require_once(__DIR__ . '/..//layout.php');
?>
