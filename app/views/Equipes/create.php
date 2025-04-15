<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Créer une Équipe</title>
</head>
<body>
    <h1>Créer une Équipe</h1>

    <form action="index.php?module=equipe&action=create&tournoi_id=<?= htmlspecialchars($_GET['tournoi_id']) ?>" method="post">
        <label for="nom">Nom de l'équipe :</label><br>
        <input type="text" id="nom" name="nom" required><br><br>

        <label for="logo">URL du logo :</label><br>
        <input type="text" id="logo" name="logo"><br><br>

        <label for="contact_email">Email de contact :</label><br>
        <input type="email" id="contact_email" name="contact_email"><br><br>

        <input type="submit" value="Créer l'équipe">
    </form>

    <p><a href="index.php?module=tournoi&action=show&id=<?= htmlspecialchars($_GET['tournoi_id']) ?>">← Retour au tournoi</a></p>
</body>
</html>
