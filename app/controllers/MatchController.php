<?php
namespace App\controllers;

use App\models\Matchs;

class MatchController {
    private $model;

    public function __construct($pdo) {
        $this->model = new Matchs($pdo);
    }

    public function index($tournoiId) {
        $matchs = $this->model->getByTournoi($tournoiId);
        require __DIR__ . '/../views/Tournois/Match/list.php';
    }
    
    public function create($tournoiId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->create(
                $tournoiId,
                $_POST['equipe1_id'],
                $_POST['equipe2_id'],
                $_POST['date_match'],
                $_POST['terrain']
            );
            header("Location: index.php?module=tournoi&action=show&id=$tournoiId");
            exit;
        }
        
        require __DIR__.'/../views/Tournois/Match/create.php';
    }

    public function updateScore($matchId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->updateScore(
                $matchId,
                $_POST['score_equipe1'],
                $_POST['score_equipe2']
            );
            header("Location: ".$_SERVER['HTTP_REFERER']."&success=score_updated");
            exit;
        }
    }
}