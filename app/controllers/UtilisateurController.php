<?php
namespace App\controllers;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PDO;
use App\models\Utilisateur;
                                              
class UtilisateurController {
    private $model;
    private $detectionController;
    private $matchController;
   private $pdo;

  
    
public function __construct($pdo) {
$this->model = new Utilisateur($pdo);
}

    public function dashboard() {
        $detectionController = new \App\controllers\DetectionController($this->pdo);
        $tournoiController = new \App\controllers\TournoiController($this->pdo);
        $matchController = new MatchController($this->pdo);
    
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION['message'] = "Veuillez vous connecter.";
            $_SESSION['message_type'] = "warning";
            header('Location: index.php?module=auth&action=login');
            exit;
        }
        
        // Séparer les vues selon le rôle
        if ($_SESSION['user']['role'] === 'admin') {

         require_once __DIR__ . '/../../app/views/Admin/dashboard_admin.php';
        
        } else {
            // Dashboard pour utilisateur normal
         
            require_once __DIR__ . '/../../app/views/Joueurs/dashboard.php';
      
        }
    }
    

    public function show() {
        // Récupérer l'ID depuis l'URL
        $id = $_GET['id'] ?? null;
        
        // Vérifier que l'ID est valide
        if (!$id || !is_numeric($id)) {
            // Afficher un message d'erreur ou rediriger
            echo "ID invalide";
            return;
        }
        
        // Récupérer le joueur depuis le modèle
        $joueur = $this->model->getPlayerById($id);
        
        // Si aucun joueur n'est trouvé, afficher une erreur
        if (!$joueur) {
            echo "Joueur non trouvé";
            return;
        }
        
        // Afficher le joueur (par exemple avec une vue)
        require_once __DIR__ . '/../views/Admin/joueur_details.php';
    }

/**
 * Affiche le formulaire d'édition (GET)
 * Accessible via: index.php?module=utilisateur&action=edit&id=123
 */
public function edit() {
    // Récupérer l'ID depuis l'URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if (!$id) {
        $_SESSION['error'] = "ID de l'utilisateur invalide.";
        header('Location: index.php?module=utilisateur&action=index');
        exit;
    }
    
    // Récupérer les données de l'utilisateur
    $utilisateur = $this->model->getPlayerById($id);
    
    if (!$utilisateur) {
        $_SESSION['error'] = "Utilisateur non trouvé.";
        header('Location: index.php?module=utilisateur&action=index');
        exit;
    }
 
      // Afficher le joueur (par exemple avec une vue)
      require_once __DIR__ . '/../views/Admin/joueur_edit.php';
 
  
}

    /**
 * Traite la soumission du formulaire d'édition (POST)
 * Accessible via: index.php?module=utilisateur&action=update
 */
public function update() {
    // Vérifier que la requête est bien en POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?module=utilisateur&action=index');
        exit;
    }
    
    // Récupérer l'ID depuis le formulaire
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if (!$id) {
        $_SESSION['error'] = "ID de l'utilisateur invalide.";
        header('Location: index.php?module=utilisateur&action=index');
        exit;
    }
    
    // Nettoyer et valider les données du formulaire
    $cleanData = [
        'nom' => trim(htmlspecialchars($_POST['nom'] ?? '')),
        'prenom' => trim(htmlspecialchars($_POST['prenom'] ?? '')),
        'email' => filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL),
        'telephone' => trim(htmlspecialchars($_POST['telephone'] ?? '')),
        'poste' => trim(htmlspecialchars($_POST['poste'] ?? '')),
        'niveau_jeu' => trim(htmlspecialchars($_POST['niveau_jeu'] ?? '')),
        'taille' => !empty($_POST['taille']) ? floatval($_POST['taille']) : null,
        'poids' => !empty($_POST['poids']) ? floatval($_POST['poids']) : null,
        'nationalite' => trim(htmlspecialchars($_POST['nationalite'] ?? '')),
        'statut' => in_array($_POST['statut'] ?? '', ['en_attente', 'approuve', 'refuse']) ? $_POST['statut'] : 'en_attente',
        'date_naissance' => !empty($_POST['date_naissance']) ? $_POST['date_naissance'] : null,
    ];
    
    // Ajouter le mot de passe uniquement s'il est fourni
    if (!empty($_POST['mot_de_passe'])) {
        $cleanData['mot_de_passe'] = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    }
    
    // Appeler le modèle pour mettre à jour
    $success = $this->model->update($id, $cleanData);
    
    if ($success) {
        $_SESSION['success'] = "Joueur mis à jour avec succès !";
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour du joueur.";
    }
    
    // Rediriger vers la liste des utilisateurs
    header('Location: index.php?module=utilisateur&action=index');
    exit;
}

