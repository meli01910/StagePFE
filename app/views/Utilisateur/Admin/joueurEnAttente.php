<?php include __DIR__ . '../../templates/header.php'; ?>

<div class="container mt-4">
    <h1>Joueurs en attente d'approbation</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
            <?= $_SESSION['message'] ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>
    
    <?php if (empty($joueurs)): ?>
        <div class="alert alert-info">
            Aucun joueur en attente d'approbation.
        </div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Poste</th>
                    <th>Justificatif</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($joueurs as $joueur): ?>
                    <tr>
                        <td><?= htmlspecialchars($joueur['nom']) ?></td>
                        <td><?= htmlspecialchars($joueur['prenom']) ?></td>
                        <td><?= htmlspecialchars($joueur['email']) ?></td>
                        <td><?= htmlspecialchars($joueur['telephone']) ?></td>
                        <td><?= htmlspecialchars($joueur['poste']) ?></td>
                        <td>
                            <a href="index.php?module=justificatif&action=afficher&fichier=<?= htmlspecialchars($joueur['justificatif']) ?>" target="_blank" class="btn btn-sm btn-info">
                                Voir le justificatif
                            </a>
                        </td>
                        <td><?= htmlspecialchars($joueur['date_inscription']) ?></td>
                        <td>
                            <a href="index.php?module=admin&action=approuver&id=<?= $joueur['id'] ?>" class="btn btn-sm btn-success" 
                               onclick="return confirm('Êtes-vous sûr de vouloir approuver ce joueur?')">
                                Approuver
                            </a>
                            <a href="index.php?module=admin&action=refuser&id=<?= $joueur['id'] ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Êtes-vous sûr de vouloir refuser ce joueur?')">
                                Refuser
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../footer.php'; ?>
