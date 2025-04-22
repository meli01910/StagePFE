<?php require __DIR__ . '/../templates/header.php'; ?>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">Créer un nouveau tournoi</h1>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?module=tournoi&action=create">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nom" class="form-label required-label">Nom du tournoi</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>

                    <div class="mb-3">
                        <label for="lieu" class="form-label required-label">Lieu</label>
                        <input type="text" class="form-control" id="lieu" name="lieu" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="date_debut" class="form-label required-label">Date de début</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                        </div>
                        <div class="col-md-6">
                            <label for="date_fin" class="form-label required-label">Date de fin</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="format" class="form-label required-label">Format</label>
                        <select class="form-select" id="format" name="format" required>
                            <option value="">Sélectionnez un format</option>
                            <option value="elimination">Élimination directe</option>
                            <option value="poules">Poules + phase finale</option>
                            <option value="mixte">Format mixte</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="categorie" class="form-label required-label">Catégorie</label>
                        <select class="form-select" id="categorie" name="categorie" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <option value="U13">U13</option>
                            <option value="U15">U15</option>
                            <option value="U17">U17</option>
                            <option value="U19">U19</option>
                            <option value="Senior">Senior</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nb_equipes" class="form-label required-label">Nombre d'équipes max</label>
                        <input type="number" class="form-control" id="nb_equipes" name="nb_equipes" min="2" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="reglement" class="form-label">Règlement (optionnel)</label>
                <textarea class="form-control" id="reglement" name="reglement" rows="4"></textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="index.php?module=tournoi&action=index" class="btn btn-secondary me-md-2">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    Créer le tournoi
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validation des dates
        document.querySelector('form').addEventListener('submit', function(e) {
            const debut = new Date(document.getElementById('date_debut').value);
            const fin = new Date(document.getElementById('date_fin').value);
            
            if (fin < debut) {
                e.preventDefault();
                alert('La date de fin doit être après la date de début');
            }
        });
    </script>
</body>
</html>