<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Équipes du tournoi</h2>
        <a href="index.php?module=equipe&action=create&tournoi_id=<?= $_GET['tournoi_id'] ?>" class="btn btn-success">
            ➕ Ajouter une équipe
        </a>
    </div>

    <table class="table table-striped table-bordered align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Logo</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipes as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['nom']) ?></td>
                    <td><img src="<?= htmlspecialchars($e['logo']) ?>" width="50" class="img-thumbnail"></td>
                    <td><?= htmlspecialchars($e['contact_email']) ?></td>
                    <td>
                        <a href="index.php?module=equipe&action=edit&id=<?= $e['id'] ?>" class="btn btn-sm btn-warning me-1">✏️</a>
                        <a href="index.php?module=equipe&action=delete&id=<?= $e['id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Supprimer ?')">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
