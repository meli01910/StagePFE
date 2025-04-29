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
            header("Location: index.php?module=admin&action=index&error=notfound");
            exit;
        }
        
        // Récupérer les joueurs de l'équipe si nécessaire
        $joueurs = $this->modelUser->getJoueursByEquipe($id);
        
        // Inclure une vue qui affichera les détails de l'équipe
        require __DIR__ . '/../views/Equipes/equipe_view.php'; // Créez une vue pour afficher les détails
    }
    






    


    public function edit($id) {
        $equipe = $this->model->getById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->update($id, $_POST['nom'], $_POST['logo'], $_POST['contact_email']);
            header("Location: index.php?module=equipe&action=listByTournoi&tournoi_id=" . $equipe['tournoi_id']);
            exit;
        }
        require __DIR__ . '/../views/Equipes/update.php';
    }

    public function delete($id) {
        $equipe = $this->model->getById($id);
        $this->model->delete($id);
        header("Location: index.php?module=equipe&action=listByTournoi&tournoi_id=" . $equipe['tournoi_id']);
        exit;
    }

    public function addPlayer($equipeId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $playerId = $_POST['player_id']; // Supposons que l'ID du joueur vient d'un formulaire
            $this->model->addPlayerToEquipe($playerId, $equipeId);
            
            // Rediriger vers la liste des joueurs de l'équipe ou une autre page pertinente
            header("Location: index.php?module=equipe&action=listejoueurs&equipe_id=$equipeId");
            exit;
        }
    
        // Vous pouvez aussi afficher une vue pour choisir un joueur
        $players = $this->modelUser->getAllPlayers(); // Ajoutez une méthode pour récupérer tous les joueurs
        require __DIR__ . '/../views/Equipes/ajout_joueur.php'; // Création d'une vue pour ajouter un joueur
    }

    public function listeJoueurs($equipeId) {
        $joueurs = $this->modelUser->getJoueursByEquipe($equipeId); 
        $equipe = $this->model->getById($equipeId); // Récupérer les détails de l'équipe
          require __DIR__ . '/../views/Equipes/joueursParEquipe.php'; // Incluez un fichier de vue pour afficher la liste
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $logoPath = '';
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $targetDir = __DIR__ . '/../../uploads/logos/'; // Chemin de sauvegarde sur le serveur
                $fileName = basename($_FILES['logo']['name']);
                $uniqueFileName = uniqid() . '-' . $fileName; // Créer un nom de fichier unique
                $targetFilePath = $targetDir . $uniqueFileName; // Chemin complet pour le déplacement de fichier
    
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
                if (in_array($fileType, $allowedTypes) && move_uploaded_file($_FILES['logo']['tmp_name'], $targetFilePath)) {
                    $logoPath = '/FootballProject/uploads/logos/' . $uniqueFileName; // Chemin relatif pour l'utilisation dans le navigateur
                    echo "Logo téléchargé avec succès : " . $logoPath; // Confirmation de téléchargement
                } else {
                    echo "Erreur lors du téléchargement du fichier.";
                    print_r($_FILES['logo']); // Détails du fichier uploadé
                }
            }
    
            $this->model->create($_POST['nom'], $logoPath, $_POST['contact_email']);
            echo "Enregistrement dans la base de données effectué avec : " . $logoPath;
            exit;
        }
        require __DIR__ . '/../views/Equipes/create.php';
 
    }}
