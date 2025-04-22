<?php
namespace App\controllers;

use App\models\Detection;
use App\models\Tournoi;

class AccueilController {
    private $detectionModel;
    private $tournoiModel;
    
    public function __construct($pdo) {
        $this->detectionModel = new Detection($pdo);
        $this->tournoiModel = new Tournoi($pdo);
    }
    
    public function index() {
        // Récupérer les 3 prochaines détections
        $detections = $this->detectionModel->getAll();
        
        
        
        // Inclure les vues
        require_once __DIR__ . '/../views/templates/header.php';
        require_once __DIR__ . '/../views/accueil.php';
        require_once __DIR__ . '/../views/templates/footer.php';
    }
}