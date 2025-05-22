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
        
        header("Location: index.php?module=tournoi&action=organiser&id=$tournoi_id");
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
        'Phase de groupes' => 'Huitièmes de finale', // Modification pour commencer par les 1/8 si besoin
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
    $equipesQualifiees = [];
    
    if ($phaseActuelle === 'Phase de groupes') {
        // Collectez les groupes du tournoi
        $groupes = $this->groupModel->getByTournoi($tournoiId);
        
        if (empty($groupes)) {
            $_SESSION['flash_message'] = "Ce tournoi n'a pas de groupes définis";
            $_SESSION['flash_type'] = "warning";
            header('Location: index.php?module=tournoi&action=show&id=' . $tournoiId);
            exit();
        }
        
        // Déterminer quelle phase générer en fonction du nombre de groupes et d'équipes qualifiées
        $nbGroupes = count($groupes);
        $nbEquipesParGroupe = 2; // Nombre d'équipes qualifiées par groupe
        $totalEquipesQualifiees = $nbGroupes * $nbEquipesParGroupe;
        
        if ($totalEquipesQualifiees == 16) {
            $phaseProchaine = 'Huitièmes de finale';
        } elseif ($totalEquipesQualifiees == 8) {
            $phaseProchaine = 'Quarts de finale';
        } elseif ($totalEquipesQualifiees == 4) {
            $phaseProchaine = 'Demi-finales';
        } elseif ($totalEquipesQualifiees == 2) {
            $phaseProchaine = 'Finale';
        }
        
        // Structure pour organiser les qualifiés par groupe
        $qualifiesParGroupe = [];
        
        // Collectez les équipes qualifiées de chaque groupe avec leur classement
        foreach ($groupes as $groupe) {
            $qualifiees = $this->groupModel->getQualifiedTeams($groupe['id'], $nbEquipesParGroupe);
            $qualifiesParGroupe[$groupe['nom']] = $qualifiees;
        }
        
        // Organiser les matchs selon le format standard des tournois
        // Par exemple, pour 8 groupes (A à H), les huitièmes de finale sont:
        // 1A vs 2B, 1C vs 2D, 1E vs 2F, 1G vs 2H, 1B vs 2A, 1D vs 2C, 1F vs 2E, 1H vs 2G
        
        if ($phaseProchaine === 'Huitièmes de finale' && $nbGroupes === 8) {
            // Format standard pour 8 groupes (Coupe du Monde, Euro)
            $affrontements = [
                ['1A', '2B'], ['1C', '2D'], ['1E', '2F'], ['1G', '2H'],
                ['1B', '2A'], ['1D', '2C'], ['1F', '2E'], ['1H', '2G']
            ];
            
            foreach ($affrontements as $match) {
                $groupe1 = substr($match[0], 1); // A, B, C, etc.
                $position1 = intval(substr($match[0], 0, 1)) - 1; // 0 pour 1er, 1 pour 2e
                
                $groupe2 = substr($match[1], 1);
                $position2 = intval(substr($match[1], 0, 1)) - 1;
                
                if (isset($qualifiesParGroupe[$groupe1][$position1]) && isset($qualifiesParGroupe[$groupe2][$position2])) {
                    $equipesQualifiees[] = [
                        'equipe1' => $qualifiesParGroupe[$groupe1][$position1],
                        'equipe2' => $qualifiesParGroupe[$groupe2][$position2],
                        'description' => $match[0] . ' vs ' . $match[1]
                    ];
                }
            }
        } elseif ($phaseProchaine === 'Huitièmes de finale' && $nbGroupes === 6) {
            // Format pour 6 groupes (comme l'Euro 2016)
            // Les 4 meilleurs 3e se qualifient aussi
            // À compléter selon les règles spécifiques
        } elseif ($phaseProchaine === 'Quarts de finale' && $nbGroupes === 4) {
            // Format pour 4 groupes
            $affrontements = [
                ['1A', '2B'], ['1B', '2A'], ['1C', '2D'], ['1D', '2C']
            ];
            
            foreach ($affrontements as $match) {
                $groupe1 = substr($match[0], 1);
                $position1 = intval(substr($match[0], 0, 1)) - 1;
                
                $groupe2 = substr($match[1], 1);
                $position2 = intval(substr($match[1], 0, 1)) - 1;
                
                if (isset($qualifiesParGroupe[$groupe1][$position1]) && isset($qualifiesParGroupe[$groupe2][$position2])) {
                    $equipesQualifiees[] = [
                        'equipe1' => $qualifiesParGroupe[$groupe1][$position1],
                        'equipe2' => $qualifiesParGroupe[$groupe2][$position2],
                        'description' => $match[0] . ' vs ' . $match[1]
                    ];
                }
            }
        } elseif ($phaseProchaine === 'Demi-finales' && $nbGroupes === 2) {
            // Format pour 2 groupes
            $equipesQualifiees[] = [
                'equipe1' => $qualifiesParGroupe['A'][0], // 1er du groupe A
                'equipe2' => $qualifiesParGroupe['B'][1], // 2e du groupe B
                'description' => '1A vs 2B'
            ];
            $equipesQualifiees[] = [
                'equipe1' => $qualifiesParGroupe['B'][0], // 1er du groupe B
                'equipe2' => $qualifiesParGroupe['A'][1], // 2e du groupe A
                'description' => '1B vs 2A'
            ];
        } else {
            // Méthode générique pour les autres formats
            // Mélangez les premiers et deuxièmes de chaque groupe pour éviter que les équipes du même groupe se rencontrent
            $premiers = [];
            $deuxiemes = [];
            
            foreach ($qualifiesParGroupe as $groupeNom => $equipes) {
                if (isset($equipes[0])) $premiers[] = $equipes[0];
                if (isset($equipes[1])) $deuxiemes[] = $equipes[1];
            }
            
            // Assurez-vous que les premiers de groupe affrontent les deuxièmes d'autres groupes
            for ($i = 0; $i < min(count($premiers), count($deuxiemes)); $i++) {
                $index2 = ($i + 1) % count($deuxiemes); // Pour éviter que le 1er du groupe A affronte le 2e du groupe A
                $equipesQualifiees[] = [
                    'equipe1' => $premiers[$i],
                    'equipe2' => $deuxiemes[$index2],
                    'description' => 'Match ' . ($i + 1)
                ];
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
        
        // Récupérer les vainqueurs et perdants (pour le match de la 3e place)
        $vainqueurs = [];
        $perdants = [];
        
        foreach ($matchsPhaseActuelle as $match) {
            $vainqueurId = null;
            $perdantId = null;
            
            if ($match['score1'] > $match['score2']) {
                $vainqueurId = $match['equipe1_id'];
                $perdantId = $match['equipe2_id'];
            } elseif ($match['score2'] > $match['score1']) {
                $vainqueurId = $match['equipe2_id'];
                $perdantId = $match['equipe1_id'];
            } else {
                // En cas d'égalité, vérifier s'il y a eu des tirs au but ou prolongation
                // Pour simplifier, on va prendre l'équipe 1 comme vainqueur par défaut
                $vainqueurId = $match['equipe1_id'];
                $perdantId = $match['equipe2_id'];
            }
            
            // Récupérer les données des équipes
            $vainqueur = $this->equipes->getById($vainqueurId);
            $perdant = $this->equipes->getById($perdantId);
            
            if ($vainqueur) $vainqueurs[] = $vainqueur;
            if ($perdant) $perdants[] = $perdant;
        }
        
        // Pour les phases à partir des quarts de finale
        if ($phaseProchaine === 'Demi-finales') {
            // 1er quart vs 2e quart, 3e quart vs 4e quart
            $equipesQualifiees[] = [
                'equipe1' => $vainqueurs[0],
                'equipe2' => $vainqueurs[1],
                'description' => 'Vainqueur QF1 vs Vainqueur QF2'
            ];
            
            $equipesQualifiees[] = [
                'equipe1' => $vainqueurs[2],
                'equipe2' => $vainqueurs[3],
                'description' => 'Vainqueur QF3 vs Vainqueur QF4'
            ];
        } elseif ($phaseProchaine === 'Finale') {
            // Créer la finale
            $equipesQualifiees[] = [
                'equipe1' => $vainqueurs[0],
                'equipe2' => $vainqueurs[1],
                'description' => 'Finale'
            ];
            
            // Créer le match pour la 3e place
            if (count($perdants) >= 2) {
                $equipesQualifiees[] = [
                    'equipe1' => $perdants[0],
                    'equipe2' => $perdants[1],
                    'description' => 'Match pour la 3e place',
                    'phase' => 'Match pour la 3e place'
                ];
            }
        } else {
            // Pour les huitièmes ou les quarts, apparier les vainqueurs dans l'ordre
            for ($i = 0; $i < count($vainqueurs); $i += 2) {
                if ($i + 1 < count($vainqueurs)) {
                    $equipesQualifiees[] = [
                        'equipe1' => $vainqueurs[$i],
                        'equipe2' => $vainqueurs[$i + 1],
                        'description' => 'Match ' . (($i / 2) + 1)
                    ];
                }
            }
        }
    }
    
    if (empty($equipesQualifiees)) {
        $_SESSION['flash_message'] = "Pas assez d'équipes qualifiées pour générer une nouvelle phase";
        $_SESSION['flash_type'] = "warning";
        header('Location: index.php?module=tournoi&action=show&id=' . $tournoiId);
        exit();
    }
    
    // Vérifier si la phase suivante existe déjà
    if ($phaseProchaine && in_array($phaseProchaine, $phasesExistantes)) {
        $_SESSION['flash_message'] = "La phase " . $phaseProchaine . " existe déjà pour ce tournoi";
        $_SESSION['flash_type'] = "warning";
        header('Location: index.php?module=tournoi&action=organiser&id=' . $tournoiId);
        exit();
    }
    
    // Créer les matchs en fonction des équipes qualifiées
    $dateDebut = date('Y-m-d'); // Par défaut aujourd'hui
    
    // Commencer la création des matchs
    $this->pdo->beginTransaction();
    
    try {
        // Créer les matchs de la phase suivante
        foreach ($equipesQualifiees as $index => $match) {
            // Incrémenter la date pour chaque match
            $matchDate = date('Y-m-d', strtotime("$dateDebut +" . ($index + 1) . " days"));
            
            // Déterminer l'heure en fonction de la phase (finales en soirée, etc.)
            $heure = ($phaseProchaine === 'Finale') ? '20:30:00' : 
                    (($phaseProchaine === 'Match pour la 3e place') ? '17:00:00' : '20:00:00');
            
            // Utiliser la phase spécifiée ou la phase prochaine par défaut
            $phase = $match['phase'] ?? $phaseProchaine;
            
            // Créer le match
            $this->matchModel->create(
                $tournoiId,
                $match['equipe1']['id'],
                $match['equipe2']['id'],
                $matchDate . ' ' . $heure,
                'À déterminer', // Stade
                $phase,
                false, // Match vedette
                $match['description'] // Description du match (1A vs 2B, etc.)
            );
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


