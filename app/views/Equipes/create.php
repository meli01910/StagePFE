<?php include __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-4">
<form method="POST" action="index.php?module=admin&action=equipe_add" enctype="multipart/form-data">
    <label>Nom de l'Équipe:</label>
    <input type="text" name="nom" required>
    
    <label>Logo:</label>
    <input type="file" name="logo" accept="image/*">
    
    <label>Email de Contact:</label>
    <input type="email" name="contact_email" required>
    
    <button type="submit">Créer l'équipe</button>
</form>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
