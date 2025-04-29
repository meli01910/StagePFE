<?php
namespace App\controllers;

use App\models\Detection;
use App\models\Utilisateur;
use App\models\InscriptionDetection;
class DetectionController {
    private $detectionModel;
    private $inscriptionModel;
    private $userModel;

    public function __construct($pdo) {
        $this->detectionModel = new Detection($pdo);
        $this->userModel = new  Utilisateur($pdo);
        $this->inscriptionModel = new InscriptionDetection($pdo);
 }


 public function getDetectionById($id) {

        // Vérifier que l'ID est valide
        if (!$id || !is_numeric($id)) {
            return false;
        }
        
        // Récupérer le joueur depuis le modèle
        $d = $this->detectionModel->getDetectionById($id);
        
        // Si aucun joueur n'est trouvé, renvoyer false
        if (!$d) {
            return false;
        }
        
        return $d;
    }
    //lister les detections 
    public function index() {
        $detections = $this->detectionModel->getAll();
        require_once __DIR__ . '/../views/Detection/list.php';   }
    

    // afficher la detection avec ses details 
    public function show($id) {
        $detection = $this->detectionModel->getDetectionById($id);
        if (!$detection) {
            header('Location: index.php?error=detection_not_found');
            exit;
        }

        // Get the count of participants
    $detection['participant_count'] = $this->inscriptionModel->countParticipants($id);
    
               require_once __DIR__ . '/../views/Detection/show.php';
    }  
    
    
    public function getAllDetections() {
        return $this->detectionModel->getAll();
    }
    
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire

            $allowedStatus = ['planned', 'ongoing', 'completed'];
        
            // Validation du statut
            if (!in_array($_POST['status'], $allowedStatus)) {
                $error = "Statut invalide. Choisissez parmi: " . implode(', ', $allowedStatus);
                require_once __DIR__ . '/../views/Detection/create.php';
                return;
            }
            $nom = $_POST['nom'];
            $date = $_POST['date'];
            $lieu = $_POST['lieu'];
            $partnerClub = $_POST['partnerClub'];
            $categorie = $_POST['categorie'];
            $maxParticipants = (int)$_POST['maxParticipants'];
            $status = $_POST['status'];
            
            // Validation basique
            if (empty($nom) || empty($date) || empty($lieu) || empty($partnerClub) || empty($categorie) || $maxParticipants <= 0) {
                $error = "Veuillez remplir tous les champs correctement";
                       require_once __DIR__ . '/../views/Detection/create.php';
                return;
            }
            
            // Création de la détection
            if ($this->detectionModel->create($nom, $date, $lieu, $partnerClub, $categorie, $maxParticipants, $status)) {
                header('Location: index.php?module=admin&action=detections');
                exit;
            } else {
                $error = "Erreur lors de la création de la détection";
                        require_once __DIR__ . '/../views/Detection/create.php';
                return;
            }
        }
        
        // Affichage du formulaire
               require_once __DIR__ . '/../views/Detection/create.php';
    }
    
    public function edit($id) {
        $detection = $this->detectionModel->getDetectionById($id);
        
        if (!$detection) {
            header('Location: index.php?error=detection_not_found');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $nom = $_POST['nom'];
            $date = $_POST['date'];
            $lieu = $_POST['lieu'];
            $partnerClub = $_POST['partnerClub'];
            $categorie = $_POST['categorie'];
            $maxParticipants = (int)$_POST['maxParticipants'];
            $status = $_POST['status'];
            
            // Validation basique
            if (empty($nom) || empty($date) || empty($lieu) || empty($partnerClub) || empty($categorie) || $maxParticipants <= 0) {
                $error = "Veuillez remplir tous les champs correctement";
                        require_once __DIR__ . '/../views/Detection/edit.php';
                return;
            }
                   if ($this->detectionModel->update($id, $nom, $date, $lieu, $partnerClub, $categorie, $maxParticipants, $status)) {
                    header('Location: index.php?module=admin&action=detections');
                    exit;
            } else {
                $error = "Erreur lors de la mise à jour de la détection";
                        require_once __DIR__ . '/../views/Detection/edit.php';
                return;
            }
        }
        
                     require_once __DIR__ . '/../views/Detection/edit.php';
    }
    
    public function delete($id) {
          if ($this->detectionModel->delete($id)) {
            header('Location: index.php?module=admin&action=delete');
        } else {
            header('Location: index.php?error=deletion_failed');
        }
        exit;
    }
    
    public function listByStatus($status) {
        $detections = $this->detectionModel->getDetectionsByStatus($status);
               require_once __DIR__ . '/../views/Detection/list.php';
    }
 
    
    /**
     * Compte le nombre de sessions de détection à venir
     * @return int Nombre de sessions à venir
     */
    public function countUpcomingSessions() {
        return $this->detectionModel->countUpcoming();
    }



// s'inscrire a une detection

public function register($detection_id) {
    session_start();
    $joueur_id = $_SESSION['user']['id']; // Assume user ID is stored in session

    // Check if the player is already registered for this detection
    if ($this->inscriptionModel->exists($joueur_id, $detection_id)) {
        header('Location: index.php?error=already_registered');
        exit;
    }

    // Register the player for the detection
    if ($this->inscriptionModel->create($joueur_id, $detection_id)) {
        header('Location: index.php?module=detection&action=confirmation_inscription&id=' . $detection_id);
    } else {
        header('Location: index.php?error=registration_failed');
    }
    
    exit;
}
public function confirmation($detection_id) {
    $detection = $this->detectionModel->getDetectionById($detection_id);
    if (!$detection) {
        header('Location: index.php?error=detection_not_found');
        exit;
    }
    require_once __DIR__ . '/../views/Detection/confirmation_inscription.php';
}

}


