<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/db.php';

// Démarrage de la session
session_start();

// Chargement avec namespace
require __DIR__ . '/../app/models/Detection.php';
require __DIR__ . '/../app/models/Tournoi.php';
require __DIR__ . '/../app/models/Utilisateur.php'; 
require __DIR__ . '/../app/controllers/AccueilController.php';  // Nouveau contrôleur d'accueil
require __DIR__ . '/../app/controllers/DetectionController.php';
require __DIR__ . '/../app/controllers/TournoiController.php';
require __DIR__ . '/../app/controllers/MatchController.php';
require __DIR__ . '/../app/controllers/UtilisateurController.php';
require __DIR__ . '/../app/controllers/JoueurController.php';
require __DIR__ . '/../app/controllers/AuthController.php';   // Nouveau contrôleur d'authentification

// Initialisation
$accueilController = new \App\controllers\AccueilController($pdo);  // Nouveau contrôleur
$utilisateurController = new \App\controllers\UtilisateurController($pdo);
$detectionController = new \App\controllers\DetectionController($pdo);
$tournoiController = new \App\controllers\TournoiController($pdo);
$matchController = new \App\controllers\MatchController($pdo);
$joueurController = new \App\controllers\JoueurController($pdo);
$authController = new \App\controllers\AuthController($pdo);  // Nouveau contrôleur

// Définir module et action par défaut
$module = $_GET['module'] ?? 'accueil';  // Par défaut, accueil au lieu de detection
$action = $_GET['action'] ?? 'index';

// Activer l'affichage des erreurs pour le développement
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérifications d'authentification pour certains modules
$protectedModules = ['admin', 'tournoi', 'equipe', 'match', 'justificatif', 'joueur'];
$adminModules = ['admin'];
$joueurModules = ['joueur'];

// Si on essaie d'accéder à un module protégé sans être connecté
if (in_array($module, $protectedModules) && !isset($_SESSION['user'])) {
    $_SESSION['message'] = "Veuillez vous connecter pour accéder à cette section.";
    $_SESSION['message_type'] = "warning";
    header('Location: index.php?module=auth&action=login');  // Redirection vers auth/login
    exit;
}

