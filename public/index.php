<?php
// Pour le développement uniquement
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CECI EST CRUCIAL : Désactivez les redirections pour débugger
define('DEBUG_MODE', true);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/db.php';

// Démarrage sécurisé de la session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// ANTI-BOUCLE DE REDIRECTION
// Réinitialisez toutes les redirections précédentes
if (isset($_GET['reset']) && $_GET['reset'] == 1) {
    $_SESSION = array();
    session_destroy();
    setcookie(session_name(), '', time() - 3600);
    echo "Session réinitialisée. <a href='index.php'>Retour à l'accueil</a>";
    exit;
}

// Compteur de redirections
if (!isset($_SESSION['redirect_count'])) {
    $_SESSION['redirect_count'] = 0;
} else {
    $_SESSION['redirect_count']++;
}

// Si trop de redirections, arrêtez tout
if ($_SESSION['redirect_count'] > 5) {
    $_SESSION['redirect_count'] = 0;
    echo "<h1>Erreur : Boucle de redirection détectée</h1>";
    echo "<p>Le site essaie de vous rediriger en boucle. Cela peut être dû à un problème de session ou de cookies.</p>";
    echo "<p>Informations de débogage :</p>";
    echo "<pre>";
    echo "Module demandé : " . ($_GET['module'] ?? 'aucun') . "<br>";
    echo "Action demandée : " . ($_GET['action'] ?? 'aucune') . "<br>";
    echo "Session active : " . (isset($_SESSION['user']) ? 'oui' : 'non') . "<br>";
    echo "</pre>";
    echo "<p><a href='index.php?reset=1'>Réinitialiser la session</a></p>";
    exit;
}

// Votre code d'autoload ici...
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});

// Contrôleurs et routes comme avant...
$module = $_GET['module'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

// IMPORTANT : Si nous sommes en mode debug, afficher la page demandée sans redirection
if (defined('DEBUG_MODE') && DEBUG_MODE === true) {
    echo "<div style='background:#ff0; padding:10px; position:fixed; top:0; left:0; z-index:9999; width:100%;'>";
    echo "MODE DÉBOGAGE ACTIF — Redirection désactivée | ";
    echo "Module: $module | Action: $action | ";
    echo "Session: " . (isset($_SESSION['user']) ? 'Active' : 'Inactive') . " | ";
    echo "<a href='index.php?reset=1'>Réinitialiser</a>";
    echo "</div>";
    
    // Continuez normalement sans redirection
}

// Votre code de contrôleurs ici, mais SANS redirections forcées


use App\controllers\AuthController;
use App\controllers\AccueilController;
use App\controllers\UtilisateurController;
use App\controllers\DetectionController;
use App\controllers\TournoiController;
use App\controllers\MatchController;
use App\controllers\EquipeController;

// Contrôleurs disponibles
$controllers = [
    'auth' => AuthController::class,
    'accueil' => AccueilController::class,
    'utilisateur' => UtilisateurController::class,
    'detection' => DetectionController::class,
    'tournoi' => TournoiController::class,
    'match' => MatchController::class,
    'equipe' => EquipeController::class,
];

// Récupérer module/action
// Par défaut, on affiche la page de connexion
$module = $_GET['module'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

// Vérifications d'accès
$protectedModules = ['tournoi', 'equipe', 'match', 'joueur'];

// Ne redirige pas si déjà sur la page de connexion
if (in_array($module, $protectedModules) && !isset($_SESSION['user'])) {
    if ($module != 'auth' || $action != 'login') {
        header('Location: index.php?module=auth&action=login');
        exit;
    }
}




if (isset($controllers[$module])) {
    $controllerClass = $controllers[$module];
    try {
        $controller = new $controllerClass($pdo);
        
        if (method_exists($controller, $action)) {
            // Déterminer les paramètres à partir de $_GET
            switch ($action) {
                case 'show':
                case 'edit':
                case 'delete':
                    // Ces actions nécessitent un ID
                    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
                    $controller->$action($id);
                    break;
                    
                default:
                    // Actions sans paramètres
                    $controller->$action();
                    break;
            }
        } else {
            // Action inconnue
            echo "Action '$action' non trouvée dans le module '$module'";
        }
    } catch (Exception $e) {
        if (DEV_MODE) {
            echo "Erreur: " . $e->getMessage();
        } else {
            header('Location: index.php?module=auth&action=login');
            exit;
        }
    }
    }


?>
