<?php
ob_start();
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-search me-2"></i> Détections disponibles</h1>
        <p class="lead">Consultez toutes les séances de détection à venir</p>
    </div>
</div>

<div class="container">
    <div class="section-header">
        <h2 class="section-title">Liste des détections</h2>
        <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="?module=detection&action=create" class="btn-primary">
                <i class="fas fa-plus"></i> Ajouter une détection
            </a>
        <?php endif; ?>
    </div>
    
    <div class="card-grid">
        <?php foreach($detections as $detection): ?>
            <div class="card">
                <?php if(!empty($detection['logo_club'])): ?>
                    <img src="<?= $detection['logo_club'] ?>" class="card-image" alt="Logo du club">
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="card-title"><?= htmlspecialchars($detection['name']) ?></h3>
                    <p class="card-text">
                        <i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($detection['date'])) ?>
                    </p>
                    <p class="card-text">
                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($detection['location']) ?>
                    </p>
                    <a href="?module=detection&action=show&id=<?= $detection['id'] ?>" class="btn-primary">
                        <i class="fas fa-info-circle"></i> Détails
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = "Détections disponibles | Football Academy";
require_once(__DIR__ . '/..//layout.php');
?>
