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
       
 }





// Lister les detections ----------------------------------------------------------------------------------------------

 public function index() {
    $detections = $this->detectionModel->getAll();
    require_once __DIR__ . '/../views/Detection/list.php';   }


// afficher la detection avec ses details 
    public function show() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = "Identifiant de détection invalide";
            header('Location: ?module=detection&action=index');
            exit;
        }

        $id = $_GET['id'];
        $detection = $this->detectionModel->getDetectionById($id);

        if (!$detection) {
            $_SESSION['error'] = "Détection introuvable";
            header('Location: ?module=detection&action=index');
            exit;
        }

        // Vérifier si l'utilisateur est connecté et récupérer son rôle
        $isUserLoggedIn = isset($_SESSION['user']);
        $isAdmin = $isUserLoggedIn && $_SESSION['user']['role'] === 'admin';
        $isJoueur = $isUserLoggedIn && $_SESSION['user']['role'] === 'joueur';
        
        // Récupérer les statistiques de participation
        $currentParticipants = $this->detectionModel->countParticipantsByDetection($id);
        $maxParticipants = $detection['maxParticipants'] ?? 30;
        
        // Si l'utilisateur est un joueur, vérifier s'il est déjà inscrit
        $inscription = null;
        if ($isJoueur) {
            $joueurId = $_SESSION['user']['id'];
            $inscription = $this->detectionModel->getInscription($joueurId, $id);
        }

        $title = "Détail de la détection : " . htmlspecialchars($detection['name']);
          // Affichage du formulaire de création
    require_once __DIR__ . '/../views/Detection/show.php';
    }


// Creer une detection -------------------------------------------------------------------------------------------------

public function create() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des données du formulaire
        $nom = $_POST['name'] ?? '';
        $date = $_POST['date'] ?? '';
        $heure = $_POST['heure'] ?? '14:00';
        $lieu = $_POST['lieu'] ?? '';
        $partnerClub = $_POST['partnerClub'] ?? '';
        $categorie = $_POST['categorie'] ?? '';
        $description = $_POST['description'] ?? '';
        $maxParticipants = intval($_POST['max_participants'] ?? 30);
        $status = $_POST['status'] ?? 'planned';
        $date_fin_inscription = $_POST['date_fin_inscription'] ?? null;
        
        // Initialisation de la variable logo_club
        $logo_club = '';
        $errors = [];
        
        // Traitement du logo si un fichier a été téléchargé
        if (isset($_FILES['logo_club']) && $_FILES['logo_club']['error'] == 0) {
            $absoluteUploadDir = '/var/www/html/FootballProject/uploads/logos/';
            $relativePath = '/FootballProject/uploads/logos/';
            
         // Vérifier que le répertoire existe et est accessible en écriture
    if (!is_dir($absoluteUploadDir) || !is_writable($absoluteUploadDir)) {
        $error = "Le répertoire pour les logos est inaccessible ou n'a pas les permissions d'écriture.";
        $formData = $_POST;
        require __DIR__ . '/../views/Equipes/create.php';

        return;
    }
    

            
            if (!is_dir($absoluteUploadDir) || !is_writable($absoluteUploadDir)) {
                $errors[] = "Le répertoire pour les logos est inaccessible ou n'a pas les permissions d'écriture.";
            } else {
                $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
                if (!in_array($_FILES['logo_club']['type'], $allowedTypes)) {
                    $errors[] = "Seuls les formats JPG, PNG et GIF sont acceptés.";
                } else {
                    if ($_FILES['logo_club']['size'] > 2 * 1024 * 1024) {
                        $errors[] = "La taille du fichier doit être inférieure à 2 Mo.";
                    } else {
                        $fileName = time() . '_' . basename($_FILES['logo_club']['name']);
                        $absoluteTargetPath = $absoluteUploadDir . $fileName;
                        $logo_club = $relativePath . $fileName;
                        
                        if (move_uploaded_file($_FILES['logo_club']['tmp_name'], $absoluteTargetPath)) {
                            // Le fichier a été déplacé avec succès
                            // Ajoutez un message de débogage
                            error_log("Logo uploadé avec succès vers: " . $absoluteTargetPath);
                            error_log("Chemin relatif enregistré: " . $logo_club);
                        } else {
                            $errors[] = "Une erreur est survenue lors du téléchargement du logo. Code: " . $_FILES['logo_club']['error'];
                            error_log("Erreur d'upload: " . $_FILES['logo_club']['error']);
                        }
                    }
                }
            }
        } else if (isset($_FILES['logo_club']) && $_FILES['logo_club']['error'] != 0) {
            // Affichage de l'erreur spécifique pour le débogage
            $uploadErrors = array(
                1 => "Le fichier dépasse la taille maximale définie dans php.ini",
                2 => "Le fichier dépasse la taille maximale spécifiée dans le formulaire HTML",
                3 => "Le fichier n'a été que partiellement uploadé",
                4 => "Aucun fichier n'a été uploadé",
                6 => "Le dossier temporaire est manquant",
                7 => "Échec d'écriture du fichier sur le disque",
                8 => "L'upload a été arrêté par une extension"
            );
            $errorCode = $_FILES['logo_club']['error'];
            $errorMessage = isset($uploadErrors[$errorCode]) ? $uploadErrors[$errorCode] : "Erreur inconnue";
            error_log("Erreur upload logo: " . $errorMessage . " (Code: " . $errorCode . ")");
        }
        
        // Validation supplémentaire
        if (empty($nom)) {
            $errors[] = "Le nom est obligatoire";
        }
        if (empty($date)) {
            $errors[] = "La date est obligatoire";
        }
        
        // Débogage des valeurs avant insertion
        error_log("Valeurs à insérer - Nom: $nom, Date: $date, Logo: $logo_club");
        
        if (empty($errors)) {
            // Appel à la méthode create du modèle
            $result = $this->detectionModel->create(
                $nom, $date, $heure, $lieu, $partnerClub, 
                $logo_club, $categorie, $description, 
                $maxParticipants, $status, $date_fin_inscription
            );
            
            if ($result) {
                // Message de succès et redirection
                $_SESSION['success_message'] = "La détection a été créée avec succès!";
                header('Location: ?module=detection&action=index');
                exit;
            } else {
                $error = "Erreur lors de la création de la détection";
                error_log("Échec de l'insertion en base de données");
                require_once __DIR__ . '/../views/Detection/create.php';
                return;
            }
        } else {
            // Affichage des erreurs
            $error = implode("<br>", $errors);
            require_once __DIR__ . '/../views/Detection/create.php';
            return;
        }
    }
    
    // Affichage du formulaire de création
    require_once __DIR__ . '/../views/Detection/create.php';
}





