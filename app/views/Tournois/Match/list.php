<h2>Liste des matchs du tournoi #<?= htmlspecialchars($tournoiId) ?></h2>

<a href="index.php?module=match&action=create&tournoi_id=<?= $tournoiId ?>">➕ Ajouter un match</a>

<table>
    <tr>
        <th>Équipe 1</th>
        <th>Équipe 2</th>
        <th>Date</th>
        <th>Terrain</th>
        <th>Statut</th>
    </tr>
    <?php foreach ($matchs as $match): ?>
    <tr>
        <td><?= htmlspecialchars($match['equipe1_nom']) ?></td>
        <td><?= htmlspecialchars($match['equipe2_nom']) ?></td>
        <td><?= htmlspecialchars($match['date_match']) ?></td>
        <td><?= htmlspecialchars($match['terrain']) ?></td>
        <td><?= htmlspecialchars($match['statut']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
