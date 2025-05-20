<?php
$pageTitle = "Dashboard Utilisateur";
$currentPage = "dashboard";
$isAdmin = $isAdmin ?? false; // Par précaution
    // Récupérer les informations de l'utilisateur
$userName = $_SESSION['user']['nom'] ?? '';
$userPrenom = $_SESSION['user']['prenom'] ?? '';

// Capturer le contenu
ob_start();
?>

    
    <!-- Contenu principal -->
    <div class="content-wrapper flex-grow-1">

        
        <!-- Main Content -->
        <div class="container-fluid px-4">
            
            <!-- Dashboard Cards -->
            <div class="row g-4 mb-4">
                <!-- Prochain Match -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-left-primary h-100 shadow-sm">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Prochain Match</div>
                                    <div class="h5 mb-0 font-weight-bold">vs FC Barcelona</div>
                                    <div class="small text-muted mt-2">20 Fév 2024 · 18:00</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-futbol fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-end">
                            <a href="#" class="small text-primary">Voir détails <i class="fas fa-chevron-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Tournoi à venir -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-left-success h-100 shadow-sm">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Prochain Tournoi</div>
                                    <div class="h5 mb-0 font-weight-bold">Coupe Internationale</div>
                                    <div class="small text-muted mt-2">25 Fév - 2 Mars 2024</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-trophy fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-end">
                            <a href="#" class="small text-success">Voir détails <i class="fas fa-chevron-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Détection Prochaine -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-left-warning h-100 shadow-sm">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Prochaine Détection</div>
                                    <div class="h5 mb-0 font-weight-bold">Catégorie U17</div>
                                    <div class="small text-muted mt-2">3 Mars 2024 · 14:30</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-search fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-end">
                            <a href="#" class="small text-warning">S'inscrire <i class="fas fa-chevron-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Performance -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-left-info h-100 shadow-sm">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Performance Globale</div>
                                    <div class="row align-items-center no-gutters">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 me-3 font-weight-bold">87%</div>
                                        </div>
                                        <div class="col">
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 87%" 
                                                     aria-valuenow="87" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="small text-muted mt-2">Basé sur vos 5 derniers matchs</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-end">
                            <a href="#" class="small text-info">Analyse détaillée <i class="fas fa-chevron-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content Row -->
            <div class="row mb-4">
                <!-- Calendrier des Événements -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Calendrier des Événements</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="calendarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="calendarDropdown">
                                    <a class="dropdown-item" href="#">Tous les événements</a>
                                    <a class="dropdown-item" href="#">Matchs uniquement</a>
                                    <a class="dropdown-item" href="#">Tournois uniquement</a>
                                    <a class="dropdown-item" href="#">Entraînements uniquement</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="upcoming-events">
                                <!-- Événement -->
                                <div class="event-item d-flex align-items-center p-3 border-bottom">
                                    <div class="event-date text-center me-4">
                                        <div class="date-day fw-bold">20</div>
                                        <div class="date-month text-muted">FÉV</div>
                                    </div>
                                    <div class="event-details flex-grow-1">
                                        <div class="event-title fw-bold">Match vs FC Barcelona</div>
                                        <div class="event-info text-muted">
                                            <span><i class="fas fa-clock me-1"></i> 18:00</span>
                                            <span class="ms-3"><i class="fas fa-map-marker-alt me-1"></i> Camp Nou Stadium</span>
                                        </div>
                                    </div>
                                    <div class="event-category badge bg-primary">Match</div>
                                </div>
                                
                                <!-- Événement -->
                                <div class="event-item d-flex align-items-center p-3 border-bottom">
                                    <div class="event-date text-center me-4">
                                        <div class="date-day fw-bold">22</div>
                                        <div class="date-month text-muted">FÉV</div>
                                    </div>
                                    <div class="event-details flex-grow-1">
                                        <div class="event-title fw-bold">Entraînement tactique spécial</div>
                                        <div class="event-info text-muted">
                                            <span><i class="fas fa-clock me-1"></i> 10:00</span>
                                            <span class="ms-3"><i class="fas fa-map-marker-alt me-1"></i> Centre d'entraînement</span>
                                        </div>
                                    </div>
                                    <div class="event-category badge bg-secondary">Entraînement</div>
                                </div>
                                
                                <!-- Événement -->
                                <div class="event-item d-flex align-items-center p-3 border-bottom">
                                    <div class="event-date text-center me-4">
                                        <div class="date-day fw-bold">25</div>
                                        <div class="date-month text-muted">FÉV</div>
                                    </div>
                                    <div class="event-details flex-grow-1">
                                        <div class="event-title fw-bold">Coupe internationale - Phase de groupes</div>
                                        <div class="event-info text-muted">
                                            <span><i class="fas fa-clock me-1"></i> 09:00</span>
                                            <span class="ms-3"><i class="fas fa-map-marker-alt me-1"></i> Complexe Sportif International</span>
                                        </div>
                                    </div>
                                    <div class="event-category badge bg-success">Tournoi</div>
                                </div>
                                
                                <!-- Événement -->
                                <div class="event-item d-flex align-items-center p-3">
                                    <div class="event-date text-center me-4">
                                        <div class="date-day fw-bold">03</div>
                                        <div class="date-month text-muted">MAR</div>
                                    </div>
                                    <div class="event-details flex-grow-1">
                                        <div class="event-title fw-bold">Session de détection U17</div>
                                        <div class="event-info text-muted">
                                            <span><i class="fas fa-clock me-1"></i> 14:30</span>
                                            <span class="ms-3"><i class="fas fa-map-marker-alt me-1"></i> Terrain principal</span>
                                        </div>
                                    </div>
                                    <div class="event-category badge bg-warning">Détection</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-end">
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="fas fa-calendar-alt me-1"></i> Voir le calendrier complet
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Classement de l'équipe -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Classement - Ligue Elite U19</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="rankingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="rankingsDropdown">
                                    <a class="dropdown-item" href="#">Voir détails</a>
                                    <a class="dropdown-item" href="#">Autres compétitions</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Équipe</th>
                                            <th>J</th>
                                            <th>V</th>
                                            <th>N</th>
                                            <th>D</th>
                                            <th>Pts</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-light">
                                            <td>1</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/teams/real.png" width="20" class="me-2">
                                                    Real Madrid
                                                </div>
                                            </td>
                                            <td>15</td>
                                            <td>12</td>
                                            <td>2</td>
                                            <td>1</td>
                                            <td><strong>38</strong></td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td>2</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/teams/elite.png" width="20" class="me-2">
                                                    <strong>Elite Academy</strong>
                                                </div>
                                            </td>
                                            <td>15</td>
                                            <td>11</td>
                                            <td>2</td>
                                            <td>2</td>
                                            <td><strong>35</strong></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/teams/barca.png" width="20" class="me-2">
                                                    FC Barcelona
                                                </div>
                                            </td>
                                            <td>15</td>
                                            <td>10</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td><strong>32</strong></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/teams/atletico.png" width="20" class="me-2">
                                                    Atletico Madrid
                                                </div>
                                            </td>
                                            <td>15</td>
                                            <td>8</td>
                                            <td>4</td>
                                            <td>3</td>
                                            <td><strong>28</strong></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/teams/valencia.png" width="20" class="me-2">
                                                    Valencia CF
                                                </div>
                                            </td>
                                            <td>15</td>
                                            <td>7</td>
                                            <td>3</td>
                                            <td>5</td>
                                            <td><strong>24</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-end">
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="fas fa-table me-1"></i> Classement complet
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Third Row -->
            <div class="row">
                <!-- Performance Statistiques -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Performances Statistiques</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="statsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in" aria-labelledby="statsDropdown">
                                    <a class="dropdown-item" href="#">5 derniers matchs</a>
                                    <a class="dropdown-item" href="#">10 derniers matchs</a>
                                    <a class="dropdown-item" href="#">Saison complète</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="height: 300px;">
                                <canvas id="performanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Classement des buteurs -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Meilleurs Buteurs (U19)</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Joueur</th>
                                            <th>Équipe</th>
                                            <th class="text-end">Buts</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-primary">
                                            <td>1</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/player.jpg" class="rounded-circle me-2" width="25" height="25">
                                                    <strong>Kylian Mbappé</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <img src="images/teams/elite.png" width="20" alt="Elite Academy">
                                            </td>
                                            <td class="text-end"><strong>18</strong></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/players/ronaldo.jpg" class="rounded-circle me-2" width="25" height="25">
                                                    R. Souza
                                                </div>
                                            </td>
                                            <td>
                                                <img src="images/teams/real.png" width="20" alt="Real Madrid">
