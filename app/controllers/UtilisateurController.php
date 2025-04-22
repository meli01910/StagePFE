<?php
namespace App\controllers;

use App\models\Utilisateur;

class UtilisateurController {
    private $model;
    
    public function __construct($pdo) {
        $this->model = new Utilisateur($pdo);
    }

      // Méthode pour traiter l'upload du justificatif de manière sécurisée
      private function traiterJustificatif($fichier) {
        // Chemin sécurisé hors de la racine web
        $upload_dir = '/var/www/secure_storage/justificatifs/';
        
        if ($fichier['error'] != 0) {
            return ['success' => false, 'message' => "Erreur lors de l'upload du justificatif."];
        }
        
        // Vérifier le type MIME pour s'assurer qu'il s'agit d'un fichier image ou PDF
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $fichier['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowed_types)) {
            return ['success' => false, 'message' => "Type de fichier non autorisé. Seuls les images (JPG, PNG, GIF) et PDF sont acceptés."];
        }
        
        // Génération d'un nom unique pour le fichier
        $nouveau_nom = uniqid() . '-' . basename($fichier['name']);
        $chemin_complet = $upload_dir . $nouveau_nom;
        
        // Déplacer le fichier vers le dossier sécurisé
        if (move_uploaded_file($fichier['tmp_name'], $chemin_complet)) {
            return ['success' => true, 'fichier' => $nouveau_nom];
        } else {
            return ['success' => false, 'message' => "Erreur lors du déplacement du fichier."];
        }
    }
    public function inscription() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire (partie inchangée)
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $date_naissance = trim($_POST['date_naissance'] ?? '');
            $poste = trim($_POST['poste'] ?? '');
            $niveau_jeu = trim($_POST['niveau_jeu'] ?? '');
            $telephone = trim($_POST['telephone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $mot_de_passe = $_POST['mot_de_passe'] ?? '';
            $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'] ?? '';
            
            $taille = intval($_POST['taille'] ?? 0);
            $poids = intval($_POST['poids'] ?? 0);
            $nationalite = trim($_POST['nationalite'] ?? '');
    
            // Validation des champs (partie inchangée)
            $errors = [];
    
            if (empty($nom) || empty($prenom) || empty($date_naissance) || empty($poste) || 
                empty($niveau_jeu) || empty($telephone) || empty($email) || empty($mot_de_passe) ||
                empty($taille) || empty($poids) || empty($nationalite)) {
                $errors[] = "Tous les champs marqués d'un * sont obligatoires.";
            }
    
            if ($mot_de_passe !== $confirmer_mot_de_passe) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }
    
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse e-mail n'est pas valide.";
            }
    
            if ($this->model->emailExists($email)) {
                $errors[] = "Cette adresse e-mail est déjà utilisée.";
            }
    
            // Traitement sécurisé du justificatif d'identité
            $justificatif = '';
            if (isset($_FILES['justificatif'])) {
                $resultat = $this->traiterJustificatif($_FILES['justificatif']);
                if ($resultat['success']) {
                    $justificatif = $resultat['fichier'];
                } else {
                    $errors[] = $resultat['message'];
                }
            } else {
                $errors[] = "Le justificatif d'identité est obligatoire.";
            }
    
            // Si pas d'erreurs, on procède à l'inscription
            if (empty($errors)) {
                if ($this->model->inscrire($nom, $prenom, $date_naissance, $poste, $niveau_jeu, $telephone, $email, $justificatif, $mot_de_passe, $taille, $poids, $nationalite)) {
                    // Redirection avec message de succès
                    $_SESSION['flash'] = [
                        'type' => 'success',
                        'message' => 'Votre inscription a été enregistrée et est en attente d\'approbation par l\'administrateur.'
                    ];
                    header('Location: index.php?module=utilisateur&action=connexion');
                    exit;
                } else {
                    $errors[] = "Une erreur s'est produite lors de l'inscription.";
                }
            }

            // Si on arrive ici, c'est qu'il y a des erreurs
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => implode('<br>', $errors)
            ];
            $_SESSION['old'] = $_POST; // Pour repopuler le formulaire
        }
        
        require_once __DIR__ . '/../views/Utilisateur/inscription.php';
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
        public function countJoueursByStatus($status) {
            return $this->model->countByStatus($status);
        }
    
        // Méthode pour lister les joueurs en attente
        public function listeJoueursAttente() {
            $joueurs = $this->model->getByStatus('en_attente');
            
            require_once __DIR__ . '/../views/templates/header.php';
            require_once __DIR__ . '/../views/Users/joueurs_attente.php';
            require_once __DIR__ . '/../views/templates/footer.php';
        }
    
       
    
        
    }
    