/**
 * Supprime un joueur
 * Accessible via: index.php?module=joueur&action=delete&id=123
 */
public function delete() {
    // Récupérer l'ID depuis l'URL ou le formulaire
    $id = isset($_GET['id']) ? intval($_GET['id']) : 
          (isset($_POST['player_id']) ? intval($_POST['player_id']) : 0);
    
    if (!$id) {
        $_SESSION['error'] = "ID utilisateur invalide.";
        header('Location: index.php?module=utilisateur&action=index');
        exit;
    }
    
    // Vérifier si la suppression a réussi
    if ($this->model->delete($id)) {
        $_SESSION['success'] = "L'utilisateur a été supprimé avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur.";
    }
    
    // Rediriger vers la liste
    header('Location: index.php?module=utilisateur&action=index');
    exit;
}



    public function index() {
        // Récupère les joueurs depuis le modèle Utilisateur
        $joueurs = $this->model->getAllPlayers();
        
        // Inclut la vue pour afficher la liste des joueurs
        include __DIR__ . '/../views/Admin/liste_joueurs.php';
    }
      




    public function afficherJustificatif() {
        // Récupération de l'ID utilisateur
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!$id) {
            // Gérer l'erreur: ID manquant ou invalide
            header('Content-Type: image/png');
            $img = imagecreate(400, 100);
            $bgColor = imagecolorallocate($img, 255, 255, 255);
            $textColor = imagecolorallocate($img, 255, 0, 0);
            imagestring($img, 5, 10, 40, 'Erreur: ID utilisateur invalide', $textColor);
            imagepng($img);
            imagedestroy($img);
            exit;
        }
        

       
        // Récupérer le nom du fichier justificatif via le modèle
        $result = $this->model->getJustificatif($id);
        
        if (!$result) {
            header('HTTP/1.0 404 Not Found');
            exit('Justificatif non trouvé pour cet utilisateur');
        }
        
        $nom_fichier = $result['justificatif'];
   
        
        if (!$result || empty($result['justificatif'])) {
            // Gérer l'erreur: aucun justificatif trouvé
            header('Content-Type: image/png');
            $img = imagecreate(400, 100);
            $bgColor = imagecolorallocate($img, 255, 255, 255);
            $textColor = imagecolorallocate($img, 255, 0, 0);
            imagestring($img, 5, 10, 40, 'Erreur: Aucun justificatif trouvé', $textColor);
            imagepng($img);
            imagedestroy($img);
            exit;
        }
        
        // Chemin complet vers le fichier
        $fichier = '/var/www/secure_storage/justificatifs/' . $result['justificatif'];
        
        // Vérifier si le fichier existe
        if (!file_exists($fichier)) {
            // Gérer l'erreur: fichier introuvable
            error_log("Fichier non trouvé: $fichier");
            header('Content-Type: image/png');
            $img = imagecreate(400, 100);
            $bgColor = imagecolorallocate($img, 255, 255, 255);
            $textColor = imagecolorallocate($img, 255, 0, 0);
            imagestring($img, 5, 10, 40, 'Erreur: Fichier introuvable', $textColor);
            imagepng($img);
            imagedestroy($img);
            exit;
        }
        
        // Déterminer le type MIME en fonction de l'extension
        $extension = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
        switch ($extension) {
            case 'pdf':
                header('Content-Type: application/pdf');
                break;
            case 'png':
                header('Content-Type: image/png');
                break;
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                break;
            default:
                // Essayer de détecter le type de fichier
                if (function_exists('mime_content_type')) {
                    $mimeType = mime_content_type($fichier);
                    header("Content-Type: $mimeType");
                } else {
                    // Par défaut, servir comme fichier binaire
                    header('Content-Type: application/octet-stream');
                }
        }
        
        // Ajouter des en-têtes supplémentaires
        header('Content-Length: ' . filesize($fichier));
        header('Content-Disposition: inline; filename="' . basename($fichier) . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        
        // Lire et afficher le fichier
        readfile($fichier);
        exit;
    }
    


    public function Accepter_joueur($id) {
        // Vérifier que l'ID est valide
        if (!$id || !is_numeric($id)) {
            return false;
        }
        $joueur = $this->model->getPlayerById($id);
    
        
        // Vérifier que le joueur existe et est en attente
        
        if (!$joueur || $joueur['statut'] !== 'en_attente') {
            return false;
        }
        
        // Générer un mot de passe aléatoire si nécessaire
       // 1. Génération plus sécurisée du mot de passe
    $newPassword = bin2hex(random_bytes(8)); // 16 caractères aléatoires
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        
        // 2. Mise à jour atomique
    $result = $this->model->update($id, [
        'mot_de_passe' => $hashedPassword,
        'statut' => 'approuve',
        'is_temp_password' => 1 // Ajoutez ce champ dans votre table
    ]);

    if ($result) {
        // 3. Sécurisation des données avant envoi
        $this->envoyerEmailApprobation([
            'email' => $joueur['email'],
            'prenom' => htmlspecialchars($joueur['prenom']),
            'nom' => htmlspecialchars($joueur['nom'])
        ], $newPassword);
    }
   
        
        
        return $result;
    }





