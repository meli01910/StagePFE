<?php
namespace App\controllers;

use App\models\Matchs;
use App\models\Tournoi;
use App\models\Equipe;

class MatchController {
    private $model;
    private $tournoisModel;
    private $equipesModel;

    public function __construct($pdo) {
        $this->model = new Matchs($pdo);
        $this->tournoisModel = new Tournoi($pdo);
        $this->equipesModel = new Equipe($pdo);
    }

    /**
     * Liste tous les matchs
     */
    public function index() {
    // Récupérer les filtres éventuels
    $filters = [];
    if (isset($_GET['tournoi_id']) && !empty($_GET['tournoi_id'])) {
        $filters['tournoi_id'] = $_GET['tournoi_id'];
    }
    if (isset($_GET['status']) && in_array($_GET['status'], ['played', 'coming'])) {
        $filters['status'] = $_GET['status'];
    }
    if (isset($_GET['type']) && in_array($_GET['type'], ['friendly', 'tournament', 'all'])) {
        if ($_GET['type'] !== 'all') {
            $filters['type'] = $_GET['type'];
        }
    }

    // Récupérer tous les matchs avec les filtres
    $matchs = $this->model->getAllMatchs($filters);
    
    // Récupérer tous les tournois pour le filtre
    $tournois = $this->tournoisModel->getAll();
    
    // Charger la vue
    require __DIR__ . '/../views/Match/list.php';
}


    /**
     * Affiche le détail d'un match
     */

    public function show($id) {
        $match = $this->model->getById($id);
        
        if (!$match) {
            $_SESSION['message'] = "Match non trouvé";
            $_SESSION['message_type'] = "danger";
            header("Location: index.php?module=match&action=index");
            exit;
        }
        
        require __DIR__ . '/../views/Match/show.php';
    }
    

    /**
     * Affiche le formulaire de création de match
     */
public function create() {
    // Vérifie si l'utilisateur est admin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['flash_message'] = "Vous n'avez pas les droits pour accéder à cette page";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php');
        exit();
    }
    
    $tournoiId = isset($_GET['tournoi_id']) ? intval($_GET['tournoi_id']) : null;
    $tournois = $this->tournoisModel->getAll();
    $typeMatch = isset($_GET['type']) ? $_GET['type'] : 'tournoi';
    
    // Si tournoi spécifié, forcer le type à tournoi
    if ($tournoiId) {
        $typeMatch = 'tournoi';
        $equipes = $this->equipesModel->getByTournoi($tournoiId);
        $tournoi = $this->tournoisModel->getById($tournoiId);
    } else {
        $equipes = $this->equipesModel->getAll();
        $tournoi = null;
    }
    
    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $typeMatch = filter_input(INPUT_POST, 'type_match', FILTER_SANITIZE_STRING);
        $estAmical = ($typeMatch === 'amical');
        $tournoiId = $estAmical ? null : filter_input(INPUT_POST, 'tournoi_id', FILTER_VALIDATE_INT);
        $equipe1Id = filter_input(INPUT_POST, 'equipe1_id', FILTER_VALIDATE_INT);
        $equipe2Id = filter_input(INPUT_POST, 'equipe2_id', FILTER_VALIDATE_INT);
        $dateMatch = filter_input(INPUT_POST, 'date_match', FILTER_SANITIZE_STRING);
        $lieuMatch = filter_input(INPUT_POST, 'lieu_match', FILTER_SANITIZE_STRING);
        $phase = $estAmical ? null : filter_input(INPUT_POST, 'phase', FILTER_SANITIZE_STRING);
        
        // Validation
        $errors = [];
        
        if (!$estAmical && !$tournoiId) $errors[] = "Le tournoi est obligatoire pour un match de tournoi";
        if (!$equipe1Id) $errors[] = "L'équipe 1 est obligatoire";
        if (!$equipe2Id) $errors[] = "L'équipe 2 est obligatoire";
        if ($equipe1Id === $equipe2Id) $errors[] = "Les deux équipes doivent être différentes";
        if (!$dateMatch) $errors[] = "La date du match est obligatoire";
        if (!$lieuMatch) $errors[] = "Le lieu est obligatoire";
        
        if (empty($errors)) {
            // Créer le match avec le paramètre est_amical
            if ($this->model->create($tournoiId, $equipe1Id, $equipe2Id, $dateMatch, $lieuMatch, $phase, $estAmical)) {
                $_SESSION['flash_message'] = "Match créé avec succès";
                $_SESSION['flash_type'] = "success";
                
                // Rediriger vers le bon endroit selon le type de match
                if ($tournoiId) {
                    header("Location: index.php?module=tournoi&action=show&id={$tournoiId}");
                } else {
                    header("Location: index.php?module=match&action=index");
                }
                exit();
            } else {
                $errors[] = "Erreur lors de la création du match";
            }
        }
        
