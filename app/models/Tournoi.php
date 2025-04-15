<?php
namespace App\models;
use PDO;

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
}