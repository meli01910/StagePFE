<?php
namespace App\controllers;

use App\models\Equipe;

class EquipeController {
    private $model;

    public function __construct($pdo) {
        $this->model = new Equipe($pdo);
    }
    public function listAll() {
        $equipes = $this->model->getAll();
        require __DIR__ . '/../views/Equipes/list.php';
    }
    
    public function listByTournoi($tournoiId) {
        $equipes = $this->model->getByTournoi($tournoiId);
        require __DIR__ . '/../views/Equipes/list.php';
    }

    public function create($tournoiId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->create($tournoiId, $_POST['nom'], $_POST['logo'], $_POST['contact_email']);
            header("Location: index.php?module=equipe&action=listByTournoi&tournoi_id=$tournoiId");
            exit;
        }
        require __DIR__ . '/../views/Equipes/create.php';
    }

    public function edit($id) {
        $equipe = $this->model->getById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->update($id, $_POST['nom'], $_POST['logo'], $_POST['contact_email']);
            header("Location: index.php?module=equipe&action=listByTournoi&tournoi_id=" . $equipe['tournoi_id']);
            exit;
        }
        require __DIR__ . '/../views/Equipes/update.php';
    }

    public function delete($id) {
        $equipe = $this->model->getById($id);
        $this->model->delete($id);
        header("Location: index.php?module=equipe&action=listByTournoi&tournoi_id=" . $equipe['tournoi_id']);
        exit;
    }
}