// Modifier une detection -------------------------------------------------------------------------------------------------
public function edit() {
    // Vérifier les permissions
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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer et valider les données du formulaire
        $data = [
            'name' => trim($_POST['nom'] ?? ''),
            'date' => trim($_POST['date'] ?? ''),
            'heure' => trim($_POST['heure'] ?? ''),
            'location' => trim($_POST['lieu'] ?? ''),
            'partner_club' => trim($_POST['partnerClub'] ?? ''),
            'age_category' => trim($_POST['categorie'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'max_participants' => intval($_POST['maxParticipants'] ?? 0),
            'status' => trim($_POST['status'] ?? 'planned'),
            'date_fin_inscription' => trim($_POST['date_fin_inscription'] ?? '')
        ];


        



        // Gestion du téléchargement du nouveau logo (si fourni)
        if (isset($_FILES['logo_club']) && $_FILES['logo_club']['error'] === UPLOAD_ERR_OK) {
               // Définir le chemin absolu du répertoire que vous avez déjà créé
    $absoluteUploadDir = '/var/www/html/FootballProject/uploads/logos/';
    
    // Définir le chemin relatif pour les liens dans votre application
    $relativePath = '/FootballProject/uploads/logos/'; // Assurez-vous que ce chemin est accessible via votre serveur web
    
    // Vérifier que le répertoire existe et est accessible en écriture
    if (!is_dir($absoluteUploadDir) || !is_writable($absoluteUploadDir)) {
        $error = "Le répertoire pour les logos est inaccessible ou n'a pas les permissions d'écriture.";
        $formData = $_POST;
        require __DIR__ . '/../views/Equipes/create.php';

        return;
    }
    
    // Générer un nom de fichier unique pour éviter les écrasements
    $fileName = time() . '_' . basename($_FILES['logo']['name']);
    
    // Chemin absolu complet pour le déplacement du fichier
    $absoluteTargetPath = $absoluteUploadDir . $fileName;
    
    // Chemin relatif pour stockage en base de données et affichage
    $logoPath = $relativePath . $fileName;
      
            if (move_uploaded_file($_FILES['logo_club']['tmp_name'], $absoluteTargetPath)) {
                $data['logo_club'] = $logoPath;
            } else {
                $_SESSION['error'] = "Erreur lors du téléchargement du logo.";
            }
        } elseif (isset($_POST['remove_logo'])) {
            $data['logo_club'] = null; // Enlever le logo si demandé
        } else {
            $data['logo_club'] = $detection['logo_club']; // Garde l'ancien logo
        }

        // Validation des données
        $error = '';
        if (empty($data['name']) || empty($data['date']) || empty($data['location'])) {
            $error = "Les champs Nom, Date et Lieu sont obligatoires.";
        } elseif ($data['max_participants'] <= 0) {
            $error = "Le nombre maximum de participants doit être supérieur à zéro.";
        }

        // Mise à jour
        if (empty($error)) {
            $result = $this->detectionModel->update($id, $data);
            
            if ($result) {
                $_SESSION['message'] = "La détection a été mise à jour avec succès.";
                $_SESSION['message_type'] = "success";
                header('Location: index.php?module=detection&action=show&id=' . $id);
                exit;
            } else {
                $_SESSION['error'] = "Une erreur est survenue lors de la mise à jour de la détection.";
            }
        } else {
            $_SESSION['error'] = $error; // Erreur de validation
        }
        
        $_SESSION['form_data'] = $_POST; // Pour garder les données dans le formulaire
        header('Location: index.php?module=detection&action=edit&id=' . $id); // Rediriger pour afficher les messages
        exit;
    }

    // Passer les données à la vue
    $pageTitle = "Modifier une détection";

    // Intégrer les données de la détection dans le tableau de formulaire
    $formData = $_SESSION['form_data'] ?? $detection;
    unset($_SESSION['form_data']); // Nettoyage après utilisation

    require_once __DIR__ . '/../views/Detection/edit.php';
}


// Dtection by ID ---------------------------------------------------------------------------------------------------

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

public function delete() {
    $id = $_POST['id'] ?? 0;  // Récupère l'ID à partir des données POST
    if ($id && $this->detectionModel->delete($id)) {
        header('Location: index.php?module=detection&action=index&success=deleted');
    } else {
        header("Location: index.php?module=detection&action=index&error=delete_failed");
    }
    exit;
}



    
    
    public function listByStatus($status) {
        $detections = $this->detectionModel->getDetectionsByStatus($status);
               require_once __DIR__ . '/../views/Detection/list.php';
    }
 



//---------------------------------------------------Traite l'inscription à une détection--------------------------------



public function register() {
    // Vérifier que l'utilisateur est connecté et est un joueur
    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'joueur') {
        $_SESSION['error'] = "Vous devez être connecté en tant que joueur pour vous inscrire.";
        header('Location: ?module=auth&action=login&redirect=' . urlencode('?module=detection&action=show&id=' . $_GET['id']));
        exit;
    }
    
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        $_SESSION['error'] = "Identifiant de détection invalide";
        header('Location: ?module=detection&action=index');
        exit;
    }
    
    $id = $_GET['id'];
    $joueurId = $_SESSION['user']['id'];
    $detection = $this->detectionModel->getDetectionById($id);
    
    if(!$detection) {
        $_SESSION['error'] = "Cette détection n'existe pas.";
        header('Location: ?module=detection&action=index');
        exit;
    }
    
    // Vérifier si la capacité maximale est atteinte
    $currentParticipants = $this->detectionModel->countParticipantsByDetection($id);
    if($currentParticipants >= $detection['max_participants']) {
        $_SESSION['error'] = "Désolé, cette détection a atteint sa capacité maximale.";
        header('Location: ?module=detection&action=show&id=' . $id);
        exit;
    }
    
    // Vérifier si le joueur n'est pas déjà inscrit
    if($this->detectionModel->getInscription($joueurId, $id)) {
        $_SESSION['info'] = "Vous êtes déjà inscrit à cette détection.";
        header('Location: ?module=detection&action=show&id=' . $id);
        exit;
    }
    
    // Procéder à l'inscription
    $inscriptionData = [
        'joueur_id' => $joueurId,
        'detection_id' => $id,
        'status' => 'registered', // Statut initial en attente
        'date_inscription' => date('Y-m-d H:i:s')
    ];
    
    $result = $this->detectionModel->addInscription($inscriptionData);
    
    if($result) {
        $_SESSION['success'] = "Votre inscription a été enregistrée avec succès ! Un responsable va la valider prochainement.";
    } else {
        $_SESSION['error'] = "Une erreur est survenue lors de l'inscription.";
    }
    
    header('Location: ?module=detection&action=show&id=' . $id);
    exit;
}

