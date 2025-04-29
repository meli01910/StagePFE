<?php
namespace App\models;

use PDO;

class Matchs {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($tournoiId, $equipe1Id, $equipe2Id, $date, $lieu_match) {
        $sql = "INSERT INTO matchs 
                (tournoi_id, equipe1_id, equipe2_id, date_match, lieu_match, statut) 
                VALUES (?, ?, ?, ?, ?, 'à_venir')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$tournoiId, $equipe1Id, $equipe2Id, $date, $lieu_match]);
    }

    public function updateScore($matchId, $score1, $score2) {
        $statut = 'terminé';
        $sql = "UPDATE matchs SET 
                score_equipe1 = ?,
                score_equipe2 = ?,
                statut = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$score1, $score2, $statut, $matchId]);
    }

    public function getByTournoi($tournoiId) {
        $sql = "SELECT m.*, 
                e1.nom as equipe1_nom, 
                e2.nom as equipe2_nom 
                FROM matchs m
                JOIN equipes e1 ON m.equipe1_id = e1.id
                JOIN equipes e2 ON m.equipe2_id = e2.id
                WHERE m.tournoi_id = ?
                ORDER BY m.date_match";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$tournoiId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


public function countAll() {
    $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM matchs");
    $stmt->execute();
    return $stmt->fetchColumn();
}

/**
 * Récupère tous les matchs avec les informations des tournois et équipes
 * @param array $filters Filtres optionnels (tournoi_id, status, etc.)
 * @return array Liste des matchs
 */
public function getAllMatchs($filters = []) {
    $sql = "
        SELECT m.*, 
               t.nom as tournoi_nom,
               e1.nom as equipe1_nom, 
               e2.nom as equipe2_nom
        FROM matchs m
        JOIN tournois t ON m.tournoi_id = t.id
        JOIN equipes e1 ON m.equipe1_id = e1.id
        JOIN equipes e2 ON m.equipe2_id = e2.id
        WHERE 1=1
    ";
    
    $params = [];
    
    // Filtrer par tournoi
    if (!empty($filters['tournoi_id'])) {
        $sql .= " AND m.tournoi_id = :tournoi_id";
        $params[':tournoi_id'] = $filters['tournoi_id'];
    }
    
    // Filtrer par statut (joué/à venir)
    if (!empty($filters['status'])) {
        if ($filters['status'] === 'played') {
            $sql .= " AND m.score_equipe1 IS NOT NULL AND m.score_equipe2 IS NOT NULL";
        } elseif ($filters['status'] === 'coming') {
            $sql .= " AND (m.score_equipe1 IS NULL OR m.score_equipe2 IS NULL)";
        }
    }
    
    // Tri par date
    $sql .= " ORDER BY m.date_match DESC";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Récupère un match par son ID
 * @param int $id ID du match
 * @return array|false Informations du match ou false si introuvable
 */
public function getById($id) {
    $stmt = $this->pdo->prepare("
        SELECT m.*, 
               t.nom as tournoi_nom,
               e1.nom as equipe1_nom, 
               e2.nom as equipe2_nom
        FROM matchs m
        JOIN tournois t ON m.tournoi_id = t.id
        JOIN equipes e1 ON m.equipe1_id = e1.id
        JOIN equipes e2 ON m.equipe2_id = e2.id
        WHERE m.id = :id
    ");
    
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}



/**
 * Supprime un match
 * @param int $id ID du match à supprimer
 * @return bool Succès de l'opération
 */
public function delete($id) {
    $stmt = $this->pdo->prepare("DELETE FROM matchs WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}


}