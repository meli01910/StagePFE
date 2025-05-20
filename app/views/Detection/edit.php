
<?php
ob_start();
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-futbol me-2"></i> modification d'une  détection</h1>
        <p class="lead">Complétez ce formulaire pour modifier une détection de nouveaux talents</p>
    </div>
</div>

<div class="container ">
    
    <div class="card">
        <div class="card-header-form">
            <h2 class="card-title"><i class="fas fa-clipboard-list"></i> Formulaire de modification</h2>
        </div>
        <div class="card-body">
                 <form method="POST" action="index.php?module=detection&action=edit&id=<?= $detection['id'] ?>" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $detection['id'] ?>">

                <!-- Informations générales -->
                <div class="form-section">
                    <h4><i class="fas fa-info-circle me-2"></i>Informations générales</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nom" class="form-label-required">Nom de la détection</label>
                                <input type="text" id="nom" name="nom" class="form-control" required 
                                       placeholder="Ex: Détection U15 Paris" 
                                       value="<?= htmlspecialchars($detection['name'] ?? '') ?>">
                                <small class="help-text">Donnez un titre clair qui indique la nature de l'événement</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categorie" class="form-label-required">Catégorie d'âge</label>
                                <select id="categorie" name="categorie" class="form-select" required>
                                    <option value="" disabled>Sélectionner une catégorie</option>
                                    <option value="U11" <?= ($detection['categorie'] ?? '') == 'U11' ? 'selected' : '' ?>>U11 (9-10 ans)</option>
                                    <option value="U13" <?= ($detection['categorie'] ?? '') == 'U13' ? 'selected' : '' ?>>U13 (11-12 ans)</option>
                                    <option value="U15" <?= ($detection['categorie'] ?? '') == 'U15' ? 'selected' : '' ?>>U15 (13-14 ans)</option>
                                    <option value="U17" <?= ($detection['categorie'] ?? '') == 'U17' ? 'selected' : '' ?>>U17 (15-16 ans)</option>
                                    <option value="U19" <?= ($detection['categorie'] ?? '') == 'U19' ? 'selected' : '' ?>>U19 (17-18 ans)</option>
                                    <option value="Seniors" <?= ($detection['categorie'] ?? '') == 'Seniors' ? 'selected' : '' ?>>Seniors (18+ ans)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="description">Description de la détection</label>
                        <textarea id="description" name="description" class="form-control" 
                                  placeholder="Décrivez les objectifs, critères de sélection et déroulement de la détection..."><?= htmlspecialchars($detection['description'] ?? '') ?></textarea>
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
                                       value="<?= isset($detection['date']) ? date('Y-m-d', strtotime($detection['date'])) : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="heure" class="form-label-required">Heure de la détection</label>
                                <input type="time" id="heure" name="heure" class="form-control" required
                                       value="<?= isset($detection['heure']) ? date('H:i', strtotime($detection['heure'])) : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_fin_inscription">Date limite d'inscription</label>
                                <input type="date" id="date_fin_inscription" name="date_fin_inscription" class="form-control"
                                       value="<?= isset($detection['date_fin_inscription']) ? date('Y-m-d', strtotime($detection['date_fin_inscription'])) : '' ?>">
                                <small class="help-text">Laissez vide pour permettre les inscriptions jusqu'à la date de l'événement</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="lieu" class="form-label-required">Lieu de la détection</label>
                        <input type="text" id="lieu" name="lieu" class="form-control" required
                               placeholder="Ex: Stade Municipal, 123 Rue du Sport, 75001 Paris"
                               value="<?= htmlspecialchars($detection['location'] ?? '') ?>">
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
                                       value="<?= htmlspecialchars($detection['partner_club'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maxParticipants" class="form-label-required">Nombre maximum de participants</label>
                                <input type="number" id="maxParticipants" name="maxParticipants" class="form-control" 
                                       min="1" max="200" required
                                       value="<?= htmlspecialchars($detection['max_participants'] ?? '30') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="logo_club">Logo du club partenaire</label>
                        
                        <?php if (isset($detection['logo_club']) && !empty($detection['logo_club'])): ?>
                        <div class="mb-3">
                            <p><strong>Logo actuel :</strong></p>
                            <img src="<?= $detection['logo_club'] ?>" alt="Logo club" style="max-height: 100px; max-width: 200px;">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo" value="1">
                                <label class="form-check-label" for="remove_logo">
                                    Supprimer ce logo
                                </label>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="file-upload-container" onclick="document.getElementById('logo_club').click();">
                            <div class="file-upload-message">
                                <i class="fas fa-upload me-2"></i>Cliquez ou glissez-déposez une image pour <?= isset($detection['logo_club']) ? 'remplacer' : 'ajouter' ?> le logo
                            </div>
                            <input type="file" id="logo_club" name="logo_club" class="form-control" accept="image/*" style="display: none;">
                            <div id="file-name"></div>
                        </div>
                        <small class="help-text">Format recommandé : JPG, PNG ou GIF (max 2Mo)</small>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label for="status" class="form-label-required">Statut de la détection</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="planned" <?= ($detection['status'] ?? '') == 'planned' ? 'selected' : '' ?>>Planifiée</option>
                            <option value="ongoing" <?= ($detection['status'] ?? '') == 'ongoing' ? 'selected' : '' ?>>En cours</option>
                            <option value="completed" <?= ($detection['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Terminée</option>
                            <option value="cancelled" <?= ($detection['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Annulée</option>
                        </select>
                    </div>
                </div>

                <!-- Statistiques d'inscription (lecture seule) -->
                <?php if (isset($detection['id'])): ?>
                <div class="form-section">
                    <h4><i class="fas fa-chart-bar me-2"></i>Statistiques d'inscription</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nombre d'inscrits actuel</label>
                                <input type="text" class="form-control" value="<?= $currentParticipants ?? 0 ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Places restantes</label>
                                <input type="text" class="form-control" value="<?= (($detection['maxParticipants'] ?? 0) - ($currentParticipants ?? 0)) ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date de création</label>
                                <input type="text" class="form-control" value="<?= isset($detection['created_at']) ? date('d/m/Y', strtotime($detection['created_at'])) : 'N/A' ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="action-buttons">
                    <a href="?module=detection&action=show&id=<?= $detection['id'] ?>" class="btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
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
$title = "Modification d'une détection | Football Academy";
require_once(__DIR__ . '/..//layout.php');
?>