// Ajoutez également la méthode pour annuler une inscription
public function cancel() {
    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'joueur') {
        $_SESSION['error'] = "Vous n'êtes pas autorisé à effectuer cette action.";
        header('Location: ?module=detection&action=index');
        exit;
    }
    
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        $_SESSION['error'] = "Identifiant de détection invalide";
        header('Location: ?module=detection&action=index');
        exit;
    }
    
    $id = $_GET['id'];
    $joueurId = $_SESSION['user']['id'];
    
    // Vérifier si le joueur est bien inscrit
    $inscription = $this->detectionModel->getInscription($joueurId, $id);
    if(!$inscription) {
        $_SESSION['error'] = "Vous n'êtes pas inscrit à cette détection.";
        header('Location: ?module=detection&action=show&id=' . $id);
        exit;
    }
    
    // Annuler l'inscription
    $result = $this->detectionModel->cancelInscription($joueurId, $id);
    
    if($result) {
        $_SESSION['success'] = "Votre inscription a été annulée avec succès.";
    } else {
        $_SESSION['error'] = "Une erreur est survenue lors de l'annulation de votre inscription.";
    }
    
    header('Location: ?module=detection&action=show&id=' . $id);
    exit;
}





// Telecharger le justificatif d'inscription -------------------------------------
/**
 * Affiche ou génère une facture/confirmation d'inscription pour un joueur
 */
