<?php
namespace App\models;
use PDO;
use PDOException;
use Exception;
class Tournoi {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Créer un tournoi
 /**
 * Crée un nouveau tournoi
 * @param string $nom Nom du tournoi
 * @param string $lieu Lieu où se déroule le tournoi
 * @param string $dateDebut Date de début (format YYYY-MM-DD)
 * @param string $dateFin Date de fin (format YYYY-MM-DD)
 * @param string $categorie Catégorie du tournoi
 * @param int $nbEquipes Nombre maximum d'équipes
 * @return int|bool L'ID du tournoi créé ou false en cas d'échec
 */
/**
 * Crée un nouveau tournoi
 * @param string $nom Nom du tournoi
 * @param string $lieu Lieu où se déroule le tournoi
 * @param string $dateDebut Date de début (format YYYY-MM-DD)
 * @param string $dateFin Date de fin (format YYYY-MM-DD)
 * @param string $categorie Catégorie du tournoi
 * @param int $nbEquipes Nombre maximum d'équipes
 * @return bool True si l'insertion a réussi, False sinon
 */
public function create($nom, $lieu, $dateDebut, $dateFin, $categorie, $nbEquipes,$format) {
    $sql = "INSERT INTO tournois (nom, lieu, date_debut, date_fin, categorie, nb_equipes_max,format) 
            VALUES (?, ?, ?, ?, ?, ?,?)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$nom, $lieu, $dateDebut, $dateFin, $categorie, $nbEquipes,$format]);
}


    // Lister tous les tournois
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM tournois ORDER BY date_debut DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un tournoi par son ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tournois WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour un tournoi
    public function update($id, $nom, $lieu, $dateDebut, $dateFin, $format, $categorie, $nbEquipes, $statut) {
        $sql = "UPDATE tournois SET 
                nom = ?,
                lieu = ?,
                date_debut = ?,
                date_fin = ?,
                categorie = ?,
                nb_equipes_max = ?,
                statut = ?
                WHERE id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $lieu, $dateDebut, $dateFin,  $categorie, $nbEquipes, $statut, $id]);
    }


    // Supprimer un tournoi
    public function delete($id) {
           $stmt = $this->pdo->prepare("DELETE FROM tournois WHERE id = ?");
           return $stmt->execute([$id]);
          
       
    }

    // Filtrer par statut
    public function getByStatus($status) {
        $stmt = $this->pdo->prepare("SELECT * FROM tournois WHERE statut = ? ORDER BY date_debut DESC");
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compter le nombre d'équipes inscrites
    public function countTeams($tournoiId) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tournoi_equipe WHERE tournoi_id = ?");
            $stmt->execute([$tournoiId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des équipes pour le tournoi #$tournoiId: " . $e->getMessage());
            return 0;
        }
    }
    

/**
 * Récupérer les tournois à venir
 * 
 * @param int $limit Le nombre maximum de tournois à récupérer
 * @return array Les tournois à venir
 */
public function getUpcomingTournois($limit = 5) {
    try {
        $today = date('Y-m-d');
        $sql = "SELECT * FROM tournois 
                WHERE date_debut >= :today 
                ORDER BY date_debut ASC 
                LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':today', $today);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $tournois = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Pour chaque tournoi, ajouter le nombre d'équipes inscrites
        foreach ($tournois as &$tournoi) {
            $tournoi['places_prises'] = $this->countTeams($tournoi['id']);
            $tournoi['places_restantes'] = $tournoi['nb_equipes_max'] - $tournoi['places_prises'];
        }
        
        return $tournois;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des tournois à venir : " . $e->getMessage());
        return [];
    }
}


