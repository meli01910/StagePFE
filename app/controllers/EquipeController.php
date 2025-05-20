<?php
namespace App\controllers;

use App\models\Equipe;
use App\models\Utilisateur;

class EquipeController {
    private $model;
    private $modelUser;

    public function __construct($pdo) {
        $this->model = new Equipe($pdo);
        $this->modelUser= new Utilisateur($pdo);
    }
    public function index() {
        $equipes = $this->model->getAll();
        require __DIR__ . '/../views/Equipes/list.php';
    }

    public function show($id) {
        $equipe = $this->model->getById($id); // Récupérer les détails de l'équipe
        if (!$equipe) {
            // Si l'équipe n'existe pas, rediriger ou afficher un message d'erreur
            header("Location: index.php?module=equipe&action=index&error=notfound");
            exit;
        }
        
        // Récupérer les joueurs de l'équipe si nécessaire
        $joueurs = $this->modelUser->getJoueursByEquipe($id);
        
        // Inclure une vue qui affichera les détails de l'équipe
        require __DIR__ . '/../views/Equipes/show.php'; // Créez une vue pour afficher les détails
    }




// Afficher le formulaire de création
public function create() {
      // Initialiser les données du formulaire (utile en cas d'erreur)
    $formData = [];
    
    // Inclure la vue
    require __DIR__ . '/../views/Equipes/create.php';
 
}

// Enregistrer une nouvelle équipe
public function store() {
    
    // Récupérer et valider les données du formulaire
    $nom = $_POST['nom'] ?? '';
    $contactEmail = $_POST['contact_email'] ?? '';
    
    // Valider les champs requis
    if (empty($nom) || empty($contactEmail)) {
        $error = "Le nom de l'équipe et l'email de contact sont obligatoires.";
        $formData = $_POST;
        require __DIR__ . '/../views/Equipes/create.php';

        return;
    }
    
    // Valider l'email
    if (!filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas au format valide.";
        $formData = $_POST;
        require __DIR__ . '/../views/Equipes/create.php';

        return;
    }
    
    // Traiter le logo si présent
 // Traiter le logo si présent
$logoPath = '';
if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
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
    
    // Vérifier le type de fichier
    $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
    if (!in_array($_FILES['logo']['type'], $allowedTypes)) {
        $error = "Seuls les formats JPG, PNG et GIF sont acceptés.";
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
    
    // Vérifier le type de fichier
    $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
    if (!in_array($_FILES['logo']['type'], $allowedTypes)) {
        $error = "Seuls les formats JPG, PNG et GIF sont acceptés.";
        $formData = $_POST;
        require __DIR__ . '/../views/Equipes/create.php';
        return;
    }
    
    // Vérifier la taille du fichier (max 2MB)
    if ($_FILES['logo']['size'] > 2 * 1024 * 1024) {
        $error = "La taille du fichier doit être inférieure à 2 Mo.";
        $formData = $_POST;
        require __DIR__ . '/../views/Equipes/create.php';
        return;
    }
    
    // Déplacer le fichier
    if (move_uploaded_file($_FILES['logo']['tmp_name'], $absoluteTargetPath)) {
        // Le fichier a été déplacé avec succès
        // $logoPath contient le chemin relatif à stocker en base de données
    } else {
        $error = "Une erreur est survenue lors du téléchargement du logo. Code erreur: " . $_FILES['logo']['error'];
        $formData = $_POST;
        require __DIR__ . '/../views/Equipes/create.php';
        return;
    }
}
 // Ajouter l'équipe dans la base de données
 $result = $this->model->add([
    'nom' => $nom,
    'contact_email' => $contactEmail,
    'logo' => $logoPath
]);

if ($result) {
    // Rediriger vers la liste des équipes avec un message de succès
    $_SESSION['flash_message'] = [
        'type' => 'success',
        'message' => "L'équipe a été créée avec succès !"
    ];
    header('Location: index.php?module=equipe&action=index');
    exit;
} else {
    // Afficher une erreur
    $error = "Une erreur est survenue lors de la création de l'équipe.";
    $formData = $_POST;
    require __DIR__ . '/../views/Equipes/create.php';
    
}




}






public function edit($id = null) {
    // Vérifier si l'ID est fourni
    if ($id === null) {
        $_SESSION['flash_message'] = "ID de l'équipe non spécifié";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=equipe&action=index');
        exit;
    }

    // Récupérer les détails de l'équipe
    $equipe = $this->model->getById($id);
    
    if (!$equipe) {
        $_SESSION['flash_message'] = "Équipe introuvable";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=equipe&action=index');
        exit;
    }

    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération et validation des données
        $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
        $email = isset($_POST['contact_email']) ? trim($_POST['contact_email']) : '';
        
        // Validation du nom
        if (empty($nom)) {
            $error = "Le nom de l'équipe est obligatoire";
            include 'app/views/equipe/edit.php';
            return;
        }
        
        // Validation de l'email
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "L'adresse email n'est pas valide";
            include 'app/views/equipe/edit.php';
            return;
        }
        
