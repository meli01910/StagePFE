<?php
// Inclure la configuration de la base de données
require __DIR__ . '/config/db.php';

// Données de l'administrateur
$admin_data = [
    'nom' => 'Admin',
    'prenom' => 'System',
    'email' => 'admin@exemple.com', // Changez pour votre email
    'mot_de_passe' => 'MotDePasseTresSécurisé123!', // Changez pour un mot de passe fort
    'date_naissance' => '1990-01-01', // Date par défaut
    'telephone' => '0123456789', // Téléphone par défaut
    'poste' => 'Gardien', // Poste par défaut
    'niveau_jeu' => 'Amateur', // Niveau de jeu par défaut
    'taille' => 170, // Taille par défaut
    'poids' => 70, // Poids par défaut
    'nationalite' => 'Française', // Nationalité par défaut
    'role' => 'admin',
    'statut' => 'approuve'
];

// Vérifier si l'administrateur existe déjà
$query = "SELECT COUNT(*) FROM utilisateurs WHERE email = :email";
$stmt = $pdo->prepare($query);
$stmt->execute(['email' => $admin_data['email']]);
$admin_exists = $stmt->fetchColumn() > 0;

if ($admin_exists) {
    echo "Un administrateur avec cette adresse email existe déjà.<br>";
} else {
    // Hashage du mot de passe
    $mot_de_passe_hash = password_hash($admin_data['mot_de_passe'], PASSWORD_DEFAULT);
    
    // Préparer la requête d'insertion avec tous les champs requis
    $query = "INSERT INTO utilisateurs (
                nom, 
                prenom, 
                email, 
                mot_de_passe, 
                date_naissance,
                telephone,
                poste,
                niveau_jeu,
                taille,
                poids,
                nationalite,
                role, 
                statut
              ) VALUES (
                :nom, 
                :prenom, 
                :email, 
                :mot_de_passe, 
                :date_naissance,
                :telephone,
                :poste,
                :niveau_jeu,
                :taille,
                :poids,
                :nationalite,
                :role, 
                :statut
              )";
    
    $stmt = $pdo->prepare($query);
    $result = $stmt->execute([
        'nom' => $admin_data['nom'],
        'prenom' => $admin_data['prenom'],
        'email' => $admin_data['email'],
        'mot_de_passe' => $mot_de_passe_hash,
        'date_naissance' => $admin_data['date_naissance'],
        'telephone' => $admin_data['telephone'],
        'poste' => $admin_data['poste'],
        'niveau_jeu' => $admin_data['niveau_jeu'],
        'taille' => $admin_data['taille'],
        'poids' => $admin_data['poids'],
        'nationalite' => $admin_data['nationalite'],
        'role' => $admin_data['role'],
        'statut' => $admin_data['statut']
    ]);
    
    if ($result) {
        echo "Administrateur créé avec succès!<br>";
        echo "Email: {$admin_data['email']}<br>";
        echo "Mot de passe: {$admin_data['mot_de_passe']}<br>";
        echo "<strong>IMPORTANT: Notez ces informations et supprimez ce fichier immédiatement après utilisation.</strong>";
    } else {
        echo "Erreur lors de la création de l'administrateur.";
        echo "<pre>" . print_r($stmt->errorInfo(), true) . "</pre>";
    }
}
