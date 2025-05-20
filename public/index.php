<?php

use App\controllers\{
    AuthController,
    UtilisateurController,
    DetectionController,
    TournoiController,
    MatchController,
    EquipeController,
    GroupeController,
    DashboardController
    
};




// ----------------------
// 📦 Autoload & config
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/db.php';

// ----------------------
// 🛡️ Démarrage sécurisé de session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// ----------------------
// 📂 Autoload personnalisé pour les classes dans /src
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});

// ----------------------
// 🗂️ Définition des contrôleurs disponibles
$controllers = [
    'auth'        => AuthController::class,
    'utilisateur' => UtilisateurController::class,
    'detection'   => DetectionController::class,
    'tournoi'     => TournoiController::class,
    'match'       => MatchController::class,
    'equipe'      => EquipeController::class,
    'dashboard'   => DashboardController::class,
    'groupe'     => GroupeController::class
    // 'joueur' => JoueurController::class, // À ajouter si nécessaire
];

// ----------------------
// 📌 Module et action par défaut
$module = $_GET['module'] ?? 'auth';
$action = $_GET['action'] ?? 'login';





// ----------------------
// 🧭 Routing vers le bon contrôleur et la bonne méthode
if (isset($controllers[$module])) {
    $controllerClass = $controllers[$module];
    $controller = new $controllerClass($pdo);

    if (method_exists($controller, $action)) {
        // Certaines actions nécessitent un ID
        switch ($action) {
            case 'show':
            case 'edit':
            case 'delete':
                case 'selectPlayers':
                case 'addPlayer':  
            case 'score':
         case 'generateMatches':
            case 'organiser':
                case 'createMatch':
                    case 'voirPhasesFinales':
                        case'genererPhaseFinale':
                            case'register':
                $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                $controller->$action($id);
                break;
            default:
                $controller->$action();
                break;
        }
        
    }}
?>
