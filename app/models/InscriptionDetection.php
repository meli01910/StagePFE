<?php
namespace App\models;

class InscriptionDetection {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($joueur_id, $detection_id) {
        $stmt = $this->pdo->prepare("INSERT INTO inscription_detections (joueur_id, detection_id) VALUES (?, ?)");
        return $stmt->execute([$joueur_id, $detection_id]);
    }

    public function getByJoueurId($joueur_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM inscription_detections WHERE joueur_id = ?");
        $stmt->execute([$joueur_id]);
        return $stmt->fetchAll();
    }

    public function getByDetectionId($detection_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM inscription_detections WHERE detection_id = ?");
        $stmt->execute([$detection_id]);
        return $stmt->fetchAll();
    }

    public function delete($joueur_id, $detection_id) {
        $stmt = $this->pdo->prepare("DELETE FROM inscription_detections WHERE joueur_id = ? AND detection_id = ?");
        return $stmt->execute([$joueur_id, $detection_id]);
    }

    public function exists($joueur_id, $detection_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM inscription_detections WHERE joueur_id = ? AND detection_id = ?");
        $stmt->execute([$joueur_id, $detection_id]);
        return $stmt->fetchColumn() > 0;
    }
    public function countParticipants($detection_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM inscription_detections WHERE detection_id = ?");
        $stmt->execute([$detection_id]);
        return $stmt->fetchColumn();
    }
}
