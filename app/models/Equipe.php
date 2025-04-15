<?php
namespace App\models;

use PDO;

class Equipe {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM equipes");
        return $stmt->fetchAll();
    }
    
    
    public function getByTournoi($tournoiId) {
        $stmt = $this->pdo->prepare("SELECT * FROM equipes WHERE tournoi_id = ?");
        $stmt->execute([$tournoiId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM equipes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($tournoiId, $nom, $logo, $email) {
        $stmt = $this->pdo->prepare("INSERT INTO equipes (tournoi_id, nom, logo, contact_email) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$tournoiId, $nom, $logo, $email]);
    }

    public function update($id, $nom, $logo, $email) {
        $stmt = $this->pdo->prepare("UPDATE equipes SET nom = ?, logo = ?, contact_email = ? WHERE id = ?");
        return $stmt->execute([$nom, $logo, $email, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM equipes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
