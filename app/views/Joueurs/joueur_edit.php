
<?php

$pageTitle = "Dashboard Utilisateur";
$currentPage = "dashboard";

// Capturer le contenu
ob_start();
function selected($champ, $valeur) {
    return (strcasecmp(trim($champ), $valeur) === 0) ? 'selected' : '';
}
?>


<div class="container mt-4">
    <h1>Modifier le profil du utilisateur</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?> alert-dismissible fade show">
        <?= $_SESSION['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php 
        unset($_SESSION['message']); 
        unset($_SESSION['message_type']);
    endif; 
    ?>

    <div class="card">
        <div class="card-body">
       <form method="POST" action="index.php?module=utilisateur&action=update" enctype="multipart/form-data">
       <input type="hidden" name="id" value="<?= htmlspecialchars($utilisateur['id']) ?>">
  
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($utilisateur['email']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($utilisateur['telephone'] ?? '') ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="poste" class="form-label">Poste</label>
                        <select class="form-select" id="poste" name="poste">
                            <option value="">Sélectionner un poste</option>
                            <option value="Gardien" <?= (strcasecmp(trim($utilisateur['poste'] ?? ''), 'Gardien') === 0) ? 'selected' : '' ?>>Gardien</option>
<option value="Défenseur" <?= (strcasecmp(trim($utilisateur['poste'] ?? ''), 'Défenseur') === 0) ? 'selected' : '' ?>>Défenseur</option>
<option value="Milieu" <?= (strcasecmp(trim($utilisateur['poste'] ?? ''), 'Milieu') === 0) ? 'selected' : '' ?>>Milieu</option>
<option value="Attaquant" <?= (strcasecmp(trim($utilisateur['poste'] ?? ''), 'Attaquant') === 0) ? 'selected' : '' ?>>Attaquant</option>
   </select>
                    </div>
                   


                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="taille" class="form-label">Taille (cm)</label>
                        <input type="number" class="form-control" id="taille" name="taille" value="<?= $utilisateur['taille'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="poids" class="form-label">Poids (kg)</label>
                        <input type="number" class="form-control" id="poids" name="poids" value="<?= $utilisateur['poids'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="nationalite" class="form-label">Nationalité</label>
                        <input type="text" class="form-control" id="nationalite" name="nationalite" value="<?= htmlspecialchars($utilisateur['nationalite'] ?? '') ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="date_naissance" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?= $utilisateur['date_naissance'] ?? '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="statut" class="form-label">Statut</label>
                        <select class="form-select" id="statut" name="statut" required>
                            <option value="en_attente" <?= $utilisateur['statut'] == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                            <option value="approuve" <?= $utilisateur['statut'] == 'approuve' ? 'selected' : '' ?>>Approuvé</option>
                            <option value="refuse" <?= $utilisateur['statut'] == 'refuse' ? 'selected' : '' ?>>Refusé</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="mot_de_passe" class="form-label">Nouveau mot de passe (laisser vide pour ne pas modifier)</label>
                    <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?module=utilisateur&action=show&id=<?= $utilisateur['id'] ?>" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = "Joueur | Football Academy";
require_once(__DIR__ . '/..//layout.php');
?>
