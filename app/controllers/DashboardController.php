<?php
namespace App\controllers;

use App\models\Detection;
use App\models\Tournoi;
use App\models\Matchs;
use App\models\Utilisateur;
class DashboardController {
    private $pdo;
    private $detectionModel;
    private $tournoiModel;
    private $matchModel;
     private $utilisateurModel;
    /**
 
     * Constructeur du contrôleur
     * 
     * @param PDO $pdo L'instance de PDO pour les connexions à la base de données
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->detectionModel = new Detection($pdo);  // Initialisation du modèle dans le constructeur
        $this->tournoiModel=new Tournoi($pdo);
        $this->matchModel= new Matchs($pdo);
          $this->matchModel= new Utilisateur($pdo);
    }
    
    /**
     * Action principale du tableau de bord
     */
    public function index() {
       // Récupérer les informations de l'utilisateur à partir de la session
       $isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
       $userId = $_SESSION['user']['id'] ?? null;

    
       
       // Passer les données à la vue
       require __DIR__ . '/../views/dashboard.php';
    }
    

}
