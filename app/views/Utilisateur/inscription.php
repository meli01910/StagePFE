<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Inscription</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-<?= $messageType ?>"><?= $message ?></div>
                    <?php endif; ?>
                    
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">Nom<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">Prénom<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>
                        </div>
                        <div class="row mb-3">
    <div class="col-md-4">
        <label for="nationalite" class="form-label">Nationalité <span class="text-danger">*</span></label>
        <select class="form-select" id="nationalite" name="nationalite" required>
            <option value="">Sélectionner...</option>
            <option value="Française">Française</option>
            <option value="Espagnole">Espagnole</option>
            <option value="Anglaise">Anglaise</option>
            <option value="Allemande">Allemande</option>
            <option value="Italienne">Italienne</option>
            <option value="Portugaise">Portugaise</option>
            <option value="Belge">Belge</option>
            <option value="Suisse">Suisse</option>
            <option value="Autre">Autre</option>
        </select>
    </div>
    <div class="col-md-4">
        <label for="taille" class="form-label">Taille (cm) <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="taille" name="taille" min="120" max="220" required>
    </div>
    <div class="col-md-4">
        <label for="poids" class="form-label">Poids (kg) <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="poids" name="poids" min="40" max="150" required>
    </div>
</div>

                        <div class="mb-3">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="poste" class="form-label">Poste préféré</label>
                                <select class="form-select" id="poste" name="poste" required>
                                    <option value="">Sélectionnez votre poste</option>
                                    <option value="gardien">Gardien</option>
                                    <option value="defenseur">Défenseur</option>
                                    <option value="milieu">Milieu</option>
                                    <option value="attaquant">Attaquant</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="niveau_jeu" class="form-label">Niveau de jeu</label>
                                <select class="form-select" id="niveau_jeu" name="niveau_jeu" required>
                                    <option value="">Sélectionnez votre niveau</option>
                                    <option value="debutant">Débutant</option>
                                    <option value="amateur">Amateur</option>
                                    <option value="confirme">Confirmé</option>
                                    <option value="expert">Expert</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="confirm_mot_de_passe" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="confirm_mot_de_passe" name="confirm_mot_de_passe" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="justificatif" class="form-label">Justificatif (Carte d'identité, licence sportive, etc.)</label>
                            <input type="file" class="form-control" id="justificatif" name="justificatif" required>
                            <small class="text-muted">Formats acceptés: PDF, JPG, PNG. Taille max: 2MB</small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">S'inscrire</button>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <p>Déjà inscrit? <a href="index.php?module=utilisateur&action=connexion">Se connecter</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validation côté client pour correspondance des mots de passe
document.querySelector('form').addEventListener('submit', function(e) {
    var pwd = document.getElementById('mot_de_passe').value;
    var confirmPwd = document.getElementById('confirm_mot_de_passe').value;
    
    if (pwd !== confirmPwd) {
        e.preventDefault();
        alert("Les mots de passe ne correspondent pas!");
    }
});
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
