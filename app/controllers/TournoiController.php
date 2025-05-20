<?php
namespace App\controllers;
use PDOException;
use App\models\Tournoi;
use App\models\Matchs;
use App\models\Equipe;
use App\models\Utilisateur;
use App\models\Groupe;
use PDO;
use FlashMessage;
class TournoiController {
    private $model;
    private $equipes;
    private $pdo;
    private $matchModel; 
    private $userModel;
    private $groupModel;

    public function __construct($pdo) {
        $this->model = new Tournoi($pdo);
        $this->pdo = $pdo;
         $this->groupModel = new Groupe ($pdo);
        $this->equipes = new Equipe($pdo);
        $this->matchModel = new Matchs($pdo);
        $this->userModel = new Utilisateur($pdo);
    }

    public function index() {
        $tournois = $this->model->getAll();
        require __DIR__.'/../views/Tournois/list.php';
    }

      // Afficher la page d'un tournoi spécifique
   /**
 * Affiche les détails complets d'un tournoi avec ses groupes et phases
 * @param int $id ID du tournoi à afficher
 */
public function show($id)
{
    // Récupérer les informations du tournoi
    $tournoi = $this->model->getById($id);
   
    
    // Récupérer les équipes du tournoi
    $equipes = $this->equipes->getByTournoi($id);
    
    // Récupérer les groupes du tournoi
    $groupesRaw = $this->groupModel->getByTournoi($id);
    
    // Récupérer tous les matchs du tournoi
    $matchs = $this->matchModel->getByTournoi($id);
    
    // Séparer les matchs de groupe des matchs de phase finale
    $matchsGroupes = [];
    $matchsPhasesFinales = [];
    $phases_finales_order = [
        'Huitièmes de finale' => 1,
        'Quarts de finale' => 2,
        'Demi-finales' => 3,
        'Match pour la 3e place' => 4,
        'Finale' => 5
    ];
    
    foreach ($matchs as $match) {
        // Vérifier si la phase correspond à un nom de groupe
        $isGroupe = false;
        foreach ($groupesRaw as $groupe) {
            if ($match['phase'] === $groupe['nom']) {
                $isGroupe = true;
                break;
            }
        }
        
        if ($isGroupe) {
            $matchsGroupes[] = $match;
        } else {
            $matchsPhasesFinales[] = $match;
        }
    }
    
    // Organiser les groupes et construire le classement
    $groupes = [];
    
    foreach ($groupesRaw as $groupe) {
        $equipesGroupe = $this->groupModel->getEquipes($groupe['id']);
        $classement = [];
        
        foreach ($equipesGroupe as $equipe) {
            // Initialiser les statistiques pour chaque équipe
            $classement[$equipe['id']] = [
                'id' => $equipe['id'],
                'nom' => $equipe['nom'],
                'logo' => $equipe['logo'],
                'joues' => 0,
                'victoires' => 0,
                'nuls' => 0,
                'defaites' => 0,
                'buts_pour' => 0,
                'buts_contre' => 0,
                'difference' => 0,
                'points' => 0
            ];
        }
        
        // Calculer les statistiques à partir des matchs du groupe
        foreach ($matchsGroupes as $match) {
            if ($match['phase'] == $groupe['nom'] && $match['statut'] == 'terminé') {
                if (isset($classement[$match['equipe1_id']])) {
                    $classement[$match['equipe1_id']]['joues']++;
                    $classement[$match['equipe1_id']]['buts_pour'] += $match['score1'];
                    $classement[$match['equipe1_id']]['buts_contre'] += $match['score2'];
                    
                    if ($match['score1'] > $match['score2']) {
                        $classement[$match['equipe1_id']]['victoires']++;
                        $classement[$match['equipe1_id']]['points'] += 3;
                    } elseif ($match['score1'] == $match['score2']) {
                        $classement[$match['equipe1_id']]['nuls']++;
                        $classement[$match['equipe1_id']]['points'] += 1;
                    } else {
                        $classement[$match['equipe1_id']]['defaites']++;
                    }
                }
                
                if (isset($classement[$match['equipe2_id']])) {
                    $classement[$match['equipe2_id']]['joues']++;
                    $classement[$match['equipe2_id']]['buts_pour'] += $match['score2'];
                    $classement[$match['equipe2_id']]['buts_contre'] += $match['score1'];
                    
                    if ($match['score2'] > $match['score1']) {
                        $classement[$match['equipe2_id']]['victoires']++;
                        $classement[$match['equipe2_id']]['points'] += 3;
                    } elseif ($match['score1'] == $match['score2']) {
                        $classement[$match['equipe2_id']]['nuls']++;
                        $classement[$match['equipe2_id']]['points'] += 1;
                    } else {
                        $classement[$match['equipe2_id']]['defaites']++;
                    }
                }
            }
        }
        
        // Calculer la différence de buts pour chaque équipe
        foreach ($classement as &$equipe) {
            $equipe['difference'] = $equipe['buts_pour'] - $equipe['buts_contre'];
        }
        
        // Trier le classement par points, puis différence de buts, puis buts marqués
        usort($classement, function($a, $b) {
            if ($a['points'] != $b['points']) {
                return $b['points'] - $a['points']; // Points
            } elseif ($a['difference'] != $b['difference']) {
                return $b['difference'] - $a['difference']; // Différence
            } else {
                return $b['buts_pour'] - $a['buts_pour']; // Buts marqués
            }
        });
        
        $groupes[$groupe['nom']] = array_values($classement);
    }
    
    // Organiser les phases finales
    $phases_finales = [];
    foreach ($matchsPhasesFinales as $match) {
        if (!isset($phases_finales[$match['phase']])) {
            $phases_finales[$match['phase']] = [];
        }
        $phases_finales[$match['phase']][] = $match;
    }
    
    // Trier les phases finales dans l'ordre chronologique
    uksort($phases_finales, function($a, $b) use ($phases_finales_order) {
        $order_a = $phases_finales_order[$a] ?? 999;
        $order_b = $phases_finales_order[$b] ?? 999;
        return $order_a - $order_b;
    });
    
    // Rassembler les statistiques du tournoi
    $statistiques = [
        'total_matchs' => count($matchs),
        'matchs_joues' => count(array_filter($matchs, function($m) { return $m['statut'] === 'terminé'; })),
        'buts_marques' => array_reduce($matchs, function($carry, $match) {
            return $carry + $match['score1'] + $match['score2']; 
        }, 0),
        'progression' => count($matchs) > 0 ? 
            (count(array_filter($matchs, function($m) { return $m['statut'] === 'terminé'; })) / count($matchs) * 100) : 0
    ];
    
    // Passer les données à la vue
    include __DIR__ . '/../views/Tournois/show.php';
}
 /**
 * Crée un nouveau tournoi
 * @param string $nom Nom du tournoi
 * @param string $lieu Lieu où se déroule le tournoi
 * @param string $dateDebut Date de début (format YYYY-MM-DD)
 * @param string $dateFin Date de fin (format YYYY-MM-DD)
 * @param string $categorie Catégorie du tournoi
 * @param int $nbEquipes Nombre maximum d'équipes
 * @return int|bool L'ID du tournoi créé ou false en cas d'échec
 */

