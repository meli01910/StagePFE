
<!-- views/accueil.php -->
<div class="container mt-4">
    <!-- Hero section -->
    <section class="hero-section text-white text-center py-5 rounded" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('assets/img/football-field.jpg') no-repeat center; background-size: cover;">
        <div class="container py-5">
            <h1 class="display-3 fw-bold mb-4">Révélez votre talent footballistique</h1>
            <p class="lead mb-4">Inscrivez-vous aux détections et tournois pour montrer vos compétences aux recruteurs professionnels</p>
            <div>
                <a href="index.php?module=detection&action=list" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-search"></i> Voir les détections
                </a>
                <?php if (!isset($_SESSION['user'])): ?>
                <a href="index.php?module=auth&action=register" class="btn btn-success btn-lg">
                    <i class="fas fa-user-plus"></i> Créer un compte
                </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Prochaines détections -->
    <section class="py-5">
        <h2 class="text-center mb-5">Prochaines détections</h2>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if (empty($detections)): ?>
                <div class="col-12 text-center">
                    <p>Aucune détection prochainement. Revenez plus tard !</p>
                </div>
            <?php else: ?>
                <?php foreach($detections as $detection): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <?= htmlspecialchars($detection['name'] ?? $detection['nom'] ?? $detection['titre'] ?? 'Sans titre') ?>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="fas fa-calendar-alt text-primary"></i> 
                                <strong>Date:</strong> 
                                <?= isset($detection['date']) ? date('d/m/Y à H:i', strtotime($detection['date'])) : 
                                   (isset($detection['date_heure']) ? date('d/m/Y à H:i', strtotime($detection['date_heure'])) : 'Date non définie') ?>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-map-marker-alt text-danger"></i> 
                                <strong>Lieu:</strong> 
                                <?= htmlspecialchars($detection['location'] ?? $detection['lieu'] ?? 'Lieu non défini') ?>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-users text-success"></i> 
                                <strong>Catégorie:</strong> 
                                <?= htmlspecialchars($detection['age_category'] ?? $detection['categorie_age'] ?? $detection['categorie'] ?? 'Catégorie non définie') ?>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-futbol text-secondary"></i> 
                                <strong>Club partenaire:</strong> 
                                <?= htmlspecialchars($detection['partner_club'] ?? $detection['club_partenaire'] ?? 'Non spécifié') ?>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="index.php?module=detection&action=show&id=<?= $detection['id'] ?>" class="btn btn-outline-primary w-100">
                                <i class="fas fa-info-circle"></i> Détails
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="index.php?module=detection&action=list" class="btn btn-primary">
                <i class="fas fa-list"></i> Voir toutes les détections
            </a>
        </div>
    </section>

    <!-- Comment ça marche -->
    <section class="py-5 bg-light rounded mt-4">
        <div class="container">
            <h2 class="text-center mb-5">Comment ça marche ?</h2>
            
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="rounded-circle bg-primary text-white mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                    <h3>1. Inscrivez-vous</h3>
                    <p>Créez votre compte joueur et complétez votre profil avec vos informations et expériences.</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="rounded-circle bg-primary text-white mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-search fa-2x"></i>
                    </div>
                    <h3>2. Trouvez une détection</h3>
                    <p>Parcourez les détections disponibles et inscrivez-vous à celles qui correspondent à votre profil.</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="rounded-circle bg-primary text-white mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-futbol fa-2x"></i>
                    </div>
                    <h3>3. Montrez votre talent</h3>
                    <p>Participez à la détection et démontrez vos compétences devant les recruteurs professionnels.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Témoignages -->
    <section class="py-5 mt-4">
        <h2 class="text-center mb-5">Ils ont réussi grâce à nous</h2>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <img src="assets/img/joueur1.jpg" alt="Témoignage" class="rounded-circle me-3" style="width: 64px; height: 64px; object-fit: cover;">
                            <div>
                                <h5 class="mb-0">Thomas Dubois</h5>
                                <p class="text-muted">Milieu de terrain - FC Nantes</p>
                            </div>
                        </div>
                        <p class="mb-0">"Grâce à FootballStar, j'ai pu participer à une détection qui m'a permis d'intégrer le centre de formation du FC Nantes. Un grand merci à cette plateforme qui a changé ma vie !"</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <img src="assets/img/joueur2.jpg" alt="Témoignage" class="rounded-circle me-3" style="width: 64px; height: 64px; object-fit: cover;">
                            <div>
                                <h5 class="mb-0">Léa Martin</h5>
                                <p class="text-muted">Attaquante - Olympique Lyonnais</p>
                            </div>
                        </div>
                        <p class="mb-0">"J'avais du mal à me faire repérer malgré mon niveau. FootballStar m'a donné l'opportunité de montrer mon talent aux bons recruteurs. Aujourd'hui je joue en D1 féminine !"</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



