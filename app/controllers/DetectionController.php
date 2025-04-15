<?php
namespace App\controllers;

use App\models\Detection;

class DetectionController {
    private $detectionModel;
    
    public function __construct($pdo) {
        $this->detectionModel = new Detection($pdo);
    }
    
    public function index() {
        $detections = $this->detectionModel->getAll();
        require_once __DIR__ . '/../views/Detection/list.php';   }
    
    public function show($id) {
        $detection = $this->detectionModel->getDetectionById($id);
        if (!$detection) {
            header('Location: index.php?error=detection_not_found');
            exit;
        }
               require_once __DIR__ . '/../views/Detection/show.php';
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
                header('Location: index.php?success=detection_created');
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
                header('Location: index.php?success=detection_updated');
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
            header('Location: index.php?success=detection_deleted');
        } else {
            header('Location: index.php?error=deletion_failed');
        }
        exit;
    }
    
    public function listByStatus($status) {
        $detections = $this->detectionModel->getDetectionsByStatus($status);
               require_once __DIR__ . '/../views/Detection/list.php';
    }
}