//--------------ENVOYER UN MAIL 
/**
 * Envoie un email de confirmation d'approbation au joueur
 * 
 * @param array $joueur Données du joueur (doit contenir email, nom, prenom)
 * @param string|null $motDePasse Le mot de passe généré (si applicable)
 * @return bool True si l'email a été envoyé avec succès, false sinon
 */
private function envoyerEmailApprobation($joueur, $motDePasse = null) {
    $mail = new PHPMailer(true);
    
    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Remplacez par votre serveur SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'melissamermouri609@gmail.com'; // Remplacez par votre email SMTP
        $mail->Password = 'oivwbutiwcswrpsk '; // Remplacez par votre mot de passe SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // ou PHPMailer::ENCRYPTION_SMTPS
        $mail->Port = 587; // ou 465 pour SSL
        
        // Destinataires
        $mail->setFrom('melissamermouri609@gmail.com', 'Plus Fort Ensemble');
        $mail->addAddress($joueur['email'], $joueur['prenom'] . ' ' . $joueur['nom']);
        
        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Votre inscription a été approuvée';
        
        // Construction du message
        $message = "<h1>Félicitations {$joueur['prenom']} !</h1>";
        $message .= "<p>Votre inscription au club a été approuvée.</p>";
        
        if ($motDePasse) {
            $message .= "<p>Voici vos identifiants pour vous connecter :</p>";
            $message .= "<ul>";
            $message .= "<li><strong>Email:</strong> {$joueur['email']}</li>";
            $message .= "<li><strong>Mot de passe temporaire:</strong> $motDePasse</li>";
            $message .= "</ul>";
            $message .= "<p>Nous vous recommandons de changer ce mot de passe après votre première connexion.</p>";
        }
        
        $message .= "<p>Vous pouvez maintenant vous connecter à votre espace membre : <a href=\"https://votresite.com/connexion\">https://votresite.com/connexion</a></p>";
        $message .= "<p>Cordialement,<br>L'équipe du club</p>";
        
        $mail->Body = $message;
        
        // Version texte pour les clients email qui ne supportent pas HTML
        $mail->AltBody = "Félicitations {$joueur['prenom']} !\n\n"
            . "Votre inscription au club a été approuvée.\n\n"
            . ($motDePasse ? "Voici vos identifiants pour vous connecter :\n"
                . "Email: {$joueur['email']}\n"
                . "Mot de passe temporaire: $motDePasse\n\n"
                . "Nous vous recommandons de changer ce mot de passe après votre première connexion.\n\n" : "")
            . "Vous pouvez maintenant vous connecter à votre espace membre : https://votresite.com/connexion\n\n"
            . "Cordialement,\nL'équipe du club";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Loguer l'erreur (vous devriez avoir un système de logs)
        error_log("Erreur d'envoi d'email: " . $mail->ErrorInfo);
        return false;
    }
}

























    public function refuser_joueur($id) {
        // Vérifier que l'ID est valide
        if (!$id || !is_numeric($id)) {
            return false;
        }
        
        // Vérifier que le joueur existe
        $joueur = $this->model->getPlayerById($id);
        if (!$joueur) {
            return false;
        }
        
        // Mettre à jour le statut du joueur
        $result = $this->model->updatePlayerStatus($id, 'refuse');
        
        // Envoyer un email de refus
       // if ($result) {
          /*  $this->sendRejectionEmail($joueur);
        }*/
        
        return $result;
    }
    
    // Admin: Approuver un joueur
    public function approuver($id) {
        if (!$this->isAdmin()) {
            header('Location: index.php?module=utilisateur&action=connexion');
            exit;
        }
        
        if ($this->model->approuverJoueur($id)) {
            $_SESSION['message'] = "Le joueur a été approuvé avec succès.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Erreur lors de l'approbation du joueur.";
            $_SESSION['message_type'] = "danger";
        }
        
        header('Location: index.php?module=utilisateur&action=attente');
        exit;
    }
    
    // Admin: Refuser un joueur
    public function refuser($id) {
        if (!$this->isAdmin()) {
            header('Location: index.php?module=utilisateur&action=connexion');
            exit;
        }
        
        if ($this->model->refuserJoueur($id)) {
            $_SESSION['message'] = "Le joueur a été refusé.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Erreur lors du refus du joueur.";
            $_SESSION['message_type'] = "danger";
        }
        
        header('Location: index.php?module=utilisateur&action=attente');
        exit;
    }
    
    // Vérification si l'utilisateur est admin
    private function isAdmin() {
        session_start();
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }


   
    
        // Méthode pour compter les joueurs selon leur statut
        public function countJoueursByStatus($statut) {
            return $this->model->countByStatus($statut);
        }
    
        // Méthode pour lister les joueurs en attente
        public function listeJoueursAttente() {
           return  $this->model->getByStatus('en_attente');    
        }
    
       
        public function voirJustificatif($id) {
            // Vérifier si l'utilisateur est admin
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
                $_SESSION['message'] = "Accès refusé";
                $_SESSION['message_type'] = "danger";
                header('Location: index.php');
                exit;
            }
            
            $id = intval($id);
            $utilisateur = $this->model->getById($id);
            
            if (!$utilisateur) {
                $_SESSION['message'] = "Utilisateur introuvable";
                $_SESSION['message_type'] = "danger";
                header('Location: index.php?module=admin&action=joueurs_attente');
                exit;
            }
            
            // Vérifier que l'utilisateur a bien un justificatif
            if (empty($utilisateur['justificatif'])) {
                $_SESSION['message'] = "Cet utilisateur n'a pas fourni de justificatif";
                $_SESSION['message_type'] = "warning";
                header('Location: index.php?module=admin&action=joueurs_attente');
                exit;
            }
            
            // Chemin vers le justificatif (à adapter selon votre configuration)
            $chemin_justificatif = '/var/www/secure_storage/justificatifs/' . $utilisateur['justificatif'];
            
            if (!file_exists($chemin_justificatif)) {
                $_SESSION['message'] = "Le fichier justificatif est introuvable";
                $_SESSION['message_type'] = "danger";
                header('Location: index.php?module=admin&action=joueurs_attente');
                exit;
            }
            
            // Déterminer le type MIME du fichier
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $chemin_justificatif);
            finfo_close($finfo);
            
            // Envoyer les entêtes HTTP appropriés
            header('Content-Type: ' . $mime);
            header('Content-Disposition: inline; filename="' . basename($utilisateur['justificatif']) . '"');
            header('Content-Length: ' . filesize($chemin_justificatif));
            
            // Envoyer le fichier
            readfile($chemin_justificatif);
            exit;
        }
        


        /**
 * Supprime un utilisateur de manière sécurisée
 * 
 * @param int $id ID de l'utilisateur à supprimer
 * @return bool True si suppression réussie, false sinon
 */
public function deleteUser($id) {
   
    // 2. Vérification des droits (seul un admin peut supprimer)
    if (!$this->isAdmin()) {
        header('HTTP/1.1 403 Forbidden');
        return false;
    }

    // 3. Vérification que l'utilisateur existe
    $user = $this->model->getPlayerById($id);
    if (!$user) {
        throw new RuntimeException("Utilisateur introuvable");
    }

    // 4. Suppression sécurisée en transaction
    try {
        // a. D'abord supprimer les dépendances (ex: justificatifs)
        $this->deleteUserFiles($user);

        // b. Puis supprimer l'utilisateur
        $success = $this->model->delete($id);

        // c. Log l'action
        if ($success) {
            error_log("Utilisateur #$id supprimé par " . $_SESSION['user']['id']);
        }

        return $success;
    } catch (Exception $e) {
        error_log("Erreur suppression utilisateur #$id : " . $e->getMessage());
        return false;
    }
}

/**
 * Supprime les fichiers associés à un utilisateur
 */
private function deleteUserFiles($user) {
    if (!empty($user['justificatif'])) {
        $filePath = '/var/www/secure_storage/justificatifs/' . $user['justificatif'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}

    }


