



   <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Académie de Football - <?= $pageTitle ?? 'Accueil' ?></title>
      <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- CSS personnalisé -->
      <!-- Votre CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>

<!-- Ajout du CSS pour intl-tel-input -->
<!-- À placer dans la section head -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">


<div class="auth-container">
    <div class="auth-box">
        <div class="auth-header">
            <img src="assets/images/logo_academie.png" alt="Logo Académie de Football" class="auth-logo">
            <h1>Académie de Football</h1>
        </div>
        
        <div class="auth-tabs">
            <button class="tab-btn active" data-target="login-form">Connexion</button>
            <button class="tab-btn" data-target="register-form">Inscription</button>
        </div>
        
        <!-- Formulaire de connexion -->
        <div class="auth-form active" id="login-form">
            <form action="index.php?module=auth&action=login" method="POST">
                <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" class="form-control" required>
        </div>
                
                <div class="form-footer">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Se souvenir de moi</label>
                    </div>
                    <a href="index.php?module=auth&action=forgotPassword" class="forgot-password">Mot de passe oublié?</a>
                </div>
                
                <button type="submit" class="auth-btn">Se connecter</button>
            </form>
        </div>
        
        <!-- Formulaire d'inscription -->
 <div class="auth-form" id="register-form">
    <form action="index.php?module=auth&action=register" method="post" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group">
                <label for="register-firstname">Prénom</label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="register-firstname" name="prenom" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="register-lastname">Nom</label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="register-lastname" name="nom" required>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="register-email">Email</label>
            <div class="input-icon">
                <i class="fas fa-envelope"></i>
                <input type="email" id="register-email" name="email" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="register-password">Mot de passe</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="register-password" name="mot_de_passe" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="register-confirm">Confirmer</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="register-confirm" name="confirm_mot_de_passe" required>
                </div>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="register-birthdate">Date de naissance</label>
                <div class="input-icon">
                    <i class="fas fa-calendar"></i>
                    <input type="date" id="register-birthdate" name="date_naissance" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="register-phone">Téléphone</label>
                <div class="input-icon">
                    <i class="fas fa-phone"></i>
                       <input type="tel" id="register-phone" name="telephone" required>
  
    <!-- Champ caché pour stocker le numéro complet avec l'indicatif -->
    <input type="hidden" name="full_telephone" id="full_telephone">
</div>       </div>
            </div>
    
        
        <div class="form-row">
            <div class="form-group">
                <label for="register-position">Poste</label>
                <div class="input-icon">
                    <i class="fas fa-futbol"></i>
                    <select id="register-position" name="poste" required>
                        <option value="">Sélectionnez votre poste</option>
                        <option value="Attaquant">Attaquant</option>
                        <option value="Milieu">Milieu</option>
                        <option value="Défenseur">Défenseur</option>
                        <option value="Gardien">Gardien</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="register-height">Taille (en cm)</label>
                <div class="input-icon">
                    <i class="fas fa-arrows-alt-v"></i>
                    <input type="number" id="register-height" name="taille" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="register-weight">Poids (en kg)</label>
                <div class="input-icon">
                    <i class="fas fa-weight"></i>
                    <input type="number" id="register-weight" name="poids" required>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="register-nationality">Nationalité</label>
            <div class="input-icon">
                <i class="fas fa-globe"></i>
                <input type="text" id="register-nationality" name="nationalite" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="register-justificatif">Justificatif</label>
            <div class="input-icon">
                <i class="fas fa-file"></i>
                <input type="file" id="register-justificatif" name="justificatif" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="register-photo">Photo (facultatif)</label>
            <div class="input-icon">
                <i class="fas fa-camera"></i>
                <input type="file" id="register-photo" name="photo">
            </div>
        </div>
        
        <div class="form-group terms">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">J'accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a></label>
        </div>
        
        <button type="submit" class="btn">S'inscrire</button>
    </form>
</div>

        
        <div class="auth-footer">
            <div class="social-login">
                <p>Ou connectez-vous avec</p>
                <div class="social-buttons">
                    <a href="#" class="social-btn facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-btn google"><i class="fab fa-google"></i></a>
                    <a href="#" class="social-btn twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="auth-image">
        <div class="overlay"></div>
    </div>
</div>

<script>
  // Initialisation du plugin intl-tel-input
