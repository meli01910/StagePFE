<?php
namespace App\models;

use PDO;
use PDOException;
use DateTime;
class Matchs {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }



public function create($tournoiId, $equipe1Id, $equipe2Id, $dateMatch, $lieuMatch, $phase = 'Phase de groupes', $estAmical = false) {
    try {
        // Si c'est un match amical, le tournoi_id doit être NULL et la phase aussi
        if ($estAmical) {
            $tournoiId = null;
            $phase = null;
        }
        
        $sql = "INSERT INTO matchs (tournoi_id, est_amical, equipe1_id, equipe2_id, date_match, lieu_match, phase) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$tournoiId, $estAmical ? 1 : 0, $equipe1Id, $equipe2Id, $dateMatch, $lieuMatch, $phase]);
    } catch (PDOException $e) {
        // Journalisation de l'erreur
        error_log($e->getMessage());
        return false;
    }
}

public function createFriendlyMatch($equipe1Id, $equipe2Id, $dateMatch, $lieuMatch) {
    try {
        $query = "INSERT INTO matches (equipe1_id, equipe2_id, date_match, lieu_match, est_amical) 
                 VALUES (:equipe1_id, :equipe2_id, :date_match, :lieu_match, 1)";
                 
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':equipe1_id', $equipe1Id, PDO::PARAM_INT);
        $stmt->bindParam(':equipe2_id', $equipe2Id, PDO::PARAM_INT);
        $stmt->bindParam(':date_match', $dateMatch);
        $stmt->bindParam(':lieu_match', $lieuMatch);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        // Gérer l'erreur
        error_log($e->getMessage());
        return false;
    }
}


    public function updateScore($matchId, $score1, $score2,$statut) {
        $statut = 'terminé';
        $sql = "UPDATE matchs SET 
                score1 = ?,
                score2 = ?,
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
        LEFT JOIN tournois t ON m.tournoi_id = t.id  /* Changé en LEFT JOIN */
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
    
    // Filtrer par type de match (ajout de cette option)
    if (isset($filters['type'])) {
        if ($filters['type'] === 'friendly') {
            $sql .= " AND m.est_amical = 1";
        } elseif ($filters['type'] === 'tournament') {
            $sql .= " AND (m.est_amical = 0 OR m.est_amical IS NULL)";
        }
    }
    
    // Filtrer par statut (joué/à venir)
    if (!empty($filters['status'])) {
        if ($filters['status'] === 'played') {
            $sql .= " AND m.statut = 'terminé'";
        } elseif ($filters['status'] === 'coming') {
            $sql .= " AND m.statut IN ('à_venir', 'en_cours')";
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
        LEFT JOIN tournois t ON m.tournoi_id = t.id  /* Changé en LEFT JOIN */
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



/**
 * Récupère les prochains matchs
 * @param int $limit Nombre maximum de matchs à récupérer
 * @return array Liste des prochains matchs
 */
public function getUpcomingMatches($limit = 5) {
    $query = "SELECT m.*, 
                     t.nom AS tournoi_nom,
                     e1.nom AS equipe1_nom, 
                     e1.logo AS equipe1_logo,
                     e2.nom AS equipe2_nom,
                     e2.logo AS equipe2_logo
              FROM matchs m
              JOIN tournois t ON m.tournoi_id = t.id
              JOIN equipes e1 ON m.equipe1_id = e1.id
              JOIN equipes e2 ON m.equipe2_id = e2.id
              WHERE m.date_match > NOW()
              ORDER BY m.date_match ASC
              LIMIT :limit";
              
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
/**
 * Récupère les statistiques d'une équipe dans un tournoi
 * @param int $tournoiId ID du tournoi
 * @param int $equipeId ID de l'équipe
 * @return array Statistiques de l'équipe
 */
public function getTeamStats($tournoiId, $equipeId) {
    // Initialiser les statistiques
    $stats = [
        'matches_joues' => 0,
        'victoires' => 0,
        'defaites' => 0,
        'nuls' => 0,
        'buts_marques' => 0,
        'buts_encaisses' => 0,
        'difference' => 0,
        'points' => 0
    ];
    
    // Récupérer tous les matchs de l'équipe dans ce tournoi
    $sql = "
        SELECT * FROM matchs 
        WHERE tournoi_id = :tournoi_id 
        AND (equipe1_id = :equipe_id OR equipe2_id = :equipe_id)
        AND statut = 'terminé'
    ";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':tournoi_id', $tournoiId, PDO::PARAM_INT);
    $stmt->bindParam(':equipe_id', $equipeId, PDO::PARAM_INT);
    $stmt->execute();
    
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($matches as $match) {
        $stats['matches_joues']++;
        
        if ($match['equipe1_id'] == $equipeId) {
            // L'équipe joue à domicile
            $stats['buts_marques'] += $match['score1'];
            $stats['buts_encaisses'] += $match['score2'];
            
            if ($match['score1'] > $match['score2']) {
                $stats['victoires']++;
                $stats['points'] += 3;
            } elseif ($match['score1'] < $match['score2']) {
                $stats['defaites']++;
            } else {
                $stats['nuls']++;
                $stats['points'] += 1;
            }
        } else {
            // L'équipe joue à l'extérieur
            $stats['buts_marques'] += $match['score2'];
            $stats['buts_encaisses'] += $match['score1'];
            
            if ($match['score2'] > $match['score1']) {
                $stats['victoires']++;
                $stats['points'] += 3;
            } elseif ($match['score2'] < $match['score1']) {
                $stats['defaites']++;
            } else {
                $stats['nuls']++;
                $stats['points'] += 1;
            }
        }
    }
    
    $stats['difference'] = $stats['buts_marques'] - $stats['buts_encaisses'];
    
    return $stats;
}
/**
 * Génère automatiquement les matchs pour un tournoi
 * 
 * @param int $tournoiId ID du tournoi
 * @param string $format Format du tournoi (championnat, coupe, poules, etc.)
 * @param array $options Options supplémentaires pour la génération
 * @return bool Succès de l'opération
 */
public function generateMatchesForTournament($tournoiId, $format = 'championnat', $options = []) {
    try {
        // Récupérer les infos du tournoi
        $tournoiStmt = $this->pdo->prepare("SELECT * FROM tournois WHERE id = ?");
        $tournoiStmt->execute([$tournoiId]);
        $tournoi = $tournoiStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$tournoi) {
            return false;
        }
        
        // Récupérer les équipes du tournoi
        $equipesStmt = $this->pdo->prepare("
            SELECT e.* 
            FROM equipes e
            JOIN tournoi_equipe te ON e.id = te.equipe_id
            WHERE te.tournoi_id = ?
        ");
        $equipesStmt->execute([$tournoiId]);
        $equipes = $equipesStmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($equipes) < 2) {
            return false; // Impossible de créer des matchs avec moins de 2 équipes
        }
        
        // Démarrer la transaction
        $this->pdo->beginTransaction();
        
        try {
            // Supprimer les matchs existants si demandé
            if (!isset($options['preserveExisting']) || !$options['preserveExisting']) {
                $phase = isset($options['phase']) ? $options['phase'] : null;
                
                $delQuery = "DELETE FROM matchs WHERE tournoi_id = ? AND statut = 'à_venir'";
                if ($phase) {
                    $delQuery .= " AND phase = ?";
                    $delStmt = $this->pdo->prepare($delQuery);
                    $delStmt->execute([$tournoiId, $phase]);
                } else {
                    $delStmt = $this->pdo->prepare($delQuery);
                    $delStmt->execute([$tournoiId]);
                }
            }
            
            $dateDebut = new \DateTime($tournoi['date_debut']);
            $dateFin = new \DateTime($tournoi['date_fin']);
            
            // Créer les matchs selon le format
            if ($format == 'championnat') {
                $this->generateChampionnatMatches($tournoiId, $equipes, $dateDebut, $dateFin, $tournoi);
            } 
            else if ($format == 'coupe') {
                $phase = isset($options['phase']) ? $options['phase'] : 'groupes';
                
                if ($phase == 'groupes') {
                    $this->generateGroupMatches($tournoiId, $equipes, $dateDebut, $dateFin, $tournoi, $options);
                } else if ($phase == 'elimination') {
                    $this->generateEliminationMatches($tournoiId, $equipes, $dateDebut, $dateFin, $tournoi, $options);
                }
            }
            
            $this->pdo->commit();
            return true;
            
        } catch (\Exception $e) {
            // S'il y a une erreur, on fait rollback
            $this->pdo->rollBack();
            throw $e;
        }
        
    } catch (\Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Génère les matchs pour un format championnat
 */
private function generateChampionnatMatches($tournoiId, $equipes, $dateDebut, $dateFin, $tournoi) {
    $nbJours = $dateFin->diff($dateDebut)->days;
    $jourInterval = max(1, floor($nbJours / (count($equipes) - 1)));
    
    $matchCount = 0;
    // Chaque équipe joue contre toutes les autres
    for ($i = 0; $i < count($equipes); $i++) {
        for ($j = $i + 1; $j < count($equipes); $j++) {
            $matchDate = clone $dateDebut;
            $matchDate->modify('+' . ($matchCount * $jourInterval) . ' days');
            
            // S'assurer que la date ne dépasse pas la date de fin
            if ($matchDate > $dateFin) {
                $matchDate = clone $dateDebut;
                $matchDate->modify('+' . ($matchCount % $nbJours) . ' days');
            }
            
            // Créer le match
            $this->create(
                $tournoiId,
                $equipes[$i]['id'],
                $equipes[$j]['id'],
                $matchDate->format('Y-m-d H:i:s'),
                $tournoi['lieu'],
                'Championnat'  // Phase
            );
            
            $matchCount++;
        }
    }
}



/**
 * Génère les matchs de la phase éliminatoire
 */
private function generateEliminationMatches($tournoiId, $equipes, $dateDebut, $dateFin, $tournoi, $options) {
    // Pour générer les phases éliminatoires, nous avons besoin des équipes qualifiées
    // Ces équipes peuvent être soit passées dans les options, soit calculées à partir des résultats de groupes
    
    $qualifiedTeams = [];
    
    if (isset($options['qualifiedTeams']) && !empty($options['qualifiedTeams'])) {
        $qualifiedTeams = $options['qualifiedTeams'];
    } else {
        // Récupérer les équipes qualifiées à partir des résultats de groupes
        $qualifiedTeams = $this->getQualifiedTeamsFromGroups($tournoiId);
    }
    
    if (count($qualifiedTeams) < 2) {
        throw new \Exception("Pas assez d'équipes qualifiées pour générer la phase éliminatoire");
    }
    
    // Déterminer la structure des phases éliminatoires
    $nbTeams = count($qualifiedTeams);
    $rounds = [
        2 => 'Finale',
        4 => 'Demi-finales',
        8 => 'Quarts de finale',
        16 => 'Huitièmes de finale'
    ];
    
    // Trouver la puissance de 2 la plus proche (inférieure ou égale)
    $power = 1;
    while ($power * 2 <= $nbTeams) {
        $power *= 2;
    }
    
    $roundName = isset($rounds[$power]) ? $rounds[$power] : 'Tour préliminaire';
    
    // Calculer la durée disponible pour les matchs éliminatoires
    $dureeTotale = $dateFin->diff($dateDebut)->days;
    $debutElimination = clone $dateDebut;
    $debutElimination->modify('+' . round($dureeTotale * 0.6) . ' days'); // Après les phases de groupes
    
    // Générer les matchs de la première phase éliminatoire
    for ($i = 0; $i < $power; $i += 2) {
        if ($i + 1 < count($qualifiedTeams)) {
            $matchDate = clone $debutElimination;
            $matchDate->modify('+' . floor($i/2) . ' days');
            
            $this->create(
                $tournoiId,
                $qualifiedTeams[$i]['id'],
                $qualifiedTeams[$i+1]['id'],
                $matchDate->format('Y-m-d H:i:s'),
                $tournoi['lieu'],
                $roundName
            );
        }
    }
    
    // Optionnellement, vous pouvez générer des matchs pour les tours suivants (demi-finales, finales)
    // Mais sans savoir quelles équipes vont se qualifier, vous pouvez seulement créer des "slots" vides
    // Ces matchs devront être mis à jour après chaque tour
}

/**
 * Récupère les équipes qualifiées à partir des résultats des matchs de groupes
 */
private function getQualifiedTeamsFromGroups($tournoiId) {
    // Cette méthode doit calculer le classement des équipes dans chaque groupe
    // et retourner les équipes qualifiées selon les règles du tournoi
    
    // Récupérer tous les matchs de groupe terminés
    $stmt = $this->pdo->prepare("
        SELECT m.*, 
               e1.id as equipe1_id, e1.nom as equipe1_nom,
               e2.id as equipe2_id, e2.nom as equipe2_nom,
               m.phase
        FROM matchs m
        JOIN equipes e1 ON m.equipe1_id = e1.id
        JOIN equipes e2 ON m.equipe2_id = e2.id
        WHERE m.tournoi_id = ? AND m.statut = 'terminé' AND m.phase LIKE 'Groupe %'
        ORDER BY m.phase, m.date_match
    ");
    $stmt->execute([$tournoiId]);
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Organiser les matchs par groupe
    $groupMatches = [];
    foreach ($matches as $match) {
        $groupe = $match['phase'];
        if (!isset($groupMatches[$groupe])) {
            $groupMatches[$groupe] = [];
        }
        $groupMatches[$groupe][] = $match;
    }
    
    // Calculer les points et le classement pour chaque groupe
    $groupStandings = [];
    foreach ($groupMatches as $groupe => $matchList) {
        $standings = [];
        
        // Calculer les points pour chaque équipe dans le groupe
        foreach ($matchList as $match) {
            // Initialiser les équipes si pas encore présentes
            if (!isset($standings[$match['equipe1_id']])) {
                $standings[$match['equipe1_id']] = [
                    'id' => $match['equipe1_id'],
                    'nom' => $match['equipe1_nom'],
                    'points' => 0,
                    'buts_pour' => 0,
                    'buts_contre' => 0,
                    'diff' => 0
                ];
            }
            
            if (!isset($standings[$match['equipe2_id']])) {
                $standings[$match['equipe2_id']] = [
                    'id' => $match['equipe2_id'],
                    'nom' => $match['equipe2_nom'],
                    'points' => 0,
                    'buts_pour' => 0,
                    'buts_contre' => 0,
                    'diff' => 0
                ];
            }
            
            // Attribuer les points et mettre à jour les statistiques
            $score1 = $match['score_equipe1'];
            $score2 = $match['score_equipe2'];
            
            // Équipe 1
            $standings[$match['equipe1_id']]['buts_pour'] += $score1;
            $standings[$match['equipe1_id']]['buts_contre'] += $score2;
            
            // Équipe 2
            $standings[$match['equipe2_id']]['buts_pour'] += $score2;
            $standings[$match['equipe2_id']]['buts_contre'] += $score1;
            
            // Attribuer les points (3 pour victoire, 1 pour nul, 0 pour défaite)
            if ($score1 > $score2) {
                $standings[$match['equipe1_id']]['points'] += 3;
            } elseif ($score1 < $score2) {
                $standings[$match['equipe2_id']]['points'] += 3;
            } else {
                $standings[$match['equipe1_id']]['points'] += 1;
                $standings[$match['equipe2_id']]['points'] += 1;
            }
        }
        
        // Calculer la différence de buts
        foreach ($standings as $id => $team) {
            $standings[$id]['diff'] = $team['buts_pour'] - $team['buts_contre'];
        }
        
        // Trier les équipes par points, puis différence de buts
        uasort($standings, function($a, $b) {
            if ($a['points'] != $b['points']) {
                return $b['points'] - $a['points']; // Descending points
            }
            return $b['diff'] - $a['diff']; // Descending goal difference
        });
        
        $groupStandings[$groupe] = array_values($standings);
    }
    
    // Sélectionner les équipes qualifiées (par exemple, les 2 premières de chaque groupe)
    $qualifiedTeams = [];
    foreach ($groupStandings as $groupe => $standings) {
        // Prendre les 2 premières équipes de chaque groupe
        for ($i = 0; $i < min(2, count($standings)); $i++) {
            $qualifiedTeams[] = [
                'id' => $standings[$i]['id'],
                'nom' => $standings[$i]['nom'],
                'groupe' => $groupe,
                'position' => $i + 1
            ];
        }
    }
    
    // Trier les équipes qualifiées pour les apparier pour la phase éliminatoire
    // (Typiquement, 1er du groupe A contre 2ème du groupe B, etc.)
    usort($qualifiedTeams, function($a, $b) {
        if ($a['groupe'] != $b['groupe']) {
            return strcmp($a['groupe'], $b['groupe']);
        }
        return $a['position'] - $b['position'];
    });
    
    return $qualifiedTeams;
}



/**
 * Vérifie si un tournoi a déjà des matchs
 * 
 * @param int $tournoiId ID du tournoi
 * @return bool True si le tournoi a des matchs, false sinon
 */

public function tournamentHasMatches($tournoiId) {
    $sql = "SELECT COUNT(*) FROM matchs WHERE tournoi_id = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$tournoiId]);
    return $stmt->fetchColumn() > 0;
}



/**
 * Récupère les phases de matchs existantes pour un tournoi
 * @param int $tournoiId ID du tournoi
 * @return array Liste des phases distinctes
 */
public function getPhasesByTournoi($tournoiId) {
    $sql = "SELECT DISTINCT phase FROM matchs WHERE tournoi_id = :tournoi_id ORDER BY phase";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['tournoi_id' => $tournoiId]);
    
    // Retourne un tableau de tableaux associatifs: [['phase' => 'Phase de groupes'], ['phase' => 'Quarts de finale'], etc.]
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}








/**
 * Récupère tous les matchs d'une phase spécifique d'un tournoi
 * 
 * @param int $tournoiId L'identifiant du tournoi
 * @param string $phase Le nom de la phase (ex: "Phase de groupes", "Quarts de finale", etc.)
 * @return array Un tableau contenant tous les matchs de la phase spécifiée
 */
public function getByPhase($tournoiId, $phase) {
    $sql = "SELECT m.*, 
                  e1.nom AS equipe1_nom, e1.id AS equipe1_id, 
                  e2.nom AS equipe2_nom, e2.id AS equipe2_id
                  
           FROM matchs m
           LEFT JOIN equipes e1 ON m.equipe1_id = e1.id
           LEFT JOIN equipes e2 ON m.equipe2_id = e2.id
        
           WHERE m.tournoi_id = :tournoi_id 
           AND m.phase = :phase
           ORDER BY m.date_match ASC";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':tournoi_id' => $tournoiId,
        ':phase' => $phase
    ]);
    
    $matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Si vous avez besoin de formater les données (dates, scores, etc.)
    foreach ($matchs as &$match) {
        // Formatage de la date si nécessaire
        if (isset($match['date_match'])) {
            $date = new DateTime($match['date_match']);
            $match['date_formattee'] = $date->format('d/m/Y');
            $match['heure_formattee'] = $date->format('H:i');
        }
        
        // Garantir que les scores sont numériques
        $match['score1'] = isset($match['score1']) ? intval($match['score1']) : null;
        $match['score2'] = isset($match['score2']) ? intval($match['score2']) : null;
    }
    
    return $matchs;
}



public function getByGroupe($groupeId) {
    $stmt = $this->pdo->prepare("
        SELECT m.*, 
               e1.nom AS equipe1_nom, 
               e2.nom AS equipe2_nom
        FROM matchs m
        JOIN equipes e1 ON m.equipe1_id = e1.id
        JOIN equipes e2 ON m.equipe2_id = e2.id
        WHERE m.groupe_id = ?
        ORDER BY m.date_match ASC
    ");
    $stmt->execute([$groupeId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Générer les matchs pour un groupe (format championnat - chaque équipe rencontre toutes les autres)
 */
public function generateGroupMatches($tournoiId, $groupeId) {
    try {
        // Récupérer les infos du tournoi
        $tournoiStmt = $this->pdo->prepare("SELECT * FROM tournois WHERE id = ?");
        $tournoiStmt->execute([$tournoiId]);
        $tournoi = $tournoiStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$tournoi) {
            return false;
        }
        
        // Récupérer le groupe
        $groupeStmt = $this->pdo->prepare("SELECT * FROM groupes WHERE id = ?");
        $groupeStmt->execute([$groupeId]);
        $groupe = $groupeStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$groupe) {
            return false;
        }
        
        // Récupérer les équipes du groupe
        $equipesStmt = $this->pdo->prepare("
            SELECT e.* 
            FROM equipes e
            JOIN groupe_equipe ge ON e.id = ge.equipe_id
            WHERE ge.groupe_id = ?
        ");
        $equipesStmt->execute([$groupeId]);
        $equipes = $equipesStmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($equipes) < 2) {
            return false; // Impossible de créer des matchs avec moins de 2 équipes
        }
        
        // Démarrer la transaction
        $this->pdo->beginTransaction();
        
        try {
            // Supprimer les matchs existants du groupe si nécessaire
            $delStmt = $this->pdo->prepare("
                DELETE FROM matchs 
                WHERE groupe_id = ? AND statut = 'à_venir'
            ");
            $delStmt->execute([$groupeId]);
            
            $dateDebut = new \DateTime($tournoi['date_debut']);
            $dateFin = new \DateTime($tournoi['date_fin']);
            $nbJours = $dateFin->diff($dateDebut)->days;
            
            // S'assurer que l'intervalle est au moins de 1 jour
            $jourInterval = max(1, floor($nbJours / (count($equipes) - 1)));
            
            $matchCount = 0;
            $phaseNom = "Phase de groupes - Groupe " . $groupe['nom'];
            
            // Chaque équipe joue contre toutes les autres
            for ($i = 0; $i < count($equipes); $i++) {
                for ($j = $i + 1; $j < count($equipes); $j++) {
                    $matchDate = clone $dateDebut;
                    $matchDate->modify('+' . ($matchCount * $jourInterval) . ' days');
                    
                    // S'assurer que la date ne dépasse pas la date de fin
                    if ($matchDate > $dateFin) {
                        $matchDate = clone $dateDebut;
                        $matchDate->modify('+' . ($matchCount % $nbJours) . ' days');
                    }
                    
                    // Créer le match
                    $stmt = $this->pdo->prepare("
                        INSERT INTO matchs (
                            tournoi_id, equipe1_id, equipe2_id, date_match, 
                            lieu_match, statut, phase, groupe_id
                        ) VALUES (?, ?, ?, ?, ?, 'à_venir', ?, ?)
                    ");
                    $stmt->execute([
                        $tournoiId,
                        $equipes[$i]['id'],
                        $equipes[$j]['id'],
                        $matchDate->format('Y-m-d H:i:s'),
                        $tournoi['lieu'],
                        $phaseNom,
                        $groupeId
                    ]);
                    
                    $matchCount++;
                }
            }
            
            $this->pdo->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        
    } catch (\Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}









public function getMatchsPhasesFinales($tournoiId) {
    $stmt = $this->pdo->prepare("
        SELECT m.*, 
               e1.nom as equipe1_nom, e1.logo as equipe1_logo,
               e2.nom as equipe2_nom, e2.logo as equipe2_logo
        FROM matchs m
        JOIN equipes e1 ON m.equipe1_id = e1.id
        JOIN equipes e2 ON m.equipe2_id = e2.id
        WHERE m.tournoi_id = ? 
        AND m.phase NOT LIKE 'Groupe%'
        AND m.phase IS NOT NULL
        ORDER BY 
            CASE m.phase
                WHEN 'Huitièmes de finale' THEN 1
                WHEN 'Quarts de finale' THEN 2
                WHEN 'Demi-finales' THEN 3
                WHEN 'Petite finale' THEN 4
                WHEN 'Finale' THEN 5
                ELSE 6
            END,
            m.date_match
    ");
    $stmt->execute([$tournoiId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}