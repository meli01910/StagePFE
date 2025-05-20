<?php
namespace App\models;

use PDOException;
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

 /**
     * Ajouter une nouvelle équipe
     * @param array $data Les données de l'équipe
     * @return int|bool L'ID de l'équipe créée ou false en cas d'échec
     */
    public function add($data) {
        $sql = "INSERT INTO equipes (nom, contact_email, logo) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        
        if ($stmt->execute([$data['nom'], $data['contact_email'], $data['logo']])) {
            return $this->pdo->lastInsertId();
        }
        
        return false;
    }
    
   /**
 * Met à jour une équipe existante
 * @param int $id ID de l'équipe
 * @param array $data Données à mettre à jour
 * @return boolean Succès de l'opération
 */
public function update($id, $data) {
    try {
        $query = "UPDATE equipes SET 
                  nom = :nom, 
                  contact_email = :contact_email";
        
        $params = [
            ':nom' => $data['nom'],
            ':contact_email' => $data['contact_email']
        ];
        
        // Ajouter la mise à jour du logo uniquement si présent dans les données
        if (isset($data['logo'])) {
            $query .= ", logo = :logo";
            $params[':logo'] = $data['logo'];
        }
        
        $query .= " WHERE id = :id";
        $params[':id'] = $id;
        
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        // Log de l'erreur
        error_log('Erreur de mise à jour d\'équipe : ' . $e->getMessage());
        return false;
    }
}

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM equipes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function addPlayerToEquipe($playerId, $id) {
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET equipe_id = ? WHERE id = ?");
        return $stmt->execute([$id, $playerId]);
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
        } catch (PDOException $e) {
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
        } catch (PDOException $e) {
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
        } catch (PDOException $e) {
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
        } catch (PDOException $e) {
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
        } catch (PDOException $e) {
            error_log('Erreur lors du comptage d\'équipes: ' . $e->getMessage());
            return 0;
        }
    }




/**
 * Met à jour l'équipe d'un joueur
 * @param int $joueurId L'ID du joueur
 * @param int $equipeId L'ID de l'équipe (ou null pour retirer le joueur de son équipe)
 * @return bool Succès de l'opération
 */

    public function updateTeam($joueurId, $equipeId) {
        try {
            $sql = "UPDATE utilisateurs SET equipe_id = :equipe_id WHERE id = :joueur_id";
            
            $stmt = $this->pdo->prepare($sql);
            
            if ($equipeId === null) {
                $stmt->bindValue(':equipe_id', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':equipe_id', $equipeId, PDO::PARAM_INT);
            }
            
            $stmt->bindParam(':joueur_id', $joueurId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            // Logger l'erreur (dans un vrai système)
            return false;
        }
    }











   /**
 * Récupère tous les joueurs d'une équipe
 * @param int $equipeId L'ID de l'équipe
 * @return array Liste des joueurs de l'équipe
 */
public function getPlayersByTeam($equipeId) {
    try {
        $sql = "SELECT * FROM utilisateurs WHERE equipe_id = :equipe_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':equipe_id', $equipeId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Logger l'erreur (dans un vrai système)
        return [];
    }
}
public function getPlayersWithoutTeam($equipeId) {
    try {
        $sql = "SELECT * FROM utilisateurs WHERE equipe_id IS NULL OR equipe_id != :equipe_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':equipe_id', $equipeId, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        // Logger l'erreur (dans un vrai système)
        return [];
    }
}

 
}