document.addEventListener('DOMContentLoaded', function() {
    // Ajout de l'initialisation du téléphone
    var phoneInput = document.querySelector("#register-phone");
    if (phoneInput) {
        var iti = window.intlTelInput(phoneInput, {
            initialCountry: "fr", // Pays par défaut (France)
            preferredCountries: ["fr", "be", "ch", "ma", "sn", "ci"], // Adaptez selon vos utilisateurs principaux
            separateDialCode: true, // Affiche le code pays séparé
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
        });
        
        // Validation du formulaire d'inscription existant
        const registerForm = document.querySelector('#register-form form');
        
        if (registerForm) {
            // On conserve la validation existante et on ajoute la validation du téléphone
            registerForm.addEventListener('submit', function(e) {
                const password = document.getElementById('register-password').value;
                const confirmPassword = document.getElementById('register-confirm').value;
                
                // Validation du mot de passe (code existant)
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Les mots de passe ne correspondent pas');
                    return false;
                }
                
                // Validation du téléphone (nouveau code)
                if (!iti.isValidNumber()) {
                    e.preventDefault();
                    alert('Veuillez entrer un numéro de téléphone valide');
                    return false;
                }
                
                // Si le numéro est valide, on le stocke au format international dans le champ caché
                document.getElementById('full_telephone').value = iti.getNumber();
                return true;
            });
        }
    }
});
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des onglets
    const tabBtns = document.querySelectorAll('.tab-btn');
    const authForms = document.querySelectorAll('.auth-form');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Réinitialiser les classes actives
            tabBtns.forEach(b => b.classList.remove('active'));
            authForms.forEach(f => f.classList.remove('active'));
            
            // Ajouter la classe active au bouton et au formulaire correspondant
            this.classList.add('active');
            const target = this.getAttribute('data-target');
            document.getElementById(target).classList.add('active');
        });
    });
    
    // Validation du formulaire d'inscription
    const registerForm = document.querySelector('#register-form form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('register-password').value;
            const confirmPassword = document.getElementById('register-confirm').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas');
            }
        });
    }
});
</script>


 <style>/* ACADÉMIE DE FOOTBALL - STYLES CSS POUR LA PAGE CONNEXION/INSCRIPTION */
/* Variables et réinitialisation - cohérence avec le style précédent */
:root {

  --shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  --transition: all 0.3s ease;
  --border-radius: 8px;
  --border-radius-lg: 12px;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Montserrat', sans-serif;
}

body {
  min-height: 100vh;
  background-color: #f0f3f7;
 
  align-items: center;
  justify-content: center;
  padding: 1rem;


  
}


/* Container pour la page d'authentification */
.auth-container {
  width: 100%;
  max-width: 1300px;
  min-height: 600px;
  background-color: white;
  border-radius: var(--border-radius-lg);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
  display: flex;
  overflow: hidden;
  justify-content: center;
  position: relative;
}

/* Partie gauche - Formulaires */
.auth-box {
  width: 50%;
  padding: 3rem;
  display: flex;
  flex-direction: column;
  position: relative;
  z-index: 2;
}

/* En-tête avec logo */
.auth-header {
  text-align: center;
  margin-bottom: 2rem;
}

.auth-logo {
  max-width: 80px;
  margin-bottom: 1rem;
}

.auth-header h1 {
  color: var(--dark-color);
  font-size: 1.8rem;
  font-weight: 700;
  position: relative;
  display: inline-block;
  padding-bottom: 0.5rem;
}

.auth-header h1:after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 25%;
  width: 50%;
  height: 3px;
  background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
  border-radius: 3px;
}

/* Onglets de navigation */
.auth-tabs {
  display: flex;
  margin-bottom: 2rem;
  border-bottom: 1px solid var(--grey-color);
}

.tab-btn {
  flex: 1;
  background: none;
  border: none;
  padding: 1rem 0;
  font-size: 1rem;
  font-weight: 600;
  color: #667785;
  cursor: pointer;
  transition: var(--transition);
  position: relative;
}

.tab-btn:hover {
  color: var(--primary-color);
}

.tab-btn.active {
  color: var(--primary-color);
}

.tab-btn.active:after {
  content: '';
  position: absolute;
  bottom: -1px;
  left: 10%;
  width: 80%;
  height: 3px;
  background-color: var(--primary-color);
  border-radius: 3px 3px 0 0;
}

/* Formulaires */
.auth-form {
  display: none;
  animation: fadeIn 0.5s ease;
}

.auth-form.active {
  display: block;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-row {
  display: flex;
  gap: 15px;
}

.form-row .form-group {
  flex: 1;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  color: var(--dark-color);
  font-weight: 500;
  font-size: 0.95rem;
}

.input-icon {
  position: relative;
}

.input-icon i {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #aab7c4;
  transition: var(--transition);
}

.input-icon input,
.input-icon select {
  width: 100%;
  padding: 12px 12px 12px 40px;
  border: 1px solid #e0e0e0;
  border-radius: var(--border-radius);
  font-size: 0.95rem;
  transition: var(--transition);
  background-color: #f9fafc;
}

.input-icon input:focus,
.input-icon select:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(14, 91, 52, 0.1);
}

.input-icon input:focus + i,
.input-icon select:focus + i {
  color: var(--primary-color);
}

/* Style pour le select */
.input-icon select {
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%230e5b34' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
  padding-right: 40px;
}

