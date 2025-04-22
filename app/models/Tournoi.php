<?php
namespace App\models;
use PDO;
use PDOException;
class Tournoi {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Créer un tournoi
    public function create($nom, $lieu, $dateDebut, $dateFin, $format, $categorie, $nbEquipes) {
        $sql = "INSERT INTO tournois (nom, lieu, date_debut, date_fin, format, categorie, nb_equipes_max) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $lieu, $dateDebut, $dateFin, $format, $categorie, $nbEquipes]);
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
                format = ?,
                categorie = ?,
                nb_equipes_max = ?,
                statut = ?
                WHERE id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $lieu, $dateDebut, $dateFin, $format, $categorie, $nbEquipes, $statut, $id]);
    }

    // Supprimer un tournoi
    public function delete($id) {
        // D'abord supprimer les dépendances (équipes, matchs)
        $this->pdo->beginTransaction();
        
        try {
            // 1. Supprimer les matchs associés
            $stmt = $this->pdo->prepare("DELETE FROM matchs WHERE tournoi_id = ?");
            $stmt->execute([$id]);
            
            // 2. Supprimer les équipes associées
            $stmt = $this->pdo->prepare("DELETE FROM equipes WHERE tournoi_id = ?");
            $stmt->execute([$id]);
            
            // 3. Supprimer le tournoi
            $stmt = $this->pdo->prepare("DELETE FROM tournois WHERE id = ?");
            $stmt->execute([$id]);
            
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erreur suppression tournoi: " . $e->getMessage());
            return false;
        }
    }

    // Filtrer par statut
    public function getByStatus($status) {
        $stmt = $this->pdo->prepare("SELECT * FROM tournois WHERE statut = ? ORDER BY date_debut DESC");
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compter le nombre d'équipes inscrites
    public function countTeams($tournoiId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM equipes WHERE tournoi_id = ?");
        $stmt->execute([$tournoiId]);
        return $stmt->fetchColumn();
    }



 /****************GENERATIONS DE GROUPES - MATCHES**************************************** */   
 

  // Créer une poule
public function creerPoule($tournoiId, $nom) {
    $stmt = $this->pdo->prepare("INSERT INTO poules (tournoi_id, nom) VALUES (?, ?)");
    $stmt->execute([$tournoiId, $nom]);
    return $this->pdo->lastInsertId();
}
// Assigner une équipe à une poule
public function assignerEquipePoule($pouleId, $equipeId) {
    $stmt = $this->pdo->prepare("INSERT INTO equipe_poule (poule_id, equipe_id) VALUES (?, ?)");
    return $stmt->execute([$pouleId, $equipeId]);
}


// Générer les poules pour un tournoi
public function genererPoules($tournoiId, $nbPoules) {
    // Vérifier que le nombre de poules est valide
    if (!in_array($nbPoules, [2, 4, 8])) {
        throw new \Exception("Le nombre de poules doit être 2, 4 ou 8");
    }
    
    // Commencer une transaction
    $this->pdo->beginTransaction();
    
    try {
        // Supprimer les anciennes poules si elles existent
        $this->supprimerPoules($tournoiId);
        
        // Récupérer toutes les équipes du tournoi
        $stmt = $this->pdo->prepare("SELECT * FROM equipes WHERE tournoi_id = ?");
        $stmt->execute([$tournoiId]);
        $equipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Vérifier qu'il y a assez d'équipes
        if (count($equipes) < $nbPoules * 2) {
            throw new \Exception("Il n'y a pas assez d'équipes pour créer " . $nbPoules . " poules");
        }
        
        // Mélanger les équipes pour une répartition aléatoire
        shuffle($equipes);
        
        // Créer les poules
        $poules = [];
        $lettres = range('A', 'Z');
        
        for ($i = 0; $i < $nbPoules; $i++) {
            $nomPoule = "Poule " . $lettres[$i];
            $pouleId = $this->creerPoule($tournoiId, $nomPoule);
            $poules[] = $pouleId;
        }
        
        // Répartir les équipes dans les poules
        $nbEquipesParPoule = floor(count($equipes) / $nbPoules);
        $equipesRestantes = count($equipes) % $nbPoules;
        
        $index = 0;
        
        for ($i = 0; $i < $nbPoules; $i++) {
            $nbEquipesDansCettePoule = $nbEquipesParPoule + ($i < $equipesRestantes ? 1 : 0);
            
            for ($j = 0; $j < $nbEquipesDansCettePoule; $j++) {
                if ($index < count($equipes)) {
                    $this->assignerEquipePoule($poules[$i], $equipes[$index]['id']);
                    $index++;
                }
            }
        }
        
        // Marquer les poules comme générées
        $this->marquerPoulesGenerees($tournoiId, true);
        
        $this->pdo->commit();
        return true;
    } catch (\Exception $e) {
        $this->pdo->rollBack();
        throw $e;
    }
}

// Marquer qu'un tournoi a ses poules générées
public function marquerPoulesGenerees($tournoiId, $etat = true) {
    $stmt = $this->pdo->prepare("UPDATE tournois SET poules_generees = ? WHERE id = ?");
    return $stmt->execute([$etat ? 1 : 0, $tournoiId]);
}

// Supprimer les poules d'un tournoi
public function supprimerPoules($tournoiId) {
    // Supprimer les matchs des poules
    $stmt = $this->pdo->prepare("
        DELETE FROM matchs 
        WHERE tournoi_id = ? AND poule_id IS NOT NULL
    ");
    $stmt->execute([$tournoiId]);
    
    // Récupérer les IDs des poules du tournoi
    $stmt = $this->pdo->prepare("SELECT id FROM poules WHERE tournoi_id = ?");
    $stmt->execute([$tournoiId]);
    $poules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Supprimer les associations équipe-poule
    foreach ($poules as $poule) {
        $stmt = $this->pdo->prepare("DELETE FROM equipe_poule WHERE poule_id = ?");
        $stmt->execute([$poule['id']]);
    }
    
    // Supprimer les poules
    $stmt = $this->pdo->prepare("DELETE FROM poules WHERE tournoi_id = ?");
    $success = $stmt->execute([$tournoiId]);
    
    if ($success) {
        $this->marquerPoulesGenerees($tournoiId, false);
    }
    
    return $success;
}
// Récupérer les poules d'un tournoi
public function getPoulesWithEquipesByTournoi($tournoiId) {
    $stmt = $this->pdo->prepare("
        SELECT p.id, p.nom, p.tournoi_id
        FROM poules p
        WHERE p.tournoi_id = ?
    ");
    $stmt->execute([$tournoiId]);
    $poules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($poules as &$poule) {
        $stmt = $this->pdo->prepare("
            SELECT e.* 
            FROM equipes e
            JOIN equipe_poule ep ON e.id = ep.equipe_id
            WHERE ep.poule_id = ?
        ");
        $stmt->execute([$poule['id']]);
        $poule['equipes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    return $poules;
}
 
}