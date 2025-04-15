k"                                                                                      <div class="container">
    <h2>Créer un nouveau match</h2>
    
    <form method="POST">
        <div class="mb-3">
            <label>Équipe 1</label>
            <select name="equipe1_id" class="form-control" required>
                <?php foreach ($equipes as $equipe): ?>
                <option value="<?= $equipe['id'] ?>"><?= htmlspecialchars($equipe['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label>Équipe 2</label>
            <select name="equipe2_id" class="form-control" required>
                <?php foreach ($equipes as $equipe): ?>
                <option value="<?= $equipe['id'] ?>"><?= htmlspecialchars($equipe['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label>Date et heure</label>
            <input type="datetime-local" name="date_match" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label>Terrain</label>
            <input type="text" name="terrain" class="form-control">
        </div>
        
        <button type="submit" class="btn btn-primary">Créer le match</button>
    </form>
</div>