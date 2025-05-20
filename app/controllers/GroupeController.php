<?php

namespace App\controllers;

use App\models\Tournoi;
use App\models\Matchs;
use App\models\Equipe;
use App\models\Groupe;

use PDO;

class GroupeController {
    private $model;
    private $equipeModel;
    private $tournoiModel;
    private $matchModel;
    private $pdo;
    public function __construct($pdo) {
        $this->model = new Groupe($pdo);
        $this->equipeModel = new Equipe($pdo);
        $this->tournoiModel = new Tournoi($pdo);
        $this->matchModel = new Matchs($pdo);
    }
    
    /**
     * Créer un nouveau groupe
     */
    public function create($tournoiId = null) {
        
        if (!$tournoiId) {
            $tournoiId = $_GET['tournoi_id'] ?? null;
        }
        
        if (!$tournoiId) {
            $_SESSION['flash_message'] = "ID de tournoi requis";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php?module=tournoi&action=index');
            exit();
        }
        
        $tournoi = $this->tournoiModel->getById($tournoiId);
        
        if (!$tournoi) {
            $_SESSION['flash_message'] = "Tournoi non trouvé";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php?module=tournoi&action=index');
            exit();
        }
        
        // Récupérer les équipes du tournoi qui ne sont pas déjà dans un groupe
        $equipesTournoi = $this->equipeModel->getByTournoi($tournoiId);
        $groupes = $this->model->getByTournoi($tournoiId);
        
        $equipesInGroups = [];
        foreach ($groupes as $groupe) {
            $equipesGroupe = $this->model->getEquipes($groupe['id']);
            foreach ($equipesGroupe as $equipe) {
                $equipesInGroups[] = $equipe['id'];
            }
        }
        
        $equipes = array_filter($equipesTournoi, function($equipe) use ($equipesInGroups) {
            return !in_array($equipe['id'], $equipesInGroups);
        });
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $description = $_POST['description'] ?? '';
            $equipesIds = $_POST['equipes'] ?? [];
            
            if (!$nom) {
                $_SESSION['flash_message'] = "Le nom du groupe est obligatoire";
                $_SESSION['flash_type'] = "warning";
            } else {
                // Création du groupe
                if ($this->model->create($tournoiId, $nom, $description)) {
                  $groupeId = $this->model->getLastInsertId();

                    
                    // Ajouter les équipes au groupe
                    foreach ($equipesIds as $equipeId) {
                        $this->model->addEquipe($groupeId, $equipeId);
                    }
                    
                    $_SESSION['flash_message'] = "Groupe créé avec succès";
                    $_SESSION['flash_type'] = "success";
                    header('Location: index.php?module=tournoi&action=organiser&id=' . $tournoiId);
                    exit();
                } else {
                    $_SESSION['flash_message'] = "Erreur lors de la création du groupe";
                    $_SESSION['flash_type'] = "danger";
                }
            }
        }
        
