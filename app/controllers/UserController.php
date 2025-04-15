<?php
namespace App\controllers;

use App\models\User;

class UserController {
    private $model;

    public function __construct($pdo) {
        $this->model = new User($pdo);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $motDePasse = $_POST['mot_de_passe'];
            $isAdmin = $_POST['is_admin'] ?? 0; 
    
            // Créer l'utilisateur
            $this->model->create($nom, $email, $motDePasse, $isAdmin);
    
            header('Location: login.php');
            exit;
        }
        require __DIR__ . '/../views/Users/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $motDePasse = $_POST['mot_de_passe'];
    
            $user = $this->model->findByEmail($email);
    
            if ($user && password_verify($motDePasse, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['is_admin'] = $user['is_admin']; // Sauvegarder le rôle dans la session
    
                // Redirection selon le rôle
                if ($user['is_admin'] == 1) {
                    header('Location: dashboard_admin.php');
                } else {
                    header('Location: dashboard.php');
                }
                exit;
            } else {
                // Erreur de connexion
                echo "Email ou mot de passe incorrect.";
            }
        }
        require __DIR__ . '/../views/Users/login.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php');
    }
}