 public function create() {
    // Si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom'] ?? '');
        $lieu = trim($_POST['lieu'] ?? '');
        $dateDebut = $_POST['date_debut'] ?? '';
        $dateFin = $_POST['date_fin'] ?? '';
        $categorie = trim($_POST['categorie'] ?? '');
        $nbEquipes = (int)($_POST['nb_equipes_max'] ?? 0);
        $format = trim($_POST['format'] ?? '');
    
        $success = $this->model->create($nom, $lieu, $dateDebut, $dateFin, $categorie, $nbEquipes,$format);
        
        if ($success) {
            // Redirection avec un message de succès
            header('Location: index.php?module=tournoi&action=index&message=created');
            exit;
        } else {
            // Redirection avec un message d'erreur
            header('Location: index.php?module=tournoi&action=create&error=1');
            exit;
        }
    }
    
    // Afficher le formulaire
    require __DIR__. '/../views/Tournois/create.php';
}



   
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->model->update(
                $id,
                $_POST['nom'],
                $_POST['lieu'],
                $_POST['date_debut'],
                $_POST['date_fin'],
                $_POST['format'],
                $_POST['categorie'],
                (int)$_POST['nb_equipes'],
                $_POST['statut']
            );
            
            if ($success) {
                header("Location: index.php?module=tournoi&action=show&id=$id&success=updated");
            } else {
                header("Location: index.php?module=tournoi&action=edit&id=$id&error=update_failed");
            }
            exit;
        }
        
        $tournoi = $this->model->getById($id);
        require __DIR__.'/../views/Tournois/edit.php';
    }

    public function delete($id) {
        if ($this->model->delete($id)) {
            header('Location: index.php?module=tournoi&action=index&success=deleted');
        } else {
            header("Location: index.php?module=tournoi&action=show&id=$id&error=delete_failed");
        }
        exit;
    }

