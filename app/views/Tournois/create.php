<!-- views/Tournois/create.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Tournoi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-4">
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                    switch($_GET['error']) {
                        case 'create_failed': echo "Une erreur s'est produite lors de la création du tournoi"; break;
                        default: echo "Une erreur s'est produite"; break;
                    }
                ?>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Créer un nouveau tournoi</h1>
            <a href="index.php?module=tournoi&action=index" class="btn btn-secondary">Retour à la liste</a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="index.php?module=tournoi&action=create" method="post">
                    <!-- Informations du tournoi -->
                    <h4 class="mb-3">Informations du tournoi</h4>
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du tournoi</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="lieu" class="form-label">Lieu</label>
                        <input type="text" class="form-control" id="lieu" name="lieu" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="date_debut" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                        </div>
                        <div class="col-md-6">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        
                         <div class="col-md-4">
                            <label for="format" class="form-label">Format</label>
                            <select class="form-select" id="format" name="format" required>
                                <option value="">Choisir une format</option>
                                <option value="championnat">championnat</option>
                                <option value="poules">poules</option>
                                <option value="coupe">coupe</option>
                                
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="categorie" class="form-label">Catégorie</label>
                            <select class="form-select" id="categorie" name="categorie" required>
                                <option value="">Choisir une catégorie</option>
                                <option value="Senior">Senior</option>
                                <option value="Junior">Junior</option>
                                <option value="Cadet">Cadet</option>
                                <option value="Minime">Minime</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="nb_equipes" class="form-label">Nombre d'équipes</label>
                            <input type="number" class="form-control" id="nb_equipes" name="nb_equipes" min="2" required>
                        </div>
                    </div>

                    <!-- Section de sélection des équipes -->
                    <h4 class="mb-3 mt-4">Équipes participantes</h4>
                    <div class="mb-3">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="toggleEquipes" onchange="toggleEquipesSection()">
                            <label class="form-check-label" for="toggleEquipes">Ajouter des équipes maintenant</label>
                        </div>
                        
                        <div id="equipesSection" style="display: none;">
                            <?php if (empty($equipes)): ?>
                                <div class="alert alert-info">
                                    Aucune équipe disponible. <a href="index.php?module=admin&action=create_equipe">Créer une équipe</a>
                                </div>
                            <?php else: ?>
                                <div class="card">
                                    <div class="card-header bg-light">
                                        Sélectionnez les équipes à inscrire au tournoi
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php foreach ($equipes as $equipe): ?>
                                            <div class="col-md-4 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="equipes[]" value="<?= $equipe['id'] ?>" id="equipe_<?= $equipe['id'] ?>">
                                                            <label class="form-check-label d-flex align-items-center" for="equipe_<?= $equipe['id'] ?>">
                                                                <?php if (!empty($equipe['logo'])): ?>
                                                                <img src="<?= htmlspecialchars($equipe['logo']) ?>" alt="Logo <?= htmlspecialchars($equipe['nom']) ?>" class="me-2" style="width: 30px; height: 30px; object-fit: contain;">
                                                                <?php else: ?>
                                                                <div class="me-2 text-center" style="width: 30px; height: 30px;">
                                                                    <i class="bi bi-people-fill fs-4"></i>
                                                                </div>
                                                                <?php endif; ?>
                                                                <?= htmlspecialchars($equipe['nom']) ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Créer le tournoi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validation côté client pour s'assurer que la date de fin est après la date de début
        document.getElementById('date_debut').addEventListener('change', function() {
            document.getElementById('date_fin').min = this.value;
        });

        // Fonction pour afficher/masquer la section des équipes
        function toggleEquipesSection() {
            const section = document.getElementById('equipesSection');
            if (document.getElementById('toggleEquipes').checked) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        }
    </script>
</body>
</html>
