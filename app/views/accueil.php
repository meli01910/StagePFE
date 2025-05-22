<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FootElite | Plateforme de gestion pour joueurs d'élite</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        :root {
            --deepblue-1: #050A30;
            --deepblue-2: #000C40;
            --midnight-blue: #0C1445;
            --navy-blue: #0F1E51;
            --royal-blue: #1A3A8F;
            --accent-blue: #2351DF;
            --pure-white: #ffffff;
            --off-white: #f5f7ff;
            --dark-gray: #1a1a2e;
            --text-gray: #b8b8d4;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, var(--deepblue-1) 0%, var(--midnight-blue) 100%);
            color: var(--off-white);
            min-height: 100vh;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
        }
        
        .text-gradient {
            background: linear-gradient(90deg, #2351DF, #00D4FF);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .btn-premium {
            background: linear-gradient(45deg, var(--accent-blue), #4f70ff);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 30px;
            box-shadow: 0 4px 15px rgba(35, 81, 223, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(35, 81, 223, 0.5);
            color: white;
        }
        
        .btn-outline {
            color: var(--pure-white);
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.15);
            font-weight: 600;
            padding: 11px 28px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        
        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
            color: var(--pure-white);
        }
        
        /* Navbar */
        .navbar-brand {
    display: flex;
    align-items: center;
    font-weight: 700;
    position: relative;
}

.brio {
    color: var(--pure-white);
    font-size: 1.6rem;
    letter-spacing: -0.5px;
}

.youth {
    color: var(--accent-blue);
    font-size: 1.6rem;
    font-weight: 800;
    letter-spacing: 0.5px;
    margin-right: 12px;
}

.association {
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--text-gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding-left: 12px;
    border-left: 2px solid rgba(255, 255, 255, 0.2);
    line-height: 1;
    margin-top: 4px;
}

/* Version responsive */
@media (max-width: 576px) {
    .navbar-brand {
        flex-direction: column;
        align-items: flex-start;
        line-height: 1.2;
    }
    
    .association {
        border-left: none;
        padding-left: 0;
        font-size: 0.65rem;
        margin-top: 0;
    }
}

        .navbar {
            background: rgba(5, 10, 48, 0.7);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 15px 0;
        }
        
     
        
        .nav-link {
            color: var(--pure-white) !important;
            font-weight: 600;
            font-size: 15px;
            padding: 8px 16px !important;
            border-radius: 6px;
            transition: all 0.3s;
            margin: 0 5px;
            opacity: 0.85;
        }
        
        .nav-link:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.05);
        }
        
        .nav-link.active {
            background: rgba(35, 81, 223, 0.15);
            color: var(--accent-blue) !important;
            opacity: 1;
        }
        
        .header-ctas .btn {
            margin-left: 8px;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(5, 10, 48, 0.8), rgba(0, 12, 64, 0.9)), url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1964&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            padding: 80px 0;
            margin-top: -76px; /* Pour compenser la hauteur de la navbar */
            padding-top: 156px;
        }
        
        .hero-content {
            max-width: 650px;
        }
        
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .hero-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 2.5rem;
            color: var(--text-gray);
        }
        
        .hero-cta {
            display: flex;
            gap: 15px;
        }
        
        .hero-image {
            position: relative;
            height: 80vh;
            max-height: 700px;
        }
        
        .hero-mockup {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            max-width: 650px;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        /*Organisation*/
        .dual-org-section {
            padding: 80px 0;
            background: linear-gradient(180deg, var(--deepblue-1) 0%, var(--midnight-blue) 100%);
        }
        
        .org-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            height: 100%;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .org-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            border-color: rgba(79, 112, 255, 0.3);
        }
        
        .org-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #2351DF, #00D4FF);
        }
        
        .org-card h3 {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }
        
        .org-card p {
            margin-bottom: 1.5rem;
            color: var(--text-gray);
            font-size: 1.1rem;
        }
        
        .org-logo {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
        }
        
        
        /* Features */
        .features-section {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--midnight-blue) 0%, var(--deepblue-2) 100%);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 70px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .feature-card {
            background: linear-gradient(145deg, rgba(10, 20, 69, 0.8), rgba(15, 30, 81, 0.5));
            border-radius: 16px;
            padding: 40px 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(45deg, var(--accent-blue), #4f70ff);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            margin-bottom: 25px;
            box-shadow: 0 10px 20px rgba(35, 81, 223, 0.3);
        }
        
        .feature-icon i {
            font-size: 28px;
            color: white;
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .feature-card p {
            color: var(--text-gray);
            line-height: 1.7;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 100px 0;

            position: relative;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(rgba(5, 10, 48, 0.8), rgba(0, 12, 64, 0.9)), url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1964&q=80');
         
                 }
        
        .cta-content {
            position: relative;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .cta-content h2 {
            font-size: 3rem;
            margin-bottom: 25px;
        }
        
        .cta-content p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            color: var(--text-gray);
        }
        
        /* Auth Modal */
        .auth-modal .modal-content {
            background: linear-gradient(135deg, var(--deepblue-2), var(--navy-blue));
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            color: var(--pure-white);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        .auth-modal .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 20px 30px;
        }
        
        .auth-modal .modal-title {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .auth-modal .btn-close {
            color: white;
            filter: invert(1) brightness(200%);
        }
        
        .auth-modal .modal-body {
            padding: 30px;
        }
        /* Styles pour les modaux d'authentification */
.auth-modal {
  border-radius: var(--border-radius-lg);
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  border: none;
}

.auth-modal .modal-header {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  padding: 0.75rem 1rem;
}

.auth-modal .btn-close {
  background-color: white;
  opacity: 0.8;
  border-radius: 50%;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  padding: 0.5rem;
  margin: 0;
}

.auth-modal .auth-brand {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  line-height: 1;
}

.auth-modal .brio {
  color: var(--dark-color);
  font-size: 1.3rem;
  letter-spacing: -0.5px;
  font-weight: 700;
}

.auth-modal .youth {
  color: var(--primary-color);
  font-size: 1.3rem;
  font-weight: 800;
  letter-spacing: 0.5px;
}

.auth-modal .association {
  font-size: 0.7rem;
  font-weight: 500;
  color: #667785;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.auth-modal h2 {
  font-weight: 700;
  color: var(--dark-color);
  position: relative;
  display: inline-block;
}

.auth-modal h2:after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 25%;
  width: 50%;
  height: 3px;
  background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
  border-radius: 3px;
}

/* Adaptation pour les téléphones */
@media (max-width: 576px) {
  .modal-dialog {
    margin: 0.5rem;
  }
  
  .form-row {
    flex-direction: column;
  }
}

        .auth-tabs {
            display: flex;
            margin-bottom: 25px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            padding: 6px;
        }
        
        .auth-tab {
            flex: 1;
            text-align: center;
            padding: 12px;
            cursor: pointer;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            color: var(--text-gray);
        }
        
        .auth-tab.active {
            background: linear-gradient(45deg, var(--accent-blue), #4f70ff);
            color: white;
        }
        
        .form-label {
            color: var(--text-gray);
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--pure-white);
            padding: 12px 15px;
            border-radius: 10px;
        }
        
        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(35, 81, 223, 0.5);
            box-shadow: 0 0 0 0.25rem rgba(35, 81, 223, 0.25);
            color: var(--pure-white);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }
        
        .form-check-label {
            font-size: 14px;
        }
        
        .auth-submit-btn {
            background: linear-gradient(45deg, var(--accent-blue), #4f70ff);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
            width: 100%;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(35, 81, 223, 0.3);
            transition: all 0.3s;
        }
        
        .auth-submit-btn:hover {
            box-shadow: 0 8px 25px rgba(35, 81, 223, 0.5);
        }
        
        .auth-separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
        }
        
        .auth-separator::before,
        .auth-separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .auth-separator span {
            padding: 0 10px;
            color: var(--text-gray);
            font-size: 14px;
        }
        
        .social-login {
            display: flex;
            gap: 15px;
        }
        
        .social-btn {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--pure-white);
            padding: 12px 15px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .social-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .google-btn:hover {
            border-color: #EA4335;
        }
        
        .facebook-btn:hover {
            border-color: #4267B2;
        }
        
        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }
        
        .forgot-password a {
            color: var(--accent-blue);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .forgot-password a:hover {
            text-decoration: underline;
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: var(--text-gray);
        }
        
        .auth-footer a {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 500;
        }
        
        .auth-footer a:hover {
            text-decoration: underline;
        }
        
        /* Responsive */
        @media (max-width: 991.98px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .hero-content p {
                font-size: 1rem;
            }
            
            .hero-image {
                margin-top: 50px;
                height: auto;
            }
            
            .hero-mockup {
                position: static;
                transform: none;
            }
            
            .navbar .header-ctas {
                margin-top: 15px;
            }
        }
        
        @media (max-width: 767.98px) {
            .hero-section {
                padding: 120px 0 60px;
                text-align: center;
            }
            
            .hero-content {
                margin-bottom: 50px;
            }
            
            .hero-cta {
                justify-content: center;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .cta-content h2 {
                font-size: 2.2rem;
            }
        }
        
        /* Animation */
        .floating {
            animation: floating 6s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(0, 15px); }
            100% { transform: translate(0, -0px); }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
           <a class="navbar-brand" href="#">
    <span class="brio">Brio</span><span class="youth">YOUTH</span>
    <span class="association">Plus Fort Ensemble</span>
</a>
   <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Détections</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tournois</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Académies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Recruteurs</a>
                    </li>
                </ul>
                <div class="header-ctas d-flex ms-lg-4">
                    <!-- Dans votre barre de navigation -->


                    <button class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#authModal" id="loginButton">Connexion</button>
                    <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#authModal" id="registerButton">S'inscrire</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 animate__animated animate__fadeInUp">
                    <div class="hero-content">
                        <h1>Votre <span class="text-gradient">carrière football</span> prend son envol</h1>
                        <p>FootElite est la plateforme complète pour les joueurs ambitieux. Créez votre profil, inscrivez-vous aux détections, et permettez aux recruteurs de découvrir votre talent.</p>
                        <div class="hero-cta">
                            <button class="btn btn-premium" data-bs-toggle="modal" data-bs-target="#authModal">Commencer maintenant</button>
                            <button class="btn btn-outline">Comment ça marche</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 animate__animated animate__fadeInRight">
                    <div class="hero-image">
                        <img src="https://media.istockphoto.com/id/2154010455/fr/photo/portrait-dun-gar%C3%A7on-gardien-de-but-souriant.webp?a=1&b=1&s=612x612&w=0&k=20&c=_bBW0YUYsdDuWnQGH4v7-vh_by5oWEbNcpO_LWsBWBc=" alt="Joueur de football" class="hero-mockup floating">
                    </div>
                </div>
            </div>
        </div>
    </section>
<section class="dual-org-section" id="dual-orgs">
        <div class="container">
            <div class="section-title mb-5">
                <h2>Deux organisations, <span class="text-gradient">une mission</span></h2>
                <p>Un écosystème complet dédié à ta progression dans le football</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="org-card">
                        <div class="org-logo">
                            <i class="fas fa-futbol"></i>
                        </div>
                        <h3>BRIO YOUTH</h3>
                        <p>Notre académie d'élite qui forme les talents de demain. En
                        <h3>BRIO YOUTH</h3>
                        <p>Notre académie d'élite qui forme les talents de demain. En rejoignant BRIO YOUTH, bénéficie d'un entraînement professionnel, d'un suivi personnalisé et d'opportunités de matchs et de tournois avec des recruteurs présents.</p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check-circle me-2 text-primary"></i> Entraînements intensifs de haut niveau</li>
                            <li class="mb-2"><i class="fas fa-check-circle me-2 text-primary"></i> Préparation physique et mentale</li>
                            <li class="mb-2"><i class="fas fa-check-circle me-2 text-primary"></i> Matchs contre des académies professionnelles</li>
                            <li class="mb-2"><i class="fas fa-check-circle me-2 text-primary"></i> Suivi technique individualisé</li>
                        </ul>
                        <a href="#" class="btn btn-primary">Découvrir l'académie</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="org-card">
                        <div class="org-logo">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h3>Plus Fort Ensemble</h3>
                        <p>Notre association d'accompagnement qui aide les jeunes talents à être repérés et à développer leur carrière. Nous créons le pont entre les joueurs amateurs prometteurs et le monde professionnel.</p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check-circle me-2 text-primary"></i> Détection de talents à travers la France</li>
                            <li class="mb-2"><i class="fas fa-check-circle me-2 text-primary"></i> Accompagnement dans les démarches</li>
                            <li class="mb-2"><i class="fas fa-check-circle me-2 text-primary"></i> Mise en relation avec des clubs professionnels</li>
                            <li class="mb-2"><i class="fas fa-check-circle me-2 text-primary"></i> Soutien socio-éducatif et scolaire</li>
                        </ul>
                        <a href="#" class="btn btn-primary">En savoir plus sur l'association</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-title animate__animated animate__fadeInUp">
                <h2>Tout ce dont vous avez besoin pour <span class="text-gradient">réussir</span></h2>
                <p class="text-gray">Des outils puissants pour votre développement</p>
            </div>
            
            <div class="row">
                <div class="col-md-6 col-lg-3 animate__animated animate__fadeInUp" data-wow-delay="0.1s">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h3>Profil Professionnel</h3>
                        <p>Créez un profil complet avec vos statistiques, vidéos, et parcours sportif pour vous démarquer.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3>Détections & Tournois</h3>
                        <p>Inscrivez-vous facilement aux événements et recevez des notifications personnalisées.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Suivi de Progression</h3>
                        <p>Visionnez vos statistiques en temps réel et analysez votre évolution technique.</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3 animate__animated animate__fadeInUp" data-wow-delay="0.4s">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Visibilité Recruteurs</h3>
                        <p>Soyez repéré par les clubs professionnels qui recherchent de nouveaux talents.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content animate__animated animate__fadeInUp">
                <h2>Prêt à commencer votre <span class="text-gradient">aventure</span> ?</h2>
                <p>Inscrivez-vous gratuitement et accédez à la plateforme complète. Votre futur dans le football commence maintenant.</p>
                <button class="btn btn-premium btn-lg" data-bs-toggle="modal" data-bs-target="#authModal">Créer mon compte</button>
            </div>
        </div>
    </section>

    <!-- Auth Modal -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content auth-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">Bienvenue sur FootElite</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="auth-tabs">
                        <div class="auth-tab active" id="loginTab">Connexion</div>
                        <div class="auth-tab" id="registerTab">Inscription</div>
                    </div>
                    
                    <!-- Login Form -->
                    <div class="auth-form-container" id="loginForm">
                        <form>
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="loginEmail" placeholder="Entrez votre email">
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="loginPassword" placeholder="Entrez votre mot de passe">
                                <div class="forgot-password">
                                    <a href="#">Mot de passe oublié?</a>
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
                            </div>
                            <button type="submit" class="btn auth-submit-btn">Se connecter</button>
                        </form>
                        
                        <div class="auth-separator">
                            <span>ou</span>
                        </div>
                        
                        <div class="social-login">
                            <button class="social-btn google-btn">
                                <i class="fab fa-google"></i>
                                <span>Google</span>
                            </button>
                            <button class="social-btn facebook-btn">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </button>
                        </div>
                        
                        <div class="auth-footer">
                            Pas encore de compte? <a href="#" id="switchToRegister">Inscrivez-vous</a>
                        </div>
                    </div>
                    
                    <!-- Register Form -->
                    <div class="auth-form-container" id="registerForm" style="display: none;">
                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="registerFirstName" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="registerFirstName" placeholder="Votre prénom">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="registerLastName" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="registerLastName" placeholder="Votre nom">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="registerEmail" class="form-label">Adresse email</label>
                                <input type="email" class="form-control" id="registerEmail" placeholder="Entrez votre email">
                            </div>
                            <div class="mb-3">
                                <label for="registerPassword" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="registerPassword" placeholder="Créez un mot de passe">
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="confirmPassword" placeholder="Confirmez votre mot de passe">
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="agreeTerms">
                                <label class="form-check-label" for="agreeTerms">J'accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a></label>
                            </div>
                            <button type="submit" class="btn auth-submit-btn">S'inscrire</button>
                        </form>
                        
                        <div class="auth-separator">
                            <span>ou inscrivez-vous avec</span>
                        </div>
                        
                        <div class="social-login">
                            <button class="social-btn google-btn">
                                <i class="fab fa-google"></i>
                                <span>Google</span>
                            </button>
                            <button class="social-btn facebook-btn">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </button>
                        </div>
                        
                        <div class="auth-footer">
                            Déjà un compte? <a href="#" id="switchToLogin">Connectez-vous</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS Bundle with Popper -->
    <!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>



// Gestion de la transition entre les modaux
document.addEventListener('DOMContentLoaded', function() {
  // Initialiser intlTelInput pour le modal d'inscription également
  var phoneInputModal = document.querySelector("#register-phone-modal");
  if (phoneInputModal) {
    var itiModal = window.intlTelInput(phoneInputModal, {
      initialCountry: "fr", 
      preferredCountries: ["fr", "be", "ch", "ma", "sn", "ci"],
      separateDialCode: true,
      utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
    });
  }
  
  // Validation du formulaire d'inscription modal
  const registerFormModal = document.getElementById('register-form-modal');
  if (registerFormModal) {
    registerFormModal.addEventListener('submit', function(e) {
      const password = document.getElementById('register-password-modal').value;
      const confirmPassword = document.getElementById('register-confirm-modal').value;
      
      if (password !== confirmPassword) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas');
      }
      
      if (phoneInputModal && !itiModal.isValidNumber()) {
        e.preventDefault();
        alert('Veuillez entrer un numéro de téléphone valide');
      }
    });
  }
});




        // Toggle entre les formulaires de connexion et d'inscription
        document.addEventListener('DOMContentLoaded', function() {
            const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const switchToRegister = document.getElementById('switchToRegister');
            const switchToLogin = document.getElementById('switchToLogin');
            const registerButton = document.getElementById('registerButton');
            const loginButton = document.getElementById('loginButton');
            
            function showLoginForm() {
                loginTab.classList.add('active');
                registerTab.classList.remove('active');
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
            }
            
            function showRegisterForm() {
                registerTab.classList.add('active');
                loginTab.classList.remove('active');
                registerForm.style.display = 'block';
                loginForm.style.display = 'none';
            }
            
            loginTab.addEventListener('click', showLoginForm);
            registerTab.addEventListener('click', showRegisterForm);
            switchToRegister.addEventListener('click', function(e) {
                e.preventDefault();
                showRegisterForm();
            });
            
            switchToLogin.addEventListener('click', function(e) {
                e.preventDefault();
                showLoginForm();
            });
            
            registerButton.addEventListener('click', function() {
                setTimeout(showRegisterForm, 300);
            });
            
            loginButton.addEventListener('click', function() {
                setTimeout(showLoginForm, 300);
            });
            
            // Animation des éléments au défilement
            const animateOnScroll = function() {
                const elements = document.querySelectorAll('.animate__animated');
                
                elements.forEach(element => {
                    const position = element.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight / 1.2;
                    
                    if (position < screenPosition) {
                        const animationClass = element.classList.contains('animate__fadeInUp') ? 'animate__fadeInUp' : 
                                             element.classList.contains('animate__fadeInRight') ? 'animate__fadeInRight' : 
                                             'animate__fadeIn';
                        element.classList.add(animationClass);
                    }
                });
            };
            
            window.addEventListener('scroll', animateOnScroll);
            animateOnScroll(); // Exécuter une fois au chargement
        });
    </script>
</body>
</html>
