<?php
namespace App\controllers;

use App\models\Utilisateur;

class AuthController {
    private $utilisateurModel;
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->utilisateurModel = new Utilisateur($pdo);
    }
    
    public function login() {
        error_log("Début de la méthode login");
        
        // Vérifier si l'utilisateur est déjà connecté
        if (isset($_SESSION['user'])) {
            error_log("Utilisateur déjà connecté");
            $_SESSION['message'] = "Vous êtes déjà connecté.";
            $_SESSION['message_type'] = "info";
            header('Location: index.php');
            exit;
        }
        
        // Traitement du formulaire de connexion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("Formulaire POST reçu");
            $email = $_POST['email'] ?? '';
            $password = $_POST['mot_de_passe'] ?? '';
            
            error_log("Tentative de connexion avec email: " . $email);
            $user = $this->utilisateurModel->getByEmail($email);
            
            if ($user) {
                error_log("Utilisateur trouvé: ID=" . $user['id'] . ", Statut=" . $user['statut'] . ", Rôle=" . $user['role']);
                
                // Test du mot de passe
                $password_match = password_verify($password, $user['mot_de_passe']);
                error_log("Vérification du mot de passe: " . ($password_match ? "SUCCÈS" : "ÉCHEC"));
                
                if ($password_match) {
                    error_log("Authentification réussie");
                    
                    // Vérifier le rôle d'abord
                    if ($user['role'] === 'admin') {
                        error_log("Utilisateur admin - connexion autorisée");
                        // Les admins peuvent toujours se connecter, peu importe leur statut
                        $_SESSION['user'] = [
                            'id' => $user['id'],
                            'nom' => $user['nom'],
                            'prenom' => $user['prenom'],
                            'email' => $user['email'],
                            'role' => $user['role']
                        ];
                        
                        $_SESSION['message'] = "Connexion réussie. Bienvenue " . $user['prenom'] . " " . $user['nom'] . " !";
                        $_SESSION['message_type'] = "success";
                        
                        error_log("Redirection admin vers dashboard");
                        header('Location: index.php?module=utilisateur&action=dashboard');
                        exit;
                    } else {
                        // Pour les non-admins, vérifier le statut du compte
                        error_log("Utilisateur non-admin, vérification du statut: " . $user['statut']);
                        
                        if ($user['statut'] === 'approuve') {
                            error_log("Statut approuvé - connexion autorisée");
                            // Compte actif, connexion normale
                            $_SESSION['user'] = [
                                'id' => $user['id'],
                                'nom' => $user['nom'],
                                'prenom' => $user['prenom'],
                                'email' => $user['email'],
                                'role' => $user['role']
                            ];
                            
                            $_SESSION['message'] = "Connexion réussie. Bienvenue " . $user['prenom'] . " " . $user['nom'] . " !";
                            $_SESSION['message_type'] = "success";
                            
                            // Rediriger selon le rôle
                            if ($user['role'] === 'joueur') {
                                error_log("Redirection joueur vers dashboard");
                                header('Location: index.php?module=utilisateur&action=dashboard');
                            } else {
                                error_log("Redirection générique vers dashboard");
                                // Redirection générique si le rôle n'est pas spécifique
                                header('Location: index.php?module=utilisateur&action=dashboard');
                            }
                            error_log("Exit effectué après redirection");
                            exit;
                        } else if ($user['statut'] === 'en_attente') {
                            error_log("Statut en attente - redirection vers confirmation");
                            // Compte en attente, rediriger vers la page de confirmation
                            $_SESSION['inscription_reussie'] = true;
                            $_SESSION['inscription_data'] = [
                                'nom' => $user['nom'],
                                'prenom' => $user['prenom'],
                                'email' => $user['email']
                            ];
                            header('Location: index.php?module=auth&action=confirmation');
                            exit;
                        } else {
                            error_log("Statut non autorisé: " . $user['statut']);
                            // Compte refusé ou désactivé
                            $_SESSION['message'] = "Votre compte a été " . 
                                ($user['statut'] === 'refuse' ? "refusé" : "désactivé") . 
                                ". Veuillez contacter l'administrateur.";
                            $_SESSION['message_type'] = "danger";
                        }
                    }
                } else {
                    error_log("Échec de vérification du mot de passe");
                    error_log("Hash stocké en DB: " . $user['mot_de_passe']);
                    // Échec de connexion
                    $_SESSION['message'] = "Identifiants incorrects. Veuillez réessayer.";
                    $_SESSION['message_type'] = "danger";
                }
            } else {
                error_log("Aucun utilisateur trouvé avec l'email: " . $email);
                // Échec de connexion
                $_SESSION['message'] = "Identifiants incorrects. Veuillez réessayer.";
                $_SESSION['message_type'] = "danger";
            }
        } else {
            error_log("Affichage du formulaire de connexion (GET)");
        }
        
        // Vérifions l'état actuel de la session
        error_log("État de la session: " . (isset($_SESSION['user']) ? "Connecté" : "Non connecté"));
        if (isset($_SESSION['message'])) {
            error_log("Message en session: " . $_SESSION['message']);
        }
        
        // Afficher le formulaire de connexion
        error_log("Chargement des vues pour afficher le formulaire");
        require_once __DIR__ . '/../views/templates/header.php';
        require_once __DIR__ . '/../views/Authentification/login.php';
        require_once __DIR__ . '/../views/templates/footer.php';
        error_log("Fin de la méthode login");
    }
    
    
    public function register() {
        // Variables pour les messages
        $message = '';
        $messageType = '';
        
        // Vérifier si l'utilisateur est déjà connecté
        if (isset($_SESSION['user'])) {
            $_SESSION['message'] = "Vous êtes déjà connecté. Déconnectez-vous pour créer un nouveau compte.";
            $_SESSION['message_type'] = "info";
            header('Location: index.php');
            exit;
        }
        
        // Traitement du formulaire d'inscription
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation des données
            $errors = [];
            
            // Vérifier si les mots de passe correspondent
            if ($_POST['mot_de_passe'] !== $_POST['confirm_mot_de_passe']) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }
            
            // Vérifier si l'email existe déjà
            if ($this->utilisateurModel->emailExists($_POST['email'])) {
                $errors[] = "Cet email est déjà utilisé.";
            }
            
            // Traitement du justificatif
            $justificatif_path = '';
            if (isset($_FILES['justificatif']) && $_FILES['justificatif']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '/var/www/secure_storage/justificatifs/';
                
                // Créer le répertoire s'il n'existe pas
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0750, true); // Permission 0750 pour plus de sécurité
                }
                
                $file_info = pathinfo($_FILES['justificatif']['name']);
                $file_ext = strtolower($file_info['extension']);
             
                
                    
                // Vérifier l'extension
                $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
                if (!in_array($file_ext, $allowed_ext)) {
                    $errors[] = "Type de fichier non autorisé. Utilisez PDF, JPG ou PNG.";
                } else {
                    // Générer un nom de fichier unique
                    $new_filename = uniqid('justificatif_') . '.' . $file_ext;
                    $upload_path = $upload_dir . $new_filename;
                    
                    // Déplacer le fichier
                    if (move_uploaded_file($_FILES['justificatif']['tmp_name'], $upload_path)) {
                        $justificatif_path = $new_filename;
                    } else {
                        $errors[] = "Erreur lors de l'upload du fichier.";
                    }
                }
            } else {
                $errors[] = "Veuillez fournir un justificatif.";
            }
            
            // Si aucune erreur, procéder à l'inscription
            if (empty($errors)) {
                $userData = [
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'email' => $_POST['email'],
                    'mot_de_passe' => $_POST['mot_de_passe'],
                    'date_naissance' => $_POST['date_naissance'],
                    'telephone' => $_POST['telephone'],
                    'poste' => $_POST['poste'],
                    'niveau_jeu' => $_POST['niveau_jeu'],
                    'taille' => $_POST['taille'],
                    'poids' => $_POST['poids'],
                    'nationalite' => $_POST['nationalite'],
                    'justificatif' => $justificatif_path
                ];
                
                $user_id = $this->utilisateurModel->inscription($userData);
                
                if ($user_id) {
                    // Au lieu de rediriger vers la page de connexion avec un message,
                    // stockez les données nécessaires pour la page de confirmation
                    $_SESSION['inscription_reussie'] = true;
                    $_SESSION['inscription_data'] = [
                        'nom' => $userData['nom'],
                        'prenom' => $userData['prenom'],
                        'email' => $userData['email']
                    ];
                     // Rediriger vers la page de confirmation
    header('Location: index.php?module=auth&action=confirmation');
                          exit;
                } else {
                    $message = "Une erreur s'est produite lors de l'inscription.";
                    $messageType = "danger";
                }
            } else {
                // Afficher les erreurs
                $message = implode('<br>', $errors);
                $messageType = "danger";
            }
        }
        
        // Afficher le formulaire d'inscription
        require_once __DIR__ . '/../views/templates/header.php';
        require_once __DIR__ . '/../views/Authentification/register.php';
        require_once __DIR__ . '/../views/templates/footer.php';
    }

    // Ajout de la méthode confirmation manquante
    public function confirmation() {
        if (!isset($_SESSION['inscription_reussie']) && !isset($_SESSION['user'])) {
                     exit;
        }
        
        require_once __DIR__ . '/../views/templates/header.php';
        require_once __DIR__ . '/../views/Authentification/confirmation_inscription.php';
        require_once __DIR__ . '/../views/templates/footer.php';
    }

    // Méthode pour la déconnexion
    public function logout() {
        // Détruire la session
        session_unset();
        session_destroy();
        
        $_SESSION = array();
        
        // Démarrer une nouvelle session pour le message
        session_start();
        $_SESSION['message'] = "Vous avez été déconnecté avec succès.";
        $_SESSION['message_type'] = "success";
        
            exit;
    }
}
