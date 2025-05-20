<?php
namespace App\models;
use App\models\PDOException;


use PDO;
class Utilisateur {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function update($id, $data) {
        if (!isset($id) || !is_numeric($id)) {
            return false;
        }
    
        $fields = [];
        $params = [];
    
        $allowedFields = [
            'nom', 'prenom', 'email', 'telephone', 'poste', 
            'niveau_jeu', 'taille', 'poids', 'nationalite', 
            'statut', 'date_naissance', 'mot_de_passe',
        ];
    
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $fields[] = "$field = :$field";
                $params[$field] = $value;
            }
        }
    
        if (empty($fields)) {
            return false;
        }
    
        $sql = "UPDATE utilisateurs SET " . implode(', ', $fields) . " WHERE id = :id AND role = 'joueur'";
        $params['id'] = $id;
    
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour du joueur ID $id: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        return $stmt->execute([$id]);
    }






 /**
     * Récupère le chemin du justificatif d'un utilisateur
     * 
     * @param int $id ID de l'utilisateur
     * @return array|false Informations sur le justificatif ou false si non trouvé
     */
    public function getJustificatif($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT justificatif FROM utilisateurs WHERE id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$result || empty($result['justificatif'])) {
                return false;
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération du justificatif: " . $e->getMessage());
            return false;
        }
    }

    public function updatePlayerStatus($id, $statut) {
        if (!in_array($statut, ['approuve', 'refuse'])) {
            return false;
        }
        
        $sql = "UPDATE utilisateurs SET statut = :statut WHERE id = :id AND role = 'joueur'";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->bindParam(':statut', $statut, \PDO::PARAM_STR);
            return $stmt->execute();
        } catch (\PDOException $e) {
            error_log('Erreur lors de la mise à jour du statut: ' . $e->getMessage());
            return false;
        }
    }
    

    public function getPlayerById($id) {
     $sql = "SELECT *, TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) AS age 
            FROM utilisateurs 
            WHERE id = :id";
     $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
    $stmt->execute();
    
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
    return $result ? $result : false;
}

    // Méthode d'inscription
    public function inscription($data) {
        // Hashage du mot de passe
        $mot_de_passe_hash = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        
        $query = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, date_naissance, 
                 telephone, poste, taille, poids, nationalite, justificatif,photo,statut) 
                 VALUES (:nom, :prenom, :email, :mot_de_passe, :date_naissance, 
                 :telephone, :poste, :taille, :poids, :nationalite, :justificatif,:photo, 'en_attente')";
        
        $stmt = $this->pdo->prepare($query);
        $result = $stmt->execute([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'mot_de_passe' => $mot_de_passe_hash,
            'date_naissance' => $data['date_naissance'],
            'telephone' => $data['telephone'],
            'poste' => $data['poste'],
            'taille' => $data['taille'],
            'poids' => $data['poids'],
            'nationalite' => $data['nationalite'],
            'justificatif' => $data['justificatif'],
            'photo'=>$data['photo']
        ]);
        
        return $result ? $this->pdo->lastInsertId() : false;
    }
    
    // Vérifier les identifiants et le statut pour la connexion
    public function connexion($email, $mot_de_passe) {
        try {
            $query = "SELECT * FROM utilisateurs WHERE email = :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            // Vérifier si l'utilisateur existe et si le mot de passe correspond
            if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
                // Si c'est un admin OU si le compte est approuvé, permettre la connexion
                if ($user['role'] === 'admin' || $user['statut'] === 'approuve') {
                    // Retirer le mot de passe de l'objet utilisateur
                    unset($user['mot_de_passe']);
                    return [
                        'success' => true,
                        'user' => $user
                    ];
                } else {
                    // Compte non approuvé
                    return [
                        'success' => false,
                        'message' => 'Votre compte est en attente de validation par un administrateur.'
                    ];
                }
            }
            
            // Identifiants incorrects
            return [
                'success' => false,
                'message' => 'Email ou mot de passe incorrect.'
            ];
        } catch (\PDOException $e) {
            // Erreur de base de données
            return [
                'success' => false,
                'message' => 'Erreur de connexion à la base de données: ' . $e->getMessage()
            ];
        }
    }


    public function getByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $user ?: null;
    }
   
    // Liste des joueurs par statut
    public function getJoueursByStatut($statut) {
        $query = "SELECT id, nom, prenom, email, date_naissance, telephone, poste, 
                 niveau_jeu, taille, poids, nationalite, justificatif, date_inscription 
                 FROM utilisateurs 
                 WHERE role = 'joueur' AND statut = :statut 
                 ORDER BY date_inscription DESC";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['statut' => $statut]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    

    // Vérifier si un email existe déjà
    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM utilisateurs WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
    
    // Vérifier qu'un utilisateur est bien le propriétaire d'un justificatif
    public function verifierProprieteJustificatif($user_id, $fichier) {
        $query = "SELECT COUNT(*) FROM utilisateurs WHERE id = :id AND justificatif = :fichier";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'id' => $user_id,
            'fichier' => $fichier
        ]);
        return $stmt->fetchColumn() > 0;
    }
    // Méthode pour compter les joueurs selon leur statut