// Ajoutez ces méthodes à votre TournoiController

// Affiche la page pour ajouter des équipes à un tournoi
public function ajouterEquipe() {
    // Vérifier que l'utilisateur est connecté et est admin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: index.php?module=auth&action=login');
        exit;
    }
    
    $tournoi_id = $_GET['id'] ?? null;
    
    if (!$tournoi_id) {
        header('Location: index.php?module=tournoi&action=list');
        exit;
    }
    
    // Récupérer le tournoi
    $tournoi = $this->model->getById($tournoi_id);
    if (!$tournoi) {
        header('Location: index.php?module=tournoi&action=list');
        exit;
    }
    
    // Si c'est un POST, traiter l'ajout d'équipe
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $equipe_id = $_POST['equipe_id'] ?? null;
        
        if ($equipe_id) {
            $success = $this->equipes->addToTournament($equipe_id, $tournoi_id);
            
            if ($success) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'L\'équipe a été ajoutée au tournoi avec succès.'
                ];
            } else {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Une erreur est survenue lors de l\'ajout de l\'équipe au tournoi.'
                ];
            }
        }
        
        header("Location: index.php?module=tournoi&action=show&id=$tournoi_id");
        exit;
    }
    
    // Si c'est un GET, afficher la page d'ajout d'équipe
    // Récupérer toutes les équipes
    $equipes = $this->equipes->getAll();
    
    // Récupérer les équipes déjà inscrites à ce tournoi
    $equipesTournoi = $this->equipes->getByTournoi($tournoi_id);
    $equipesInscritesIds = array_column($equipesTournoi, 'id');
    
    require __DIR__ . '/../views/Tournois/ajout_equipe.php';
}


// Retire une équipe d'un tournoi
public function retirerEquipe() {
    if (!isset($_GET['tournoi_id']) || !isset($_GET['equipe_id'])) {
        header('Location: index.php?module=tournoi&action=list');
        exit;
    }
    
    $tournoi_id = $_GET['tournoi_id'];
    $equipe_id = $_GET['equipe_id'];
    
    if ($this->equipes->removeFromTournament($equipe_id, $tournoi_id)) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'L\'équipe a été retirée du tournoi avec succès.'
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'danger',
            'message' => 'Une erreur est survenue lors du retrait de l\'équipe du tournoi.'
        ];
    }
    
    header("Location: index.php?module=tournoi&action=show&id=$tournoi_id");
    exit;
}

/************ORGANISER UN TORUNOI */

