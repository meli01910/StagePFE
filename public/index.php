<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/db.php';

// Chargement avec namespace
require __DIR__ . '/../app/models/Detection.php';
require __DIR__ . '/../app/models/Tournoi.php';
require __DIR__ . '/../app/controllers/DetectionController.php';
require __DIR__ . '/../app/controllers/TournoiController.php';
require __DIR__ . '/../app/controllers/MatchController.php';
require __DIR__ . '/../app/controllers/UserController.php';

// Initialisation
$utilisateurController = new \App\controllers\UserController($pdo);

$detectioncontroller = new \App\controllers\DetectionController($pdo);
$tournoiController = new \App\controllers\TournoiController($pdo);
$matchController = new \App\controllers\MatchController($pdo);
// Nouveau routage
// Nouveau routage
$action = $_GET['action'] ?? 'register';
$module = $_GET['module'] ?? 'user'; // Nouveau paramètre
ini_set('display_errors', 1);
error_reporting(E_ALL);

switch ($module) {
    case 'user':
        switch ($action) {
            case 'register':
                $utilisateurController->register();
                break;
            case 'login':
                $utilisateurController->login();
                break;
            case 'logout':
                $utilisateurController->logout();
                break;
        }
    
    case 'equipe':
        $controller = new \App\controllers\EquipeController($pdo);
        switch ($action) {
            case 'listByTournoi':
                if (isset($_GET['tournoi_id'])) {
                    $controller->listByTournoi($_GET['tournoi_id']);
                } else {
                    echo "Erreur : tournoi_id manquant pour afficher les équipes.";
                }
                break;
            case 'create':
                if (isset($_GET['tournoi_id'])) {
                    $controller->create($_GET['tournoi_id']);
                } else {
                    echo "Erreur : tournoi_id manquant pour créer une équipe.";
                }
                break;
            case 'edit':
                $controller->edit($_GET['id']);
                break;
            case 'delete':
                $controller->delete($_GET['id']);
                break;
        }
        break;

    case 'match':
        if (isset($_GET['tournoi_id'])) {
            switch ($action) {
                case 'create':
                    $matchController->create($_GET['tournoi_id']);
                    break;
                case 'update_score':
                    if (isset($_GET['id'])) {
                        $matchController->updateScore($_GET['id']);
                    }
                    break;
                case 'index':
                    $matchController->index($_GET['tournoi_id']);
                    break;
            }
        } else {
            echo "Erreur : aucun tournoi_id fourni.";
            exit;
        }
        break;
    
        case 'tournoi':
   
        switch ($action) {
            case 'index':
                $tournoiController->index();
                break;
            case 'create':
                $tournoiController->create();
                break;
                case 'show':
                    if (isset($_GET['id'])) {
                        $tournoiController->show($_GET['id']);
                    } else {
                        header('Location: index.php?module=tournoi&error=missing_id');
                    }
                    break;
                case 'edit':
                    if (isset($_GET['id'])) {
                        $tournoiController->edit($_GET['id']);
                    } else {
                        header('Location: index.php?module=tournoi&error=missing_id');
                    }
                    break;
                case 'delete':
                    if (isset($_GET['id'])) {
                        $tournoiController->delete($_GET['id']);
                    } else {
                        header('Location: index.php?module=tournoi&error=missing_id');
                    }
                    break;
            default:
                header('Location: index.php?module=tournoi&action=index');
        }
        break;
        
    default: // Module detection
        switch ($action) {

     case 'index':
       
        $detectioncontroller->index();
        
        break;
        
    case 'list':
        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $detectioncontroller->listByStatus($_GET['status']);
        } else {
            $detectioncontroller->index();
        }
        break;
        
    case 'show':
        if (isset($_GET['id'])) {
            $detectioncontroller->show($_GET['id']);
        } else {
            header('Location: index.php?error=missing_id');
        }
        break;
        
    case 'create':
        $detectioncontroller->create();
        break;
        
    case 'edit':
        if (isset($_GET['id'])) {
            $detectioncontroller->edit($_GET['id']);
        } else {
            header('Location: index.php?error=missing_id');
        }
        break;
        
    case 'delete':
        if (isset($_GET['id'])) {
            $detectioncontroller->delete($_GET['id']);
        } else {
            header('Location: index.php?error=missing_id');
        }
        break;
        
    default:
        // Action non reconnue, redirection vers la page d'accueil
        header('Location: index.php');
        break;
}
}