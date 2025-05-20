<?php
ob_start();
?>


<?php if (!empty($_SESSION['form_errors'])): ?>
    <div class="alert alert-danger">
        <?php foreach ($_SESSION['form_errors'] as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


<div class="container mt-5">
    <h1>Éditer le Match</h1>

    <?php if (!empty($_SESSION['form_errors'])): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($_SESSION['form_errors'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="tournoi_id">Tournoi :</label>
            <select name="tournoi_id" id="tournoi_id" class="form-control" required>
                <?php foreach ($tournois as $tournoi): ?>
                    <option value="<?php echo htmlspecialchars($tournoi['id']); ?>" 
                        <?php echo $tournoi['id'] == $match['tournoi_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($tournoi['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="equipe1_id">Équipe 1 :</label>
            <select name="equipe1_id" id="equipe1_id" class="form-control" required>
                <?php foreach ($equipes as $equipe): ?>
                    <option value="<?php echo htmlspecialchars($equipe['id']); ?>" 
                        <?php echo $equipe['id'] == $match['equipe1_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($equipe['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="equipe2_id">Équipe 2 :</label>
            <select name="equipe2_id" id="equipe2_id" class="form-control" required>
                <?php foreach ($equipes as $equipe): ?>
                    <option value="<?php echo htmlspecialchars($equipe['id']); ?>" 
                        <?php echo $equipe['id'] == $match['equipe2_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($equipe['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="date_match">Date :</label>
            <input type="datetime-local" name="date_match" id="date_match" class="form-control" 
                   value="<?php echo htmlspecialchars($match['date_match']); ?>" required>
        </div>

        <div class="form-group">
            <label for="lieu_match">Lieu :</label>
            <input type="text" name="lieu_match" id="lieu_match" class="form-control" 
                   value="<?php echo htmlspecialchars($match['lieu_match']); ?>" required>
        </div>

        <div class="form-group">
            <label for="phase">Phase :</label>
            <input type="text" name="phase" id="phase" class="form-control" 
                   value="<?php echo htmlspecialchars($match['phase']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="index.php?module=match&action=index" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php
$content = ob_get_clean();
// Inclure le layout
include __DIR__ . '/../layout.php';
?>