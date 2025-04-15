<?php
namespace App\models;

use PDO;

class Matchs {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($tournoiId, $equipe1Id, $equipe2Id, $date, $terrain) {
        $sql = "INSERT INTO matchs 
                (tournoi_id, equipe1_id, equipe2_id, horaire, terrain, statut) 
                VALUES (?, ?, ?, ?, ?, 'à_venir')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$tournoiId, $equipe1Id, $equipe2Id, $date, $terrain]);
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
                ORDER BY m.horaire";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$tournoiId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}