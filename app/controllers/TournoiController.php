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

    public function index() {
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
        
        $equipes = $this->equipes->getByTournoi($id);
        $nombreEquipes = count($equipes);
        
        // Récupérer les poules si elles existent
        $poules = [];
        if ($tournoi['poules_generees']) {
            $poules = $this->model->getPoulesWithEquipesByTournoi($id);
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
                header('Location: index.php?module=tournoi&action=index&success=created');
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



/*********************************************** */



// Générer les poules
public function genererPoules() {
    if (!isset($_POST['tournoi_id']) || !isset($_POST['nb_poules'])) {
        $_SESSION['message'] = 'Paramètres manquants.';
        $_SESSION['message_class'] = 'alert-danger';
        header('Location: index.php?controller=tournoi');
        exit;
    }
    
    $tournoiId = $_POST['tournoi_id'];
    $nbPoules = (int)$_POST['nb_poules'];
    
    try {
        $this->model->genererPoules($tournoiId, $nbPoules);
        $_SESSION['message'] = 'Les poules ont été générées avec succès.';
        $_SESSION['message_class'] = 'alert-success';
    } catch (\Exception $e) {
        $_SESSION['message'] = 'Erreur lors de la génération des poules: ' . $e->getMessage();
        $_SESSION['message_class'] = 'alert-danger';
    }
    
    header('Location: index.php?controller=tournoi&action=show&id=' . $tournoiId);
    exit;
}






}