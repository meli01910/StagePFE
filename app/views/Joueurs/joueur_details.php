<?php
ob_start();
?>

<?php
// Vérifier que $joueur existe
if (empty($joueur)) {
    echo '<div class="alert alert-danger">Joueur non trouvé</div>';
    exit;
}
?>

<!-- En-tête Modifié -->
<div class="page-header">
    <div class="container">
        <div class="header-content">
            <div class="profile-pic-wrapper">
                <?php if (!empty($joueur['photo'])): ?>
                    <img src="<?= htmlspecialchars($joueur['photo']) ?>" alt="Photo de profil" class="profile-pic">
                <?php else: ?>
                    <div class="default-pic">
                        <i class="fas fa-user fa-5x"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="player-info">
                <h1><?= htmlspecialchars($joueur['prenom'] . ' ' . $joueur['nom']) ?></h1>
                <p class="lead">Joueur d'exception, prêt à briller sur le terrain!</p>
                <p class="message">Découvrez ses statistiques et son parcours.</p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <header class="header">
        <h1>Profil détaillé du joueur</h1>
        <div class="buttons">
            <a href="index.php?module=utilisateur&action=index" class="btn-primary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
            <a href="index.php?module=utilisateur&action=edit&id=<?= $joueur['id'] ?>" class="btn-outline-primary">
                <i class="fas fa-edit"></i> Modifier
            </a>
        </div>
    </header>

    <div class="profile-card">
        <div class="profile-content">
            <h2>Informations personnelles</h2>
            <div class="profile-info">
                <p><strong>Email:</strong> <?= htmlspecialchars($joueur['email']) ?></p>
                <p><strong>Téléphone:</strong> <?= htmlspecialchars($joueur['telephone'] ?? 'Non renseigné') ?></p>
                <p><strong>Date de naissance:</strong> <?= htmlspecialchars($joueur['date_naissance'] ?? 'Non renseigné') ?></p>
                <p><strong>Âge:</strong> <?= htmlspecialchars($joueur['age'] ?? 'Non renseignée') ?></p>
                <p><strong>Nationalité:</strong> <?= htmlspecialchars($joueur['nationalite'] ?? 'Non renseignée') ?></p>
            </div>

            <h2>Profil sportif</h2>
            <div class="profile-sportif">
                <p><strong>Poids:</strong> <?= htmlspecialchars($joueur['poids'] ?? 'Non renseignée') ?></p>
                <p><strong>Taille:</strong> <?= htmlspecialchars($joueur['taille'] ?? 'Non renseignée') ?></p>
                <p><strong>Poste:</strong> <?= htmlspecialchars($joueur['poste'] ?? 'Non renseignée') ?></p>
            </div>

            <h2>Statistiques</h2>
            <div class="profile-statistiques">
                <p><strong>Buts marqués:</strong> <?= htmlspecialchars($joueur['buts_marques'] ?? '0') ?></p>
                <p><strong>Passes décisives:</strong> <?= htmlspecialchars($joueur['passes_decisives'] ?? '0') ?></p>
            </div>

            <h2>Statut du compte</h2>
            <div class="account-status">
                <p>
                    <strong>Statut:</strong> <span class="status <?= $joueur['statut'] == 'approuve' ? 'approved' : ($joueur['statut'] == 'en_attente' ? 'pending' : 'rejected') ?>"><?= htmlspecialchars($joueur['statut']) ?></span>
                </p>
                <?php if ($joueur['statut'] == 'en_attente'): ?>
                <div class="action-buttons-status">
                    <a href="index.php?module=utilisateur&action=accepter_joueur&id=<?= $joueur['id'] ?>" class="button-success">
                        <i class="fas fa-check"></i> Accepter
                    </a>
                    <a href="index.php?module=utilisateur&action=refuser&id=<?= $joueur['id'] ?>" class="button-danger">
                        <i class="fas fa-times"></i> Refuser
                    </a>
                </div>
                <?php endif; ?>
                <p><strong>Justificatif:</strong>
                    <?php if (!empty($joueur['justificatif'])): ?>
                        <a href="index.php?module=utilisateur&action=afficherJustificatif&id=<?= $joueur['id'] ?>" class="btn-primary">
                        <i class="fas fa-file-pdf"></i> Voir le document
                        </a>
                    <?php else: ?>
                        <span>Aucun justificatif</span>
                    <?php endif; ?>
                </p>
            </div>

            <div class="action-buttons">
                <a href="index.php?module=utilisateur&action=delete&id=<?= $joueur['id'] ?>" class="button-danger" onclick="return confirm('Confirmer la suppression de ce joueur?')">
                    <i class="fas fa-trash"></i> Supprimer ce joueur
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = "Profil du Joueur | Football Academy";
require_once(__DIR__ . '/../layout.php');
?>


