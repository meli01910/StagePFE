<?php
namespace App\models;
use PDO; 
use PDOException;
use DateTime;
use Exception;
class Detection {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getAll() {
        $sql = "SELECT * FROM detections";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
                                                                                                                       
  // Dans le modèle DetectionModel
public function getDetectionById($id, $userId = null) {
    try {
        // Requête SQL pour récupérer les infos de la détection avec le comptage des inscrits
        $sql = "SELECT d.*, COUNT(i.id) as inscriptions_count 
                FROM detections d 
                LEFT JOIN inscription_detections i ON d.id = i.detection_id AND i.status != 'canceled'
                WHERE d.id = :id
                GROUP BY d.id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $detection = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$detection) {
            return null;
        }
        
        // Vérifier si l'utilisateur est inscrit
        $estInscrit = false;
        if ($userId) {
            $checkSql = "SELECT id FROM inscription_detections 
                        WHERE joueur_id = :userId AND detection_id = :detectionId 
                        AND status != 'canceled'";
            $checkStmt = $this->pdo->prepare($checkSql);
            $checkStmt->bindParam(':userId', $userId);
            $checkStmt->bindParam(':detectionId', $id);
            $checkStmt->execute();
            $estInscrit = $checkStmt->rowCount() > 0;
        }
        
        // Calculer les places restantes
        $placesRestantes = $detection['max_participants'] - $detection['inscriptions_count'];
        
        // Ajouter les informations supplémentaires au tableau
        $detection['places_restantes'] = $placesRestantes;
        $detection['est_complet'] = $placesRestantes <= 0;
        $detection['places_limitees'] = !empty($detection['max_participants']);
        $detection['inscription_ouverte'] = ($detection['status'] === 'planned');
        $detection['est_inscrit'] = $estInscrit;
        
        // Formater la date pour l'affichage si nécessaire
        $detection['date_formatee'] = date('d/m/Y', strtotime($detection['date']));
        
        return $detection;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération de la détection: " . $e->getMessage());
        return null;
    }
}

