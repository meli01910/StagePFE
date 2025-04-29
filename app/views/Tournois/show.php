<!-- views/Tournois/show.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du tournoi - <?= htmlspecialchars($tournoi['nom']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .equipe-card {
            transition: transform 0.3s ease;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        .equipe-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .tournament-header {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .logo-container {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        .logo-container img {
            max-height: 100px;
            max-width: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item"><a href="index.php?module=tournoi&action=list">Tournois</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($tournoi['nom']) ?></li>
            </ol>
        </nav>

        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['flash']['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <!-- Entête du tournoi -->
        <div class="tournament-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><?= htmlspecialchars($tournoi['nom']) ?></h1>
                    <p class="lead">
                        <i class="bi bi-calendar-event"></i> 
                        <?= date('d/m/Y', strtotime($tournoi['date_debut'])) ?> au 
                        <?= date('d/m/Y', strtotime($tournoi['date_fin'])) ?>
                    </p>
                    <p><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($tournoi['lieu'] ?: 'Non précisé') ?></p>
                    <p>
                        <span class="badge bg-primary"><?= htmlspecialchars($tournoi['format']) ?></span>
                        <span class="badge bg-secondary"><?= htmlspecialchars($tournoi['categorie']) ?></span>
                        <span class="badge bg-info"><?= htmlspecialchars($tournoi['statut']) ?></span>
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="index.php?module=admin&action=ajouterEquipe&id=<?= $tournoi['id'] ?>" class="btn btn-success me-2">
                        <i class="bi bi-plus-circle"></i> Ajouter des équipes
                    </a>
                    <div class="btn-group mt-2">
                        <a href="index.php?module=tournoi&action=edit&id=<?= $tournoi['id'] ?>" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des équipes participantes -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="fs-4 mb-0"><i class="bi bi-people-fill"></i> Équipes participantes</h2>
                    <a href="index.php?module=admin&action=ajouterEquipe&id=<?= $tournoi['id'] ?>" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-plus-circle"></i> Ajouter des équipes
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php
                // Récupérer les équipes de ce tournoi via la table equipe_tournoi
                $equipes = $this->equipes->getByTournoi($tournoi['id']);
                
                if (empty($equipes)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-people fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Aucune équipe inscrite</h5>
                        <p class="text-muted mb-4">Commencez par ajouter des équipes à ce tournoi.</p>
                        <a href="index.php?module=admin&action=ajouterEquipe&id=<?= $tournoi['id'] ?>" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Ajouter des équipes
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($equipes as $equipe): ?>
                            <div class="col-md-4">
                                <div class="equipe-card card h-100">
                                    <div class="logo-container p-3">
                                        <?php if (!empty($equipe['logo']) && file_exists(__DIR__ . '/../../' . $equipe['logo'])): ?>
                                            <img src="<?= $equipe['logo'] ?>" alt="Logo <?= htmlspecialchars($equipe['nom']) ?>" class="img-fluid">
                                        <?php else: ?>
                                            <i class="bi bi-people-fill fs-1 text-muted"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($equipe['nom']) ?></h5>
                                        <?php if (!empty($equipe['contact_email'])): ?>
                                            <p class="card-text"><i class="bi bi-envelope"></i> <?= htmlspecialchars($equipe['contact_email']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <div class="d-flex justify-content-between">
                                            <a href="index.php?module=equipe&action=show&id=<?= $equipe['id'] ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> Détails
                                            </a>
                                            <a href="index.php?module=tournoi&action=retirerEquipe&tournoi_id=<?= $tournoi['id'] ?>&equipe_id=<?= $equipe['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Êtes-vous sûr de vouloir retirer cette équipe du tournoi?')">
                                                <i class="bi bi-x-circle"></i> Retirer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression du tournoi -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer ce tournoi ? Cette action est irréversible.</p>
                    <p>Toutes les associations avec les équipes seront également supprimées.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <a href="index.php?module=tournoi&action=delete&id=<?= $tournoi['id'] ?>" class="btn btn-danger">Supprimer définitivement</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