        // Traiter le logo si présent
        $logoPath = $equipe['logo']; // Conserver l'ancien logo par défaut
        
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            // Définir le chemin absolu du répertoire
            $absoluteUploadDir = '/var/www/html/FootballProject/uploads/logos/';
            
            // Définir le chemin relatif à stocker en base de données
            $relativePath = '/FootballProject/uploads/logos/';
            
            // Vérifier que le répertoire existe
            if (!is_dir($absoluteUploadDir) || !is_writable($absoluteUploadDir)) {
                $error = "Le répertoire pour les logos est inaccessible ou n'a pas les permissions d'écriture.";
                require __DIR__ . '/../views/Equipes/edit.php';
         return;
            }
            
            // Générer un nom de fichier unique
            $fileName = time() . '_' . basename($_FILES['logo']['name']);
            $absoluteTargetPath = $absoluteUploadDir . $fileName;
            $logoPath = $relativePath . $fileName;
            
            // Vérifier le type de fichier
            $allowedTypes = array('image/jpeg', 'image/png', 'image/gif');
            if (!in_array($_FILES['logo']['type'], $allowedTypes)) {
                $error = "Seuls les formats JPG, PNG et GIF sont acceptés.";
                require __DIR__ . '/../views/Equipes/edit.php';
    
                return;
            }
            
            // Vérifier la taille du fichier (max 2MB)
            if ($_FILES['logo']['size'] > 2 * 1024 * 1024) {
                $error = "La taille du fichier doit être inférieure à 2 Mo.";
                require __DIR__ . '/../views/Equipes/edit.php';
        return;
            }
            
            // Déplacer le fichier
            if (!move_uploaded_file($_FILES['logo']['tmp_name'], $absoluteTargetPath)) {
                $error = "Une erreur est survenue lors du téléchargement du logo.";
                require __DIR__ . '/../views/Equipes/edit.php';
                return;
            }
            
            // Supprimer l'ancien logo s'il existe
            if (!empty($equipe['logo']) && file_exists(str_replace('/FootballProject', '/var/www/html/FootballProject', $equipe['logo']))) {
                unlink(str_replace('/FootballProject', '/var/www/html/FootballProject', $equipe['logo']));
            }
        }
        
        // Option pour supprimer le logo
        if (isset($_POST['supprimer_logo']) && $_POST['supprimer_logo'] == '1') {
            // Supprimer le fichier
            if (!empty($equipe['logo']) && file_exists(str_replace('/FootballProject', '/var/www/html/FootballProject', $equipe['logo']))) {
                unlink(str_replace('/FootballProject', '/var/www/html/FootballProject', $equipe['logo']));
            }
            $logoPath = ''; // Effacer le chemin en base de données
        }
        
        // Mise à jour dans la base de données
        $success = $this->model->update($id, [
            'nom' => $nom,
            'contact_email' => $email,
            'logo' => $logoPath
        ]);
        
        if ($success) {
            $_SESSION['flash_message'] = "L'équipe a été mise à jour avec succès";
            $_SESSION['flash_type'] = "success";
            header('Location: index.php?module=equipe&action=index');
            exit;
        } else {
            $error = "Une erreur est survenue lors de la mise à jour de l'équipe";
            require __DIR__ . '/../views/Equipes/edit.php';
            return;
        }
    }
    
    // Afficher le formulaire avec les données existantes
    require __DIR__ . '/../views/Equipes/edit.php';
}








/**
 * Ajoute plusieurs joueurs à une équipe (en mettant à jour leur equipe_id dans la table utilisateurs)
 * @param int $id L'ID de l'équipe
 * @return void
 */
