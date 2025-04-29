<?php
namespace App\controllers;

use App\models\Tournoi;

use App\models\Equipe;
class TournoiController {
    private $model;
    private $equipes;
    private $pdo;

    public function __construct($pdo) {
        $this->model = new Tournoi($pdo);
        $this->pdo = $pdo;
        $this->equipes = new Equipe($pdo);
    }

    public function list() {
        $tournois = $this->model->getAll();
        require __DIR__.'/../views/Tournois/list.php';
    }

      // Afficher la page d'un tournoi spécifique
      public function show($id = null) {
        // Si aucun ID n'est passé en paramètre, vérifier dans $_GET
        if ($id === null) {
            if (!isset($_GET['id'])) {
                header('Location: index.php?controller=tournoi');
                exit;
            }
            $id = $_GET['id'];
        }
        
        $tournoi = $this->model->getById($id);
        
        if (!$tournoi) {
            header('Location: index.php?controller=tournoi');
            exit;
        }
        
        
        
        require __DIR__.'/../views/Tournois/show.php';
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->model->create(
                $_POST['nom'],
                $_POST['lieu'],
                $_POST['date_debut'],
                $_POST['date_fin'],
                $_POST['format'],
                $_POST['categorie'],
                (int)$_POST['nb_equipes']
            );
            
            if ($success) {
                header('Location: index.php?module=admin&action=list_tournois&success=created');
            } else {
                header('Location: index.php?module=tournoi&action=create&error=create_failed');
            }
            exit;
        }
        require __DIR__.'/../views/Tournois/create.php';
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
        
        header("Location: index.php?module=admin&action=tournoi_view&id=$tournoi_id");
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






}