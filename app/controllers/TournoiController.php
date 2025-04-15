<?php
namespace App\controllers;

use App\models\Tournoi;

class TournoiController {
    private $model;

    public function __construct($pdo) {
        $this->model = new Tournoi($pdo);
    }

    public function index() {
        $tournois = $this->model->getAll();
        require __DIR__.'/../views/Tournois/list.php';
    }

    public function show($id) {
        $tournoi = $this->model->getById($id);
        if (!$tournoi) {
            header('Location: index.php?module=tournoi&action=index&error=not_found');
            exit;
        }
        
        $teamCount = $this->model->countTeams($id);
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
}