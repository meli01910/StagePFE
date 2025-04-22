<?php
namespace App\controllers;

class JustificatifController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function afficher($nom_fichier) {
        // Vérifier que l'utilisateur est connecté
        session_start();
        if (!isset($_SESSION['user'])) {
            header('HTTP/1.0 403 Forbidden');
            exit('Accès non autorisé');
        }
        
        // Un administrateur peut voir tous les justificatifs
        $est_admin = $_SESSION['user']['role'] === 'admin';
        
        if (!$est_admin) {
            // Vérifier que le joueur demande son propre justificatif
            $stmt = $this->pdo->prepare("SELECT id FROM utilisateurs WHERE id = ? AND justificatif = ?");
            $stmt->execute([$_SESSION['user']['id'], $nom_fichier]);
            
            if ($stmt->rowCount() === 0) {
                header('HTTP/1.0 403 Forbidden');
                exit('Vous n\'êtes pas autorisé à accéder à ce fichier');
            }
        }
        
        // Chemin complet du fichier sécurisé
        $chemin = '/var/www/secure_storage/justificatifs/' . $nom_fichier;
        
        // Vérifier que le fichier existe
        if (!file_exists($chemin)) {
            header('HTTP/1.0 404 Not Found');
            exit('Fichier non trouvé');
        }
        
        // Déterminer le type MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $chemin);
        finfo_close($finfo);
        
        // Envoyer les en-têtes appropriés
        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . basename($nom_fichier) . '"');
        header('Content-Length: ' . filesize($chemin));
        
        // Désactiver la mise en cache
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        
        // Lire le fichier et l'envoyer au client
        readfile($chemin);
        exit;
    }
}