public function invoice() {
    // Vérifier que l'utilisateur est connecté
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Vous devez être connecté pour voir votre inscription";
        header('Location: ?module=auth&action=login');
        exit;
    }

    // Récupérer l'ID de la détection
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        $_SESSION['error'] = "Identifiant de détection invalide";
        header('Location: ?module=detection&action=index');
        exit;
    }

    $detectionId = $_GET['id'];
    $joueurId = $_SESSION['user']['id'];

    // Récupérer les informations de la détection
    $detection = $this->detectionModel->getDetectionById($detectionId);
    if (!$detection) {
        $_SESSION['error'] = "Détection introuvable";
        header('Location: ?module=detection&action=index');
        exit;
    }

    // Récupérer les informations d'inscription
    $inscription = $this->detectionModel->getInscription($joueurId, $detectionId);
    if (!$inscription) {
        $_SESSION['error'] = "Vous n'êtes pas inscrit à cette détection";
        header('Location: ?module=detection&action=show&id=' . $detectionId);
        exit;
    }

    // Récupérer les informations de l'utilisateur
    $user = $this->userModel->getPlayerById($joueurId);

    // Format de sortie (HTML par défaut, mais peut être PDF si spécifié)
    $format = $_GET['format'] ?? 'html';

    if ($format === 'pdf') {
        $this->generateInvoicePdf($detection, $inscription, $user);
    } else {
        // Afficher la page HTML
        require_once __DIR__ . '/../views/Detection/invoice.php';
    }
}

/**
 * Génère une facture/confirmation au format PDF
 */