// Si on essaie d'accéder à un module admin sans être admin
if (in_array($module, $adminModules) && (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin')) {
    $_SESSION['message'] = "Accès refusé. Cette section est réservée aux administrateurs.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php');
    exit;
}

// Si on essaie d'accéder à un module joueur sans être joueur
if (in_array($module, $joueurModules) && (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'joueur')) {
    $_SESSION['message'] = "Accès refusé. Cette section est réservée aux joueurs.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php');
    exit;
}

// Routage principal
switch ($module) {
    case 'accueil':
        // Page d'accueil principale
        $accueilController->index();
        break;

    case 'auth':
        switch ($action) {
            case 'login':
                $authController->login();
                break;
            
            case 'register':
                $authController->register();
                break;
             case 'confirmation': // Nouvelle action
                    $authController->confirmation();
                    break;
            case 'logout':
                $authController->logout();
                break;
                
            default:
                $_SESSION['message'] = "Action d'authentification inconnue.";
                $_SESSION['message_type'] = "danger";
                header('Location: index.php');
                exit;
        }
        break;
        
    case 'utilisateur':
        // Rétrocompatibilité avec l'ancien système
        switch ($action) {
            case 'inscription':
                header('Location: index.php?module=auth&action=register');
                exit;
                case 'confirmation': // Nouvelle action
                    $authController->confirmation();
                    break;
                
            case 'connexion':
                header('Location: index.php?module=auth&action=login');
                exit;
                
            case 'deconnexion':
                header('Location: index.php?module=auth&action=logout');
                exit;
                
            default:
                $_SESSION['message'] = "Action utilisateur inconnue.";
                $_SESSION['message_type'] = "danger";
                header('Location: index.php');
                exit;
        }
        break;
        
    case 'admin':
        switch ($action) {
            case 'dashboard':
                // Récupération de statistiques pour le dashboard
                $stats = [
                    'joueurs_attente' => $utilisateurController->countJoueursByStatus('en_attente'),
                    ///detection //tournoi
                ];
                include __DIR__ . '/../app/views/Users/dashboard_admin.php';
                break;
                
            case 'joueurs_attente':
                $utilisateurController->listeJoueursAttente();
                break;
                
            case 'approuver':
                if (isset($_GET['id'])) {
                    $utilisateurController->approuver($_GET['id']);
                } else {
                    $_SESSION['message'] = "Identifiant du joueur manquant.";
                    $_SESSION['message_type'] = "danger";
                    header('Location: index.php?module=admin&action=joueurs_attente');
                    exit;
                }
                break;
                
            case 'refuser':
                if (isset($_GET['id'])) {
                    $utilisateurController->refuser($_GET['id']);
                } else {
                    $_SESSION['message'] = "Identifiant du joueur manquant.";
                    $_SESSION['message_type'] = "danger";
                    header('Location: index.php?module=admin&action=joueurs_attente');
                    exit;
                }
                break;
                
            default:
                header('Location: index.php?module=admin&action=dashboard');
                exit;
        }
        break;
        
    case 'joueur':
        switch ($action) {
            case 'profile':  // Renommé pour cohérence avec le lien dans le header
                $joueurController->afficherProfil();
                break;
                
            case 'profil':  // Garder le nom original pour rétrocompatibilité
                header('Location: index.php?module=joueur&action=profile');
                exit;
                
            case 'mes_detections':
                $joueurController->mesDetections();
                break;
                
            case 'voir_justificatif':
                $joueurController->voirJustificatif();
                break;
                
            case 'edit_profil':
                $joueurController->editProfil();
                break;
                
            default:
                header('Location: index.php?module=joueur&action=profile');
                exit;
        }
        break;
        
    case 'detection':
        switch ($action) {
            case 'index':
                $detectionController->index();
                break;
                
            case 'list':
                if (isset($_GET['status']) && !empty($_GET['status'])) {
                    $detectionController->listByStatus($_GET['status']);
                } else {
                    $detectionController->index();
                }
                break;
                
            case 'show':
                if (isset($_GET['id'])) {
                    $detectionController->show($_GET['id']);
                } else {
                    $_SESSION['message'] = "ID de détection manquant.";
                    $_SESSION['message_type'] = "danger";
                    header('Location: index.php?module=detection&action=list');
                    exit;
                }
                break;
                
            case 'create':
                // Seul l'admin peut créer des détections
                if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
                    $_SESSION['message'] = "Accès refusé. Seuls les administrateurs peuvent créer des détections.";
                    $_SESSION['message_type'] = "danger";
                    header('Location: index.php?module=detection&action=list');
                    exit;
                }
                $detectionController->create();
                break;
                
            case 'edit':
                // Seul l'admin peut modifier des détections
                if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
                    $_SESSION['message'] = "Accès refusé. Seuls les administrateurs peuvent modifier des détections.";
                    $_SESSION['message_type'] = "danger";
                    header('Location: index.php?module=detection&action=show&id=' . $_GET['id']);
                    exit;
                }
                
                if (isset($_GET['id'])) {
                    $detectionController->edit($_GET['id']);
                } else {
                    $_SESSION['message'] = "ID de détection manquant.";
                    $_SESSION['message_type'] = "danger";
                    header('Location: index.php?module=detection&action=list');
                    exit;
                }
                break;
                
            case 'delete':
                // Seul l'admin peut supprimer des détections
                if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
                    $_SESSION['message'] = "Accès refusé. Seuls les administrateurs peuvent supprimer des détections.";
                    $_SESSION['message_type'] = "danger";
                    header('Location: index.php?module=detection&action=list');
                    exit;
                }
                
                if (isset($_GET['id'])) {
                    $detectionController->delete($_GET['id']);
                } else {
                    $_SESSION['message'] = "ID de détection manquant.";
                    $_SESSION['message_type'] = "danger";
                    header('Location: index.php?module=detection&action=list');
                    exit;
                }
                break;
                
            // Inscription à une détection pour les joueurs
            case 'register':
                if (!isset($_SESSION['user'])) {
                    $_SESSION['message'] = "Veuillez vous connecter pour vous inscrire à une détection.";
                    $_SESSION['message_type'] = "warning";
                    header('Location: index.php?module=auth&action=login');
                    exit;
                }
                
                if (isset($_GET['id'])) {
                    $joueurController->inscrireDetection($_GET['id']);
                } else {
                    $_SESSION['message'] = "ID de détection manquant.";
                    $_SESSION['message_type'] = "danger";
                    header('Location: index.php?module=detection&action=list');
                    exit;
                }
                break;
                
            default:
                header('Location: index.php?module=detection&action=list');
                exit;
        }
        break;
        
    case 'tournoi':
        switch ($action) {
            case 'list':
                $tournoiController->index();
                break;
                
            // Ajoutez d'autres actions pour les tournois ici
                
            default:
                header('Location: index.php?module=tournoi&action=list');
                exit;
        }
        break;
        
    default:
        // Module non reconnu
        $_SESSION['message'] = "Module non reconnu.";
        $_SESSION['message_type'] = "danger";
        header('Location: index.php');
        exit;
}
