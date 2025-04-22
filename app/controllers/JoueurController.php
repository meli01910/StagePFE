<?php
namespace App\controllers;

class JoueurController {
    private $model;
    private $detectionModel;
    
    public function __construct($pdo) {
        $this->model = new \App\models\Utilisateur($pdo);
        $this->detectionModel = new \App\models\Detection($pdo);
    }
    
    public function afficherProfil() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION['message'] = "Vous devez être connecté pour accéder à votre profil.";
            $_SESSION['message_type'] = "warning";
            header('Location: index.php?module=utilisateur&action=connexion');
            exit;
        }
        
        // Récupérer les infos du joueur
        $joueur = $_SESSION['user'];
        
        // Inclure la vue
        include __DIR__ . '/../views/Joueur/profil.php';
    }
    
    public function mesDetections() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION['message'] = "Vous devez être connecté pour accéder à vos inscriptions.";
            $_SESSION['message_type'] = "warning";
            header('Location: index.php?module=utilisateur&action=connexion');
            exit;
        }
        
        // Récupérer les inscriptions du joueur
        $inscriptions = $this->detectionModel->getInscriptionsByJoueur($_SESSION['user']['id']);
        
        // Inclure la vue
        include __DIR__ . '/../views/Joueur/mes_detections.php';
    }
    
    public function inscrireDetection($detection_id) {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION['message'] = "Vous devez être connecté pour vous inscrire à une détection.";
            $_SESSION['message_type'] = "warning";
            header('Location: index.php?module=utilisateur&action=connexion');
            exit;
        }
        
        // Vérifier si le joueur est approuvé
        if ($_SESSION['user']['statut'] !== 'approuve') {
            $_SESSION['message'] = "Vous devez être approuvé par un administrateur pour vous inscrire à une détection.";
            $_SESSION['message_type'] = "warning";
            header('Location: index.php?module=detection&action=show&id=' . $detection_id);
            exit;
        }
        
        // Inscrire le joueur
        $result = $this->detectionModel->inscrireJoueur($detection_id, $_SESSION['user']['id']);
        
        if ($result) {
            $_SESSION['message'] = "Vous êtes inscrit à cette détection avec succès !";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Erreur lors de l'inscription. Vous êtes peut-être déjà inscrit.";
            $_SESSION['message_type'] = "danger";
        }
        
        header('Location: index.php?module=detection&action=show&id=' . $detection_id);
        exit;
    }
    
    public function voirJustificatif() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION['message'] = "Accès non autorisé.";
            $_SESSION['message_type'] = "danger";
            header('Location: index.php');
            exit;
        }
        
        $fichier = $_SESSION['user']['justificatif'];
        
        if (empty($fichier)) {
            $_SESSION['message'] = "Aucun justificatif n'a été téléchargé.";
            $_SESSION['message_type'] = "warning";
            header('Location: index.php?module=joueur&action=profil');
            exit;
        }
        
        // Rediriger vers le contrôleur de justificatifs
        header('Location: index.php?module=justificatif&action=afficher&fichier=' . urlencode($fichier));
        exit;
    }
    
    public function editProfil() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION['message'] = "Vous devez être connecté pour modifier votre profil.";
            $_SESSION['message_type'] = "warning";
            header('Location: index.php?module=utilisateur&action=connexion');
            exit;
        }
        
        // Si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation et traitement des données
            // [...]
            
            $_SESSION['message'] = "Profil mis à jour avec succès.";
            $_SESSION['message_type'] = "success";
            header('Location: index.php?module=joueur&action=profil');
            exit;
        }
        
        // Récupérer les infos du joueur
        $joueur = $_SESSION['user'];
        
        // Inclure la vue
        include __DIR__ . '/../views/Joueur/edit_profil.php';
    }
}