        // Afficher le formulaire de création
        require_once __DIR__ . '/../views/Groupe/create.php';
    }
    
    /**
     * Modifier un groupe
     */
    public function edit($id) {
        // Vérifier les droits d'admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash_message'] = "Vous n'avez pas les droits pour effectuer cette action";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php');
            exit();
        }
        
        if (!$id) {
            $_SESSION['flash_message'] = "ID de groupe manquant";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php?module=tournoi&action=index');
            exit();
        }
        
        $groupe = $this->model->getById($id);
        
        if (!$groupe) {
            $_SESSION['flash_message'] = "Groupe non trouvé";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php?module=tournoi&action=index');
            exit();
        }
        
        $tournoi = $this->tournoiModel->getById($groupe['tournoi_id']);
        $equipesGroupe = $this->model->getEquipes($id);
        
        // Récupérer toutes les équipes du tournoi
        $equipesTournoi = $this->equipeModel->getByTournoi($tournoi['id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $description = $_POST['description'] ?? '';
            $equipesIds = $_POST['equipes'] ?? [];
            
            if (!$nom) {
                $_SESSION['flash_message'] = "Le nom du groupe est obligatoire";
                $_SESSION['flash_type'] = "warning";
            } else {
                // Mise à jour du groupe
                if ($this->model->update($id, $nom, $description)) {
                    // Récupérer les équipes existantes
                    $equipeIds = array_column($equipesGroupe, 'id');
                    
                    // Ajouter les nouvelles équipes
                    foreach ($equipesIds as $equipeId) {
                        if (!in_array($equipeId, $equipeIds)) {
                            $this->model->addEquipe($id, $equipeId);
                        }
                    }
                    
                    // Supprimer les équipes qui ne sont plus dans le groupe
                    foreach ($equipeIds as $equipeId) {
                        if (!in_array($equipeId, $equipesIds)) {
                            $this->model->removeEquipe($id, $equipeId);
                        }
                    }
                    
                    $_SESSION['flash_message'] = "Groupe mis à jour avec succès";
                    $_SESSION['flash_type'] = "success";
                    header('Location: index.php?module=tournoi&action=organiser&id=' . $tournoi['id']);
                    exit();
                } else {
                    $_SESSION['flash_message'] = "Erreur lors de la mise à jour du groupe";
                    $_SESSION['flash_type'] = "danger";
                }
            }
        }
        
        // Récupérer les matchs du groupe
        $matchs = $this->matchModel->getByGroupe($id);
        
        // Afficher le formulaire d'édition
        require_once __DIR__ . '/../views/Groupe/edit.php';
    }
    
    /**
     * Supprimer un groupe
     */
    public function delete($id) {
        // Vérifier les droits d'admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash_message'] = "Vous n'avez pas les droits pour effectuer cette action";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php');
            exit();
        }
        
        if (!$id) {
            $_SESSION['flash_message'] = "ID de groupe manquant";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php?module=tournoi&action=index');
            exit();
        }
        
        $groupe = $this->model->getById($id);
        
        if (!$groupe) {
            $_SESSION['flash_message'] = "Groupe non trouvé";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php?module=tournoi&action=index');
            exit();
        }
        
        $tournoiId = $groupe['tournoi_id'];
        
        if ($this->model->delete($id)) {
            $_SESSION['flash_message'] = "Groupe supprimé avec succès";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Erreur lors de la suppression du groupe";
            $_SESSION['flash_type'] = "danger";
        }
        
        header('Location: index.php?module=tournoi&action=organiser&id=' . $tournoiId);
        exit();
    }
    
    /**
     * Afficher les détails d'un groupe
     */
    public function show($id) {
        if (!$id) {
            $_SESSION['flash_message'] = "ID de groupe manquant";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php?module=tournoi&action=index');
            exit();
        }
        
        $groupe = $this->model->getById($id);
        
        if (!$groupe) {
            $_SESSION['flash_message'] = "Groupe non trouvé";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php?module=tournoi&action=index');
            exit();
        }
        
        $tournoi = $this->tournoiModel->getById($groupe['tournoi_id']);
        $equipes = $this->model->getEquipes($id);
        $matchs = $this->matchModel->getByGroupe($id);
        
        // Recalculer les statistiques du groupe
        $this->model->calculateGroupStats($id);
        
        // Récupérer les équipes avec les statistiques à jour
        $equipes = $this->model->getEquipes($id);
        
        require_once __DIR__ . '/../views/Groupe/show.php';
    }
    
    /**
     * Générer les matchs pour un groupe
     */
    public function generateMatches($id) {
        // Vérifier les droits d'admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash_message'] = "Vous n'avez pas les droits pour effectuer cette action";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php');
            exit();
        }
        
        if (!$id) {
            $_SESSION['flash_message'] = "ID de groupe manquant";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php?module=tournoi&action=index');
            exit();
        }
        
        $groupe = $this->model->getById($id);
        
        if (!$groupe) {
            $_SESSION['flash_message'] = "Groupe non trouvé";
            $_SESSION['flash_type'] = "danger";
            header('Location: index.php?module=tournoi&action=index');
            exit();
        }
        
        $tournoi = $this->tournoiModel->getById($groupe['tournoi_id']);
        $equipes = $this->model->getEquipes($id);
        
        if (count($equipes) < 2) {
            $_SESSION['flash_message'] = "Il faut au moins 2 équipes dans le groupe pour générer des matchs";
            $_SESSION['flash_type'] = "warning";
            header('Location: index.php?module=groupe&action=show&id=' . $id);
            exit();
        }
        
        if ($this->matchModel->generateGroupMatches($tournoi['id'], $id)) {
            $_SESSION['flash_message'] = "Les matchs ont été générés avec succès pour le groupe";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Erreur lors de la génération des matchs pour le groupe";
            $_SESSION['flash_type'] = "danger";
        }
        
        header('Location: index.php?module=groupe&action=show&id=' . $id);
        exit();
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
                $equipe['buts_pour'] += $match['score_equipe1'];
                $equipe['buts_contre'] += $match['score_equipe2'];
                
                if ($match['score_equipe1'] > $match['score_equipe2']) {
                    $equipe['gagnes']++;
                    $equipe['points'] += 3;
                } elseif ($match['score_equipe1'] == $match['score_equipe2']) {
                    $equipe['nuls']++;
                    $equipe['points'] += 1;
                } else {
                    $equipe['perdus']++;
                }
                
            } else {
                // L'équipe joue à l'extérieur
                $equipe['buts_pour'] += $match['score_equipe2'];
                $equipe['buts_contre'] += $match['score_equipe1'];
                
                if ($match['score_equipe2'] > $match['score_equipe1']) {
                    $equipe['gagnes']++;
                    $equipe['points'] += 3;
                } elseif ($match['score_equipe1'] == $match['score_equipe2']) {
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




public function getQualifiedTeams($tournoiId, $teamsPerGroup = 2) {
    $groupes = $this->model->getByTournoi($tournoiId);
    $qualifiedTeams = [];
    
    foreach ($groupes as $groupe) {
        $standings = $this->getGroupStandings($groupe['id']);
        // Prendre les N premières équipes de chaque groupe
        for ($i = 0; $i < $teamsPerGroup && $i < count($standings); $i++) {
            $qualifiedTeams[] = [
                'equipe_id' => $standings[$i]['equipe_id'],
                'nom' => $standings[$i]['nom'],
                'groupe_id' => $groupe['id'],
                'groupe_nom' => $groupe['nom'],
                'position' => $i + 1  // 1er ou 2ème du groupe
            ];
        }
    }
    
    return $qualifiedTeams;
}

}
