<div class="container mt-4">
    <h1>Joueurs en attente d'approbation</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
            <?= $_SESSION['message'] ?>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>
    
    <?php if (empty($joueurs)): ?>
        <div class="alert alert-info">Aucun joueur en attente d'approbation.</div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Date d'inscription</th>
                    <th>Justificatif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($joueurs as $joueur): ?>
                    <tr>
                        <td><?= htmlspecialchars($joueur['nom']) ?></td>
                        <td><?= htmlspecialchars($joueur['prenom']) ?></td>
                        <td><?= htmlspecialchars($joueur['email']) ?></td>
                        <td><?= isset($joueur['date_creation']) ? date('d/m/Y', strtotime($joueur['date_creation'])) : 'N/A' ?></td>
                        <td>
                            <?php if(!empty($joueur['justificatif'])): ?>
                                <a href="index.php?module=admin&action=voir_justificatif&id=<?= $joueur['id'] ?>" 
                                   class="btn btn-info btn-sm" target="_blank">
                                    Voir le justificatif
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Non fourni</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?module=admin&action=approuver&id=<?= $joueur['id'] ?>" 
                               class="btn btn-success btn-sm" 
                               onclick="return confirm('Êtes-vous sûr d\'approuver ce joueur ?')">
                                Approuver
                            </a>
                            <a href="index.php?module=admin&action=refuser&id=<?= $joueur['id'] ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Êtes-vous sûr de refuser ce joueur ?')">
                                Refuser
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <a href="index.php?module=admin&action=dashboard" class="btn btn-primary">Retour au tableau de bord</a>
</div>
