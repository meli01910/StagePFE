<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Académie Elite Football - Détection de Talents</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Animate.css pour les animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue:rgb(12, 31, 77);
            --secondary-blue: #1e3799;
            --accent-blue: #4a69bd;
            --light-blue: #6a89cc;
            --white: #ffffff;
            --light-gray: #f8f9fa;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            color: #333;
            overflow-x: hidden;
        }
        
        .navbar {
            background-color: var(--primary-blue);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 28px;
            color: var(--white);
        }
        
        .nav-link {
            color: var(--white) !important;
            font-weight: 600;
            margin: 0 10px;
            transition: transform 0.3s, color 0.3s;
        }
        
        .nav-link:hover {
            transform: translateY(-2px);
            color: var(--light-blue) !important;
        }
        
        /* Hero Section avec image de fond plein écran */
        .hero-section {
            background: linear-gradient(rgba(10, 36, 99, 0.8), rgba(10, 36, 99, 0.9)), url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2936&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 180px 0 150px;
            text-align: center;
            position: relative;
            min-height: 90vh;
            display: flex;
            align-items: center;
        }
        
        .hero-heading {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 3.5rem;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
        }
        
        .hero-subheading {
            font-weight: 500;
            font-size: 1.5rem;
            margin-bottom: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.3);
        }
        
        .btn-hero {
            font-size: 1.1rem;
            font-weight: 600;
            padding: 12px 30px;
            margin: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-primary {
            background-color: var(--accent-blue);
            border-color: var(--accent-blue);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-blue);
            border-color: var(--secondary-blue);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        .btn-outline-light:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        /* Features Section avec fond bleu dégradé */
        .features-section {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .section-heading {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 2.8rem;
            margin-bottom: 50px;
            text-align: center;
            position: relative;
        }
        
        .section-heading::after {
            content: "";
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: var(--accent-blue);
        }
        
        .features-section .section-heading::after {
            background-color: white;
        }
        
        .feature-card {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 30px 25px;
            margin-bottom: 30px;
            text-align: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            height: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: white;
        }
        
        .feature-title {
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 15px;
        }
        
        /* Events Section avec images en arrière-plan */
        .events-section {
            position: relative;
            padding: 100px 0;
            background: linear-gradient(rgba(248, 249, 250, 0.9), rgba(248, 249, 250, 0.9)), url('https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .event-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .event-image {
            height: 200px;
            overflow: hidden;
        }
        
        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .event-card:hover .event-image img {
            transform: scale(1.1);
        }
        
        .event-details {
            padding: 25px;
        }
        
        .event-date {
            font-size: 0.9rem;
            color: var(--accent-blue);
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .event-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary-blue);
        }
        
        .event-location {
            margin-bottom: 15px;
        }
        
        .btn-register {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            background-color: var(--secondary-blue);
            transform: translateY(-2px);
        }
        
        /* Testimonials Section avec fond bleu et image */
        .testimonials-section {
            padding: 100px 0;
            background: linear-gradient(rgba(10, 36, 99, 0.9), rgba(10, 36, 99, 0.85)), url('https://images.unsplash.com/photo-1459865264687-595d652de67e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
        }
        
        .testimonial-card {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            height: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        
        .testimonial-card:hover {
            transform: translateY(-10px);
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .testimonial-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 20px;
            border: 3px solid var(--accent-blue);
        }
        
        .testimonial-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .testimonial-quote {
            font-size: 1.1rem;
            font-style: italic;
            margin-bottom: 20px;
            position: relative;
            padding: 0 20px;
        }
        
        .testimonial-quote::before, .testimonial-quote::after {
            content: '"';
            font-size: 2rem;
            position: absolute;
            color: var(--accent-blue);
            font-family: Georgia, serif;
        }
        
        .testimonial-quote::before {
            left: 0;
            top: -10px;
        }
        
        .testimonial-quote::after {
            right: 0;
            bottom: -10px;
        }
        
        .testimonial-author {
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .testimonial-role {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        /* CTA Section avec image de terrain de football */
        .cta-section {
            background: linear-gradient(rgba(10, 36, 99, 0.85), rgba(30, 55, 153, 0.9)), url('https://images.unsplash.com/photo-1556056504-5c7696c4c28d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2076&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 100px 0;
            position: relative;
        }
        
        .cta-heading {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        
        .cta-subheading {
            font-size: 1.2rem;
            margin-bottom: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Footer avec fond bleu foncé */
        .footer {
            background-color: var(--primary-blue);
            color: white;
            padding: 70px 0 20px;
        }
        
        .footer-logo {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        
        .footer-contact {
            margin-bottom: 30px;
        }
        
        .footer-contact a {
            color: var(--light-blue);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-contact a:hover {
            color: white;
        }
        
        .footer-heading {
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-heading::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background-color: var(--accent-blue);
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            display: block;
            opacity: 0.8;
        }
        
        .footer-links a:hover {
            color: var(--light-blue);
            transform: translateX(5px);
            opacity: 1;
        }
        
        .social-links {
            margin-top: 20px;
        }
        
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            margin-right: 10px;
            text-align: center;
            line-height: 40px;
            color: white;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background-color: var(--accent-blue);
            transform: translateY(-5px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            margin-top: 50px;
        }
        
        /* Auth Modals */
        .modal-content {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            border: none;
            border-radius: 10px;
        }
        
        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .modal-title {
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .form-label {
            font-weight: 600;
        }
        
        .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 15px;
            border-radius: 5px;
        }
        
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .auth-btn {
            font-weight: 600;
            padding: 10px 20px;
            width: 100%;
        }
        
        .auth-separator {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }
        
        .auth-separator::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            width: 45%;
            height: 1px;
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .auth-separator::after {
            content: "";
            position: absolute;
            right: 0;
            top: 50%;
            width: 45%;
            height: 1px;
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .auth-separator span {
            background-color: var(--secondary-blue);
            padding: 0 10px;
            position: relative;
            z-index: 1;
        }
        
        .modal-body a {
            color: var(--light-blue);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .modal-body a:hover {
            color: white;
            text-decoration: underline;
        }
        
        /* Stats Counter Section */
        .stats-counter {
            position: relative;
            z-index: 10;
            margin-top: -80px;
            margin-bottom: 40px;
        }
        
        .counter-box {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px 20px;
            text-align: center;
            transition: all 0.3s ease;
            border-bottom: 5px solid var(--accent-blue);
        }
        
        .counter-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .counter-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--accent-blue);
        }
        
        .counter-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 5px;
            font-family: 'Oswald', sans-serif;
        }
        
        .counter-text {
            font-size: 1rem;
            font-weight: 600;
            color: #555;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">BRIO YOUTH</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
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
                        <a class="nav-link" href="#">Équipes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Actualités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <button class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Connexion</button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal">Inscription</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto text-center">
                    <h1 class="hero-heading animate__animated animate__fadeInDown">RÉVÉLEZ VOTRE TALENT</h1>
                    <p class="hero-subheading animate__animated animate__fadeInUp animate__delay-1s">L'Académie Elite Football vous accompagne dans votre parcours vers l'élite du football professionnel grâce à nos programmes de détection et de formation d'excellence.</p>
                    <div class="animate__animated animate__fadeInUp animate__delay-2s">
                        <button class="btn btn-primary btn-hero" data-bs-toggle="modal" data-bs-target="#registerModal">S'INSCRIRE MAINTENANT</button>
                        <button class="btn btn-outline-light btn-hero">DÉCOUVRIR L'ACADÉMIE</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Stats Counter Section -->
    <section class="stats-counter">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="counter-box">
                        <div class="counter-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="counter-number">500+</div>
                        <div class="counter-text">JOUEURS FORMÉS</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="counter-box">
                        <div class="counter-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="counter-number">25</div>
                        <div class="counter-text">TOURNOIS REMPORTÉS</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="counter-box">
                        <div class="counter-icon">
                            <i class="fas fa-futbol"></i>
                        </div>
                        <div class="counter-number">45</div>
                        <div class="counter-text">JOUEURS PROS</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="counter-box">
                        <div class="counter-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <div class="counter-number">12</div>
                        <div class="counter-text">ANNÉES D'EXPERTISE</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-heading">POURQUOI NOUS CHOISIR</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-search"></i></div>
                        <h3 class="feature-title">DÉTECTION DE TALENTS</h3>
                        <p>Nos détections sont conçues pour identifier le potentiel et permettre aux jeunes talents de se faire remarquer par les clubs professionnels.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <h3 class="feature-title">ENTRAÎNEURS QUALIFIÉS</h3>
                        <p>Notre équipe d'entraîneurs diplômés possède une riche expérience dans la formation de jeunes talents et le football de haut niveau.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-dumbbell"></i></div>
                        <h3 class="feature-title">PRÉPARATION PHYSIQUE</h3>
                        <p>Un programme de préparation physique personnalisé pour développer endurance, puissance, vitesse et agilité essentielles pour le football moderne.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-globe"></i></div>
                        <h3 class="feature-title">RÉSEAU INTERNATIONAL</h3>
                        <p>Notre réseau de contacts dans le monde du football professionnel offre des opportunités uniques à nos meilleurs joueurs.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-video"></i></div>
                        <h3 class="feature-title">ANALYSE VIDÉO</h3>
                        <p>Analyse vidéo détaillée pour comprendre vos forces, faiblesses et progresser rapidement grâce à des feedbacks personnalisés.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas fa-graduation-cap"></i></div>
                        <h3 class="feature-title">SUIVI SCOLAIRE</h3>
                        <p>Nous accordons une importance capitale au suivi scolaire pour assurer un équilibre entre sport de haut niveau et études.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="events-section">
        <div class="container">
            <h2 class="section-heading">PROCHAINS ÉVÉNEMENTS</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="event-card">
                        <div class="event-image">
                            <img src="https://images.unsplash.com/photo-1560272564-c83b66b1ad12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2187&q=80" alt="Détection U15">
                        </div>
                        <div class="event-details">
                            <div class="event-date">15 Octobre 2023 | 09:00 - 17:00</div>
                            <h3 class="event-title">Détection U15</h3>
                            <p class="event-location"><i class="fas fa-map-marker-alt me-2"></i>Stade Michel Hidalgo, Paris</p>
                            <p>Journée de détection pour les joueurs nés en 2009. Places limitées à 50 participants.</p>
                            <button class="btn btn-register">S'inscrire</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="event-card">
                        <div class="event-image">
                              <img src="https://images.unsplash.com/photo-1434648957308-5e6a859697e8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80" alt="Stage de perfectionnement">
                          <img src="https://images.unsplash.com/photo-1710788989294-844321bf734d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8VE9VUk5PSSUyMEZPT1RCQUxMfGVufDB8fDB8fHww" alt="Stage de perfectionnement">
                        
                         </div>
                        <div class="event-details">
                            <div class="event-date">28-29 Octobre 2023 | Toute la journée</div>
                            <h3 class="event-title">Tournoi Elite Cup U17</h3>
                            <p class="event-location"><i class="fas fa-map-marker-alt me-2"></i>Complexe Sportif Jean Bouin, Lyon</p>
                            <p>Tournoi international avec la participation de clubs professionnels. Catégorie U17.</p>
                            <button class="btn btn-register">S'inscrire</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="event-card">
                        <div class="event-image">
                            <img src="https://images.unsplash.com/photo-1434648957308-5e6a859697e8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80" alt="Stage de perfectionnement">
                        </div>
                        <div class="event-details">
                            <div class="event-date">5-9 Novembre 2023 | 10:00 - 16:00</div>
                            <h3 class="event-title">Stage de Perfectionnement</h3>
                            <p class="event-location"><i class="fas fa-map-marker-alt me-2"></i>Centre d'Entraînement Académie Elite, Marseille</p>
                            <p>Stage intensif pour les U13-U15 avec entraîneurs professionnels. Pension complète disponible.</p>
                            <button class="btn btn-register">S'inscrire</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-heading">TÉMOIGNAGES</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">
                            <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80" alt="Thomas Dupont">
                        </div>
                        <div class="testimonial-quote">
                            L'Académie Elite a été un tremplin dans ma carrière. La qualité de la formation et les compétitions internationales m'ont permis d'être repéré par un club de Ligue 1.
                        </div>
                        <div class="testimonial-author">Thomas Dupont</div>
                        <div class="testimonial-role">Joueur Professionnel, FC Nantes</div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80" alt="Marc Laurent">
                        </div>
                        <div class="testimonial-quote">
                            En tant que parent, j'ai été impressionné par le professionnalisme et l'encadrement offerts par l'Académie. Mon fils a progressé techniquement et a gagné en maturité.
                        </div>
                        <div class="testimonial-author">Marc Laurent</div>
                        <div class="testimonial-role">Parent d'un joueur U17</div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="testimonial-card">
                        <div class="testimonial-avatar">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80" alt="Lucas Martin">
                        </div>
                        <div class="testimonial-quote">
                            Les détections organisées par l'Académie m'ont permis de me faire remarquer. Aujourd'hui, je vis de ma passion grâce à leur réseau et leur formation d'excellence.
                        </div>
                        <div class="testimonial-author">Lucas Martin</div>
                        <div class="testimonial-role">Ancien joueur, maintenant en Ligue 2</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-heading animate__animated animate__pulse animate__infinite animate__slower">PRÊT À REJOINDRE L'ÉLITE ?</h2>
            <p class="cta-subheading">Inscrivez-vous dès maintenant pour participer à nos prochaines détections et commencer votre parcours vers le football professionnel.</p>
            <button class="btn btn-primary btn-hero" data-bs-toggle="modal" data-bs-target="#registerModal">JE M'INSCRIS</button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="footer-logo">ACADÉMIE ELITE</div>
                    <p>L'académie de référence pour la détection et la formation des futures stars du football français.</p>
                    <div class="footer-contact">
                        <p><i class="fas fa-map-marker-alt me-2"></i> 123 Avenue du Football, 75001 Paris</p>
                        <p><i class="fas fa-phone me-2"></i> <a href="tel:+33123456789">+33 1 23 45 67 89</a></p>
                        <p><i class="fas fa-envelope me-2"></i> <a href="mailto:contact@academie-elite.fr">contact@academie-elite.fr</a></p>
                    </div>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-5">
                    <h4 class="footer-heading">L'ACADÉMIE</h4>
                    <ul class="footer-links">
                        <li><a href="#">À propos</a></li>
                        <li><a href="#">Notre équipe</a></li>
                        <li><a href="#">Installations</a></li>
                        <li><a href="#">Partenaires</a></li>
                        <li><a href="#">Actualités</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-5">
                    <h4 class="footer-heading">PROGRAMMES</h4>
                    <ul class="footer-links">
                        <li><a href="#">Détections</a></li>
                        <li><a href="#">Formation</a></li>
                        <li><a href="#">Tournois</a></li>
                        <li><a href="#">Stages</a></li>
                        <li><a href="#">Accompagnement</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <h4 class="footer-heading">NEWSLETTER</h4>
                    <p>Restez informés de nos prochains événements et actualités en vous inscrivant à notre newsletter.</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Votre email" aria-label="Votre email">
                        <button class="btn btn-primary" type="button">S'abonner</button>
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="newsletterAgree">
                        <label class="form-check-label" for="newsletterAgree">
                            J'accepte de recevoir des informations de l'Académie Elite
                        </label>
                    </div>
                </div>
            </div>
            <div class="row footer-bottom">
                <div class="col-md-6 text-center text-md-start">
                    <p>&copy; 2023 Académie Elite Football. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="me-3 text-white">Mentions légales</a>
                    <a href="#" class="me-3 text-white">Politique de confidentialité</a>
                    <a href="#" class="text-white">CGU</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">CONNEXION</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="loginEmail" placeholder="Votre email">
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="loginPassword" placeholder="Votre mot de passe">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
                        </div>
                        <button type="submit" class="btn btn-primary auth-btn">Se connecter</button>
                        
                        <div class="text-end mt-2">
                            <a href="#">Mot de passe oublié ?</a>
                        </div>
                        
                        <div class="auth-separator">
                            <span>ou</span>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-light auth-btn" type="button">
                                <i class="fab fa-google me-2"></i> Se connecter avec Google
                            </button>
                            <button class="btn btn-outline-light auth-btn" type="button">
                                <i class="fab fa-facebook-f me-2"></i> Se connecter avec Facebook
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p>Vous n'avez pas de compte ? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">S'inscrire</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">INSCRIPTION</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="firstName" placeholder="Prénom">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="lastName" placeholder="Nom">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="registerEmail" placeholder="votreemail@exemple.com">
                        </div>
                        <div class="mb-3">
                            <label for="birthDate" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control" id="birthDate">
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
                        <button type="submit" class="btn btn-primary auth-btn">Créer un compte</button>
                        
                        <div class="auth-separator">
                            <span>ou</span>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-light auth-btn" type="button">
                                <i class="fab fa-google me-2"></i> S'inscrire avec Google
                            </button>
                            <button class="btn btn-outline-light auth-btn" type="button">
                                <i class="fab fa-facebook-f me-2"></i> S'inscrire avec Facebook
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p>Vous avez déjà un compte ? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Se connecter</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Animation sur défilement
        document.addEventListener('DOMContentLoaded', function() {
            const scrollElements = document.querySelectorAll('.feature-card, .testimonial-card, .event-card');
            
            const elementInView = (el, scrollOffset = 0) => {
                const elementTop = el.getBoundingClientRect().top;
                return (elementTop <= (window.innerHeight || document.documentElement.clientHeight) * 0.8);
            };
            
            const displayScrollElement = (element) => {
                element.classList.add('animate__animated', 'animate__fadeInUp');
            };
            
            const handleScrollAnimation = () => {
                scrollElements.forEach((el) => {
                    if (elementInView(el, 100)) {
                        displayScrollElement(el);
                    }
                });
            };
            
            window.addEventListener('scroll', () => {
                handleScrollAnimation();
            });
            
            // Déclencher lors du chargement initial
            handleScrollAnimation();
        });
    </script>
</body>
</html>
