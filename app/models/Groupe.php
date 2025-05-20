<?php

namespace App\models;
use PDO;
class Groupe {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Créer un nouveau groupe
     */
    public function create($tournoiId, $nom, $description = null) {
        $stmt = $this->pdo->prepare("
            INSERT INTO groupes (tournoi_id, nom, description)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$tournoiId, $nom, $description]);
    }
    
    /**
     * Récupérer un groupe par son ID
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM groupes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getLastInsertId() {
    return $this->pdo->lastInsertId();
}
    
    /**
     * Récupérer tous les groupes d'un tournoi
     */
    public function getByTournoi($tournoiId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM groupes 
            WHERE tournoi_id = ? 
            ORDER BY nom
        ");
        $stmt->execute([$tournoiId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Mettre à jour un groupe
     */
    public function update($id, $nom, $description = null) {
        $stmt = $this->pdo->prepare("
            UPDATE groupes 
            SET nom = ?, description = ?
            WHERE id = ?
        ");
        return $stmt->execute([$nom, $description, $id]);
    }
    
    /**
     * Supprimer un groupe
     */
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM groupes WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Ajouter une équipe à un groupe
     */
    public function addEquipe($groupeId, $equipeId) {
        $stmt = $this->pdo->prepare("
            INSERT INTO groupe_equipe (groupe_id, equipe_id)
            VALUES (?, ?)
        ");
        return $stmt->execute([$groupeId, $equipeId]);
    }
    
    /**
     * Retirer une équipe d'un groupe
     */
    public function removeEquipe($groupeId, $equipeId) {
        $stmt = $this->pdo->prepare("
            DELETE FROM groupe_equipe 
            WHERE groupe_id = ? AND equipe_id = ?
        ");
        return $stmt->execute([$groupeId, $equipeId]);
    }
    
    /**
     * Récupérer les équipes d'un groupe
     */
    public function getEquipes($groupeId) {
        $stmt = $this->pdo->prepare("
            SELECT e.*, ge.points, ge.joues, ge.gagnes, ge.nuls, ge.perdus, 
                   ge.buts_pour, ge.buts_contre, 
                   (ge.buts_pour - ge.buts_contre) AS diff
            FROM groupe_equipe ge
            JOIN equipes e ON ge.equipe_id = e.id
            WHERE ge.groupe_id = ?
            ORDER BY ge.points DESC, diff DESC, ge.buts_pour DESC, e.nom ASC
        ");
        $stmt->execute([$groupeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Mettre à jour les statistiques d'une équipe dans un groupe
     */
    public function updateEquipeStats($groupeId, $equipeId, $stats) {
        $stmt = $this->pdo->prepare("
            UPDATE groupe_equipe 
            SET points = ?, joues = ?, gagnes = ?, nuls = ?, perdus = ?,
                buts_pour = ?, buts_contre = ?
            WHERE groupe_id = ? AND equipe_id = ?
        ");
        return $stmt->execute([
            $stats['points'], $stats['joues'], $stats['gagnes'], $stats['nuls'], $stats['perdus'],
            $stats['buts_pour'], $stats['buts_contre'], $groupeId, $equipeId
        ]);
    }
    
    /**
     * Calculer automatiquement les statistiques des équipes dans un groupe
     */
    public function calculateGroupStats($groupeId) {
        // Récupérer tous les matchs du groupe qui sont terminés
        $matchStmt = $this->pdo->prepare("
            SELECT * FROM matchs 
            WHERE groupe_id = ? AND statut = 'terminé'
        ");
        $matchStmt->execute([$groupeId]);
        $matchs = $matchStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Récupérer toutes les équipes du groupe
        $equipes = $this->getEquipes($groupeId);
        
        // Initialiser les statistiques à zéro
        $stats = [];
        foreach ($equipes as $equipe) {
            $stats[$equipe['id']] = [
                'points' => 0,
                'joues' => 0,
                'gagnes' => 0,
                'nuls' => 0,
                'perdus' => 0,
                'buts_pour' => 0,
                'buts_contre' => 0
            ];
        }
        
        // Calculer les statistiques à partir des matchs
        foreach ($matchs as $match) {
            $equipe1Id = $match['equipe1_id'];
            $equipe2Id = $match['equipe2_id'];
            $score1 = $match['score1'];
            $score2 = $match['score2'];
            
            // Mettre à jour les statistiques pour l'équipe 1
            if (isset($stats[$equipe1Id])) {
                $stats[$equipe1Id]['joues']++;
                $stats[$equipe1Id]['buts_pour'] += $score1;
                $stats[$equipe1Id]['buts_contre'] += $score2;
                
                if ($score1 > $score2) {
                    $stats[$equipe1Id]['gagnes']++;
                    $stats[$equipe1Id]['points'] += 3;
                } elseif ($score1 == $score2) {
                    $stats[$equipe1Id]['nuls']++;
                    $stats[$equipe1Id]['points'] += 1;
                } else {
                    $stats[$equipe1Id]['perdus']++;
                }
            }
            
            // Mettre à jour les statistiques pour l'équipe 2
            if (isset($stats[$equipe2Id])) {
                $stats[$equipe2Id]['joues']++;
                $stats[$equipe2Id]['buts_pour'] += $score2;
                $stats[$equipe2Id]['buts_contre'] += $score1;
                
                if ($score2 > $score1) {
                    $stats[$equipe2Id]['gagnes']++;
                    $stats[$equipe2Id]['points'] += 3;
                } elseif ($score1 == $score2) {
                    $stats[$equipe2Id]['nuls']++;
                    $stats[$equipe2Id]['points'] += 1;
                } else {
                    $stats[$equipe2Id]['perdus']++;
                }
            }
        }
        
        // Mettre à jour les statistiques dans la base de données
        $this->pdo->beginTransaction();
        try {
            foreach ($stats as $equipeId => $equipeStats) {
                $this->updateEquipeStats($groupeId, $equipeId, $equipeStats);
            }
            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    
    /**
     * Obtenir les équipes qualifiées d'un groupe (les 2 premières par défaut)
     */public function getQualifiedTeams($groupeId, $limit = 2) {
    // Cast $limit en entier pour plus de sécurité
    $limit = (int)$limit;
    
    // Insérer directement la valeur dans la requête
    $stmt = $this->pdo->prepare("
        SELECT e.* 
        FROM groupe_equipe ge
        JOIN equipes e ON ge.equipe_id = e.id
        WHERE ge.groupe_id = ?
        ORDER BY ge.points DESC, (ge.buts_pour - ge.buts_contre) DESC, ge.buts_pour DESC
        LIMIT $limit
    ");
    
    // Exécuter seulement avec le paramètre groupeId
    $stmt->execute([$groupeId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}}