public function addPlayer($id = null) {
    // Vérifier si l'utilisateur est connecté et a les droits
    if (!isset($_SESSION['user'])) {
        $_SESSION['flash_message'] = "Vous devez être connecté pour effectuer cette action.";
        $_SESSION['flash_type'] = "warning";
        header('Location: index.php?module=utilisateur&action=login');
        exit();
    }

    // Vérifier si l'ID de l'équipe est valide
    if ($id === null) {
        $_SESSION['flash_message'] = "ID de l'équipe non spécifié";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=equipe&action=index');
        exit();
    }

    // Vérifier si l'équipe existe
  ;
    $equipe = $this->model->getById($id);
    if (!$equipe) {
        $_SESSION['flash_message'] = "L'équipe demandée n'existe pas";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=equipe&action=index');
        exit();
    }

    // Récupérer les joueurs sélectionnés
    $playerIds = isset($_POST['player_ids']) ? $_POST['player_ids'] : [];

    // Vérifier qu'au moins un joueur est sélectionné
    if (empty($playerIds)) {
        $_SESSION['flash_message'] = "Aucun joueur sélectionné";
        $_SESSION['flash_type'] = "warning";
        header('Location: index.php?module=equipe&action=selectPlayers&id=' . $id);
        exit();
    }

    // Initialiser le compteur de joueurs ajoutés et d'erreurs
    $addedCount = 0;
    $errorCount = 0;

    // Modèle pour les joueurs
   
    
    // Traiter chaque joueur sélectionné
    foreach ($playerIds as $playerId) {
        // Vérifier que le joueur existe
        $joueur =$this->modelUser->getById($playerId);
        if (!$joueur) {
            $errorCount++;
            continue;
        }
        
        // Si le joueur appartient déjà à cette équipe, passer au suivant
        if ($joueur['equipe_id'] == $id) {
            continue;
        }
        
        // Mettre à jour l'equipe_id du joueur
        $success = $this->model->updateTeam($playerId, $id);
        
        if ($success) {
            $addedCount++;
        } else {
            $errorCount++;
        }
    }

    // Préparer le message de résultat
    if ($addedCount > 0) {
        $message = $addedCount . " joueur" . ($addedCount > 1 ? 's' : '') . " ajouté" . ($addedCount > 1 ? 's' : '') . " avec succès";
        $type = "success";
        
        if ($errorCount > 0) {
            $message .= ". " . $errorCount . " joueur" . ($errorCount > 1 ? 's n\'ont' : ' n\'a') . " pas pu être ajouté" . ($errorCount > 1 ? 's' : '');
            $type = "warning";
        }
    } else {
        if ($errorCount > 0) {
            $message = "Aucun joueur n'a pu être ajouté à l'équipe";
            $type = "danger";
        } else {
            $message = "Aucun nouveau joueur n'a été ajouté à l'équipe";
            $type = "info";
        }
    }

    // Enregistrer le message flash et rediriger
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
    
    // Rediriger vers la vue détaillée de l'équipe
    header('Location: index.php?module=equipe&action=show&id=' . $id);
    exit();
}

/**
 * Affiche la page de sélection des joueurs à ajouter à une équipe
 * @param int $id L'ID de l'équipe
 * @return void
 */
public function selectPlayers($id = null) {
         // Vérifier si l'utilisateur est connecté et a les droits
    if (!isset($_SESSION['user'])) {
        $_SESSION['flash_message'] = "Vous devez être connecté pour effectuer cette action.";
        $_SESSION['flash_type'] = "warning";
        header('Location: index.php?module=utilisateur&action=login');
        exit();
    }

    // Vérifier si l'ID de l'équipe est valide
    if ($id === null) {
        $_SESSION['flash_message'] = "ID de l'équipe non spécifié";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=equipe&action=index');
        exit();
    }

   
    $equipe = $this->model->getById($id);
    if (!$equipe) {
        $_SESSION['flash_message'] = "L'équipe demandée n'existe pas";
        $_SESSION['flash_type'] = "danger";
        header('Location: index.php?module=equipe&action=index');
        exit();
    }

    // Récupérer tous les joueurs qui n'appartiennent pas à cette équipe
  
    $availablePlayers = $this->model->getPlayersWithoutTeam($id);

    // Passer les données à la vue
    $equipeId = $id;
    $players = $availablePlayers;
    
    require_once __DIR__ . '/../views/Equipes/selection_joueurs.php';
}


    
   
    }