</td>
                                            <td class="text-end">15</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/players/messi.jpg" class="rounded-circle me-2" width="25" height="25">
                                                    L. Markus
                                                </div>
                                            </td>
                                            <td>
                                                <img src="images/teams/barca.png" width="20" alt="Barcelona">
                                            </td>
                                            <td class="text-end">14</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/players/benzema.jpg" class="rounded-circle me-2" width="25" height="25">
                                                    K. Benz
                                                </div>
                                            </td>
                                            <td>
                                                <img src="images/teams/juve.png" width="20" alt="Juventus">
                                            </td>
                                            <td class="text-end">12</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/players/salah.jpg" class="rounded-circle me-2" width="25" height="25">
                                                    M. Salah
                                                </div>
                                            </td>
                                            <td>
                                                <img src="images/teams/liverpool.png" width="20" alt="Liverpool">
                                            </td>
                                            <td class="text-end">10</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Détections à venir -->
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Détections à venir</h6>
                            <a href="detections.php" class="btn btn-sm btn-outline-primary">Voir tout</a>
                        </div>
                        <div class="card-body">
                            <div class="detection-item mb-3">
                                <div class="detection-date">
                                    <span class="month">DÉC</span>
                                    <span class="day">15</span>
                                </div>
                                <div class="detection-info">
                                    <h5>Détection U17 Elite</h5>
                                    <div class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> Stade Michel Hidalgo
                                    </div>
                                    <div class="text-muted">
                                        <i class="fas fa-clock"></i> 14:00 - 17:00
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge bg-success">Places disponibles: 12</span>
                                    </div>
                                    <a href="registration.php?id=1" class="btn btn-sm btn-primary mt-2">S'inscrire</a>
                                </div>
                            </div>
                            
                            <div class="detection-item mb-3">
                                <div class="detection-date">
                                    <span class="month">DÉC</span>
                                    <span class="day">22</span>
                                </div>
                                <div class="detection-info">
                                    <h5>Détection Gardiens U15-U17</h5>
                                    <div class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> Centre d'entraînement
                                    </div>
                                    <div class="text-muted">
                                        <i class="fas fa-clock"></i> 10:00 - 12:30
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge bg-warning text-dark">Places limitées: 5</span>
                                    </div>
                                    <a href="registration.php?id=2" class="btn btn-sm btn-primary mt-2">S'inscrire</a>
                                </div>
                            </div>
                            
                            <div class="detection-item">
                                <div class="detection-date">
                                    <span class="month">JAN</span>
                                    <span class="day">08</span>
                                </div>
                                <div class="detection-info">
                                    <h5>Détection Académie U19</h5>
                                    <div class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> Stade Jean Bouin
                                    </div>
                                    <div class="text-muted">
                                        <i class="fas fa-clock"></i> 15:30 - 18:00
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge bg-danger">Complet</span>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-secondary mt-2 disabled">Liste d'attente</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Troisième rangée -->
            <div class="row mt-4">
                <!-- Calendrier des matchs -->
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Calendrier des matchs</h6>
                            <div class="dropdown no-arrow">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="calendarFilterDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Filtrer par équipe
                                </button>
                                <div class="dropdown-menu" aria-labelledby="calendarFilterDropdown">
                                    <a class="dropdown-item active" href="#">Toutes les équipes</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">U15</a>
                                    <a class="dropdown-item" href="#">U17</a>
                                    <a class="dropdown-item" href="#">U19</a>
                                    <a class="dropdown-item" href="#">Seniors</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Catégorie</th>
                                            <th colspan="3" class="text-center">Match</th>
                                            <th>Compétition</th>
                                            <th>Lieu</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="align-middle">
                                                <strong>15/12/2023</strong><br>
                                                <small class="text-muted">15:00</small>
                                            </td>
                                            <td class="align-middle"><span class="badge bg-info">U17</span></td>
                                            <td class="align-middle text-end">
                                                <img src="images/teams/elite.png" width="24" alt="Elite Academy">
                                                <strong>Elite Academy</strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="score-badge">VS</span>
                                            </td>
                                            <td class="align-middle">
                                                <strong>FC Lyon</strong>
                                                <img src="images/teams/lyon.png" width="24" alt="FC Lyon">
                                            </td>
                                            <td class="align-middle">Championnat Régional</td>
                                            <td class="align-middle">Stade Michel Hidalgo</td>
                                            <td class="align-middle">
                                                <a href="match.php?id=1" class="btn btn-sm btn-outline-primary">Détails</a>
                                            </td>
                                        </tr>
                                        <tr class="bg-light">
                                            <td class="align-middle">
                                                <strong>18/12/2023</strong><br>
                                                <small class="text-muted">20:00</small>
                                            </td>
                                            <td class="align-middle"><span class="badge bg-danger">Seniors</span></td>
                                            <td class="align-middle text-end">
                                                <img src="images/teams/marseille.png" width="24" alt="Marseille SC">
                                                <strong>Marseille SC</strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="score-badge">VS</span>
                                            </td>
                                            <td class="align-middle">
                                                <strong>Elite Academy</strong>
                                                <img src="images/teams/elite.png" width="24" alt="Elite Academy">
                                            </td>
                                            <td class="align-middle">Coupe Nationale</td>
                                            <td class="align-middle">Stade Vélodrome</td>
                                            <td class="align-middle">
                                                <a href="match.php?id=2" class="btn btn-sm btn-outline-primary">Détails</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle">
                                                <strong>22/12/2023</strong><br>
                                                <small class="text-muted">14:30</small>
                                            </td>
                                            <td class="align-middle"><span class="badge bg-success">U19</span></td>
                                            <td class="align-middle text-end">
                                                <img src="images/teams/elite.png" width="24" alt="Elite Academy">
                                                <strong>Elite Academy</strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="score-badge">VS</span>
                                            </td>
                                            <td class="align-middle">
                                                <strong>Paris FC</strong>
                                                <img src="images/teams/paris.png" width="24" alt="Paris FC">
                                            </td>
                                            <td class="align-middle">Championnat Élite</td>
                                            <td class="align-middle">Stade Jean Bouin</td>
                                            <td class="align-middle">
                                                <a href="match.php?id=3" class="btn btn-sm btn-outline-primary">Détails</a>
                                            </td>
                                        </tr>
                                        <tr class="bg-light">
                                            <td class="align-middle">
                                                <strong>05/01/2024</strong><br>
                                                <small class="text-muted">16:00</small>
                                            </td>
                                            <td class="align-middle"><span class="badge bg-warning text-dark">U15</span></td>
                                            <td class="align-middle text-end">
                                                <img src="images/teams/bordeaux.png" width="24" alt="Bordeaux AC">
                                                <strong>Bordeaux AC</strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="score-badge">VS</span>
                                            </td>
                                            <td class="align-middle">
                                                <strong>Elite Academy</strong>
                                                <img src="images/teams/elite.png" width="24" alt="Elite Academy">
                                            </td>
                                            <td class="align-middle">Tournoi Espoirs</td>
                                            <td class="align-middle">Stade Chaban-Delmas</td>
                                            <td class="align-middle">
                                                <a href="match.php?id=4" class="btn btn-sm btn-outline-primary">Détails</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="matches.php" class="btn btn-primary">Voir tous les matchs</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Classements -->
                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Classement U17 Elite</h6>
                            <div class="dropdown no-arrow">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="rankingDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Changer de catégorie
                                </button>
                                <div class="dropdown-menu" aria-labelledby="rankingDropdown">
                                    <a class="dropdown-item" href="#">U15</a>
                                    <a class="dropdown-item active" href="#">U17</a>
                                    <a class="dropdown-item" href="#">U19</a>
                                    <a class="dropdown-item" href="#">Seniors</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Pos</th>
                                            <th>Équipe</th>
                                            <th class="text-center">J</th>
                                            <th class="text-center">G</th>
                                            <th class="text-center">N</th>
                                            <th class="text-center">P</th>
                                            <th class="text-center">+/-</th>
                                            <th class="text-end">Pts</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-primary">
                                            <td>1</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/teams/elite.png" class="me-2" width="20">
                                                    <span>Elite Academy</span>
                                                </div>
                                            </td>
                                            <td class="text-center">12</td>
                                            <td class="text-center">10</td>
                                            <td class="text-center">1</td>
                                            <td class="text-center">1</td>
                                            <td class="text-center">+24</td>
                                            <td class="text-end"><strong>31</strong></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/teams/lyon.png" class="me-2" width="20">
                                                    <span>FC Lyon</span>
                                                </div>
                                            </td>
                                            <td class="text-center">12</td>
                                            <td class="text-center">9</td>
                                            <td class="text-center">1</td>
                                            <td class="text-center">2</td>
                                            <td class="text-center">+18</td>
                                            <td class="text-end">28</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/teams/paris.png" class="me-2" width="20">
                                                    <span>Paris FC</span>
                                                </div>
                                            </td>
                                            <td class="text-center">12</td>
                                            <td class="text-center">8</td>
                                            <td class="text-center">2</td>
                                            <td class="text-center">2</td>
                                            <td class="text-center">+11</td>
                                            <td class="text-end">26</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/teams/monaco.png" class="me-2" width="20">
                                                    <span>AS Monaco</span>
                                                </div>
                                            </td>
                                            <td class="text-center">12</td>
                                            <td class="text-center">7</td>
                                            <td class="text-center">2</td>
                                            <td class="text-center">3</td>
                                            <td class="text-center">+9</td>
                                            <td class="text-end">23</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="images/teams/lille.png" class="me-2" width="20">
                                                    <span>Lille OSC</span>
                                                </div>
                                            </td>
                                            <td class="text-center">12</td>
                                            <td class="text-center">6</td>
                                            <td class="text-center">3</td>
                                            <td class="text-center">3</td>
                                            <td class="text-center">+6</td>
                                            <td class="text-end">21</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="standings.php" class="btn btn-outline-primary">Voir tous les classements</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pied de page -->
            <footer class="sticky-footer bg-white mt-4">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Elite Football Academy 2023</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Charts for Player Performance
    const ctx = document.getElementById('playerStatsChart').getContext('2d');
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['Vitesse', 'Technique', 'Tir', 'Passe', 'Défense', 'Physique'],
            datasets: [{
                label: 'Performance actuelle',
                data: [85, 78, 82, 90, 75, 80],
                borderColor: 'rgba(78, 115, 223, 1)',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 2
            }, {
                label: 'Mois précédent',
                data: [80, 75, 78, 85, 70, 76],
                borderColor: 'rgba(28, 200, 138, 1)',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                r: {
                    angleLines: {
                        display: true
                    },
                    suggestedMin: 50,
                    suggestedMax: 100
                }
            }
        }
    });
    
    // Chart for Team Performance
    const teamChart = document.getElementById('teamPerformanceChart').getContext('2d');
    new Chart(teamChart, {
        type: 'line',
        data: {
            labels: ['Sep', 'Oct', 'Nov', 'Déc', 'Jan', 'Fév'],
            datasets: [{
                label: 'Buts marqués',
                data: [8, 12, 15, 18, 22, 20],
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Buts concédés',
                data: [5, 7, 6, 4, 6, 5],
                backgroundColor: 'rgba(231, 74, 59, 0.05)',
                borderColor: 'rgba(231, 74, 59, 1)',
                pointBackgroundColor: 'rgba(231, 74, 59, 1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</body>
</html>


<?php
// Récupérer le contenu et l'assigner à la variable $content
$content = ob_get_clean();

// Inclure le layout
require_once 'layout.php';
?>