// Dans TournoiController.php
public function organiser($id) {
    // Vérification des droits d'admin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['flash_message'] = "Vous n'avez pas les droits pour effectuer cette action";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php');
        exit();
    }
    
    if (!$id) {
        $_SESSION['flash_message'] = "ID de tournoi invalide";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=tournoi&action=index');
        exit();
    }
    
    $tournoi = $this->model->getById($id);
    
    if (!$tournoi) {
        $_SESSION['flash_message'] = "Tournoi non trouvé";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=tournoi&action=index');
        exit();
    }
       // Récupérer les matchs existants du tournoi
    $matchs = $this->matchModel->getByTournoi($id);
    
    // Récupérer les équipes du tournoi
    $equipes = $this->equipes->getByTournoi($id);
     // Récupération des phases du tournoi
    $phases = $this->matchModel->getPhasesByTournoi($id);
  // AJOUT : Organiser les matchs par phase
    $matchsParPhase = [];
   
 // Si aucune phase n'existe encore, créer une phase par défaut
    if (empty($phases)) {
        $phases = [['phase' => 'Phase de groupes']];
    }
 // Organiser les matchs par phase
    foreach ($matchs as $match) {
        $phaseMatch = $match['phase'] ?? 'Non définie';
        if (!isset($matchsParPhase[$phaseMatch])) {
            $matchsParPhase[$phaseMatch] = [];
        }
        $matchsParPhase[$phaseMatch][] = $match;
    }

 
   $groupes = $this->groupModel->getByTournoi($id);
    $groupesComplets = [];
 foreach ($groupes as $groupe) {
        $equipesGroupe = $this->groupModel->getEquipes($groupe['id']); 
        $matchsGroupe = $this->matchModel->getByGroupe($groupe['id']);
        $matchsTermines = count(array_filter($matchsGroupe, function($m) { 
            return $m['statut'] === 'terminé'; 
        }));
        
        $progression = count($matchsGroupe) > 0 ? ($matchsTermines / count($matchsGroupe) * 100) : 0;
        
        $groupesComplets[] = [
            'groupe' => $groupe,
            'equipes' => $equipesGroupe,
            'matchs' => $matchsGroupe,
            'matchsTermines' => $matchsTermines,
            'progression' => $progression
        ];
    }
    // AJOUT : Calculer la progression de chaque phase
    $progressionPhases = [];
    foreach ($phases as $phase) {
        $nomPhase = $phase['phase'];
        $matchsPhase = $matchsParPhase[$nomPhase] ?? [];
        $matchsTermines = count(array_filter($matchsPhase, function($m) { 
            return $m['statut'] === 'terminé'; 
        }));
        
        $progression = count($matchsPhase) > 0 ? ($matchsTermines / count($matchsPhase) * 100) : 0;
        
        $progressionPhases[$nomPhase] = [
            'total' => count($matchsPhase),
            'termines' => $matchsTermines,
            'progression' => $progression
        ];
    }

    // Charger la vue d'organisation
    require_once __DIR__ . '/../views/Tournois/organiser.php';
}

// Ajoutez également une action pour créer manuellement un match
public function createMatch($id) {
    // Vérification des droits
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['flash_message'] = "Vous n'avez pas les droits pour effectuer cette action";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php');
        exit();
    }
    
    if (!$id) {
        $_SESSION['flash_message'] = "ID de tournoi invalide";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=tournoi&action=index');
        exit();
    }
    
    $tournoi = $this->model->getById($id);
    
    if (!$tournoi) {
        $_SESSION['flash_message'] = "Tournoi non trouvé";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=tournoi&action=index');
        exit();
    }
    
    // Récupérer les équipes du tournoi pour le formulaire
    $equipes = $this->equipes->getByTournoi($id);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $equipe1Id = $_POST['equipe1_id'] ?? null;
        $equipe2Id = $_POST['equipe2_id'] ?? null;
        $dateMatch = $_POST['date_match'] ?? null;
        $lieuMatch = $_POST['lieu_match'] ?? $tournoi['lieu'];
        $phase = $_POST['phase'] ?? '';
        
        if ($equipe1Id && $equipe2Id && $dateMatch) {
            if ($this->matchModel->create($id, $equipe1Id, $equipe2Id, $dateMatch, $lieuMatch, $phase)) {
                $_SESSION['flash_message'] = "Match créé avec succès";
                $_SESSION['flash_type'] = "success";
                header('Location: index.php?module=tournoi&action=organiser&id=' . $id);
                exit();
            } else {
                $_SESSION['flash_message'] = "Erreur lors de la création du match";
                $_SESSION['flash_type'] = "danger";
            }
        } else {
            $_SESSION['flash_message'] = "Veuillez remplir tous les champs obligatoires";
            $_SESSION['flash_type'] = "warning";
        }
    }
    
    // Charger la vue de création de match
    require_once __DIR__ . '/../views/Match/create_tournament.php';
}


public function showClassement() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    $tournoi = $this->model->getById($id);
    // Récupérer tous les groupes de ce tournoi
    $groupes = $this->model->getGroupesByTournoiId($id);
     $classements = [];
    foreach ($groupes as $groupe) {
        $classements[$groupe['id']] = $this->model->getGroupStandings($groupe['id']);
    }
    // Vérifier si tous les matchs de groupe sont terminés
    $tousMatchsTermines = $this->model->allGroupMatchesCompleted($id);
    
    require_once __DIR__ . '/../views/Tournois/classement.php';
}











