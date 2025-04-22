<?php
namespace App\models;

class Utilisateur {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Méthode d'inscription
    public function inscription($data) {
        // Hashage du mot de passe
        $mot_de_passe_hash = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        
        $query = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, date_naissance, 
                 telephone, poste, niveau_jeu, taille, poids, nationalite, justificatif, statut) 
                 VALUES (:nom, :prenom, :email, :mot_de_passe, :date_naissance, 
                 :telephone, :poste, :niveau_jeu, :taille, :poids, :nationalite, :justificatif, 'en_attente')";
        
        $stmt = $this->pdo->prepare($query);
        $result = $stmt->execute([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'mot_de_passe' => $mot_de_passe_hash,
            'date_naissance' => $data['date_naissance'],
            'telephone' => $data['telephone'],
            'poste' => $data['poste'],
            'niveau_jeu' => $data['niveau_jeu'],
            'taille' => $data['taille'],
            'poids' => $data['poids'],
            'nationalite' => $data['nationalite'],
            'justificatif' => $data['justificatif']
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
    
    // Approuver un joueur
    public function approuverJoueur($id) {
        $query = "UPDATE utilisateurs SET statut = 'approuve' WHERE id = :id AND role = 'joueur'";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
    
    // Refuser un joueur
    public function refuserJoueur($id) {
        $query = "UPDATE utilisateurs SET statut = 'refuse' WHERE id = :id AND role = 'joueur'";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(['id' => $id]);
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
public function countByStatus($status) {
    $sql = "SELECT COUNT(*) FROM utilisateurs WHERE statut = :status AND role != 'admin'";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['status' => $status]);
    return $stmt->fetchColumn();
}

// Méthode pour récupérer les utilisateurs selon leur statut
public function getByStatus($status) {
    $sql = "SELECT id, nom, prenom, email, date_inscription as date_creation FROM utilisateurs 
            WHERE statut = :status AND role != 'admin'";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['status' => $status]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

// Méthode pour mettre à jour le statut d'un utilisateur
public function updateStatus($id, $status) {
    $sql = "UPDATE utilisateurs SET statut = :status WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        'status' => $status,
        'id' => $id
    ]);
}

// Méthode pour récupérer les détails d'un utilisateur par son ID
public function getById($id) {
    $sql = "SELECT * FROM utilisateurs WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

// Méthode pour obtenir des statistiques sur les utilisateurs
public function getStatistics() {
    $stats = [];
    
    // Nombre de joueurs par statut
    $statuses = ['en_attente', 'approuve', 'refuse'];
    foreach ($statuses as $status) {
        $stats[$status] = $this->countByStatus($status);
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
}