   // Creation Detection---------------------------------------------------------------------------------------------------------------------------- 
 public function create($nom, $date, $heure, $lieu, $partnerClub, $logo_club, $categorie, $description, $maxParticipants, $status, $date_fin_inscription) {
    $stmt = $this->pdo->prepare(
        "INSERT INTO detections (
            name, date, heure, location, partner_club, logo_club, 
            age_category, description, max_participants, status, date_fin_inscription
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    
    return $stmt->execute([
        $nom, 
        $date, 
        $heure, 
        $lieu, 
        $partnerClub, 
        $logo_club, 
        $categorie, 
        $description, 
        $maxParticipants, 
        $status, 
        $date_fin_inscription
    ]);
}

 // Retoune la detection selon le statut -----------------------------------------------------------------------------------------------------------------   
    
    public function getDetectionsByStatus($status) {
        $stmt = $this->pdo->prepare("SELECT * FROM detections WHERE status = ? ORDER BY date");
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



public function update($id, $data) {
    $stmt = $this->pdo->prepare(
        "UPDATE detections SET 
            name = ?, 
            date = ?, 
            heure = ?, 
            location = ?, 
            partner_club = ?, 
            logo_club = ?, 
            age_category = ?, 
            description = ?, 
            max_participants = ?, 
            status = ?, 
            date_fin_inscription = ?
        WHERE id = ?"
    );
    
    return $stmt->execute([
        $data['name'] ?? '', 
        $data['date'] ?? '', 
        $data['heure'] ?? '', 
        $data['location'] ?? '', 
        $data['partner_club'] ?? '', 
        $data['logo_club'] ?? '', 
        $data['age_category'] ?? '', 
        $data['description'] ?? '', 
        $data['max_participants'] ?? 0, 
        $data['status'] ?? 'planned', 
        $data['date_fin_inscription'] ?? null,
        $id
    ]);
}



    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM detections WHERE id = ?");
        return $stmt->execute([$id]);
    }




 // Récupère l'inscription d'un joueur à une détection
    
    public function getInscription($joueurId, $detectionId) {
        $query = "SELECT * FROM inscription_detections 
                  WHERE joueur_id = ? AND detection_id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$joueurId, $detectionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Compte le nombre de participants inscrits à une détection
     */
    public function countParticipantsByDetection($detectionId) {
        $query = "SELECT COUNT(*) FROM inscription_detections 
                  WHERE detection_id = ? AND status IN ('registered', 'confirmed', 'attended')";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$detectionId]);
        return $stmt->fetchColumn();
    }  
    
    

     /**
     * Récupère la liste des participants inscrits à une détection
     */
    public function getParticipantsByDetection($detectionId) {
        $query = "SELECT i.*, u.nom, u.prenom, u.email, u.telephone, u.date_naissance 
                  FROM inscription_detections i
                  JOIN utilisateurs u ON i.joueur_id = u.id
                  WHERE i.detection_id = ?
                  ORDER BY i.date_inscription DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$detectionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute une inscription à une détection
     */
    public function addInscription($data) {
        $query = "INSERT INTO inscription_detections 
                  (joueur_id, detection_id, status) 
                  VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($query);
        $result = $stmt->execute([
            $data['joueur_id'],
            $data['detection_id'],
            $data['status']
        ]);
        
        return $result ? $this->pdo->lastInsertId() : false;
    }

    /**
     * Met à jour le statut d'une inscription
     */
    public function updateInscriptionStatus($inscriptionId, $status) {
        $query = "UPDATE inscription_detections SET status = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([$status, $inscriptionId]);
    }


/**
 * Récupère les prochaines détections disponibles
 * 
 * @param int $limit Nombre maximum de détections à récupérer
 * @param int|null $userId ID de l'utilisateur pour vérifier ses inscriptions
 * @return array Les détections à venir, avec informations complémentaires
 */
/**
 * Récupère et prépare les détections à venir
 * 
 * @param int $limit Nombre maximum de détections à récupérer
 * @param int|null $userId ID de l'utilisateur pour vérifier ses inscriptions
 * @return array Les détections formatées pour l'affichage
 */
public function getUpcomingDetections($limit = 10, $userId = null) {
    try {
        $currentDate = date('Y-m-d');
        
        $sql = "SELECT d.*, COUNT(i.id) as inscriptions_count 
                FROM detections d 
                LEFT JOIN inscription_detections i ON d.id = i.detection_id AND i.status != 'canceled'
                WHERE d.date >= :currentDate AND d.status = 'planned'
                GROUP BY d.id
                ORDER BY d.date ASC LIMIT :limit";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':currentDate', $currentDate);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $detections = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Préparer les données pour l'affichage
        $result = [];
        foreach ($detections as $detection) {
            // Vérifier si l'utilisateur est inscrit
            $estInscrit = false;
            if ($userId) {
                $checkSql = "SELECT id FROM inscription_detections 
                            WHERE joueur_id = :userId AND detection_id = :detectionId 
                            AND status != 'canceled'";
                $checkStmt = $this->pdo->prepare($checkSql);
                $checkStmt->bindParam(':userId', $userId);
                $checkStmt->bindParam(':detectionId', $detection['id']);
                $checkStmt->execute();
                $estInscrit = $checkStmt->rowCount() > 0;
            }
            
            // Calculer les places restantes
            $placesRestantes = $detection['max_participants'] - $detection['inscriptions_count'];
            
            // Formater la date pour l'affichage
            $dateFormatee = date('d/m/Y', strtotime($detection['date']));
            
            $result[] = [
                'id' => $detection['id'],
                'titre' => $detection['name'],
                'date' => $dateFormatee,
                'date_brute' => $detection['date'],
                'heure'=>$detection['heure'],
                'lieu' => $detection['location'],
                'categorie_age' => $detection['age_category'],
                'max_players' => $detection['max_participants'],
                'places_restantes' => $placesRestantes,
                'est_complet' => $placesRestantes <= 0,
                'places_limitees' => !empty($detection['max_participants']),
                'inscription_ouverte' => ($detection['status'] === 'planned'),
                'est_inscrit' => $estInscrit,
                'club_partenaire' => $detection['partner_club'] ?? '',
                           ];
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des prochaines détections: " . $e->getMessage());
        return [];
    }
}



/**
 * Calcule le nombre de places restantes pour une détection
 * 
 * @param array $detection Les informations de la détection
 * @return int|string Nombre de places restantes ou "Illimité"
 */
private function calculateRemainingPlaces($detection) {
    // Si pas de limite de participants, retourner "Illimité"
    if (empty($detection['max_participants'])) {
        return "Illimité";
    }
    
    try {
        $inscriptionsCount = $this->countRegistrations($detection['id']);
        $placesRestantes = max(0, $detection['max_participants'] - $inscriptionsCount);
        return $placesRestantes;
    } catch (Exception $e) {
        error_log("Erreur lors du calcul des places restantes: " . $e->getMessage());
        return "Erreur";
    }
}




/**
 * Inscrit un joueur à une détection
 * @param int $detectionId ID de la détection
 * @param int $playerId ID du joueur
 * @return array Résultat avec succès et message
 */



/**
 * Inscrit un joueur à une détection
 * @param int $detectionId ID de la détection
 * @param int $playerId ID du joueur
 * @return array Résultat avec succès et message
 */
public function registerPlayer($detectionId, $playerId) {
    $result = [
        'success' => false,
        'message' => ''
    ];

    try {
        // Vérifie si le joueur est déjà inscrit
        $existing = $this->isPlayerRegistered($detectionId, $playerId);
        
        if ($existing) {
            if ($existing['status'] === 'canceled') {
                // Réactiver une ancienne inscription annulée
                $stmt = $this->pdo->prepare(
                    "UPDATE inscription_detections 
                     SET status = 'confirmed', date_inscription = NOW() 
                     WHERE joueur_id = :joueur_id AND detection_id = :detection_id"
                );
                $stmt->bindParam(':joueur_id', $playerId);
                $stmt->bindParam(':detection_id', $detectionId);
                $stmt->execute();

                $result['success'] = true;
                $result['message'] = "Votre inscription a été réactivée avec succès.";
                return $result;
            } else {
                $result['message'] = "Vous êtes déjà inscrit à cette détection.";
                return $result;
            }
        }

        // Vérifie si la détection existe
        $detection = $this->getDetectionById($detectionId);
        if (!$detection) {
            $result['message'] = "Cette détection n'existe pas.";
            return $result;
        }

        // Vérifie si les inscriptions sont encore ouvertes
        $dateFermeture = $detection['date_fermeture_inscription'] ?? null;
        if ($dateFermeture && strtotime($dateFermeture) < time()) {
            $result['message'] = "Les inscriptions pour cette détection sont fermées.";
            return $result;
        }

        // Vérifie les places restantes
        $inscritsCount = $this->countRegistrations($detectionId);
        if ($detection['max_participants'] > 0 && $inscritsCount >= $detection['max_participants']) {
            $result['message'] = "Il n'y a plus de places disponibles pour cette détection.";
            return $result;
        }

        // Insertion de l'inscription
        $stmt = $this->pdo->prepare(
            "INSERT INTO inscription_detections (joueur_id, detection_id, status, date_inscription)
             VALUES (:joueur_id, :detection_id, 'confirmed', NOW())"
        );
        $stmt->bindParam(':joueur_id', $playerId);
        $stmt->bindParam(':detection_id', $detectionId);
        $stmt->execute();

        $result['success'] = true;
        $result['message'] = "Vous êtes inscrit avec succès à la détection.";
        return $result;

    } catch (PDOException $e) {
        error_log("Erreur lors de l'inscription : " . $e->getMessage());
        $result['message'] = "Une erreur est survenue lors de l'inscription.";
        return $result;
    }
}

/**
 * Vérifie si un joueur est déjà inscrit à une détection
 * @param int $detectionId ID de la détection
 * @param int $playerId ID du joueur
 * @return array|false Les infos d'inscription ou false si non inscrit
 */
public function isPlayerRegistered($detectionId, $playerId) {
    $sql = "SELECT status FROM inscription_detections 
            WHERE detection_id = :detectionId AND joueur_id = :playerId";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':detectionId' => $detectionId,
        ':playerId' => $playerId
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC); // false si non inscrit
}





/**
 * Compte le nombre d'inscrits à une détection
 * @param int $detectionId ID de la détection-----------------------------------------------------------------------------------
 * @return int Nombre d'inscrits
 */
public function countRegistrations($detectionId) {
    $sql = "SELECT COUNT(*) FROM inscription_detections
            WHERE detection_id = :detectionId AND status != 'declined'";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':detectionId' => $detectionId]);
    
    return (int)$stmt->fetchColumn();
}

/**
 * Annule l'inscription d'un joueur à une détection
 * @param int $detectionId ID de la détection
 * @param int $playerId ID du joueur
 * @return array Résultat avec succès et message
 */
// Méthode pour annuler une inscription
public function cancelInscription($joueurId, $detectionId) {
    $query = "DELETE FROM inscription_detections WHERE joueur_id = ? AND detection_id = ?";
    $stmt = $this->pdo->prepare($query);
    return $stmt->execute([$joueurId, $detectionId]);
}

private function getUserRegistrations($userId) {
    try {
        $sql = "SELECT d.*, i.date_inscription, i.status AS inscription_status
                FROM inscription_detections i
                JOIN detections d ON i.detection_id = d.id
                WHERE i.joueur_id = :userId
                ORDER BY d.date ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $inscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Préparer et enrichir les données
        $result = [];
        foreach ($inscriptions as $inscription) {
            // Déterminer si c'est une détection passée
            $isPast = strtotime($inscription['date']) < strtotime(date('Y-m-d'));
            
            // Formater la date pour l'affichage
            $dateObj = new DateTime($inscription['date']);
            $formattedDate = $dateObj->format('d/m/Y');
            
            // Formater la date d'inscription
            $inscriptionDateObj = new DateTime($inscription['date_inscription']);
            $formattedInscriptionDate = $inscriptionDateObj->format('d/m/Y H:i');
            
            // Formater les heures
            $heureDebut = isset($inscription['start_time']) ? 
                (new DateTime($inscription['start_time']))->format('H:i') : '';
            $heureFin = isset($inscription['end_time']) ? 
                (new DateTime($inscription['end_time']))->format('H:i') : '';
            
            // Traduction du statut pour l'affichage
            $statusLabel = $this->getStatusLabel($inscription['inscription_status'] ?? 'registered');
            
            // Constitution du résultat
            $result[] = [
                'id' => $inscription['id'],
                'detection_id' => $inscription['id'],
                'name' => $inscription['name'],
                'description' => $inscription['description'] ?? '',
                'date' => $formattedDate,
                'date_brute' => $inscription['date'],
                'est_passe' => $isPast,
                'heure_debut' => $heureDebut,
                'heure_fin' => $heureFin,
                'lieu' => $inscription['location'],
                'club_partenaire' => $inscription['partner_club'] ?? '',
                'categorie_age' => $inscription['age_category'] ?? 'Toutes catégories',
                'date_inscription' => $formattedInscriptionDate,
                'statut' => $statusLabel,
                'statut_code' => $inscription['inscription_status'] ?? 'registered'
            ];
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des inscriptions: " . $e->getMessage());
        return [];
    }
}

/**
 * Retourne le libellé d'un statut d'inscription
 * 
 * @param string $status Code du statut
 * @return string Libellé du statut
 */
public function getStatusLabel($status) {
    $labels = [
        'registered' => 'Inscrit',
        'confirmed' => 'Confirmé',
        'canceled' => 'Annulé',
        'declined' => 'Refusé',
        'attended' => 'Présent',
        'absent' => 'Absent'
    ];
    
    return $labels[$status] ?? 'Inscrit';
}

/**
 * Compte le nombre de détections à venir
 * 
 * @param string $currentDate Date courante au format Y-m-d
 * @return int Nombre de détections à venir
 */
private function countUpcomingDetections($currentDate) {
    try {
        $sql = "SELECT COUNT(*) FROM detections 
                WHERE date >= :currentDate 
                AND (status = 'planned' OR status = 'open')";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':currentDate', $currentDate);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Erreur lors du comptage des détections à venir: " . $e->getMessage());
        return 0;
    }
}

/**
 * Compte le nombre de détections passées auxquelles l'utilisateur a participé
 * 
 * @param int $userId ID de l'utilisateur
 * @param string $currentDate Date courante au format Y-m-d
 * @return int Nombre de détections passées
 */
private function countPastDetections($userId, $currentDate) {
    try {
        $sql = "SELECT COUNT(*) FROM inscription_detections i
                JOIN detections d ON i.detection_id = d.id
                WHERE i.joueur_id = :userId
                AND d.date < :currentDate";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':currentDate', $currentDate);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Erreur lors du comptage des détections passées: " . $e->getMessage());
        return 0;
    }
}


/**
 * Récupère les participants d'une détection
 * 
 * @param int $detectionId ID de la détection
 * @return array Liste des participants
 */
public function getParticipantsForDetection($detectionId) {
    try {
        $sql = "SELECT j.photo,j.id, j.nom, j.prenom, j.email, i.status AS inscription_status, i.date_inscription

                FROM inscription_detections i
                JOIN utilisateurs j ON i.joueur_id = j.id
                WHERE i.detection_id = :detectionId AND i.status != 'canceled'
                ORDER BY i.date_inscription ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':detectionId', $detectionId);
        $stmt->execute();
        
        $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ajouter des informations supplémentaires sur chaque participant si nécessaire
        $result = [];
        foreach ($participants as $participant) {
            $result[] = [
                'id' => $participant['id'],
                'photo'=>$participant['photo'],
                'nom' => $participant['nom'],
                'prenom' => $participant['prenom'],
                'email' => $participant['email'],
                'status' => $this->getStatusLabel($participant['inscription_status']),
                'date_inscription' => $participant['date_inscription']
            ];
            
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des participants : " . $e->getMessage());
        return [];
    }
}





}