public function genererPhaseFinale($tournoiId) {
    // Vérifier les autorisations
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['flash_message'] = "Vous n'avez pas les droits pour cette action";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=tournoi&action=show&id=' . $tournoiId);
        exit();
    }
    
    $tournoi = $this->model->getById($tournoiId);
    
    if (!$tournoi) {
        $_SESSION['flash_message'] = "Tournoi introuvable";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=tournoi');
        exit();
    }
    
    // Obtenir toutes les phases du tournoi
    $phases = $this->matchModel->getPhasesByTournoi($tournoiId);
    $phasesExistantes = array_column($phases, 'phase');
    
    // Structure de progression des phases
    $structurePhases = [
        'Phase de groupes' => 'Huitièmes de finale',
        'Huitièmes de finale' => 'Quarts de finale',
        'Quarts de finale' => 'Demi-finales',
        'Demi-finales' => 'Finale'
    ];
    
    // Identifier la phase actuelle
    $phaseActuelle = null;
    $phaseProchaine = null;
    
    // Si aucune phase n'existe encore, commencer par la phase de groupes
    if (empty($phasesExistantes)) {
        // Cas spécial : aucune phase existante, commencer directement par les groupes
        $phaseActuelle = 'Aucune';
        $phaseProchaine = 'Phase de groupes';
    } else {
        // Identifier la dernière phase jouée
        foreach (array_keys($structurePhases) as $phase) {
            if (in_array($phase, $phasesExistantes)) {
                $phaseActuelle = $phase;
                $phaseProchaine = $structurePhases[$phase] ?? null;
            }
        }
    }
    
    // Si c'est la phase de groupes, récupérer les équipes qualifiées des groupes
    $equipesAQualifier = [];
    
    if ($phaseActuelle === 'Phase de groupes') {
        // Collectez les groupes du tournoi
        $groupes = $this->groupModel->getByTournoi($tournoiId);
        
        if (empty($groupes)) {
            $_SESSION['flash_message'] = "Ce tournoi n'a pas de groupes définis";
            $_SESSION['flash_type'] = "warning";
            header('Location: index.php?module=tournoi&action=show&id=' . $tournoiId);
            exit();
        }
        
        // Collectez les équipes qualifiées de chaque groupe (généralement 2 par groupe)
        foreach ($groupes as $groupe) {
            $qualifiees = $this->groupModel->getQualifiedTeams($groupe['id'], 2);
            foreach ($qualifiees as $equipe) {
                $equipesAQualifier[] = $equipe;
            }
        }
    } else if ($phaseActuelle) {
        // Pour les autres phases, récupérer les vainqueurs des matchs de la phase actuelle
        $matchsPhaseActuelle = $this->matchModel->getByPhase($tournoiId, $phaseActuelle);
        
        // Vérifier si tous les matchs de la phase actuelle sont terminés
        $tousTermines = true;
        foreach ($matchsPhaseActuelle as $match) {
            if ($match['statut'] !== 'terminé') {
                $tousTermines = false;
                break;
            }
        }
        
        if (!$tousTermines) {
            $_SESSION['flash_message'] = "Tous les matchs de la phase actuelle doivent être terminés avant de générer la phase suivante";
            $_SESSION['flash_type'] = "warning";
            header('Location: index.php?module=tournoi&action=organiser&id=' . $tournoiId);
            exit();
        }
        
        // Récupérer les vainqueurs
        foreach ($matchsPhaseActuelle as $match) {
            $vainqueurId = null;
            if ($match['score1'] > $match['score2']) {
                $vainqueurId = $match['equipe1_id'];
            } elseif ($match['score2'] > $match['score1']) {
                $vainqueurId = $match['equipe2_id'];
            } else {
                // En cas d'égalité, on peut prendre des décisions (tirs au but, tirage au sort...)
                // Pour simplifier, on va prendre l'équipe 1 comme vainqueur par défaut
                $vainqueurId = $match['equipe1_id'];
            }
            
            // Récupérer les données de l'équipe
            $vainqueur = $this->equipes->getById($vainqueurId);
            if ($vainqueur) {
                $equipesAQualifier[] = $vainqueur;
            }
        }
    }
    
    $nbEquipes = count($equipesAQualifier);
    
    if ($nbEquipes < 2) {
        $_SESSION['flash_message'] = "Pas assez d'équipes qualifiées pour générer une nouvelle phase";
        $_SESSION['flash_type'] = "warning";
        header('Location: index.php?module=tournoi&action=organiser&id=' . $tournoiId);
        exit();
    }
    
    // Vérifier si la phase suivante existe déjà
    if ($phaseProchaine && in_array($phaseProchaine, $phasesExistantes)) {
        $_SESSION['flash_message'] = "La phase " . $phaseProchaine . " existe déjà pour ce tournoi";
        $_SESSION['flash_type'] = "warning";
        header('Location: index.php?module=tournoi&action=organiser&id=' . $tournoiId);
        exit();
    }
    
    // Créer les matchs en fonction du nombre d'équipes qualifiées
    $dateDebut = date('Y-m-d'); // Par défaut aujourd'hui
    
    // Mélanger les équipes pour créer des affiches aléatoires (sauf pour la finale)
    if ($phaseProchaine !== 'Finale' && $phaseProchaine !== 'Match pour la 3e place') {
        shuffle($equipesAQualifier);
    }
    
    // Commencer la création des matchs
    $this->pdo->beginTransaction();
    
    try {
        // Gérer le cas de la petite finale (match pour la 3e place)
        if ($phaseProchaine === 'Finale' && $nbEquipes === 4) {
            // Créer le match pour la 3e place avec les perdants des demi-finales
            $equipe3 = $equipesAQualifier[2];
            $equipe4 = $equipesAQualifier[3];
            
            $matchDate = date('Y-m-d', strtotime("$dateDebut +1 days"));
            
            $this->matchModel->create(
                $tournoiId,
                $equipe3['id'],
                $equipe4['id'],
                $matchDate . ' 17:00:00', // 17h par défaut pour le match pour la 3e place
                'À déterminer',
                'Match pour la 3e place',
                false
            );
            
            // Ne conserver que les 2 premières équipes pour la finale
            $equipesAQualifier = array_slice($equipesAQualifier, 0, 2);
        }
        
        // Créer les matchs de la phase suivante
        for ($i = 0; $i < count($equipesAQualifier); $i += 2) {
            // S'assurer qu'on a une paire d'équipes
            if ($i + 1 < count($equipesAQualifier)) {
                $equipe1 = $equipesAQualifier[$i];
                $equipe2 = $equipesAQualifier[$i + 1];
                
                // Incrémenter la date pour chaque match
                $matchDate = date('Y-m-d', strtotime("$dateDebut +" . ($i/2 + 1) . " days"));
                
                // Créer le match
                $this->matchModel->create(
                    $tournoiId,
                    $equipe1['id'],
                    $equipe2['id'],
                    $matchDate . ' 20:00:00', // 20h par défaut
                    'À déterminer',
                    $phaseProchaine,
                    false
                );
            }
        }
        
        $this->pdo->commit();
        
        $_SESSION['flash_message'] = "Phase " . $phaseProchaine . " générée avec succès";
        $_SESSION['flash_type'] = "success";
    } catch (\Exception $e) {
        $this->pdo->rollBack();
        $_SESSION['flash_message'] = "Erreur lors de la génération de la phase: " . $e->getMessage();
        $_SESSION['flash_type'] = "danger";
    }
    
    header('Location: index.php?module=tournoi&action=organiser&id=' . $tournoiId);
    exit();
}


/*public function voirPhasesFinales($tournoiId) {
    $tournoi = $this->model->getById($tournoiId);
    
    if (!$tournoi) {
        $_SESSION['flash_message'] = "Tournoi introuvable";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=tournoi');
        exit;
    }
    
    // Récupérer les matchs de phase finale
   
    $matchsPhaseFinale =$this->matchModel->getMatchsPhasesFinales($tournoiId);
    
    // Organiser les matchs par phase
    $matchsParPhase = [];
    foreach ($matchsPhaseFinale as $match) {
        $phase = $match['phase']; 
        if (!isset($matchsParPhase[$phase])) {
            $matchsParPhase[$phase] = [];
        }
        $matchsParPhase[$phase][] = $match;
    }
    
    // Afficher la vue
    require_once __DIR__ . '/../views/Tournois/phasesFinales.php';
}*/



}