/* Options du formulaire */
.form-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
  font-size: 0.9rem;
}

.remember-me {
  display: flex;
  align-items: center;
}

.remember-me input[type="checkbox"] {
  margin-right: 8px;
  accent-color: var(--primary-color);
}

.forgot-password {
  color: var(--primary-color);
  text-decoration: none;
  transition: var(--transition);
}

.forgot-password:hover {
  text-decoration: underline;
}

/* Bouton de soumission */
.auth-btn {
  width: 100%;
  padding: 14px;
  background: linear-gradient(135deg, var(--primary-color), #0a4628);
  color: black;
  border: none;
  border-radius: var(--border-radius);
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  font-size: 1rem;
  margin-top: 1rem;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  overflow: hidden;
}

.auth-btn:after {
  content: '';
  position: absolute;
  width: 0;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.1);
  left: 0;
  top: 0;
  transition: width 0.3s ease;
}

.auth-btn:hover:after {
  width: 100%;
}

.auth-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(14, 91, 52, 0.4);
}
 /* Adaptation du sélecteur au style existant */
    .iti {
        width: 100%;
        display: block;
    }
    
    /* Repositionne l'icône Font Awesome pour qu'elle n'interfère pas avec l'indicatif */
    .iti ~ .input-icon i {
        display: none;
    }
    
    #register-phone {
        padding-left: 90px !important; /* Espace pour l'indicatif */
    }
    
    /* Style cohérent avec le design de votre formulaire */
    .iti__flag-container:hover .iti__selected-flag {
        background-color: rgba(0, 0, 0, 0.05);
    }
    
    .iti__selected-flag {
        border-radius: var(--border-radius) 0 0 var(--border-radius);
    }
/* Termes et conditions */
.terms {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-size: 0.85rem;
}

.terms input[type="checkbox"] {
  margin-top: 3px;
  accent-color: var(--primary-color);
}

.terms a {
  color: var(--primary-color);
  text-decoration: none;
}

.terms a:hover {
  text-decoration: underline;
}

/* Pied de page d'authentification */
.auth-footer {
  margin-top: auto;
  text-align: center;
}

.social-login {
  margin-top: 2rem;
}

.social-login p {
  color: #667785;
  margin-bottom: 1rem;
  position: relative;
}

.social-login p:before,
.social-login p:after {
  content: '';
  position: absolute;
  top: 50%;
  width: 60px;
  height: 1px;
  background-color: #ddd;
}

.social-login p:before {
  left: 0;
}

.social-login p:after {
  right: 0;
}

.social-buttons {
  display: flex;
  justify-content: center;
  gap: 15px;
}

.social-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  text-decoration: none;
  transition: var(--transition);
}

.social-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}

.facebook {
  background-color: #3b5998;
}

.google {
  background-color: #dd4b39;
}

.twitter {
  background-color: #1da1f2;
}

/* Partie droite - Image */
.auth-image {
  width: 50%;
  background-image: url('assets/images/football-field.jpg');
  background-size: cover;
  background-position: center;
  position: relative;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, rgba(14, 91, 52, 0.85), rgba(26, 42, 54, 0.8));
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  color: white;
  text-align: center;
  padding: 2rem;
}

/* Design responsive */
@media (max-width: 768px) {
  .auth-container {
    flex-direction: column;
    height: auto;
  }
  
  .auth-box {
    width: 100%;
    padding: 2rem;
    order: 2;
  }
  
  .auth-image {
    width: 100%;
    height: 200px;
    order: 1;
  }
  
  .form-row {
    flex-direction: column;
    gap: 0;
  }
  
  .auth-footer {
    margin-top: 2rem;
  }
  
  .social-login p:before,
  .social-login p:after {
    width: 30px;
  }
}

/* Animation supplémentaire pour améliorer l'UX */
.input-icon input:focus::placeholder {
  opacity: 0.5;
  transform: translateX(5px);
  transition: var(--transition);
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
  20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.form-error {
  animation: shake 0.5s ease;
}

/* Style d'erreur pour validation du formulaire */
.error-message {
  color: var(--accent-color);
  font-size: 0.85rem;
  margin-top: 0.5rem;
  display: flex;
  align-items: center;
  gap: 5px;
}

.error-message:before {
  content: '⚠';
}

/* Accessibilité améliorée */
.tab-btn:focus,
.auth-btn:focus,
input:focus,
select:focus {
  outline: 2px solid var(--primary-color);
  outline-offset: 2px;
}

/* Remplacement pour les navigateurs qui ne supportent pas le pseudo-élément :focus-visible */
@supports selector(:focus-visible) {
  .tab-btn:focus,
  .auth-btn:focus,
  input:focus,
  select:focus {
    outline: none;
  }
  
  .tab-btn:focus-visible,
  .auth-btn:focus-visible,
  input:focus-visible,
  select:focus-visible {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
  }
}


</style>