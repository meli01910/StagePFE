<h2>Modifier l’équipe</h2>
<form method="post">
    <input name="nom" value="<?= htmlspecialchars($equipe['nom']) ?>" required><br>
    <input name="logo" value="<?= htmlspecialchars($equipe['logo']) ?>"><br>
    <input name="contact_email" value="<?= htmlspecialchars($equipe['contact_email']) ?>"><br>
    <button type="submit">Mettre à jour</button>
</form>
