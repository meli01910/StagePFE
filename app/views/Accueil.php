<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FootDetect - Trouve ta prochaine détection de football</title>
    <meta name="description" content="Plateforme pour joueurs de football: inscris-toi aux détections, tournois et matchs près de chez toi. Fais-toi repérer par les recruteurs et clubs professionnels.">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e88e5;
            --primary-dark: #1976d2;
            --secondary-color: #ff5722;
            --secondary-dark: #e64a19;
            --success-color: #43a047;
            --gray-light: #f8f9fa;
            --gray: #e9ecef;
            --gray-dark: #343a40;
            --text-color: #212529;
            --white: #ffffff;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            --shadow-dark: 0 10px 30px rgba(0, 0, 0, 0.15);
            --border-radius: 10px;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        ul {
            list-style: none;
        }

        img {
            max-width: 100%;
        }
        
        /* Utilities */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: var(--border-radius);
            transition: var(--transition);
            cursor: pointer;
            border: none;
            font-size: 1rem;
        }
        
        .btn i {
            margin-right: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            box-shadow: var(--shadow);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: var(--white);
        }
        
        .btn-secondary:hover {
            background-color: var(--secondary-dark);
            box-shadow: var(--shadow);
        }
        
        .btn-outline {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: var(--primary-color);
            color: var(--white);
            box-shadow: var(--shadow);
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-highlight {
            color: var(--secondary-color);
            font-weight: 700;
        }
        
        .section {
            padding: 5rem 0;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title span {
            color: var(--primary-color);
        }
        
        /* Animations */
        [class*="animate"] {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }
        
        .animate {
            opacity: 1;
            transform: translateY(0);
        }
        
        .animate-delay-1 {
            transition-delay: 0.2s;
        }
        
        .animate-delay-2 {
            transition-delay: 0.4s;
        }
        
        .animate-delay-3 {
            transition-delay: 0.6s;
        }
        
        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            background-color: transparent;
            transition: var(--transition);
            padding: 1.5rem 0;
        }
        
        .navbar.scrolled {
            background-color: var(--white);
            box-shadow: var(--shadow);
            padding: 1rem 0;
        }
        
        .nav-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .logo i {
            font-size: 1.75rem;
            margin-right: 0.5rem;
        }
        
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .nav-link {
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }
        
        .nav-link:hover {
            color: var(--primary-color);
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: var(--transition);
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .nav-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-color);
        }
        
        /* Hero Section */
        .hero {
            position: relative;
            min-height: 100vh;
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1508098682722-e99c643e7f0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            padding-top: 5rem;
        }
        
        .hero-content {
            color: var(--white);
            max-width: 700px;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .hero-btns {
            display: flex;
            gap: 1rem;
            margin-bottom: 3rem;
        }
        
        .hero-stats {
            display: flex;
            gap: 2.5rem;
        }
        
        .hero-stat {
            display: flex;
            flex-direction: column;
        }
        
        .hero-stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary-color);
        }
        
        .hero-stat-text {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Upcoming Detections */
        .upcoming-detections {
            background-color: var(--gray-light);
        }
        
        .detections-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .detections-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0;
        }
        
        .detection-card-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .detection-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        
        .detection-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-dark);
        }
        
        .detection-img {
            height: 180px;
            width: 100%;
            object-fit: cover;
        }
        
        .detection-content {
            padding: 1.5rem;
        }
        
        .detection-date {
            display: flex;
            align-items: center;
            color: var(--primary-color);
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .detection-date i {
            margin-right: 0.5rem;
        }
        
        .detection-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .detection-club {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        
        .detection-club img {
            width: 24px;
            height: 24px;
            margin-right: 0.5rem;
        }
        
        .detection-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid var(--gray);
        }
        
        .detection-location {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }
        
        .detection-location i {
            margin-right: 0.5rem;
            color: var(--gray-dark);
        }
        
        .detection-category {
            background-color: var(--gray);
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        /* How It Works */
        .works-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .steps-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            width: 100%;
            margin-bottom: 3rem;
        }
        
        .step-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background-color: var(--white);
            padding: 2rem 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
        }
        
        .step-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-dark);
        }
        
        .step-card::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -1.5rem;
            width: 1rem;
            height: 1rem;
            border-top: 2px solid var(--primary-color);
            border-right: 2px solid var(--primary-color);
            transform: translateY(-50%) rotate(45deg);
            z-index: 10;
        }
        
        .step-card:last-child::after {
            display: none;
        }
        
        .step-number {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-color);
            color: var(--white);
            border-radius: 50%;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .step-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        
        .step-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .step-text {
            font-size: 0.9rem;
            color: var(--gray-dark);
        }
        
        /* Success Stories */
        .success-stories {
            background-color: var(--gray-light);
        }
        
        .story-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }
        
        .story-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            display: flex;
            transition: var(--transition);
        }
        
        .story-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-dark);
        }
        
        .story-image {
            width: 40%;
        }
        
        .story-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .story-content {
            width: 60%;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .story-quote {
            font-size: 1.25rem;
            font-style: italic;
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .story-quote::before {
            content: '"';
            font-size: 3rem;
            color: var(--primary-color);
            opacity: 0.2;
            position: absolute;
            top: -1rem;
            left: -1rem;
        }
        
        .story-player {
            display: flex;
            align-items: center;
        }
        
        .story-player-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 1rem;
            overflow: hidden;
        }
        
        .story-player-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .story-player-info h3 {
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .story-player-club {
            display: flex;
            align-items: center;
            color: var(--gray-dark);
            font-size: 0.9rem;
        }
        
        .story-player-club img {
            width: 20px;
            height: 20px;
            margin-right: 0.5rem;
        }
        
        /* CTA Section */
        .cta {
            background-color: var(--primary-color);
            color: var(--white);
            text-align: center;
            padding: 5rem 0;
        }
        
        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .cta-text {
            font-size: 1.25rem;
            max-width: 700px;
            margin: 0 auto 2.5rem;
            opacity: 0.9;
        }
        
        .cta-btn {
            background-color: var(--white);
            color: var(--primary-color);
            font-size: 1.1rem;
            padding: 1rem 2rem;
            font-weight: 600;
        }
        
        .cta-btn:hover {
            background-color: var(--secondary-color);
            color: var(--white);
        }
        
        /* Footer */
        .footer {
            background-color: var(--gray-dark);
            color: var(--white);
            padding: 5rem 0 2rem;
        }
        
        .footer-top {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 1.5rem;
        }
        
        .footer-logo i {
            font-size: 1.75rem;
            margin-right: 0.5rem;
        }
        
        .footer-about {
            font-size: 0.9rem;
            opacity: 0.7;
            margin-bottom: 1.5rem;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
        }
        
        .social-link {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: var(--transition);
        }
        
        .social-link:hover {
            background-color: var(--primary-color);
        }
        
        .footer-heading {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        
        .footer-links a {
            opacity: 0.7;
            transition: var(--transition);
        }
        
        .footer-links a:hover {
            opacity: 1;
            color: var(--primary-color);
        }
        
        .footer-links i {
            margin-right: 0.5rem;
            font-size: 0.8rem;
        }
        
        .footer-contact-item {
            display: flex;
            margin-bottom: 1rem;
        }
        
        .contact-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            margin-right: 1rem;
        }
        
        .footer-contact-item p {
            opacity: 0.7;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            opacity: 0.7;
        }
        
        .footer-bottom a {
            transition: var(--transition);
        }
        
        .footer-bottom a:hover {
            color: var(--primary-color);
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .detection-card-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .steps-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .step-card::after {
                display: none;
            }
            
            .story-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-top {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .nav-menu {
                position: fixed;
                right: -100%;
                top: 0;
                flex-direction: column;
                background-color: var(--white);
                width: 70%;
                height: 100vh;
                z-index: 999;
                padding-top: 6rem;
                transition: 0.3s;
                box-shadow: var(--shadow-dark);
                align-items: flex-start;
                padding-left: 2rem;
            }
            
            .nav-menu.active {
                right: 0;
            }
            
            .hamburger {
                display: block;
                z-index: 9999;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-btns {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .hero-stats {
                flex-direction: column;
                gap: 1.5rem;
            }
            
            .detection-card-grid {
                grid-template-columns: 1fr;
            }
            
            .story-card {
                flex-direction: column;
            }
            
            .story-image {
                width: 100%;
                height: 200px;
            }
            
            .story-content {
                width: 100%;
            }
            
            .nav-btn {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }
            
            .showcase-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="#" class="logo">
                <i class="fas fa-futbol"></i>
                FootDetect
            </a>
            
            <ul class="nav-menu">
                <li><a href="#detections" class="nav-link">Détections</a></li>
                <li><a href="#how-it-works" class="nav-link">Comment ça marche</a></li>
                <li><a href="#success-stories" class="nav-link">Réussites</a></li>
                <li><a href="#contact" class="nav-link">Contact</a></li>
            </ul>
            
            <div class="nav-buttons">
                <a href="login.php" class="btn btn-outline nav-btn">Connexion</a>
                <a href="register.php" class="btn btn-primary nav-btn">Inscription</a>
            </div>
            
            <button class="hamburger">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title animate">
                    Trouve ta prochaine <span class="text-highlight">détection de football</span> et montre ton talent
                </h1>
                <p class="hero-subtitle animate animate-delay-1">
                    Rejoins les milliers de joueurs qui ont déjà trouvé leur opportunité grâce à notre plateforme. Inscris-toi aux détections, tournois et matchs partout en France.
                </p>
                <div class="hero-btns animate animate-delay-2">
                    <a href="register.php" class="btn btn-secondary">
                        <i class="fas fa-user-plus"></i>
                        Créer mon profil joueur
                    </a>
                    <a href="#detections" class="btn btn-outline">
                        <i class="fas fa-search"></i>
                        Voir les prochaines détections
                    </a>
                </div>
                
                <div class="hero-stats animate animate-delay-3">
                    <div class="hero-stat">
                        <span class="hero-stat-number">500+</span>
                        <span class="hero-stat-text">Détections par an</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-number">150+</span>
                        <span class="hero-stat-text">Clubs partenaires</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-number">5,000+</span>
                        <span class="hero-stat-text">Joueurs sélectionnés</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Upcoming Detections -->
    <section class="section upcoming-detections" id="detections">
        <div class="container">
            <div class="detections-header">
                <h2 class="detections-title">Prochaines détections</h2>
                <a href="detections.php" class="btn btn-outline">Voir toutes les détections</a>
            </div>
            
            <div class="detection-card-grid">
                <div class="detection-card">
                    <img src="https://images.unsplash.com/photo-1517466787929-bc90951d0974?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Détection FC Lyon" class="detection-img">
                    <div class="detection-content">
                        <div class="detection-date">
                            <i class="far fa-calendar-alt"></i> 15 novembre 2023
                        </div>
                        <h3 class="detection-title">Détection Espoirs U17-U19</h3>
                        <div class="detection-club">
                            <img src="https://upload.wikimedia.org/wikipedia/fr/thumb/e/e2/Logo_Olympique_Lyonnais_-_2022.svg/240px-Logo_Olympique_Lyonnais_-_2022.svg.png" alt="FC Lyon">
                            Olympique Lyonnais
                        </div>
                        <p>Détection officielle pour rejoindre les équipes Espoirs U17-U19 de l'académie de l'OL.</p>
                        <div class="detection-meta">
                            <div class="detection-location">
                                <i class="fas fa-map-marker-alt"></i> Lyon
                            </div>
                            <span class="detection-category">U17-U19</span>
                        </div>
                    </div>
                </div>
                
                <div class="detection-card">
                    <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Tournoi National de Paris" class="detection-img">
                    <div class="detection-content">
                        <div class="detection-date">
                            <i class="far fa-calendar-alt"></i> 22-23 novembre 2023
                        </div>
                        <h3 class="detection-title">Tournoi National de Paris</h3>
                        <div class="detection-club">
                            <img src="https://upload.wikimedia.org/wikipedia/fr/thumb/4/4a/Paris_Saint-Germain_Football_Club.svg/240px-Paris_Saint-Germain_Football_Club.svg.png" alt="PSG">
                            Paris Saint-Germain
                        </div>
                        <p>Tournoi de détection avec la présence de recruteurs de clubs professionnels de Ligue 1 et Ligue 2.</p>
                        <div class="detection-meta">
                            <div class="detection-location">
                                <i class="fas fa-map-marker-alt"></i> Paris
                            </div>
                            <span class="detection-category">U15-U17</span>
                        </div>
                    </div>
                </div>
                
                <div class="detection-card">
                    <img src="https://images.unsplash.com/photo-1555862124-94036315bee2?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Détection Gardiens de but" class="detection-img">
                    <div class="detection-content">
                        <div class="detection-date">
                            <i class="far fa-calendar-alt"></i> 5 décembre 2023
                        </div>
                        <h3 class="detection-title">Spécial Gardiens de but</h3>
                        <div class="detection-club">
                            <img src="https://upload.wikimedia.org/wikipedia/fr/thumb/2/2e/Logo_AS_Monaco_FC_-_2021.svg/240px-Logo_AS_Monaco_FC_-_2021.svg.png" alt="AS Monaco">
                            AS Monaco
                        </div>
                        <p>Session spéciale pour les gardiens de but de tous niveaux avec des entraîneurs spécialisés.</p>
                        <div class="detection-meta">
                            <div class="detection-location">
                                <i class="fas fa-map-marker-alt"></i> Monaco
                            </div>
                            <span class="detection-category">U15-U21</span>
                        </div>
                    
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <a href="register.php" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Crée ton profil pour postuler
                </a>
            </div>
        </div>
    </section>
    
    <!-- How It Works -->
    <section class="section" id="how-it-works">
        <div class="container works-container">
            <h2 class="section-title">Comment ça <span>fonctionne</span>?</h2>
            
            <div class="steps-container">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3 class="step-title">Inscris-toi</h3>
                    <p class="step-text">Crée ton profil joueur complet avec tes stats, vidéos et informations</p>
                </div>
                
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="step-title">Trouve des détections</h3>
                    <p class="step-text">Explore les prochaines détections et tournois près de chez toi</p>
                </div>
                
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <h3 class="step-title">Postule facilement</h3>
                    <p class="step-text">Inscris-toi aux détections en quelques clics depuis ton espace joueur</p>
                </div>
                
                <div class="step-card">
                    <div class="step-number">4</div>
                    <div class="step-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="step-title">Montre ton talent</h3>
                    <p class="step-text">Participe à l'événement et suis ton évaluation après la détection</p>
                </div>
            </div>
            
            <a href="register.php" class="btn btn-primary">
                <i class="fas fa-rocket"></i> Commencer maintenant
            </a>
        </div>
    </section>
    
    <!-- Success Stories -->
    <section class="section success-stories" id="success-stories">
        <div class="container">
            <h2 class="section-title">Histoires de <span>réussite</span></h2>
            
            <div class="story-grid">
                <div class="story-card">
                    <div class="story-image">
                        <img src="https://images.unsplash.com/photo-1557053506-9afa64659008?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Mathis Durant">
                    </div>
                    <div class="story-content">
                        <p class="story-quote">
                            Grâce à FootDetect, j'ai participé à une détection qui a changé ma vie. Aujourd'hui, je joue en équipe réserve d'un club de Ligue 1!
                        </p>
                        <div class="story-player">
                            <div class="story-player-image">
                                <img src="https://images.unsplash.com/photo-1539701938214-0d9736e1c16b?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Mathis Durant">
                            </div>
                            <div class="story-player-info">
                                <h3>Mathis Durant, 19 ans</h3>
                                <div class="story-player-club">
                                    <img src="https://upload.wikimedia.org/wikipedia/fr/thumb/2/2e/Logo_AS_Monaco_FC_-_2021.svg/240px-Logo_AS_Monaco_FC_-_2021.svg.png" alt="AS Monaco">
                                    AS Monaco Réserve
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="story-card">
                    <div class="story-image">
                        <img src="https://images.unsplash.com/photo-1518631031446-c2b74e195d16?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Sofia Laurent">
                    </div>
                    <div class="story-content">
                        <p class="story-quote">
                            La plateforme m'a permis de m'inscrire à plusieurs détections féminines et de finalement décrocher une place au centre de formation!
                        </p>
                        <div class="story-player">
                            <div class="story-player-image">
                                <img src="https://images.unsplash.com/photo-1566753323558-f4e0952af115?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Sofia Laurent">
                            </div>
                            <div class="story-player-info">
                                <h3>Sofia Laurent, 18 ans</h3>
                                <div class="story-player-club">
                                    <img src="https://upload.wikimedia.org/wikipedia/fr/thumb/c/c7/Logo_Olympique_de_Marseille.svg/240px-Logo_Olympique_de_Marseille.svg.png" alt="OM Féminin">
                                    OM Féminin
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2 class="cta-title">Prêt à montrer ton talent?</h2>
            <p class="cta-text">Rejoins des milliers de joueurs qui ont déjà trouvé leur opportunité grâce à notre plateforme. L'inscription est gratuite et prend moins de 5 minutes.</p>
            <a href="register.php" class="btn cta-btn">
                <i class="fas fa-user-plus"></i> Créer mon compte joueur
            </a>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-top">
                <div>
                    <a href="#" class="footer-logo">
                        <i class="fas fa-futbol"></i>
                        FootDetect
                    </a>
                    <p class="footer-about">
                        La plateforme qui connecte les joueurs avec les détections et tournois près de chez eux. Nous aidons les joueurs à trouver leur opportunité de briller.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div>
                    <h3 class="footer-heading">Liens rapides</h3>
                    <ul class="footer-links">
                        <li><a href="detections.php"><i class="fas fa-angle-right"></i> Toutes les détections</a></li>
                        <li><a href="tournaments.php"><i class="fas fa-angle-right"></i> Tournois</a></li>
                        <li><a href="matches.php"><i class="fas fa-angle-right"></i> Matchs amicaux</a></li>
                        <li><a href="login.php"><i class="fas fa-angle-right"></i> Connexion</a></li>
                        <li><a href="register.php"><i class="fas fa-angle-right"></i> Inscription</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="footer-heading">Informations</h3>
                    <ul class="footer-links">
                        <li><a href="about.php"><i class="fas fa-angle-right"></i> À propos de nous</a></li>
                        <li><a href="faq.php"><i class="fas fa-angle-right"></i> FAQ</a></li>
                        <li><a href="privacy.php"><i class="fas fa-angle-right"></i> Politique de confidentialité</a></li>
                        <li><a href="terms.php"><i class="fas fa-angle-right"></i> Conditions d'utilisation</a></li>
                        <li><a href="contact.php"><i class="fas fa-angle-right"></i> Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="footer-heading">Contact</h3>
                    <div class="footer-contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <p>123 Avenue du Football, 75000 Paris, France</p>
                    </div>
                    <div class="footer-contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <p>+33 1 23 45 67 89</p>
                    </div>
                    <div class="footer-contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <p>contact@footdetect.fr</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2023 FootDetect - Tous droits réservés | Conçu par <a href="#">VotreNom</a></p>
            </div>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation active on scroll
            const navbar = document.querySelector('.navbar');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
            
            // Mobile Menu
            const hamburger = document.querySelector('.hamburger');
            const navMenu = document.querySelector('.nav-menu');
            
            hamburger.addEventListener('click', () => {
                navMenu.classList.toggle('active');
                hamburger.innerHTML = navMenu.classList.contains('active') ? 
                    '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
            });
            
            // Smooth scroll
            const navLinks = document.querySelectorAll('.nav-link, .logo');
            
            navLinks.forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    const target = document.querySelector(this.getAttribute('href'));
                    
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 80,
                            behavior: 'smooth'
                        });
                        
                        // Close mobile menu if open
                        if (navMenu.classList.contains('active')) {
                            navMenu.classList.remove('active');
                            hamburger.innerHTML = '<i class="fas fa-bars"></i>';
                        }
                    }
                });
            });
            
            // Animation on scroll
            const observerOptions = {
                threshold: 0.25,
                rootMargin: "0px 0px -50px 0px"
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate');
                    }
                });
            }, observerOptions);
            
            document.querySelectorAll('.feature-card, .detection-card, .step-card, .story-card').forEach(el => {
                observer.observe(el);
            });
            
            // Animation on load for hero section
            const heroElements = document.querySelectorAll('.hero [class*="animate"]');
            setTimeout(() => {
                heroElements.forEach(el => {
                    el.classList.add('animate');
                });
            }, 100);
        });
    </script>
</body>
</html>
