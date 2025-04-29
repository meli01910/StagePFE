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
            header('Location: index.php?module=detection&action=create');
            exit;
        } else {
            $error = "Erreur lors de la création de la détection";
                    require_once __DIR__ . '/../views/Detection/create.php';
            return;
        }
    }
    
    // Affichage du formulaire
           require_once __DIR__ . '/../views/Detection/list.php';
}






public function edit() {
    // Ajoutez ce code temporairement au début de votre méthode edit() pour déboguer
echo "<pre>";
echo "SESSION : ";
print_r($_SESSION);
echo "</pre>";


    // Vérifier les permissions (optionnel - si besoin de vérifier si l'utilisateur est admin)
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['message'] = "Vous n'avez pas les droits pour modifier une détection.";
        $_SESSION['message_type'] = "danger";
        header('Location: index.php?module=detection&action=index');
        exit;
    }
    
    // Récupérer l'ID depuis l'URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if (!$id) {
        $_SESSION['message'] = "ID de détection invalide.";
        $_SESSION['message_type'] = "danger";
        header('Location: index.php?module=detection&action=index');
        exit;
    }
    
    // Récupérer les données de la détection
    $detection = $this->detectionModel->getDetectionById($id);
    if (!$detection) {
        $_SESSION['message'] = "Détection introuvable.";
        $_SESSION['message_type'] = "danger";
        header('Location: index.php?module=detection&action=index');
        exit;
    }
    
    // Si le formulaire est soumis, traiter la mise à jour
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer et valider les données du formulaire
        $data = [
            'name' => trim($_POST['nom'] ?? ''),
            'date' => trim($_POST['date'] ?? ''),
            'location' => trim($_POST['lieu'] ?? ''),
            'partner_club' => trim($_POST['partnerClub'] ?? ''),
            'age_category' => trim($_POST['categorie'] ?? ''),
            'max_participants' => intval($_POST['maxParticipants'] ?? 0),
            'status' => trim($_POST['status'] ?? 'planned')
        ];
        
        // Validation basique
        if (empty($data['name']) || empty($data['date']) || empty($data['location'])) {
            $error = "Tous les champs obligatoires doivent être remplis.";
        } elseif ($data['max_participants'] <= 0) {
            $error = "Le nombre maximum de participants doit être supérieur à zéro.";
        } else {
            // Appeler la méthode update du modèle
            $result = $this->detectionModel->update($id, $data);
            
            if ($result) {
                $_SESSION['message'] = "La détection a été mise à jour avec succès.";
                $_SESSION['message_type'] = "success";
                header('Location: index.php?module=detection&action=index');
                exit;
            } else {
                $error = "Une erreur est survenue lors de la mise à jour de la détection.";
            }
        }
    }
    
    // Afficher le formulaire avec les données actuelles
    require_once __DIR__ . '/../views/templates/header.php';
    require_once __DIR__ . '/../views/Detection/edit.php';
    require_once __DIR__ . '/../views/templates/footer.php';
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
    
    
    public function getAllDetections() {
        return $this->detectionModel->getAll();
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


