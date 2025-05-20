<!-- views/Tournoi/generateMatches.php -->
<div class="container mt-4">
    <h1>Générer les matchs pour "<?= htmlspecialchars($tournoi['nom']) ?>"</h1>
    
    <form method="post" action="">
        <div class="mb-3">
            <label for="format" class="form-label">Format du tournoi</label>
            <select name="format" id="format" class="form-select" required>
                <option value="championnat">Championnat (tous contre tous)</option>
                <option value="coupe">Coupe (phases de groupes + élimination)</option>
            </select>
        </div>
        
        <div id="coupeoptions" class="mb-3" style="display: none;">
            <label for="phase" class="form-label">Phase</label>
            <select name="phase" id="phase" class="form-select">
                <option value="groupes">Phase de groupes</option>
                <option value="elimination">Phase éliminatoire</option>
            </select>
            
            <div id="groupesoptions" class="mt-3">
                <label for="nbGroupes" class="form-label">Nombre de groupes</label>
                <select name="nbGroupes" id="nbGroupes" class="form-select">
                    <option value="2">2 groupes</option>
                    <option value="4">4 groupes</option>
                    <option value="8">8 groupes</option>
                </select>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Générer les matchs</button>
        <a href="index.php?module=tournoi&action=show&id=<?= $tournoi['id'] ?>" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<script>
document.getElementById('format').addEventListener('change', function() {
    var coupeOptions = document.getElementById('coupeoptions');
    if (this.value === 'coupe') {
        coupeOptions.style.display = 'block';
    } else {
        coupeOptions.style.display = 'none';
    }
});

document.getElementById('phase').addEventListener('change', function() {
    var groupesOptions = document.getElementById('groupesoptions');
    if (this.value === 'groupes') {
        groupesOptions.style.display = 'block';
    } else {
        groupesOptions.style.display = 'none';
    }
});
</script>