private function generateInvoicePdf($detection, $inscription, $user) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    
    $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Informations du document
    $pdf->SetCreator('Football Detections');
    $pdf->SetAuthor('Football Detections');
    $pdf->SetTitle('Confirmation d\'inscription - ' . $detection['nom']);
    
    // En-tête et pied de page
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(true);
    $pdf->setFooterData(array(0,0,0), array(0,0,0));
    $pdf->setFooterFont(Array('helvetica', '', 8));
    $pdf->SetFooterMargin(10);
    
    // Marges
    $pdf->SetMargins(15, 15, 15);
    
    // Ajouter une page
    $pdf->AddPage();
    
    // Logo et en-tête
    $pdf->Image(__DIR__ . '/../public/assets/img/logo.png', 15, 15, 50, 0, 'PNG', '', 'T', false, 300, '', false, false, 0);
    
    // Titre
    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->Cell(0, 20, 'Confirmation d\'inscription', 0, 1, 'R');
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Référence: ' . sprintf('DETECT-%05d', $detection['id']) . '-' . sprintf('USER-%05d', $user['id']), 0, 1, 'R');
    $pdf->Cell(0, 8, 'Date: ' . date('d/m/Y'), 0, 1, 'R');
    
    $pdf->Ln(10);
    
    // Informations de la détection
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Détail de la détection', 0, 1);
    $pdf->Ln(2);
    
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(40, 8, 'Événement:', 0);
    $pdf->Cell(0, 8, $detection['nom'], 0, 1);
    
    $pdf->Cell(40, 8, 'Date:', 0);
    $pdf->Cell(0, 8, date('d/m/Y', strtotime($detection['date'])) . ' à ' . date('H:i', strtotime($detection['heure'])), 0, 1);
    
    $pdf->Cell(40, 8, 'Lieu:', 0);
    $pdf->Cell(0, 8, $detection['lieu'], 0, 1);
    
    $pdf->Cell(40, 8, 'Catégorie:', 0);
    $pdf->Cell(0, 8, $detection['categorie'] ?? 'Toutes catégories', 0, 1);
    
    $pdf->Ln(10);
    
    // Informations du participant
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Informations du participant', 0, 1);
    $pdf->Ln(2);
    
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(40, 8, 'Nom:', 0);
    $pdf->Cell(0, 8, $user['nom'] . ' ' . $user['prenom'], 0, 1);
    
    $pdf->Cell(40, 8, 'Email:', 0);
    $pdf->Cell(0, 8, $user['email'], 0, 1);
    
    $pdf->Cell(40, 8, 'Téléphone:', 0);
    $pdf->Cell(0, 8, $user['telephone'] ?? 'Non renseigné', 0, 1);
    
    $pdf->Cell(40, 8, 'Date de naissance:', 0);
    $pdf->Cell(0, 8, $user['date_naissance'] ? date('d/m/Y', strtotime($user['date_naissance'])) : 'Non renseignée', 0, 1);
    
    $pdf->Ln(10);
    
    // Détails de l'inscription
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Détails de l\'inscription', 0, 1);
    $pdf->Ln(2);
    
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(40, 8, 'Date d\'inscription:', 0);
    $pdf->Cell(0, 8, date('d/m/Y à H:i', strtotime($inscription['date_inscription'])), 0, 1);
    
    $pdf->Cell(40, 8, 'Statut:', 0);
    $pdf->Cell(0, 8, $this->formatStatus($inscription['status']), 0, 1);
    
    // Si l'inscription a un coût
    if (isset($detection['prix']) && $detection['prix'] > 0) {
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Informations de paiement', 0, 1);
        $pdf->Ln(2);
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(40, 8, 'Montant:', 0);
        $pdf->Cell(0, 8, number_format($detection['prix'], 2, ',', ' ') . ' €', 0, 1);
        
        $pdf->Cell(40, 8, 'Statut du paiement:', 0);
        $pdf->Cell(0, 8, ($inscription['paiement_status'] ?? 'Non payé'), 0, 1);
        
        if (isset($inscription['paiement_date'])) {
            $pdf->Cell(40, 8, 'Date du paiement:', 0);
            $pdf->Cell(0, 8, date('d/m/Y', strtotime($inscription['paiement_date'])), 0, 1);
        }
    }
    
    $pdf->Ln(15);
    
    // Instructions supplémentaires
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 8, 'Informations importantes:', 0, 1);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 6, 'Veuillez vous présenter 30 minutes avant le début de la détection muni(e) de ce document et d\'une pièce d\'identité. N\'oubliez pas votre équipement sportif complet.', 0, 'L', 0, 1);
    
    $pdf->Ln(5);
    
    $pdf->MultiCell(0, 6, 'Pour toute question concernant cette détection, veuillez nous contacter à contact@footballdetections.com ou au 01 23 45 67 89.', 0, 'L', 0, 1);
    
    // Générer le nom du fichier
    $fileName = 'inscription_' . $detection['name'] . '_' . $user['nom'] . '.pdf';
    
    // Envoyer le PDF au navigateur
    $pdf->Output($fileName, 'D');
    exit;
}



// Exporter la liste des participants 
/**
 * Exporte la liste des participants inscrits à une détection
 */
