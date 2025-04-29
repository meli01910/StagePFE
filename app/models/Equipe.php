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
    
 

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM equipes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nom, $logo, $email) {
        $stmt = $this->pdo->prepare("INSERT INTO equipes (nom, logo, contact_email) VALUES (?, ?, ?)");
        return $stmt->execute([$nom, $logo, $email]);
    }
    public function update($id, $nom, $logo, $email) {
        $stmt = $this->pdo->prepare("UPDATE equipes SET nom = ?, logo = ?, contact_email = ? WHERE id = ?");
        return $stmt->execute([$nom, $logo, $email, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM equipes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function addPlayerToEquipe($playerId, $equipeId) {
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET equipe_id = ? WHERE id = ?");
        return $stmt->execute([$equipeId, $playerId]);
    }
   

    // Récupérer les équipes d'un tournoi spécifique
    public function getByTournoi($tournoi_id) {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT e.* 
                FROM equipes e
                JOIN tournoi_equipe et ON e.id = et.equipe_id
                WHERE et.tournoi_id = ?
                ORDER BY e.nom"
            );
            $stmt->execute([$tournoi_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Erreur lors de la récupération des équipes par tournoi: ' . $e->getMessage());
            return [];
        }
    }
    
    // Ajouter une équipe à un tournoi
    public function addToTournament($equipe_id, $tournoi_id) {
        try {
            // Vérifier si l'association n'existe pas déjà
            $stmt = $this->pdo->prepare(
                "SELECT COUNT(*) FROM tournoi_equipe WHERE equipe_id = ? AND tournoi_id = ?"
            );
            $stmt->execute([$equipe_id, $tournoi_id]);
            
            if ($stmt->fetchColumn() > 0) {
                // L'équipe est déjà dans ce tournoi
                return false;
            }
            
            // Ajouter l'association
            $stmt = $this->pdo->prepare(
                "INSERT INTO tournoi_equipe (equipe_id, tournoi_id) VALUES (?, ?)"
            );
            
            return $stmt->execute([$equipe_id, $tournoi_id]);
        } catch (\PDOException $e) {
            error_log('Erreur lors de l\'ajout d\'une équipe à un tournoi: ' . $e->getMessage());
            return false;
        }
    }
    
    // Retirer une équipe d'un tournoi
    public function removeFromTournament($equipe_id, $tournoi_id) {
        try {
            $stmt = $this->pdo->prepare(
                "DELETE FROM tournoi_equipe WHERE equipe_id = ? AND tournoi_id = ?"
            );
            
            return $stmt->execute([$equipe_id, $tournoi_id]);
        } catch (\PDOException $e) {
            error_log('Erreur lors du retrait d\'une équipe d\'un tournoi: ' . $e->getMessage());
            return false;
        }
    }
    
    // Vérifier si une équipe participe à un tournoi
    public function isInTournament($equipe_id, $tournoi_id) {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT COUNT(*) FROM tournoi_equipe WHERE equipe_id = ? AND tournoi_id = ?"
            );
            $stmt->execute([$equipe_id, $tournoi_id]);
            
            return $stmt->fetchColumn() > 0;
        } catch (\PDOException $e) {
            error_log('Erreur lors de la vérification d\'appartenance: ' . $e->getMessage());
            return false;
        }
    }
    
    // Compter le nombre d'équipes dans un tournoi
    public function countInTournament($tournoi_id) {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT COUNT(*) FROM tournoi_equipe WHERE tournoi_id = ?"
            );
            $stmt->execute([$tournoi_id]);
            
            return (int)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('Erreur lors du comptage d\'équipes: ' . $e->getMessage());
            return 0;
        }
    }

    
}