public function getGroupesByTournoiId($tournoiId) {
    $stmt = $this->pdo->prepare("
        SELECT * FROM groupes WHERE tournoi_id = ? ORDER BY nom
    ");
    $stmt->execute([$tournoiId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function allGroupMatchesCompleted($tournoiId) {
    $stmt = $this->pdo->prepare("
        SELECT COUNT(*) as total FROM matchs m
        JOIN groupes g ON m.groupe_id = g.id
        WHERE g.tournoi_id = ? AND m.statut != 'terminé'
    ");
    $stmt->execute([$tournoiId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Si le nombre de matchs non terminés est 0, alors tous sont terminés
    return $result['total'] == 0;
}








public function getGroupStandings($groupeId) {
    // 1. Récupérer toutes les équipes du groupe
    $stmt = $this->pdo->prepare("
        SELECT eg.equipe_id, e.nom
        FROM groupe_equipe eg
        JOIN equipes e ON eg.equipe_id = e.id
        WHERE eg.groupe_id = ?
    ");
    $stmt->execute([$groupeId]);
    $equipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 2. Pour chaque équipe, calculer ses stats
    foreach ($equipes as &$equipe) {
        $equipeId = $equipe['equipe_id'];
        
        // Initialiser les stats
        $equipe['points'] = 0;
        $equipe['joues'] = 0;
        $equipe['gagnes'] = 0;
        $equipe['nuls'] = 0;
        $equipe['perdus'] = 0;
        $equipe['buts_pour'] = 0;
        $equipe['buts_contre'] = 0;
        
        // Récupérer tous les matchs terminés de l'équipe dans ce groupe
        $stmt = $this->pdo->prepare("
            SELECT m.*, g.id as groupe_id
            FROM matchs m
            JOIN groupes g ON m.groupe_id = g.id
            WHERE g.id = ? 
            AND (m.equipe1_id = ? OR m.equipe2_id = ?)
            AND m.statut = 'terminé'
        ");
        $stmt->execute([$groupeId, $equipeId, $equipeId]);
        $matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculer les statistiques
        foreach ($matchs as $match) {
            $equipe['joues']++;
            
            if ($match['equipe1_id'] == $equipeId) {
                // L'équipe joue à domicile
                $equipe['buts_pour'] += $match['score1'];
                $equipe['buts_contre'] += $match['score2'];
                
                if ($match['score1'] > $match['score2']) {
                    $equipe['gagnes']++;
                    $equipe['points'] += 3;
                } elseif ($match['score1'] == $match['score2']) {
                    $equipe['nuls']++;
                    $equipe['points'] += 1;
                } else {
                    $equipe['perdus']++;
                }
                
            } else {
                // L'équipe joue à l'extérieur
                $equipe['buts_pour'] += $match['score2'];
                $equipe['buts_contre'] += $match['score1'];
                
                if ($match['score2'] > $match['score1']) {
                    $equipe['gagnes']++;
                    $equipe['points'] += 3;
                } elseif ($match['score1'] == $match['score2']) {
                    $equipe['nuls']++;
                    $equipe['points'] += 1;
                } else {
                    $equipe['perdus']++;
                }
            }
        }
        
        // Calculer la différence de buts
        $equipe['diff_buts'] = $equipe['buts_pour'] - $equipe['buts_contre'];
    }
    
    // 3. Trier les équipes par points, puis différence de buts, puis buts marqués
    usort($equipes, function($a, $b) {
        // D'abord par points
        if ($b['points'] != $a['points']) {
            return $b['points'] - $a['points'];
        }
        // Ensuite par différence de buts
        if ($b['diff_buts'] != $a['diff_buts']) {
            return $b['diff_buts'] - $a['diff_buts'];
        }
        // Puis par buts marqués
        return $b['buts_pour'] - $a['buts_pour'];
    });
    
    return $equipes;
}








public function knockoutStageExists($tournoiId) {
    $stmt = $this->pdo->prepare("
        SELECT COUNT(*) as count FROM matchs 
        WHERE tournoi_id = ? AND phase != 'groupe'
    ");
    $stmt->execute([$tournoiId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
}

public function createKnockoutStage($tournoiId) {
    // 1. Obtenir tous les groupes du tournoi
    $groupes = $this->getGroupesByTournoiId($tournoiId);
    
    // 2. Déterminer le nombre d'équipes qualifiées (2 par groupe)
    $nombreEquipesQualifiees = count($groupes) * 2;
    
    // 3. Déterminer la phase initiale (1/8 de finale, 1/4 de finale, etc.)
    $phaseInitiale = $this->determinerPhaseInitiale($nombreEquipesQualifiees);
    
    // 4. Récupérer les équipes qualifiées (2 premières de chaque groupe)
    $equipesQualifiees = [];
    foreach ($groupes as $groupe) {
        $classement = $this->getGroupStandings($groupe['id']);
        // Prendre les 2 premières équipes
        if (count($classement) >= 2) {
            $equipesQualifiees[] = $classement[0]; // 1er du groupe
            $equipesQualifiees[] = $classement[1]; // 2e du groupe
        }
    }
    
    // 5. Créer les matchs de la première phase à élimination directe
    $this->pdo->beginTransaction();
    
    try {
        // Générer les matchs selon le format: 1er groupe A vs 2e groupe B, etc.
        for ($i = 0; $i < count($equipesQualifiees); $i += 4) {
            // 1er du groupe i/2 contre 2e du groupe i/2+1
            $this->createKnockoutMatch(
                $tournoiId,
                $equipesQualifiees[$i]['equipe_id'],      // 1er groupe A
                $equipesQualifiees[$i+3]['equipe_id'],    // 2e groupe B
                $phaseInitiale,
                1 + ($i/4)                                // Numéro du match
            );
            
            // 1er du groupe i/2+1 contre 2e du groupe i/2
            $this->createKnockoutMatch(
                $tournoiId,
                $equipesQualifiees[$i+2]['equipe_id'],    // 1er groupe B
                $equipesQualifiees[$i+1]['equipe_id'],    // 2e groupe A
                $phaseInitiale,
                2 + ($i/4)                                // Numéro du match
            );
        }
        
        // Créer les emplacements pour les phases suivantes (1/4, 1/2, finale)
        $this->createNextRounds($tournoiId, $phaseInitiale, $nombreEquipesQualifiees);
        
        $this->pdo->commit();
        return true;
    } catch (Exception $e) {
        $this->pdo->rollBack();
        error_log($e->getMessage());
        return false;
    }
}

private function determinerPhaseInitiale($nombreEquipes) {
    if ($nombreEquipes >= 16) return '1/8';
    if ($nombreEquipes >= 8) return '1/4';
    if ($nombreEquipes >= 4) return '1/2';
    return 'finale';
}

private function createKnockoutMatch($tournoiId, $equipe1Id, $equipe2Id, $phase, $numeroMatch) {
    $stmt = $this->pdo->prepare("
        INSERT INTO matchs (tournoi_id, equipe1_id, equipe2_id, phase, numero_match, statut, date_match, created_at)
        VALUES (?, ?, ?, ?, ?, 'à_venir', DATE_ADD(NOW(), INTERVAL 1 DAY), NOW())
    ");
    $stmt->execute([$tournoiId, $equipe1Id, $equipe2Id, $phase, $numeroMatch]);
    return $this->pdo->lastInsertId();
}

private function createNextRounds($tournoiId, $phaseInitiale, $nombreEquipes) {
    $phases = ['1/8', '1/4', '1/2', 'finale'];
    $phaseIndex = array_search($phaseInitiale, $phases);
    
    // Créer les matchs pour les phases suivantes
    for ($i = $phaseIndex + 1; $i < count($phases); $i++) {
        $phase = $phases[$i];
        $nombreMatchs = pow(2, count($phases) - $i - 1);
        
        for ($j = 1; $j <= $nombreMatchs; $j++) {
            $stmt = $this->pdo->prepare("
                INSERT INTO matchs (tournoi_id, phase, numero_match, statut, date_match, created_at)
                VALUES (?, ?, ?, 'à_venir', DATE_ADD(NOW(), INTERVAL ? DAY), NOW())
            ");
            $stmt->execute([$tournoiId, $phase, $j, ($i-$phaseIndex)+1]);
        }
    }
}

public function getKnockoutMatches($tournoiId) {
    $phases = ['1/8', '1/4', '1/2', 'finale'];
    $result = [];
    
    foreach ($phases as $phase) {
        $stmt = $this->pdo->prepare("
            SELECT m.*, 
                   e1.nom as equipe1_nom, 
                   e2.nom as equipe2_nom
            FROM matchs m
            LEFT JOIN equipes e1 ON m.equipe1_id = e1.id
            LEFT JOIN equipes e2 ON m.equipe2_id = e2.id
            WHERE m.tournoi_id = ? AND m.phase = ?
            ORDER BY m.numero_match
        ");
        $stmt->execute([$tournoiId, $phase]);
        $matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($matchs) > 0) {
            $result[$phase] = $matchs;
        }
    }
    
    return $result;
}





}