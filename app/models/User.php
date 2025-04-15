<?php
namespace App\models;

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Créer un utilisateur avec un rôle (0 par défaut)
    public function create($nom, $email, $motDePasse, $isAdmin = 0) {
        $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, is_admin) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nom, $email, $hash, $isAdmin]);
    }

    // Trouver un utilisateur par email
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // Vérifier si l'utilisateur est un admin
    public function isAdmin($id) {
        $stmt = $this->pdo->prepare("SELECT is_admin FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result['is_admin'] == 1;
    }
    // Supprimer un utilisateur et ses connexions associées
function deleteUser($id) {
  


    $stmt = $this->pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $stmt->execute([$id]);
}
}
