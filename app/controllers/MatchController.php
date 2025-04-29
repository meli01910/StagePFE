<?php
namespace App\controllers;

use App\models\Matchs;
use App\models\Tournoi;
use App\models\Equipe;

class MatchController {
    private $model;
    private $equipeModel;
    private $tournoiModel;

    public function __construct($pdo) {
        $this->model = new Matchs($pdo);
        $this->equipeModel = new Equipe($pdo);
        $this->tournoiModel = new Tournoi($pdo);
    }

    /**
     * Liste tous les matchs disponibles dans le système
     */
    public function listAll() {
        $matchs = $this->model->getAllMatchs();
        require __DIR__ . '/../views/matchs/list.php';
    }
    


    /**
     * Liste les matchs spécifiques à un tournoi
     */
    public function index($tournoiId) {
        $matchs = $this->model->getByTournoi($tournoiId);
        // Pour l'affichage du nom du tournoi dans la vue
        $tournoi = $this->tournoiModel->getById($tournoiId);
        require __DIR__ . '/../views/Tournois/Match/list.php';
    }
    
    /**
     * Création d'un match
     */
    public function create() {
        // Vérification des droits d'admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['message'] = "Accès refusé";
            $_SESSION['message_type'] = "danger";
            header("Location: index.php?module=match&action=listAll");
            exit;
        }
        
        // Récupération de la liste des tournois et équipes pour le formulaire
        $tournois = $this->tournoiModel->getAll();
        $equipes = $this->equipeModel->getAll();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation: vérifier que les équipes sont différentes
            if ($_POST['equipe1_id'] === $_POST['equipe2_id']) {
                $_SESSION['message'] = "Une équipe ne peut pas jouer contre elle-même";
                $_SESSION['message_type'] = "danger";
                require __DIR__.'/../views/Tournois/Match/create.php';
                return;
            }
            
            $tournoiId = $_POST['tournoi_id'];
            $phase = isset($_POST['phase']) ? $_POST['phase'] : 'Poule';
            
            $this->model->create(
                $tournoiId,
                $_POST['equipe1_id'],
                $_POST['equipe2_id'],
                $_POST['date_match'],
                $_POST['terrain'],
              
            );
            
            $_SESSION['message'] = "Match créé avec succès";
            $_SESSION['message_type'] = "success";
            header("Location: index.php?module=tournoi&action=show&id=$tournoiId");
            exit;
        }
        
        require __DIR__.'/../views/Tournois/Match/create.php';
    }

    /**
     * Affiche la page de saisie de score
     */
    public function score($matchId) {
        // Vérification des droits d'admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['message'] = "Accès refusé";
            $_SESSION['message_type'] = "danger";
            header("Location: index.php?module=match&action=listAll");
            exit;
        }
        
        $match = $this->model->getById($matchId);
        if (!$match) {
            $_SESSION['message'] = "Match introuvable";
            $_SESSION['message_type'] = "danger";
            header("Location: index.php?module=match&action=listAll");
            exit;
        }
        
        require __DIR__.'/../views/Tournois/Match/score.php';
    }
    
    /**
     * Met à jour le score d'un match
     */
    public function updateScore($matchId) {
        // Vérification des droits d'admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['message'] = "Accès refusé";
            $_SESSION['message_type'] = "danger";
            header("Location: index.php?module=match&action=listAll");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->updateScore(
                $matchId,
                $_POST['score_equipe1'],
                $_POST['score_equipe2']
            );
            
            $_SESSION['message'] = "Score mis à jour avec succès";
            $_SESSION['message_type'] = "success";
            
            // Redirection vers la page précédente ou la liste des matchs
            if (isset($_SERVER['HTTP_REFERER'])) {
                header("Location: ".$_SERVER['HTTP_REFERER']);
            } else {
                header("Location: index.php?module=match&action=listAll");
            }
            exit;
        }
    }

   
    /**
     * Supprime un match
     */
    public function delete($matchId) {
        // Vérification des droits d'admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['message'] = "Accès refusé";
            $_SESSION['message_type'] = "danger";
            header("Location: index.php");
            exit;
        }
        
        $match = $this->model->getById($matchId);
        if (!$match) {
            $_SESSION['message'] = "Match introuvable";
            $_SESSION['message_type'] = "danger";
            header("Location: index.php?module=match&action=listAll");
            exit;
        }
        
        $tournoiId = $match['tournoi_id'];
        $this->model->delete($matchId);
        
        $_SESSION['message'] = "Match supprimé avec succès";
        $_SESSION['message_type'] = "success";
        
        // Redirection vers la liste des matchs du tournoi ou tous les matchs
        if (isset($_GET['returnToAll']) && $_GET['returnToAll'] == 1) {
            header("Location: index.php?module=match&action=listAll");
        } else {
            header("Location: index.php?module=match&action=index&tournoi_id=$tournoiId");
        }
        exit;
    }
    
    /**
     * Compte le nombre total de matchs
     */
    public function countAllMatches() {
        return $this->model->countAll();
    }
}