        $_SESSION['form_errors'] = $errors;
    }
    
    require_once __DIR__ . '/../views/Match/create.php';
}

/**
 * Affiche le formulaire d'édition d'un match
 */
public function edit($id) {
    // Vérifie si l'utilisateur est admin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['flash_message'] = "Vous n'avez pas les droits pour accéder à cette page";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php');
        exit();
    }

    // Récupérer les informations du match
    $match = $this->model->getById($id);
    
    if (!$match) {
        $_SESSION['flash_message'] = "Match non trouvé";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=match&action=index');
        exit();
    }

    $tournois = $this->tournoisModel->getAll();
    $equipes = $this->equipesModel->getAll(); // Assurez-vous de récupérer toutes les équipes

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tournoiId = filter_input(INPUT_POST, 'tournoi_id', FILTER_VALIDATE_INT);
        $equipe1Id = filter_input(INPUT_POST, 'equipe1_id', FILTER_VALIDATE_INT);
        $equipe2Id = filter_input(INPUT_POST, 'equipe2_id', FILTER_VALIDATE_INT);
        $dateMatch = filter_input(INPUT_POST, 'date_match', FILTER_SANITIZE_STRING);
        $lieuMatch = filter_input(INPUT_POST, 'lieu_match', FILTER_SANITIZE_STRING);
        $phase = filter_input(INPUT_POST, 'phase', FILTER_SANITIZE_STRING);
        
        // Validation des données
        $errors = [];
        
        if (!$tournoiId) $errors[] = "Le tournoi est obligatoire";
        if (!$equipe1Id) $errors[] = "L'équipe 1 est obligatoire";
        if (!$equipe2Id) $errors[] = "L'équipe 2 est obligatoire";
        if ($equipe1Id === $equipe2Id) $errors[] = "Les deux équipes doivent être différentes";
        if (!$dateMatch) $errors[] = "La date du match est obligatoire";
        if (!$lieuMatch) $errors[] = "Le lieu est obligatoire";
        
        if (empty($errors)) {
            // Met à jour les données du match
            if ($this->model->updateMatch($id, $tournoiId, $equipe1Id, $equipe2Id, $dateMatch, $lieuMatch, $phase)) {
                $_SESSION['flash_message'] = "Match mis à jour avec succès";
                $_SESSION['flash_type'] = "success";
                
                // Rediriger vers l'index des matchs
                header("Location: index.php?module=match&action=index");
                exit();
            } else {
                $errors[] = "Erreur lors de la mise à jour du match";
            }
        }

        $_SESSION['form_errors'] = $errors;
    }

    require_once __DIR__ . '/../views/Match/edit.php'; // Chargez la vue pour l'édition
}

   /**
     * Traite le formulaire de saisie du score
     */
    public function updateScore() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $matchId = filter_input(INPUT_POST, 'match_id', FILTER_VALIDATE_INT);
        $tournoiId = filter_input(INPUT_POST, 'tournoi_id', FILTER_VALIDATE_INT);
        $scoreEquipe1 = filter_input(INPUT_POST, 'score1', FILTER_VALIDATE_INT);
        $scoreEquipe2 = filter_input(INPUT_POST, 'score2', FILTER_VALIDATE_INT);
        $statut = filter_input(INPUT_POST, 'statut', FILTER_SANITIZE_STRING);
        
        if (!$matchId || !$tournoiId || !is_numeric($scoreEquipe1) || !is_numeric($scoreEquipe2) || !$statut) {
            $_SESSION['flash_message'] = "Données invalides pour mettre à jour le score";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php?module=tournoi&action=organiser&id=' . $tournoiId);
            exit();
        }
        
        // Mise à jour du score dans la base de données
        if ($this->model->updateScore($matchId, $scoreEquipe1, $scoreEquipe2, $statut)) {
            $_SESSION['flash_message'] = "Score mis à jour avec succès";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Erreur lors de la mise à jour du score";
            $_SESSION['flash_type'] = "danger";
        }
        
        header('Location: index.php?module=tournoi&action=organiser&id=' . $tournoiId);
        exit();
    }
}
        
//        require_once __DIR__ . '/../views/Match/score.php';
    
    

    /**
     * Supprime un match
     */
    public function delete($id) {
        if ($this->model->delete($id)) {
            $_SESSION['message'] = "Match supprimé avec succès";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Erreur lors de la suppression du match";
            $_SESSION['message_type'] = "danger";
        }
        
        header("Location: index.php?module=match&action=index");
        exit;
    }



    
}
