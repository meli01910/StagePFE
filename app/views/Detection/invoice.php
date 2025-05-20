<?php

// Définir le titre et la page active
// Définir le titre de la page et la page active
$pageTitle = 'Gestion des Joueurs';
$currentPage = 'players';

// Capturer le contenu
ob_start();
?>
<div class="container py-5">
    <div class="invoice-container">
        <div class="row mb-4">
                  <div class="col-md-6">
                <img src="assets/img/logo.png" alt="Logo" class="invoice-logo">
            </div>
            <div class="col-md-6 text-md-end">
                <h2 class="mb-0">Confirmation d'inscription</h2>
                <p class="mb-0">Référence: DETECT-<?= sprintf('%05d', $detection['id']) ?>-USER-<?= sprintf('%05d', $user['id']) ?></p>
                <p>Date: <?= date('d/m/Y') ?></p>
            </div>
        </div>

        <hr class="my-4">

        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="mb-3">Détail de la détection</h5>
                <p><strong>Événement:</strong> <?= htmlspecialchars($detection['name']) ?></p>
                <p><strong>Date:</strong> <?= date('d/m/Y', strtotime($detection['date'])) ?> à <?= date('H:i', strtotime($detection['heure'])) ?></p>
                <p><strong>Lieu:</strong> <?= htmlspecialchars($detection['location']) ?></p>
                <p><strong>Catégorie:</strong> <?= htmlspecialchars($detection['categorie'] ?? 'Toutes catégories') ?></p>
            </div>
            <div class="col-md-6">
                <h5 class="mb-3">Informations du participant</h5>
                <p><strong>Nom:</strong> <?= htmlspecialchars($user['nom'] . ' ' . $user['prenom']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Téléphone:</strong> <?= htmlspecialchars($user['telephone'] ?? 'Non renseigné') ?></p>
                <p><strong>Date de naissance:</strong> <?= $user['date_naissance'] ? date('d/m/Y', strtotime($user['date_naissance'])) : 'Non renseignée' ?></p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <h5 class="mb-3">Détails de l'inscription</h5>
                <table class="table table-striped">
                    <tr>
                        <td><strong>Date d'inscription:</strong></td>
                        <td><?= date('d/m/Y à H:i', strtotime($inscription['date_inscription'])) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Statut:</strong></td>
                      
                    </tr>
                    <?php if (isset($detection['prix']) && $detection['prix'] > 0): ?>
                    <tr>
                        <td><strong>Montant:</strong></td>
                        <td><?= number_format($detection['prix'], 2, ',', ' ') ?> €</td>
                    </tr>
                    <tr>
                        <td><strong>Statut du paiement:</strong></td>
                        <td>
                            <span class="badge bg-<?= ($inscription['paiement_status'] ?? '') === 'paid' ? 'success' : 'warning' ?>">
                                <?= ($inscription['paiement_status'] ?? '') === 'paid' ? 'Payé' : 'Non payé' ?>
                            </span>
                        </td>
                    </tr>
                    <?php if (isset($inscription['paiement_date'])): ?>
                    <tr>
                        <td><strong>Date du paiement:</strong></td>
                        <td><?= date('d/m/Y', strtotime($inscription['paiement_date'])) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <div class="alert alert-info">
            <h5 class="alert-heading">Informations importantes:</h5>
            <p class="mb-2">Veuillez vous présenter 30 minutes avant le début de la détection muni(e) de ce document et d'une pièce d'identité. N'oubliez pas votre équipement sportif complet.</p>
            <p class="mb-0">Pour toute question concernant cette détection, veuillez nous contacter à contact@footballdetections.com ou au 01 23 45 67 89.</p>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="?module=detection&action=show&id=<?= $detection['id'] ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
            <a href="?module=detection&action=invoice&id=<?= $detection['id'] ?>&format=pdf" class="btn btn-primary">
                <i class="fas fa-download me-2"></i> Télécharger en PDF
            </a>
        </div>
    </div>
</div>

<style>
.invoice-container {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    padding: 30px;
    max-width: 800px;
    margin: 0 auto;
}

.invoice-logo {
    max-height: 80px;
    width: auto;
}
</style>
