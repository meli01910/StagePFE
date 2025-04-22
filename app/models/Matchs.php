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

  // Génère les matchs pour toutes les poules d'un tournoi
  public function genererMatchsPoules($tournoiId) {
    try {
        // Afficher explicitement les erreurs
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        
        echo "<h3>Début de la génération des matchs</h3>";
        
        $this->pdo->beginTransaction();
        
        // 1. Récupérer les IDs de poules pour ce tournoi
        $stmt = $this->pdo->prepare("SELECT id FROM poules WHERE tournoi_id = ?");
        $stmt->execute([$tournoiId]);
        $poules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Nombre de poules trouvées: " . count($poules) . "<br>";
        
        // Si aucune poule n'est trouvée, c'est une erreur
        if (empty($poules)) {
            throw new \Exception("Aucune poule trouvée pour ce tournoi");
        }
        
        $pouleIds = array_column($poules, 'id');
        echo "IDs des poules: " . implode(', ', $pouleIds) . "<br>";
        
        // 2. Suppression des matchs existants pour les poules de ce tournoi
        if (!empty($pouleIds)) {
            $placeholders = implode(',', array_fill(0, count($pouleIds), '?'));
            $sql = "DELETE FROM matchs WHERE poule_id IN ($placeholders)";
            echo "SQL de suppression: $sql <br>";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($pouleIds);
            echo "Matchs existants supprimés<br>";
        }
        
        $totalMatchs = 0;
        
        foreach ($poules as $poule) {
            $pouleId = $poule['id'];
            echo "<h4>Traitement de la poule ID: $pouleId</h4>";
            
            // 3. Récupération des équipes de cette poule
            $stmt = $this->pdo->prepare("
                SELECT e.id, e.nom
                FROM equipes e
                JOIN equipe_poule ep ON e.id = ep.equipe_id
                WHERE ep.poule_id = ?
            ");
            $stmt->execute([$pouleId]);
            $equipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "Équipes dans la poule: ";
            foreach ($equipes as $equipe) {
                echo "{$equipe['id']}({$equipe['nom']}) ";
            }
            echo "<br>";
            
            // Vérifier le nombre d'équipes
            $nbEquipes = count($equipes);
            if ($nbEquipes < 2) {
                throw new \Exception("Pas assez d'équipes dans la poule $pouleId");
            }
            
            // 4. Génération des matchs (chaque équipe contre toutes les autres)
            echo "Génération des matchs pour $nbEquipes équipes<br>";
            
            for ($i = 0; $i < $nbEquipes; $i++) {
                for ($j = $i + 1; $j < $nbEquipes; $j++) {
                    $equipe1Id = $equipes[$i]['id'];
                    $equipe2Id = $equipes[$j]['id'];
                    
                    echo "Match: {$equipes[$i]['nom']} vs {$equipes[$j]['nom']}<br>";
                    
                    // Date du match (aujourd'hui + X jours)
                    $date = date('Y-m-d H:i:s', strtotime('+' . rand(1, 14) . ' days'));
                    
                    // Création du match - Vérifions si poule_id existe dans la table
                    try {
                        $sql = "INSERT INTO matchs (tournoi_id, equipe1_id, equipe2_id, date_match, lieu_match, statut, poule_id) 
                               VALUES (?, ?, ?, ?, ?, 'à_venir', ?)";
                        echo "SQL d'insertion: $sql <br>";
                        
                        $stmt = $this->pdo->prepare($sql);
                        $stmt->execute([
                            $tournoiId,
                            $equipe1Id,
                            $equipe2Id,
                            $date,
                            'lieu_match ' . rand(1, 5),
                            $pouleId
                        ]);
                        $totalMatchs++;
                    } catch (\PDOException $e) {
                        // Si erreur sur poule_id, essayer sans
                        echo "<div style='color:red'>Erreur PDO: " . $e->getMessage() . "</div>";
                        throw $e; // Rethrow pour qu'elle soit attrapée par le catch principal
                    }
                }
            }
        }
     
        echo "<h4>Total matchs créés: $totalMatchs</h4>";
        
        // 5. Mise à jour du statut du tournoi
        $stmt = $this->pdo->prepare("UPDATE tournois SET matchs_poule_generes = 1 WHERE id = ?");
        $stmt->execute([$tournoiId]);
        
        $this->pdo->commit();
        echo "<h3>Génération terminée avec succès!</h3>";
        return true;
    } catch (\Exception $e) {
        $this->pdo->rollBack();
        echo "<div style='color:red; font-weight:bold;'>ERREUR: " . $e->getMessage() . "</div>";
        return false;
    }
}
}