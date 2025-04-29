<?php
// Assurez-vous que $pageTitle et $currentPage sont définis
if (!isset($pageTitle)) $pageTitle = "Administration";
if (!isset($currentPage)) $currentPage = "";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - Football Arena</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar - largeur fixe -->
            <div class="col-auto p-0 min-vh-100 border-end">
                <?php   require_once __DIR__ .'/../templates/sidebar.php'; ?>
            </div>
            
            <!-- Contenu principal - s'adapte au reste de l'espace -->
            <div class="col py-3">
                <?php 
                // Afficher les messages de session s'ils existent
                if(isset($_SESSION['message'])) {
                    $messageType = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info';
                    echo '<div class="alert alert-'.$messageType.' alert-dismissible fade show" role="alert">';
                    echo $_SESSION['message'];
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                    echo '</div>';
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                }
                ?>
                
                <!-- Le contenu de la page sera inséré ici -->
                <?php echo $content ?? '<div class="alert alert-warning">Aucun contenu à afficher</div>'; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