public function countByStatus($statut) {
    $sql = "SELECT COUNT(*) FROM utilisateurs WHERE statut = :statut AND role != 'admin'";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['statut' => $statut]);
    return $stmt->fetchColumn();
}

// Méthode pour récupérer les utilisateurs selon leur statut
public function getByStatus($statut) {
    $sql = "SELECT id, nom, prenom, email, date_naissance, telephone, poste, niveau_jeu, 
            taille, poids, nationalite, justificatif, statut, date_creation 
            FROM utilisateurs 
            WHERE statut = :statut AND role != 'admin'";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['statut' => $statut]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}


// Méthode pour mettre à jour le statut d'un utilisateur
public function updateStatus($id, $statut) {
    $sql = "UPDATE utilisateurs SET statut = :status WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        'status' => $statut,
        'id' => $id
    ]);
}

// Méthode pour récupérer les détails d'un utilisateur par son ID
public function getById($id) {
    // Ajout du calcul d'âge avec TIMESTAMPDIFF directement dans la requête SQL
    $sql = "SELECT *, TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) AS age 
            FROM utilisateurs 
            WHERE id = :id";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

// Méthode pour obtenir des statistiques sur les utilisateurs
public function getStatistics() {
    $stats = [];
    
    // Nombre de joueurs par statut
    $statuses = ['en_attente', 'approuve', 'refuse'];
    foreach ($statuses as $statut) {
        $stats[$statut] = $this->countByStatus($statut);
    }
    
    // Total des utilisateurs (hors admin)
    $sql = "SELECT COUNT(*) FROM utilisateurs WHERE role != 'admin'";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    $stats['total'] = $stmt->fetchColumn();
    
    // Joueurs les plus récents
    $sql = "SELECT id, nom, prenom, date_inscription FROM utilisateurs 
            WHERE role != 'admin' 
            ORDER BY date_inscription DESC LIMIT 5";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    $stats['recent_users'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    return $stats;
}


/**
 * Récupère tous les joueurs (utilisateurs avec role='joueur')
 * @return array La liste des joueurs
 */
public function getAllPlayers() {
    $query = "SELECT id, nom, prenom, email, telephone, poste as poste, 
    niveau_jeu, taille, poids, nationalite, statut,
    date_naissance, date_creation, justificatif,photo,TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) AS age
    FROM utilisateurs 
    WHERE role = 'joueur' 
    ORDER BY nom ASC";
    
    $stmt = $this->pdo->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}



public function getJoueursByEquipe($equipeId) {
    $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE equipe_id = :equipe_id");
    $stmt->execute(['equipe_id' => $equipeId]);
    return $stmt->fetchAll();
}






}
