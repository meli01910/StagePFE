<?php
namespace App\models;
use PDO; 

class Detection {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getAll() {
        $sql = "SELECT * FROM detections";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    
                                                                                                                       
    public function getDetectionById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM detections WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($nom, $date, $lieu, $partnerClub, $categorie, $maxParticipants, $status) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO detections (name, date,location,partner_club,age_category, max_participants, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$nom, $date, $lieu,$partnerClub, $categorie, $maxParticipants, $status]);
    }

    
    
    public function getDetectionsByStatus($status) {
        $stmt = $this->pdo->prepare("SELECT * FROM detections WHERE status = ? ORDER BY date");
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public function update($id, $data) {
        // Vérifier que l'ID est valide
        if (!isset($id) || !is_numeric($id)) {
            return false;
        }
        
        // Construire la requête SQL dynamiquement en fonction des champs fournis
        $fields = [];
        $params = [];
        
        // Liste des champs autorisés à être mis à jour
        $allowedFields = [
            'name', 'date', 'location', 'partner_club', 
            'age_category', 'max_participants', 'status'
        ];
        
        // Construction des paires champ=valeur pour la requête SQL
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $fields[] = "$field = :$field";
                $params[$field] = $value;
            }
        }
        
        // Vérifier qu'il y a des champs à mettre à jour
        if (empty($fields)) {
            return false;
        }
        
        // Construction de la requête SQL complète
        $sql = "UPDATE detections SET " . implode(', ', $fields) . " WHERE id = :id";
        $params['id'] = $id;
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result) {
                error_log("Détection ID $id mise à jour avec succès");
            } else {
                error_log("Échec de mise à jour de la détection ID $id");
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour de la détection ID $id: " . $e->getMessage());
            return false;
        }
    }


    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM detections WHERE id = ?");
        return $stmt->execute([$id]);
    }
    /**
 * Récupère les sessions de détection à venir
 * @param int $limit
 * @return array
 */
/**
 * Récupère les sessions de détection à venir
 * @param int $limit
 * @return array
 */
public function getUpcoming($limit = 3) {
    // Version simplifiée sans compter les inscriptions
    $sql = "SELECT * 
            FROM detections";
    
    if ($limit) {
        $sql .= " LIMIT :limit";
    }
    
    $stmt = $this->pdo->prepare($sql);
    
    if ($limit) {
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    }
    
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Vérification des données pour éviter les erreurs
    foreach ($results as &$detection) {
        if (!isset($detection['nom'])) $detection['nom'] = 'Session sans nom';
        if (!isset($detection['location'])) $detection['location'] = 'Lieu non défini';
        if (!isset($detection['date']) || empty($detection['date'])) $detection['date'] = null;
        // Fournir une valeur par défaut pour inscrits puisque nous n'avons pas cette information
      
    }
    
    return $results;
}


/**
 * Compte les sessions de détection à venir
 * @return int
 */
public function countUpcoming() {
    $sql = "SELECT COUNT(*) FROM detections WHERE date >= CURDATE()";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}

    
}
