<?php
namespace App\models;
use PDO; 

class Detection {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getAll() {
        return $this->pdo->query("SELECT * FROM detections")->fetchAll();
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




    public function update($id, $nom, $date, $lieu, $partnerClub, $categorie, $maxParticipants, $status) {
        $stmt = $this->pdo->prepare(
            "UPDATE detections 
             SET name = ?, date = ?, location = ?, partner_club = ?, age_category = ?, max_participants = ?, status = ? 
             WHERE id = ?"
        );
        return $stmt->execute([$nom, $date, $lieu, $partnerClub, $categorie, $maxParticipants, $status, $id]);
    }
    


    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM detections WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
}
