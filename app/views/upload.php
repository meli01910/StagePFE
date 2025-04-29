<?php
// upload.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/logos/";
        $fileName = basename($_FILES['logo']['name']);
        $targetFilePath = $targetDir . uniqid() . '-' . $fileName; // Renommez le fichier pour éviter les collisions

        // Vérifiez le type de fichier
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'png', 'jpeg', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            // Déplacez le fichier téléchargé
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFilePath)) {
                // Connexion à la base de données
                $pdo = new PDO('mysql:host=localhost;dbname=nom_de_la_base_de_donnees', 'utilisateur', 'motdepasse');

                // Insérer le chemin du logo dans la base de données
                $sql = "UPDATE equipes SET logo = :logo WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['logo' => $targetFilePath, 'id' => $_POST['equipe_id']]);

                echo "Logo téléchargé avec succès!";
            } else {
                echo "Une erreur s'est produite lors du déplacement du fichier.";
            }
        } else {
            echo "Format de fichier non autorisé.";
        }
    } else {
        echo "Erreur lors du téléchargement : " . $_FILES['logo']['error'];
    }
} else {
    echo "Méthode de requête invalide.";
}
?>
