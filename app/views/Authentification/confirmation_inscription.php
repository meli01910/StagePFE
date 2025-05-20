<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Académie de Football - <?= $pageTitle ?? 'Accueil' ?></title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet" />

  <!-- CSS personnalisé -->
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

  <div class="container">
    <div class="card">
      <div class="card-header">
        <h2>Inscription réussie !</h2>
      </div>
      <div class="card-body">
        <div class="icon-check">
          <i class="fas fa-check-circle"></i>
        </div>

        <?php if (isset($_SESSION['inscription_data'])): ?>
          <h3 class="thanks">Merci, <?= htmlspecialchars($_SESSION['inscription_data']['prenom']) ?> !</h3>
          <div class="alert info">
            <p><strong>Votre inscription a bien été prise en compte.</strong></p>
            <p>Votre compte est en attente de validation par un administrateur. Vous recevrez un email à l'adresse <strong><?= htmlspecialchars($_SESSION['inscription_data']['email']) ?></strong> lorsque votre compte sera validé.</p>
          </div>
        <?php else: ?>
          <h3 class="thanks">Merci pour votre inscription !</h3>
          <div class="alert info">
            <p><strong>Votre inscription a bien été prise en compte.</strong></p>
            <p>Votre compte est en attente de validation par un administrateur.</p>
          </div>
        <?php endif; ?>

        <div class="alert warning">
          <p><strong>Important :</strong> Pour compléter votre profil et participer à nos événements, vous devrez attendre la validation de votre compte.</p>
        </div>

        <div class="actions">
          <a href="index.php" class="btn">Retour à l'accueil</a>
          <a href="index.php?module=auth&action=login" class="btn btn-outline">Se connecter</a>
        </div>
      </div>
    </div>
  </div>

  <?php
  if (isset($_SESSION['inscription_reussie'])) {
      unset($_SESSION['inscription_reussie']);
  }
  ?>
</body>
</html>
<style>
    /* Reset simple */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Corps de la page */
body {
  font-family: 'Montserrat', sans-serif;
  background-color: #f5f8fc;
  display: flex;
  justify-content: center;
  padding: 2rem;
  min-height: 100vh;
}

/* Container principal */
.container {
  max-width: 600px;
  width: 100%;
}

/* Carte */
.card {
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

/* En-tête */
.card-header {
  background-color: #28a745;
  color: white;
  padding: 1.5rem;
  text-align: center;
}

/* Corps */
.card-body {
  padding: 2rem;
}

/* Icône de succès */
.icon-check {
  text-align: center;
  margin-bottom: 1rem;
}

.icon-check i {
  font-size: 4rem;
  color: #28a745;
}

/* Merci */
.thanks {
  text-align: center;
  margin-bottom: 1.5rem;
  font-size: 1.3rem;
}

/* Alertes */
.alert {
  padding: 1rem;
  border-radius: 6px;
  margin-bottom: 1rem;
}

.alert.info {
  background-color: #e6f0ff;
  border-left: 5px solid #007bff;
}

.alert.warning {
  background-color: #fff8e5;
  border-left: 5px solid #ffc107;
}

/* Actions boutons */
.actions {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-top: 1.5rem;
}

/* Boutons */
.btn {
  padding: 0.7rem 1.2rem;
  border: none;
  border-radius: 6px;
  background-color: #007bff;
  color: white;
  text-decoration: none;
  font-weight: 500;
  transition: background-color 0.3s;
}

.btn:hover {
  background-color: #0056b3;
}

.btn-outline {
  background-color: white;
  color: #007bff;
  border: 2px solid #007bff;
}

.btn-outline:hover {
  background-color: #e6f0ff;
}

</style>