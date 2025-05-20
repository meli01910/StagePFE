<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentSpot - Détections et tournois de football simplifiés</title>
    <meta name="description" content="Plateforme professionnelle de gestion des détections, tournois et suivi des talents dans le football. Organisation simplifiée pour clubs et recruteurs.">
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
            padding: 0.8rem 1.8rem;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            gap: 0.5rem;
            border: none;
            outline: none;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(30, 136, 229, 0.3);
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(30, 136, 229, 0.4);
        }
        
        .btn-secondary {
            background: var(--secondary-color);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(255, 87, 34, 0.3);
        }
        
        .btn-secondary:hover {
            background: var(--secondary-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 87, 34, 0.4);
        }
        
        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-outline:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: translateY(-3px);
        }
        
        .section {
            padding: 5rem 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            font-size: 2.2rem;
            font-weight: 700;
        }
        
        .section-title span {
            color: var(--primary-color);
        }
        
        .text-center {
            text-align: center;
        }

        .text-highlight {
            color: var(--primary-color);
            font-weight: 600;
        }

        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }
        .mb-4 { margin-bottom: 2rem; }
        .mb-5 { margin-bottom: 3rem; }

        /* Header / Navigation */
        .navbar {
            padding: 1.2rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            background-color: transparent;
            z-index: 100;
            transition: var(--transition);
        }
        
        .navbar.scrolled {
            background-color: var(--white);
            box-shadow: var(--shadow);
            padding: 0.8rem 0;
        }
        
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }
        
        .scrolled .logo {
            color: var(--text-color);
        }

        .logo i {
            color: var(--secondary-color);
            font-size: 1.6rem;
        }
        
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-link {
            color: var(--white);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }

        .scrolled .nav-link {
            color: var(--text-color);
        }
        
        .nav-link:hover {
            color: var(--secondary-color);
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--secondary-color);
            transition: var(--transition);
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .nav-buttons {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-btn {
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
        }

        .hamburger {
            display: none;
            cursor: pointer;
            background: transparent;
            border: none;
            color: var(--white);
            font-size: 1.5rem;
        }

        .scrolled .hamburger {
            color: var(--text-color);
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=1936&auto=format&fit=crop') center/cover no-repeat;
            color: var(--white);
            padding-top: 5rem;
            overflow: hidden;
        }

        .hero::before {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 150px;
            background: linear-gradient(to top, var(--white), transparent);
            z-index: 1;
        }
        
        .hero-content {
            max-width: 700px;
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            max-width: 600px;
        }
        
        .hero-btns {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-top: 3rem;
        }

        .hero-stat {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hero-stat:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.2);
        }

        .hero-stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
            display: block;
        }

        .hero-stat-text {
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Features Section */
        .features {
            background-color: var(--white);
            position: relative;
            z-index: 2;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .feature-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            text-align: center;
            position: relative;
            overflow: hidden;
            z-index: 1;
            border: 1px solid var(--gray);
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--primary-color);
            transition: var(--transition);
            z-index: -1;
        }
        
        .feature-card:hover::before {
            width: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-dark);
        }
        
        .feature-card:hover .feature-icon,
        .feature-card:hover .feature-title,
        .feature-card:hover .feature-text {
            color: var(--white);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            transition: var(--transition);
        }
        
        .feature-text {
            transition: var(--transition);
            color: var(--gray-dark);
        }

        /* Product Showcase Section */
        .showcase {
            background-color: var(--gray-light);
            position: relative;
            overflow: hidden;
        }
        
        .showcase::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: var(--primary-color);
            opacity: 0.05;
            border-radius: 50%;
        }
        
        .showcase::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 200px;
            height: 200px;
            background: var(--secondary-color);
            opacity: 0.05;
            border-radius: 50%;
        }
        
        .showcase-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 4rem;
            flex-wrap: wrap;
        }
        
        .showcase-content {
            flex: 1;
            min-width: 300px;
        }
        
        .showcase-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .showcase-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--secondary-color);
        }
        
        .showcase-text {
            margin-bottom: 2rem;
            color: var(--gray-dark);
        }
        
        .feature-list {
            margin-bottom: 2rem;
        }
        
        .feature-item {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .feature-item i {
            color: var(--success-color);
            font-size: 1.2rem;
            background: rgba(67, 160, 71, 0.1);
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .showcase-image {
            flex: 1;
            min-width: 300px;
            position: relative;
        }
        
        .showcase-image img {
            width: 100%;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-dark);
            transform: perspective(1000px) rotateY(-10deg);
            transition: var(--transition);
            border: 10px solid var(--white);
        }
        
        .showcase-image img:hover {
            transform: perspective(1000px) rotateY(0);
        }

        .showcase-image::before {
            content: '';
            position: absolute;
            top: 20px;
            right: 20px;
            bottom: 20px;
            left: 20px;
            border: 2px dashed var(--primary-color);
            border-radius: var(--border-radius);
            opacity: 0.3;
            z-index: -1;
        }

        /* Testimonial Section */
        .testimonials {
            background-color: var(--white);
            position: relative;
        }

        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .testimonial-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            border: 1px solid var(--gray);
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-dark);
        }

        .testimonial-quote {
            font-size: 4rem;
            position: absolute;
            top: -20px;
            right: 20px;
            color: var(--gray);
            z-index: 0;
        }

        .testimonial-text {
            position: relative;
            z-index: 1;
            margin-bottom: 2rem;
            font-style: italic;
            color: var(--gray-dark);
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--primary-color);
        }

        .author-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .author-info h4 {
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .author-club {
            font-size: 0.9rem;
            color: var(--primary-color);
        }

        /* Partner Section */
        .partners {
            background-color: var(--gray-light);
            padding: 3rem 0;
        }

        .partners-title {
            margin-bottom: 2rem;
            font-size: 1.5rem;
        }

        .partners-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 2rem;
            align-items: center;
        }

        .partner-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            filter: grayscale(100%);
            opacity: 0.7;
            transition: var(--transition);
        }

        .partner-logo:hover {
            filter: grayscale(0);
            opacity: 1;
            transform: scale(1.05);
        }

        .partner-logo img {
            max-height: 60px;
            max-width: 100%;
        }

        /* CTA Section */
        .cta {
            padding: 5rem 0;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            top: -150px;
            right: -150px;
            border-radius: 50%;
        }

        .cta::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            bottom: -100px;
            left: -100px;
            border-radius: 50%;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-text {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
        }

        .cta-btn {
            background: var(--white);
            color: var(--primary-dark);
            font-size: 1.1rem;
            padding: 1rem 2rem;
        }

        .cta-btn:hover {
            background: var(--secondary-color);
            color: var(--white);
        }

        /* FAQ Section */
        .faq {
            background-color: var(--white);
        }

        .faq-grid {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
            border: 1px solid var(--gray);
        }

        .faq-question {
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: var(--text-color);
        }

        .faq-icon {
            font-size: 0.8rem;
            transition: var(--transition);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: var(--transition);
            background: var(--gray-light);
            color: var(--gray-dark);
        }

        .faq-answer-inner {
            padding: 0 1.5rem 1.5rem;
        }

        .faq-item.active .faq-question {
            color: var(--primary-color);
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-item.active .faq-answer {
            max-height: 200px;
        }

        /* Footer */
        .footer {
            background-color: var(--gray-dark);
            color: var(--gray);
            padding-top: 4rem;
        }

        .footer-top {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .footer-logo i {
            color: var(--secondary-color);
        }

        .footer-about {
            margin-bottom: 1.5rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: var(--transition);
            font-size: 1.2rem;
        }

        .social-link:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: translateY(-3px);
        }

        .footer-heading {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 1.5rem;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a:hover {
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .footer-links i {
            font-size: 0.8rem;
        }

        .footer-contact-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .footer-bottom {
            padding: 1.5rem 0;
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.5);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-delay-1 {
            animation-delay: 0.2s;
        }

        .animate-delay-2 {
            animation-delay: 0.4s;
        }

        .animate-delay-3 {
            animation-delay: 0.6s;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.8rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .showcase-title {
                font-size: 2.2rem;
            }
            
            .cta-title {
                font-size: 2.2rem;
            }
            
            .hero-stats {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .nav-menu {
                position: fixed;
                top: 0;
                right: -300px;
                width: 280px;
                height: 100vh;
                background: var(--white);
                flex-direction: column;
                justify-content: center;
                padding: 2rem;
                box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
                transition: var(--transition);
                z-index: 999;
            }
            
            .nav-menu.active {
                right: 0;
            }
            
            .nav-link {
                color: var(--text-color) !important;
            }
            
            .hamburger {
                display: block;
                z-index: 1000;
            }
            
            .hero-btns {
                flex-direction: column;
            }

            .showcase-flex {
                flex-direction: column;
                gap: 2rem;
            }

            .showcase-image {
                order: -1;
            }

            .showcase-image img {
                transform: none;
            }
        }

        @media (max-width: 576px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .section {
                padding: 3rem 0;
            }
            
            .section-title {
                font-size: 1.8rem;
                margin-bottom: 2rem;
            }
            
            .feature-card {
                padding: 1.5rem;
            }
            
            .feature-icon {
                font-size: 2.5rem;
            }
            
            .feature-title {
                font-size: 1.3rem;
            }
            
            .nav-buttons {
                gap: 0.5rem;
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
                TalentSpot
            </a>
            
            <ul class="nav-menu">
                <li><a href="#features" class="nav-link">Fonctionnalités</a></li>
                <li><a href="#showcase" class="nav-link">Plateforme</a></li>
                <li><a href="#testimonials" class="nav-link">Témoignages</a></li>
                <li><a href="#faq" class="nav-link">FAQ</a></li>
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
                    La plateforme <span class="text-highlight">professionnelle</span> pour vos détections de football
                </h1>
                <p class="hero-subtitle animate animate-delay-1">
                    Organisez facilement des tournois de détection, suivez les performances des joueurs et identifiez les meilleurs talents grâce à notre suite d'outils spécialisés.
                </p>
                <div class="hero-btns animate animate-delay-2">
                    <a href="register.php" class="btn btn-secondary">
                        <i class="fas fa-user-plus"></i>
                        Créer un compte gratuit
                    </a>
                    <a href="#showcase" class="btn btn-outline">
                        <i class="fas fa-play-circle"></i>
                        Découvrir la plateforme
                    </a>
                </div>
                
                <div class="hero-stats animate animate-delay-3">
                    <div class="hero-stat
                    <div class="hero-stat">
                        <span class="hero-stat-number">500+</span>
                        <span class="hero-stat-text">Détections organisées</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-number">10,000+</span>
                        <span class="hero-stat-text">Joueurs évalués</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-number">200+</span>
                        <span class="hero-stat-text">Clubs utilisateurs</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="section features" id="features">
        <div class="container">
            <h2 class="section-title">Nos <span>Fonctionnalités</span></h2>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="feature-title">Organisation simplifiée</h3>
                    <p class="feature-text">
                        Planifiez et gérez vos sessions de détection en quelques clics. Invitez les participants et envoyez des rappels automatiques.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Suivi des performances</h3>
                    <p class="feature-text">
                        Évaluez les joueurs selon différents critères techniques et physiques. Générez des rapports détaillés et visualisez leur progression.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Base de données talents</h3>
                    <p class="feature-text">
                        Créez votre vivier de talents avec profils complets, historique des évaluations et vidéos. Recherche avancée par compétences.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3 class="feature-title">Tournois et compétitions</h3>
                    <p class="feature-text">
                        Organisez des tournois avec tableaux automatisés, suivi des scores en direct et classements mis à jour en temps réel.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-title">Application mobile</h3>
                    <p class="feature-text">
                        Accédez à toutes les fonctionnalités sur le terrain via notre application mobile. Évaluez les joueurs en temps réel.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="feature-title">Rapports professionnels</h3>
                    <p class="feature-text">
                        Générez des rapports personnalisés pour vos dirigeants, joueurs ou parents. Exportez en PDF ou partagez directement.
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Product Showcase -->
    <section class="section showcase" id="showcase">
        <div class="container">
            <div class="showcase-flex">
                <div class="showcase-content">
                    <h2 class="showcase-title">Une plateforme conçue pour les professionnels du football</h2>
                    <p class="showcase-text">
                        Notre solution complète s'adapte à tous vos besoins, que vous soyez entraîneur, recruteur, directeur technique ou responsable de formation. Prenez des décisions éclairées grâce à des données précises et facilement accessibles.
                    </p>
                    
                    <ul class="feature-list">
                        <li class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Interface intuitive et adaptée au terrain</span>
                        </li>
                        <li class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Statistiques avancées et visualisations</span>
                        </li>
                        <li class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Collaboration entre plusieurs évaluateurs</span>
                        </li>
                        <li class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Compatible avec tous les appareils</span>
                        </li>
                    </ul>
                    
                    <a href="register.php" class="btn btn-primary">Essayer gratuitement</a>
                </div>
                
                <div class="showcase-image">
                    <img src="https://i.ibb.co/Pttt1XJ/dashboard-football-detection.jpg" alt="Interface TalentSpot">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Testimonial Section -->
    <section class="section testimonials" id="testimonials">
        <div class="container">
            <h2 class="section-title">Ce qu'ils <span>pensent de nous</span></h2>
            
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <p class="testimonial-text">
                        TalentSpot a révolutionné notre processus de détection. Nous pouvons maintenant traiter trois fois plus de candidats avec une équipe réduite et identifier les talents avec une précision remarquable.
                    </p>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Thomas Laurent">
                        </div>
                        <div class="author-info">
                            <h4>Thomas Laurent</h4>
                            <p class="author-club">Directeur du centre de formation, FC Olympique</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <p class="testimonial-text">
                        La qualité des données et des analyses nous permet d'avoir une vision précise de chaque joueur. L'interface est intuitive même pour les membres moins technophiles de notre équipe.
                    </p>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sophie Moreau">
                        </div>
                        <div class="author-info">
                            <h4>Sophie Moreau</h4>
                            <p class="author-club">Responsable recrutement, AS Monaco Academy</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-quote">
                        <i class="fas fa-quote-right"></i>
                    </div>
                    <p class="testimonial-text">
                        Nous utilisons TalentSpot pour tous nos tournois de détection internationaux. La possibilité de collaborer à distance avec d'autres recruteurs est un vrai plus pour notre réseau global.
                    </p>
                    <div class="testimonial-author">
                        <div class="author-image">
                            <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Marc Dubois">
                        </div>
                        <div class="author-info">
                            <h4>Marc Dubois</h4>
                            <p class="author-club">Scout international, Paris Saint-Germain</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Partners Section -->
    <section class="partners">
        <div class="container">
            <h3 class="partners-title text-center">Ils nous font confiance</h3>
            
            <div class="partners-grid">
                <div class="partner-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/fr/thumb/9/9b/Logo_Olympique_Lyonnais_2022.svg/240px-Logo_Olympique_Lyonnais_2022.svg.png" alt="Olympique Lyonnais">
                </div>
                <div class="partner-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/fr/thumb/a/a1/Logo_FC_Nantes.svg/240px-Logo_FC_Nantes.svg.png" alt="FC Nantes">
                </div>
                <div class="partner-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/fr/thumb/4/43/Logo_Stade_Rennais_FC.svg/240px-Logo_Stade_Rennais_FC.svg.png" alt="Stade Rennais">
                </div>
                <div class="partner-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/fr/thumb/1/1f/Logo_RC_Strasbourg_Alsace_2016.svg/240px-Logo_RC_Strasbourg_Alsace_2016.svg.png" alt="RC Strasbourg">
                </div>
                <div class="partner-logo">
                    <img src="https://upload.wikimedia.org/wikipedia/fr/thumb/c/cc/Logo_Toulouse_FC_2018.svg/240px-Logo_Toulouse_FC_2018.svg.png" alt="Toulouse FC">
                </div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2 class="cta-title">Prêt à révolutionner vos détections ?</h2>
            <p class="cta-text">
                Rejoignez plus de 200 clubs qui font confiance à TalentSpot pour identifier et développer les champions de demain.
            </p>
            <a href="register.php" class="btn cta-btn">
                <i class="fas fa-rocket"></i>
                Commencer maintenant
            </a>
        </div>
    </section>
    
    <!-- FAQ Section -->
    <section class="section faq" id="faq">
        <div class="container">
            <h2 class="section-title">Questions <span>fréquentes</span></h2>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Comment débuter avec TalentSpot ?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            Inscrivez-vous gratuitement pour créer votre compte. Vous pourrez immédiatement créer votre première détection, inviter vos collaborateurs et commencer à utiliser les fonctionnalités de base. Notre équipe peut vous proposer une démonstration personnalisée.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Quels sont les différents forfaits disponibles ?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            Nous proposons trois forfaits : Starter (gratuit), Pro (29€/mois) et Elite (99€/mois). Chaque forfait offre des fonctionnalités adaptées à différents besoins, de la simple organisation de détections ponctuelles à un système complet de gestion des talents pour les grands clubs.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Peut-on utiliser TalentSpot sans connexion internet ?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            Oui, notre application mobile permet de travailler hors connexion. Toutes les données sont synchronisées automatiquement une fois la connexion rétablie, ce qui est idéal pour les détections sur des terrains sans couverture réseau.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Comment sont sécurisées les données des joueurs ?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            La protection des données est notre priorité. Toutes les informations sont cryptées et stockées selon les normes RGPD. Nous n'utilisons jamais les données à des fins commerciales et vous en gardez l'entière propriété.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Est-il possible de personnaliser les critères d'évaluation ?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            Absolument ! Vous pouvez créer vos propres grilles d'évaluation avec des critères spécifiques à votre philosophie de jeu. Créez autant de modèles que nécessaire pour différentes catégories d'âge ou postes.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-top">
                <div class="footer-column">
                    <a href="#" class="footer-logo">
                        <i class="fas fa-futbol"></i>
                        TalentSpot
                    </a>
                    <p class="footer-about">
                        TalentSpot révolutionne la détection de talents dans le football grâce à des outils innovants pour les clubs, académies et recruteurs professionnels.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3 class="footer-heading">Liens rapides</h3>
                    <ul class="footer-links">
                        <li><a href="#features"><i class="fas fa-angle-right"></i> Fonctionnalités</a></li>
                        <li><a href="#showcase"><i class="fas fa-angle-right"></i> Plateforme</a></li>
                        <li><a href="#testimonials"><i class="fas fa-angle-right"></i> Témoignages</a></li>
                        <li><a href="#faq"><i class="fas fa-angle-right"></i> FAQ</a></li>
                        <li><a href="login.php"><i class="fas fa-angle-right"></i> Connexion</a></li>
                        <li><a href="register.php"><i class="fas fa-angle-right"></i> Inscription</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3 class="footer-heading">Ressources</h3>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-angle-right"></i> Centre d'aide</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Blog</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Tutoriels vidéo</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> Webinaires</a></li>
                        <li><a href="#"><i class="fas fa-angle-right"></i> API Documentation</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3 class="footer-heading">Contact</h3>
                    <div class="footer-contact">
                        <div class="footer-contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <p>123 Avenue du Football,<br>75001 Paris, France</p>
                            </div>
                        </div>
                        
                        <div class="footer-contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <p>+33 1 23 45 67 89</p>
                            </div>
                        </div>
                        
                        <div class="footer-contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <p>contact@talentspot.fr</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2023 TalentSpot. Tous droits réservés. | <a href="#">Mentions légales</a> | <a href="#">Politique de confidentialité</a></p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Navbar scroll effect
        window.addEventListener('DOMContentLoaded', () => {
            const navbar = document.querySelector('.navbar');
            
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
            
            // Hamburger menu
            const hamburger = document.querySelector('.hamburger');
            const navMenu = document.querySelector('.nav-menu');
            
            hamburger.addEventListener('click', () => {
                navMenu.classList.toggle('active');
                
                // Change icon based on menu state
                const icon = hamburger.querySelector('i');
                if (navMenu.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
            
            // Close menu when clicking a nav link
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    navMenu.classList.remove('active');
                    hamburger.querySelector('i').classList.remove('fa-times');
                    hamburger.querySelector('i').classList.add('fa-bars');
                });
            });
            
            // Smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    const target = document.querySelector(this.getAttribute('href'));
                    
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // FAQ accordion
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                
                question.addEventListener('click', () => {
                    item.classList.toggle('active');
                });
            });
            
            // Intersection Observer for animation
            const observerOptions = {
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px"
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate');
                    }
                });
            }, observerOptions);
            
            document.querySelectorAll('.feature-card, .testimonial-card, .showcase-content, .showcase-image').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