public function export() {
    // Vérifier les droits d'administrateur
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        $_SESSION['error'] = "Vous n'avez pas les droits pour effectuer cette action";
        header('Location: ?module=detection&action=index');
        exit;
    }
    
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        $_SESSION['error'] = "Identifiant de détection invalide";
        header('Location: ?module=detection&action=index');
        exit;
    }
    
    $id = $_GET['id'];
    $format = $_GET['format'] ?? 'excel';
    
    // Récupérer la détection
    $detection = $this->detectionModel->getDetectionById($id);
    if (!$detection) {
        $_SESSION['error'] = "Cette détection n'existe pas";
        header('Location: ?module=detection&action=index');
        exit;
    }
    
    // Récupérer la liste des participants
    $participants = $this->detectionModel->getParticipantsByDetection($id);
    
    // Formater le nom du fichier
    $fileName = 'liste_inscrits_';
    if (isset($detection['title'])) {
        $fileName .= str_replace(' ', '_', $detection['title']) . '_';
    } elseif (isset($detection['nom'])) {
        $fileName .= str_replace(' ', '_', $detection['nom']) . '_';
    }
    $fileName .= date('Y-m-d');
    
    switch ($format) {
        case 'pdf':
            $this->exportToPdf($participants, $detection, $fileName);
            break;
    
    }
}



/**
 * Exporte les données au format PDF
 * Nécessite d'installer une bibliothèque comme FPDF ou TCPDF via Composer
 */
private function exportToPdf($participants, $detection, $fileName) {
    require_once __DIR__ . '/../../vendor/autoload.php'; // Pour utiliser TCPDF
    
    $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Informations du document
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Football Detections');
    $pdf->SetTitle('Liste des inscrits - ' . ($detection['title'] ?? $detection['nom'] ?? 'Détection'));
    $pdf->SetSubject('Liste des participants');
    
    // En-tête et pied de page
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(true);
    
    // Ajouter une page
    $pdf->AddPage();
    
    // Titre
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Liste des inscrits - ' . ($detection['title'] ?? $detection['nom'] ?? 'Détection'), 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, 'Date d\'export: ' . date('d/m/Y'), 0, 1, 'C');
    $pdf->Ln(10);
    
    // Tableau
    $pdf->SetFont('helvetica', 'B', 10);
    
    // En-têtes du tableau
    $headers = ['ID', 'Nom', 'Prénom', 'Téléphone', 'Date de naiss.', 'Inscription'];
    $widths = [15, 30, 30, 50, 30, 30, 30, 30];
    
    // Couleur d'en-tête
    $pdf->SetFillColor(220, 220, 220);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(128, 128, 128);
    $pdf->SetLineWidth(0.3);
    
    // Ajout des en-têtes
    for ($i = 0; $i < count($headers); $i++) {
        $pdf->Cell($widths[$i], 7, $headers[$i], 1, 0, 'C', true);
    }
    $pdf->Ln();
    
    // Couleur et police pour les données
    $pdf->SetFillColor(245, 245, 245);
    $pdf->SetTextColor(0);
    $pdf->SetFont('helvetica', '', 9);
    
    // Données
    $fill = false;
    foreach ($participants as $participant) {
        $pdf->Cell($widths[0], 6, $participant['id'], 1, 0, 'C', $fill);
        $pdf->Cell($widths[1], 6, $participant['nom'], 1, 0, 'L', $fill);
        $pdf->Cell($widths[2], 6, $participant['prenom'], 1, 0, 'L', $fill);
        $pdf->Cell($widths[3], 6, $participant['telephone'], 1, 0, 'C', $fill);
        $pdf->Cell($widths[4], 6, date('d/m/Y', strtotime($participant['date_naissance'])), 1, 0, 'C', $fill);
        $pdf->Cell($widths[5], 6, date('d/m/Y', strtotime($participant['date_inscription'])), 1, 0, 'C', $fill);
        $pdf->Ln();
        $fill = !$fill;
    }
    
    // Envoyer le PDF au navigateur
    $pdf->Output($fileName . '.pdf', 'D');
    exit;
}

/**
 * Formate le statut pour un affichage plus lisible
 */
private function formatStatus($status) {
    $statusLabels = [
        'pending' => 'En attente',
        'accepted' => 'Accepté',
        'rejected' => 'Refusé',
        'confirmed' => 'Confirmé',
        'cancelled' => 'Annulé',
        'waiting' => 'Liste d\'attente'
    ];
    
    return $statusLabels[$status] ?? ucfirst($status);
}



}


