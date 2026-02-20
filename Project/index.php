<?php
session_start();
// Note: The reference code redirects if not logged in on the main page.
// If you want redirection, uncomment the following block:

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

?>
<!DOCTYPE html>
<!-- JS will toggle 'dark' class -->
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Updated Title and Meta -->
    <title>Incredible India - Your AI Powered Travel Planner</title>
    <meta name="description" content="Explore Incredible India with our AI-powered trip planner. Find personalized deals, verified stays, and plan your dream vacation seamlessly.">
    <meta name="keywords" content="India tourism, AI travel planner, India trips, Incredible India, travel deals, Rajasthan tour, Kerala backwaters, Ladakh adventure, <?php echo isset($_SESSION['user_id']) ? 'dashboard, profile, bookings' : 'login, signup'; ?>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500&display=swap">

    <!-- AOS Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <!-- Swiper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.css">

    <style>
        /* --- Styles Copied & Adapted from Reference --- */
        :root {
            /* Palette */
            --color-sunset-orange: #FF6B3D;
            --color-indigo-blue: #3F51B5;
            /* --color-sand-beige: #F8F4E3; */ /* Original beige */
            --color-sand-beige: #f1f5f9; /* Changed to slate-100 for better contrast */
            --color-himalaya-gray: #E0E0E0;
            --color-forest-green: #388E3C;
            --color-text-light: #e2e8f0; /* slate-200 */
            --color-text-dark: #334155; /* slate-700 */
            --color-heading-dark: #1e293b; /* slate-800 */
            --color-heading-light: #cbd5e1; /* slate-300 */
            /* --color-bg-light: #f8fafc; */ /* Original light bg (slate-50) */
            --color-bg-light: #ffffff; /* Changed to pure white */
            --color-bg-dark: #0f172a; /* slate-900 */
            --color-card-light: #ffffff; /* White cards */
            --color-card-dark: #1e293b; /* slate-800 for dark cards */
            --color-navbar-bg-light: rgba(255, 255, 255, 0.9); /* White scrolled */
            --color-navbar-bg-dark: rgba(30, 41, 59, 0.9); /* slate-800 scrolled */
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            transition: background-color 0.4s ease, color 0.4s ease;
            background-color: var(--color-bg-light); /* Body starts white */
            color: var(--color-text-dark);
        }

        /* --- Basic Dark Mode Setup (Add toggle later if needed) --- */
        /* .dark body {
            background-color: var(--color-bg-dark);
            color: var(--color-text-light);
        } */
        /* --- End Basic Dark Mode Setup --- */

        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }

        /* Palette Utilities */
        .bg-indigo-primary { background-color: var(--color-indigo-blue); }
        .bg-orange-accent { background-color: var(--color-sunset-orange); }
        .text-indigo-primary { color: var(--color-indigo-blue); }
        .text-orange-accent { color: var(--color-sunset-orange); }
        .border-indigo-primary { border-color: var(--color-indigo-blue); }
        .border-orange-accent { border-color: var(--color-sunset-orange); }
        .hover\:text-orange-accent:hover { color: var(--color-sunset-orange); }
        .hover\:text-indigo-primary:hover { color: var(--color-indigo-blue); }
        .hover\:bg-indigo-primary-darker:hover { background-color: #303F9F; } /* Darker Indigo */
        .hover\:bg-orange-accent-darker:hover { background-color: #F57C00; } /* Darker Orange */
        /* Add dark mode versions if implementing dark mode */

        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: background 0.4s ease, border 0.4s ease;
        }
        /* Add dark mode glass card styles if implementing dark mode */

        .text-shadow { text-shadow: 0px 2px 5px rgba(0, 0, 0, 0.4); }

        /* Scroll indicator */
        .scroll-indicator { animation: scrollBounce 2s infinite; }
        @keyframes scrollBounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        /* Neon glow for Orange buttons */
        .neon-button-orange {
            background-color: var(--color-sunset-orange);
            box-shadow: 0 0 6px rgba(255, 107, 61, 0.6), 0 0 12px rgba(255, 107, 61, 0.4);
            transition: all 0.3s ease;
        }
        .neon-button-orange:hover {
            box-shadow: 0 0 10px rgba(255, 107, 61, 0.8), 0 0 20px rgba(255, 107, 61, 0.6);
            transform: translateY(-1px);
        }

        /* Basic Transition */
        .dark-mode-transition { transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease, fill 0.4s ease; }

        

        /* Pulse hover */
        .pulse-hover:hover { animation: pulse 1.2s infinite; }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Responsive Nav */
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .mobile-menu-button { display: block; }
        }
        @media (min-width: 769px) {
            .mobile-menu-button { display: none; }
            .mobile-menu { display: none !important; }
        }

        /* Counter Styles */
        .counter-number {
            font-size: 2.5rem; /* Make numbers bigger */
            font-weight: 700;
            color: var(--color-indigo-blue);
            line-height: 1.1;
        }
        /* Add dark mode counter styles if needed */

        /* Footer Wave */
        .footer-wave path {
            /* Fill color is controlled by Tailwind class on the path element */
            transition: fill 0.4s ease;
        }
        /* Add dark mode footer wave styles if needed */

        /* Navbar Styling */
        #navbar {
            transition: background-color 0.4s ease-out, box-shadow 0.4s ease-out, padding 0.3s ease-out;
        }
        #navbar.navbar-scrolled {
            background-color: var(--color-navbar-bg-light);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        #navbar.navbar-scrolled .nav-links a,
        #navbar.navbar-scrolled .mobile-menu-button,
        #navbar.navbar-scrolled .navbar-logo-base,
        #navbar.navbar-scrolled .navbar-logo-icon {
             color: var(--color-text-dark); /* Dark text on light scroll */
        }
        #navbar.navbar-scrolled .navbar-logo-accent{
             color: var(--color-sunset-orange); /* Accent stays orange */
        }
        #navbar.navbar-scrolled .nav-links a:hover {
             color: var(--color-orange-accent); /* Orange hover */
        }
        #navbar.navbar-scrolled .login-signup-btn { /* Target specific login/signup buttons */
            background-color: var(--color-indigo-blue);
            color: white;
        }
         #navbar.navbar-scrolled .login-signup-btn:hover {
             background-color: #303F9F; /* Darker indigo */
         }
         /* Add dark mode navbar styles if needed */

        /* Mobile Menu */
        .mobile-menu {
            background-color: var(--color-card-light);
            color: var(--color-text-dark);
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
            right: -100%;
            width: 75%;
            max-width: 300px;
            height: 100%;
            z-index: 100;
            transition: right 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            padding: 2rem;
            overflow-y: auto;
            display: block; /* Always block, control visibility with 'open' class */
        }
        .mobile-menu.open { right: 0; }
        #menu-overlay {
            position: fixed; inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 99;
            opacity: 0; visibility: hidden;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }
        #menu-overlay.open { opacity: 1; visibility: visible; }
        /* Add dark mode mobile menu styles if needed */

        /* Section Backgrounds */
        .bg-section-light { background-color: var(--color-bg-light); } /* Will be white */
        .bg-section-alternate { background-color: var(--color-sand-beige); } /* Will be slate-100 */
        /* Add dark mode section bg styles if needed */

        /* Back to Top */
        #scrollToTop {
            transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease, background-color 0.3s ease;
        }

        /* Ensure icons have a consistent size (already in your code, maybe refine) */
        .icon-size {
            font-size: 2rem; /* Adjust as needed */
            width: 3.5rem; height: 3.5rem;
            display: inline-flex; align-items: center; justify-content: center;
        }

         /* Your original gradient - keep if you prefer it over reference */
         @keyframes gradient-animation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animated-gradient {
            background: linear-gradient(-45deg, #4338ca, #3b82f6, #22d3ee, #6ee7b7); /* Adjusted gradient */
            /* background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab); */
            background-size: 400% 400%;
            animation: gradient-animation 18s ease infinite;
        }

        /* Class to use Tailwind's fill color for the wave */
        .fill-bg-light { fill: var(--color-bg-light); }
        .dark .fill-bg-light { fill: var(--color-bg-dark); } /* Adjust for dark mode if needed */
        .fill-bg-alternate { fill: var(--color-sand-beige); } /* For the footer wave top layer */
        .dark .fill-bg-alternate { fill: var(--color-card-dark); } /* Use slate-800 for dark mode wave */


        /* Improved contrast for glass card text in light mode */
        .glass-card {
            color: var(--color-heading-dark); /* Darker text for better readability on light blurry bg */
        }
        .dark .glass-card {
             color: var(--color-text-light); /* Restore light text for dark mode */
        }

        /* Simple card style for Destinations (Replaces Flip Card) */
        .destination-card {
            position: relative; /* Needed for absolute positioned overlay */
            width: 100%;
            height: 100%;
            border-radius: 0.75rem; /* rounded-xl */
            overflow: hidden; /* Clips the image */
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); /* Basic shadow */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
         .destination-card:hover {
            transform: translateY(-5px); /* Simple hover effect */
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.25);
        }

    </style>
    <!-- No dark mode script initially, add if needed -->
</head>

<body class="bg-gray-100 font-sans dark-mode-transition"> <!-- Added dark-mode-transition -->

<!-- Preloader (Optional, from reference) -->
<!-- <div class="preloader"> ... Preloader HTML ... </div> -->

<!-- Navbar - Adapted from Reference -->
<nav class="fixed top-0 left-0 w-full z-50 transition-all duration-300 py-4" id="navbar">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a href="#" class="flex items-center pulse-hover">
            <span class="text-2xl md:text-3xl font-bold">
                <i class="fas fa-map-marked-alt mr-2 navbar-logo-icon text-white transition-colors duration-300"></i>
                <span class="navbar-logo-base text-white transition-colors duration-300">Incredible</span><span class="navbar-logo-accent text-orange-400 transition-colors duration-300">India</span>
            </span>
        </a>
        <div class="nav-links hidden md:flex items-center space-x-6">
            <a href="#home" class="text-white hover:text-orange-400 transition-colors font-medium">Home</a>
            <?php if (isset($_SESSION['user_id'])): ?>
             <a href="dashboard.php" class="text-white hover:text-orange-400 transition-colors font-medium">Dashboard</a>
             <?php endif; ?>
            <a href="destination.php" class="text-white hover:text-orange-400 transition-colors font-medium">Destinations</a>
            <!-- <a href="#deals" class="text-white hover:text-orange-400 transition-colors font-medium">Packages</a> --> <!-- Removed Deals Link from Nav -->
            <a href="plannar.php" class="text-white hover:text-orange-400 transition-colors font-medium">AI Planner</a>
            <?php if (isset($_SESSION['user_id'])): ?>
            <a href="bookings.php" class="text-white hover:text-orange-400 transition-colors font-medium">Bookings</a>
            <?php endif; ?>
            <!-- <a href="#contact" class="text-white hover:text-orange-400 transition-colors font-medium">Contact</a> --> <!-- REMOVED Contact Link from Nav -->
        </div>
        <div class="flex items-center space-x-4">
            <!-- Dark Mode Toggle Placeholder - Add if needed -->
            <!-- <button id="darkModeToggle" title="Toggle Theme" class="text-white hover:text-orange-400 transition-colors">
                <i class="fas fa-moon text-xl"></i>
            </button> -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="bg-orange-accent px-5 py-2 text-white rounded-full font-semibold hover:bg-orange-accent-darker hover:shadow-lg transition-all text-sm login-signup-btn">
                    Logout
                </a>
            <?php else: ?>
                <a href="login.php" class="bg-white px-5 py-2 text-indigo-600 rounded-full font-semibold hover:bg-gray-100 transition-all text-sm login-signup-btn">
                    Login
                </a>
                 <a href="signup.php" class="hidden sm:inline-block bg-indigo-primary px-5 py-2 text-white rounded-full font-semibold hover:bg-indigo-primary-darker transition-all text-sm login-signup-btn">
                    Sign Up
                </a>
            <?php endif; ?>
            <button class="mobile-menu-button md:hidden text-white text-2xl">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
    <!-- Mobile Menu -->
    <div class="mobile-menu dark-mode-transition">
        <button id="close-menu-btn" class="absolute top-4 right-4 p-2 text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-white text-2xl">×</button>
        <div class="mt-12 space-y-4">
             <a href="#home" class="block py-2 hover:text-orange-500 transition-colors text-lg">Home</a>
             <?php if (isset($_SESSION['user_id'])): ?>
             <a href="dashboard.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Dashboard</a>
             <?php endif; ?>
            <a href="destination.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Destinations</a>
            <!-- <a href="#deals" class="block py-2 hover:text-orange-500 transition-colors text-lg">Packages</a> --> <!-- Removed Deals Link from Mobile Menu -->
            <a href="plannar.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">AI Planner</a>
             <?php if (isset($_SESSION['user_id'])): ?>
            <a href="bookings.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Bookings</a>
             <a href="logout.php" class="mt-6 w-full neon-button-orange px-6 py-3 text-white rounded-full font-semibold hover:shadow-lg transition-all text-center">Logout</a>
            <?php else: ?>
             <a href="login.php" class="mt-6 w-full bg-indigo-600 px-6 py-3 text-white rounded-full font-semibold hover:shadow-lg transition-all text-center">Login</a>
             <a href="signup.php" class="mt-4 w-full neon-button-orange px-6 py-3 text-white rounded-full font-semibold hover:shadow-lg transition-all text-center">Sign Up</a>
            <?php endif; ?>
            <!-- <a href="#contact" class="block py-2 hover:text-orange-500 transition-colors text-lg">Contact</a> --> <!-- REMOVED Contact Link from Mobile Menu -->
        </div>
    </div>
    <div id="menu-overlay" class="fixed inset-0 bg-black/50 z-90 hidden"></div>
</nav>

    <!-- Hero Section - Adapted from Reference -->
    <section id="home" class="relative h-screen flex items-center justify-center overflow-hidden animated-gradient">
        <!-- Using your animated-gradient -->
        <div class="container mx-auto px-4 text-center z-10 relative">
             <!-- Optional: Typewriter effect (requires more JS) -->
             <!-- <div class="typewriter mx-auto max-w-4xl mb-6"> -->
                <h1 class="text-5xl md:text-7xl font-bold text-white text-shadow mb-6" data-aos="fade-down">Explore Incredible India.</h1>
             <!-- </div> -->
            <p class="text-xl md:text-2xl text-white mb-10 text-shadow max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Your personalized journey starts here. Explore India's wonders with our smart AI travel planner.
            </p>
            <div class="flex flex-col md:flex-row justify-center gap-4 md:gap-6">
                <!-- AI Planner Button -->
                <a href="Plannar.php" id="aiPlannerButtonHero" class="bg-orange-accent px-8 py-4 text-white rounded-full text-lg font-semibold shadow-lg hover:shadow-xl transition-all transform hover:scale-105 inline-block hover:bg-orange-accent-darker" data-aos="fade-up" data-aos-delay="200">
                    <i class="fas fa-robot mr-2"></i>Generate AI Trip Plan
                </a>
                <!-- Explore Button -->
                <a href="destination.php" id="exploreButtonHero" class="bg-indigo-primary px-8 py-4 text-white rounded-full text-lg font-semibold shadow-lg hover:shadow-xl transition-all transform hover:translate-y-[-2px] inline-block hover:bg-indigo-primary-darker" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-compass mr-2"></i>Explore Destinations
                </a>
            </div>
        </div>
        <!-- Scroll Down Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white text-center z-10">
            <div class="scroll-indicator flex flex-col items-center cursor-pointer" onclick="document.getElementById('destinations').scrollIntoView({ behavior: 'smooth' });">
                <span class="text-sm mb-1">Scroll Down</span>
                <i class="fas fa-chevron-down text-xl"></i>
            </div>
        </div>
    </section>

    <!-- Popular Destinations Section - Using Swiper (NO Flip Cards, NO Explore Button) -->
    <section id="destinations" class="py-20 bg-section-alternate dark-mode-transition"> <!-- Uses slate-100 -->
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4 text-indigo-900 dark:text-indigo-300" data-aos="fade-up">Popular Destinations</h2>
            </div>
            <!-- Swiper -->
            <div class="swiper destinationSwiper" data-aos="fade-up" data-aos-delay="200">
                <div class="swiper-wrapper pb-12 h-96"> <!-- Set height for slides -->

                    <!-- Card 1: Goa (Simplified) -->
                    <div class="swiper-slide h-full">
                         <div class="destination-card h-full">
                            <img src="img/Goa Beaches & Goa Travel _ Places to visit in Goa _ Beaches in Goa _ Pic 92.jpeg" alt="Goa Beaches" class="w-full h-full object-cover">
                            <!-- Overlay Text on Front -->
                            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 via-black/30 to-transparent">
                                 <h3 class="text-white text-xl font-bold">Goa</h3>
                             </div>
                             <!-- NO Back Side or Explore Button -->
                        </div>
                    </div>

                     <!-- Card 2: Manali (Simplified) -->
                     <div class="swiper-slide h-full">
                        <div class="destination-card h-full">
                             <img src="img/manali.jpg" alt="Manali Mountains" class="w-full h-full object-cover">
                             <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 via-black/30 to-transparent">
                                 <h3 class="text-white text-xl font-bold">Manali</h3>
                             </div>
                             <!-- NO Back Side or Explore Button -->
                        </div>
                     </div>

                    <!-- Card 3: Rishikesh (Simplified) -->
                     <div class="swiper-slide h-full">
                        <div class="destination-card h-full">
                             <img src="img/Rishikesh, ganga, Uttrakhand_.jpeg" alt="Rishikesh Ganges" class="w-full h-full object-cover">
                              <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 via-black/30 to-transparent">
                                 <h3 class="text-white text-xl font-bold">Rishikesh</h3>
                             </div>
                             <!-- NO Back Side or Explore Button -->
                        </div>
                     </div>

                     <!-- Card 4: Udaipur (Simplified) -->
                    <div class="swiper-slide h-full">
                        <div class="destination-card h-full">
                            <img src="img/udainpur.jpeg" alt="Udaipur Lake Palace" class="w-full h-full object-cover">
                             <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 via-black/30 to-transparent">
                                 <h3 class="text-white text-xl font-bold">Udaipur</h3>
                             </div>
                             <!-- NO Back Side or Explore Button -->
                        </div>
                    </div>

                     <!-- Card 5: Darjeeling (Simplified) -->
                    <div class="swiper-slide h-full">
                         <div class="destination-card h-full">
                            <img src="img/darjeeling_.jpeg" alt="Darjeeling Tea Gardens" class="w-full h-full object-cover">
                             <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 via-black/30 to-transparent">
                                 <h3 class="text-white text-xl font-bold">Darjeeling</h3>
                             </div>
                             <!-- NO Back Side or Explore Button -->
                        </div>
                    </div>

                     <!-- Card 6: Ladakh (Simplified) -->
                    <div class="swiper-slide h-full">
                         <div class="destination-card h-full">
                            <img src="img/Ladakh.jpeg" alt="Ladakh Monastery" class="w-full h-full object-cover">
                             <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 via-black/30 to-transparent">
                                 <h3 class="text-white text-xl font-bold">Ladakh</h3>
                             </div>
                             <!-- NO Back Side or Explore Button -->
                        </div>
                    </div>
                    <!-- Add more cards as needed using the simplified structure -->
                </div>
                <!-- Swiper Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us / Counters Section (from reference) -->
    <section id="why-choose-us" class="py-20 bg-section-light dark-mode-transition"> <!-- Uses white -->
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4 text-indigo-900 dark:text-indigo-300" data-aos="fade-up">Why Travel With Us?</h2>
                <p class="text-gray-700 dark:text-gray-300 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                    Experience seamless travel planning powered by AI and human expertise.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center" id="counters">
                <div data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-white dark:bg-slate-800 p-8 rounded-xl shadow-lg h-full border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center">
                        <i class="fas fa-map-marked-alt text-5xl text-indigo-primary dark:text-indigo-primary-dark mb-4"></i>
                        <div class="counter-number" data-target="500">0</div>
                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-2">Indian Destinations</p>
                    </div>
                </div>
                <div data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-white dark:bg-slate-800 p-8 rounded-xl shadow-lg h-full border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center">
                        <i class="fas fa-users text-5xl text-green-600 dark:text-green-400 mb-4"></i>
                        <div class="counter-number" data-target="10000">0</div>
                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-2">Happy Travelers</p>
                    </div>
                </div>
                <div data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-white dark:bg-slate-800 p-8 rounded-xl shadow-lg h-full border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center">
                        <i class="fas fa-robot text-5xl text-orange-accent dark:text-orange-accent-dark mb-4"></i>
                        <div class="counter-number" data-target="25000">0</div>
                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-2">AI Plans Generated</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Deals of the Week Section REMOVED -->
    <!-- <section id="deals" class="py-20 bg-section-alternate dark-mode-transition"> ... </section> -->

    <!-- How It Works / AI Planner Section (from reference) -->
    <section id="ai-planner-section" class="py-20 bg-section-alternate dark-mode-transition"> <!-- Uses slate-100 -->
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4 text-indigo-900 dark:text-indigo-300" data-aos="fade-up">Plan Your Trip with AI</h2>
                <p class="text-gray-700 dark:text-gray-300 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                    Our intelligent platform makes crafting your perfect Indian journey effortless.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Step 1 -->
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                     <div class="bg-indigo-100 dark:bg-slate-700 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg hover:shadow-indigo-300/50 dark:hover:shadow-indigo-500/30 transition-shadow">
                        <i class="fas fa-map-signs text-indigo-primary dark:text-indigo-primary-dark text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">1. Share Your Vision</h3>
                    <p class="text-gray-600 dark:text-gray-400"> Tell us your destination, travel style, budget, and interests. </p>
                </div>
                <!-- Step 2 -->
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                     <div class="bg-green-100 dark:bg-slate-700 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg hover:shadow-green-300/50 dark:hover:shadow-green-500/30 transition-shadow">
                        <i class="fas fa-cogs text-green-600 dark:text-green-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">2. AI Crafts Your Plan</h3>
                    <p class="text-gray-600 dark:text-gray-400"> Our smart AI generates a personalized itinerary just for you. </p>
                </div>
                <!-- Step 3 -->
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                     <div class="bg-orange-100 dark:bg-slate-700 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg hover:shadow-orange-300/50 dark:hover:shadow-orange-500/30 transition-shadow">
                        <i class="fas fa-check-circle text-orange-accent dark:text-orange-accent-dark text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">3. Review & Book</h3>
                    <p class="text-gray-600 dark:text-gray-400"> Customize the plan, get quotes, and book securely. </p>
                </div>
            </div>

            <!-- Feature Banner -->
            <div class="mt-20 rounded-2xl overflow-hidden bg-gradient-to-r from-indigo-700 to-indigo-900 shadow-xl" data-aos="fade-up" data-aos-delay="500">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-1/2 p-8 md:p-12 text-white">
                        <h3 class="text-3xl font-bold mb-4">Ready for Your AI-Powered Itinerary?</h3>
                        <p class="mb-6 text-indigo-100"> Let our smart tech design a unique Indian adventure just for you. Get recommendations for hidden gems, local experiences, and the best travel routes. </p>
                        <a href="Plannar.php" id="startAiPlanningButton" class="bg-orange-accent text-white hover:bg-orange-accent-darker px-8 py-3 rounded-full font-semibold transition-colors shadow-md hover:shadow-lg">Start Planning Now</a>
                    </div>
                    <div class="md:w-1/2 h-64 md:h-auto">
                    <img src="img\Planner.png" alt="AI Travel Planner Interface" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section (from reference, using glass cards) -->
    <section class="py-20 bg-section-light dark-mode-transition relative overflow-hidden"> <!-- Uses white -->
        <!-- Optional Dotted Background -->
        <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.02]">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="dotted-pattern-2" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1" class="text-gray-500 dark:text-gray-700" fill="currentColor" /></pattern></defs><rect width="100%" height="100%" fill="url(#dotted-pattern-2)" /></svg>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4 text-indigo-900 dark:text-indigo-300" data-aos="fade-up">Hear From Fellow Travellers</h2>
                <p class="text-gray-700 dark:text-gray-300 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100"> Real stories from travellers who explored India with our platform. </p>
            </div>
            <!-- Testimonial Swiper -->
            <div class="swiper testimonialSwiper" data-aos="fade-up" data-aos-delay="200">
                <div class="swiper-wrapper pb-16">
                    <!-- Testimonial 1 -->
                    <div class="swiper-slide h-auto">
                        <div class="glass-card rounded-xl p-8 h-full flex flex-col justify-between"> <!-- Removed explicit text color, inheriting from style block -->
                            <div>
                                <div class="flex items-center mb-6">
                                    <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Ayush Kumar" class="w-16 h-16 rounded-full object-cover mr-4 border-2 border-indigo-300">
                                    <div>
                                        <h4 class="text-xl font-bold ">Ayush Kumar</h4>
                                        <p class="text-indigo-primary dark:text-indigo-primary-dark text-sm">Rajasthan Tour</p>
                                    </div>
                                </div>
                                <div class="mb-4 text-yellow-400"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                                <p class=" text-base leading-relaxed"> "The AI planner was brilliant! It created a magical Rajasthan trip, perfectly balancing forts, culture, and a desert safari. Hotel choices were spot on!" </p>
                            </div>
                            <div class="mt-4 text-right text-xs text-gray-500 dark:text-gray-400">Reviewed 2 weeks ago</div>
                        </div>
                    </div>
                    <!-- Testimonial 2 -->
                    <div class="swiper-slide h-auto">
                        <div class="glass-card rounded-xl p-8 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center mb-6">
                                    <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Priya Verma" class="w-16 h-16 rounded-full object-cover mr-4 border-2 border-green-300">
                                    <div>
                                        <h4 class="text-xl font-bold ">Priya Verma</h4>
                                        <p class="text-green-600 dark:text-green-400 text-sm">Kerala Backwaters</p>
                                    </div>
                                </div>
                                <div class="mb-4 text-yellow-400"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
                                <p class=" text-base leading-relaxed"> "The AI suggested a fantastic route through Kerala. The houseboat experience was unforgettable. Booking was smooth and saved me a lot of hassle." </p>
                            </div>
                            <div class="mt-4 text-right text-xs text-gray-500 dark:text-gray-400">Reviewed 1 month ago</div>
                        </div>
                    </div>
                    <!-- Testimonial 3 -->
                     <div class="swiper-slide h-auto">
                         <div class="glass-card rounded-xl p-8 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center mb-6">
                                    <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="Amit Patel" class="w-16 h-16 rounded-full object-cover mr-4 border-2 border-orange-300">
                                    <div>
                                        <h4 class="text-xl font-bold ">Amit Patel</h4>
                                        <p class="text-orange-accent dark:text-orange-accent-dark text-sm">Solo Himachal Trip</p>
                                    </div>
                                </div>
                                <div class="mb-4 text-yellow-400"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i></div>
                                <p class=" text-base leading-relaxed"> "Traveling solo, safety was key. The AI plan included great tips and suggested safe stays in Himachal. Saved me hours of research!" </p>
                            </div>
                             <div class="mt-4 text-right text-xs text-gray-500 dark:text-gray-400">Reviewed 3 weeks ago</div>
                        </div>
                    </div>
                    <!-- Add more testimonials -->
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>


     <!-- Newsletter Section (Contact) - Adapted from reference -->
    <section id="contact" class="py-20 bg-section-alternate dark-mode-transition"> <!-- Uses slate-100 -->
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto bg-gradient-to-r from-indigo-700 to-indigo-900 rounded-2xl p-8 md:p-12 shadow-xl" data-aos="fade-up">
                <div class="text-center mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Get Inspired & Stay Connected!</h2>
                    <p class="text-indigo-100 max-w-2xl mx-auto text-lg"> Subscribe for exclusive Indian travel deals, insider tips, AI-curated destination ideas, or send us a message directly! </p>
                </div>
                <!-- Newsletter Form -->
                <form class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto mb-8" id="newsletterForm" action="subscribe.php" method="POST"> <!-- Added action/method -->
                    <input type="email" name="email" placeholder="Enter your email address" class="flex-grow px-6 py-4 rounded-full focus:outline-none focus:ring-2 focus:ring-orange-500 transition shadow-inner text-gray-800" required>
                    <button type="submit" class="bg-orange-accent rounded-full px-8 py-4 text-white font-semibold hover:bg-orange-accent-darker hover:shadow-xl transition-all shrink-0"> <i class="fas fa-paper-plane mr-2"></i>Subscribe </button>
                </form>
                 <div class="text-center">
                    <p class="text-sm text-indigo-200 mb-4">We value your privacy. No spam, ever.</p>
                     <p class="text-indigo-100">Or contact us directly:</p>
                     <a href="mailto:support@incredibleindia.ai" class="text-orange-300 hover:text-white font-semibold text-lg transition-colors">support@incredibleindia.ai</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer - Adapted from reference -->
    <footer class="bg-indigo-900 dark:bg-slate-900 text-indigo-100 dark:text-gray-300 pt-24 pb-10 relative">
        <!-- Wave SVG -->
        <div class="absolute top-0 left-0 w-full overflow-hidden leading-none">
            <svg class="relative block w-full h-[80px] md:h-[120px] dark-mode-transition footer-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                 <!-- The fill color now uses var(--color-sand-beige) which is slate-100 -->
                 <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-bg-alternate dark:fill-slate-800"></path> <!-- Adjusted fill class -->
             </svg>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                 <!-- About -->
                <div>
                    <h3 class="text-2xl font-bold mb-6 text-white">Incredible<span class="text-orange-400">India</span></h3>
                    <p class="text-indigo-200 dark:text-gray-400 mb-6 text-sm leading-relaxed"> Your AI-powered guide to exploring India's diverse beauty. Plan smarter, travel better. </p>
                     <!-- Social Links from your original Footer -->
                    <div class="flex space-x-4">
                         <a href="https://wa.me/919876543210" target="_blank" aria-label="WhatsApp" class="text-indigo-200 hover:text-green-500 transition-colors"><i class="fab fa-whatsapp fa-lg"></i></a>
                         <a href="https://www.instagram.com/YOUR_PROFILE" target="_blank" aria-label="Instagram" class="text-indigo-200 hover:text-pink-500 transition-colors"><i class="fab fa-instagram fa-lg"></i></a>
                         <a href="https://twitter.com/YOUR_PROFILE" target="_blank" aria-label="Twitter" class="text-indigo-200 hover:text-blue-400 transition-colors"><i class="fab fa-twitter fa-lg"></i></a>
                         <a href="https://www.youtube.com/channel/YOUR_CHANNEL" target="_blank" aria-label="YouTube" class="text-indigo-200 hover:text-red-600 transition-colors"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
                 <!-- Quick Links -->
                <div>
                    <h4 class="text-xl font-semibold mb-6 text-white">Quick Links</h4>
                    <ul class="space-y-3 text-sm">
                         <li><a href="#home" class="text-indigo-200 hover:text-orange-300 transition-colors">Home</a></li>
                         <?php if (isset($_SESSION['user_id'])): ?>
                         <li><a href="dashboard.php" class="text-indigo-200 hover:text-orange-300 transition-colors">Dashboard</a></li>
                         <?php endif; ?>
                        <li><a href="#destinations" class="text-indigo-200 hover:text-orange-300 transition-colors">Destinations</a></li>
                        <!-- <li><a href="#deals" class="text-indigo-200 hover:text-orange-300 transition-colors">Packages</a></li> --> <!-- Removed Deals Link -->
                        <li><a href="#ai-planner-section" class="text-indigo-200 hover:text-orange-300 transition-colors">AI Planner</a></li>
                    </ul>
                </div>
                 <!-- Support -->
                <div>
                    <h4 class="text-xl font-semibold mb-6 text-white">Support</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-indigo-200 hover:text-orange-300 transition-colors">FAQs</a></li>
                        <li><a href="#contact" class="text-indigo-200 hover:text-orange-300 transition-colors">Contact Us</a></li> <!-- Contact Link kept here -->
                        <li><a href="#" class="text-indigo-200 hover:text-orange-300 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-indigo-200 hover:text-orange-300 transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
                 <!-- Get In Touch -->
                <div>
                    <h4 class="text-xl font-semibold mb-6 text-white">Get In Touch</h4>
                    <ul class="space-y-4 text-sm text-indigo-200">
                        <li class="flex items-start"> <i class="fas fa-map-marker-alt mt-1 mr-3 text-orange-400 shrink-0"></i> <span>123 Yatra Marg,122002</span> </li>
                        <li class="flex items-center"> <i class="fas fa-phone-alt mr-3 text-orange-400"></i> <a href="tel:+911244000000" class="hover:text-white transition-colors">+91-124-4000-000</a> </li>
                        <li class="flex items-center"> <i class="fas fa-envelope mr-3 text-orange-400"></i> <a href="mailto:support@incredibleindia.ai" class="hover:text-white transition-colors">support@incredibleindia.ai</a> </li>
                    </ul>
                </div>
            </div>
            <hr class="border-indigo-700/50 dark:border-slate-700 my-8">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm">
                 <p class="text-gray-400 dark:text-gray-500 mb-4 md:mb-0">© <span id="currentYear"></span> Incredible India AI (Ayush). All rights reserved.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 dark:text-gray-500 hover:text-white transition-colors">Privacy</a>
                    <a href="#" class="text-gray-400 dark:text-gray-500 hover:text-white transition-colors">Terms</a>
                    <a href="#" class="text-gray-400 dark:text-gray-500 hover:text-white transition-colors">Sitemap</a>
                </div>
            </div>
        </div>
        <!-- Scroll to Top Button -->
        <button id="scrollToTop" title="Scroll to Top" class="fixed bottom-6 right-6 bg-indigo-primary text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-primary-darker transition-all duration-300 opacity-0 invisible z-50 scale-90"> <i class="fas fa-arrow-up"></i> </button>
    </footer>

    <!-- Weather Widget (Requires API Key) -->
    <button id="showWeather" title="Show Weather" class="fixed bottom-24 right-6 bg-indigo-primary text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-primary-darker transition-colors z-50"> <i class="fas fa-cloud-sun"></i> </button>
    <div id="weatherWidget" class="fixed top-24 right-8 z-40 hidden">
        <div class="glass-card rounded-xl p-4 w-64 text-gray-900 dark:text-white">
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-bold">Weather Update</h4>
                <button id="closeWeather" title="Close Weather" class="hover:text-red-500 transition-colors text-lg">×</button>
            </div>
            <div class="text-center">
                <div class="text-5xl mb-2" id="weatherIcon"><i class="fas fa-spinner fa-spin"></i></div>
                <div class="text-3xl font-bold mb-1" id="weatherTemp">--°C</div>
                <div class="text-sm capitalize" id="weatherDesc">Loading...</div>
                <div class="text-sm font-semibold mt-1" id="weatherLocation">Select City</div>
                <div class="mt-3 flex gap-2">
                    <input type="text" id="weatherLocationInput" placeholder="Enter Indian City" class="flex-grow px-3 py-1 text-sm rounded border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-black/20 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark-mode-transition text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                    <button id="searchWeatherButton" title="Search Weather" class="px-3 py-1 text-sm bg-indigo-primary text-white rounded hover:bg-indigo-primary-darker transition-colors shrink-0"> <i class="fas fa-search"></i> </button>
                </div>
                <div id="weatherError" class="text-red-500 text-xs mt-1 text-left hidden"></div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <!-- Vanilla Tilt Not needed for flip cards -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.2/vanilla-tilt.min.js"></script> -->

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true, // Animate only once
            offset: 50 // Trigger animation slightly before element reaches viewport
        });

        $(document).ready(function() {

            // --- Navbar Scroll Effect ---
            const nav = $('#navbar');
            $(window).scroll(function() {
                const scrolled = $(window).scrollTop() > 80;
                nav.toggleClass('navbar-scrolled', scrolled);
                nav.toggleClass('py-4', !scrolled).toggleClass('py-3', scrolled);

                // Scroll to Top Button Visibility
                $('#scrollToTop').toggleClass('opacity-100 visible scale-100', $(window).scrollTop() > 500)
                    .toggleClass('opacity-0 invisible scale-90', $(window).scrollTop() <= 500);
            });
            // Initial check for scroll position on load
            if ($(window).scrollTop() > 80) $(window).trigger('scroll');
            if ($(window).scrollTop() > 500) $('#scrollToTop').addClass('opacity-100 visible scale-100').removeClass('opacity-0 invisible scale-90');

            // --- Scroll to Top Click ---
            $('#scrollToTop').click(() => $('html, body').animate({ scrollTop: 0 }, 800, 'easeInOutExpo'));
            jQuery.extend(jQuery.easing, { // Add easeInOutExpo easing if not already present
                easeInOutExpo: (x, t, b, c, d) => (t == 0) ? b : (t == d) ? b + c : (t /= d / 2) < 1 ? c / 2 * Math.pow(2, 10 * (t - 1)) + b : c / 2 * (-Math.pow(2, -10 * --t) + 2) + b
            });

            // --- Mobile Menu Toggle ---
            const mobileMenu = $('.mobile-menu');
            const menuOverlay = $('#menu-overlay');
            const burgerIcon = $('.mobile-menu-button i');

            function toggleMenu(open) {
                mobileMenu.toggleClass('open', open);
                menuOverlay.toggleClass('open', open);
                burgerIcon.toggleClass('fa-bars fa-times', open);
                $('body').css('overflow', open ? 'hidden' : ''); // Prevent body scroll
            }

            $('.mobile-menu-button').click(() => toggleMenu(!mobileMenu.hasClass('open')));
            $('#close-menu-btn').click(() => toggleMenu(false));
            menuOverlay.click(() => toggleMenu(false));
            // Close menu when a link inside it is clicked
            $('.mobile-menu a, .mobile-menu button').not('#close-menu-btn').click(() => { // Exclude close button itself
                 // Check if it's NOT an external link or action button before closing
                 // For simplicity here, we close on any click inside. Adjust if needed.
                toggleMenu(false);
            });

            // --- Swiper Initializations ---
            const commonSwiperOpts = {
                loop: true,
                grabCursor: true,
                pagination: { clickable: true },
                autoplay: { delay: 5000, disableOnInteraction: false }
            };

            // Destination Swiper (Works with simplified cards)
            new Swiper('.destinationSwiper', {
                ...commonSwiperOpts,
                slidesPerView: 1,
                spaceBetween: 20,
                pagination: { el: '.destinationSwiper .swiper-pagination' },
                breakpoints: {
                    640: { slidesPerView: 2, spaceBetween: 25 },
                    1024: { slidesPerView: 3, spaceBetween: 30 }
                }
            });

             // Testimonial Swiper
            new Swiper('.testimonialSwiper', {
                ...commonSwiperOpts,
                autoplay: { delay: 6000 },
                 slidesPerView: 1,
                spaceBetween: 30,
                pagination: { el: '.testimonialSwiper .swiper-pagination' },
                breakpoints: {
                    768: { slidesPerView: 2 },
                    1280: { slidesPerView: 3 }
                }
            });


            // --- Counter Animation ---
            const counters = document.querySelectorAll('.counter-number');
            const speed = 200; // Adjust animation speed
            const counterObserver = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                        const counter = entry.target;
                        const target = +counter.getAttribute('data-target');
                        const updateCount = () => {
                            const count = +counter.innerText.replace(/,/g, '');
                            const increment = Math.max(1, Math.ceil(target / speed));
                            if (count < target) {
                                counter.innerText = Math.min(target, count + increment).toLocaleString();
                                requestAnimationFrame(updateCount);
                            } else {
                                counter.innerText = target.toLocaleString();
                                counter.classList.add('counted');
                                counterObserver.unobserve(counter);
                            }
                        };
                        requestAnimationFrame(updateCount);
                    }
                });
            }, { threshold: 0.6 });
            counters.forEach(counter => counterObserver.observe(counter));


            // --- Weather Widget ---
            const weatherWidget = $('#weatherWidget');
            // IMPORTANT: Replace 'YOUR_API_KEY' with your actual OpenWeatherMap API key
            const API_KEY = '6f08e260539a44e3461e26be5bfa7bcf'; // Use the one from reference or get your own free key

            function fetchWeatherData(location) {
                if (!location) {
                    $('#weatherError').text('Please enter a city.').slideDown(200).delay(3000).slideUp(200);
                    return;
                }
                $('#weatherIcon').html('<i class="fas fa-spinner fa-spin"></i>');
                $('#weatherTemp').text('--°C');
                $('#weatherDesc').text('Loading...');
                $('#weatherLocation').text(`Loading for ${location}...`);
                $('#weatherError').hide().text('');

                const url = `https://api.openweathermap.org/data/2.5/weather?q=${encodeURIComponent(location)},IN&appid=${API_KEY}&units=metric`;

                $.ajax({
                    url: url, method: 'GET',
                    success: function(data) {
                        const temp = Math.round(data.main.temp);
                        const desc = data.weather[0].description;
                        const weatherCode = data.weather[0].id;
                        const locationName = data.name;
                        let iconClass = 'fas fa-question-circle'; // Default
                        // Map OpenWeatherMap codes to Font Awesome icons (simplified)
                        if (weatherCode >= 200 && weatherCode < 300) iconClass = 'fas fa-bolt';
                        else if (weatherCode >= 300 && weatherCode < 600) iconClass = 'fas fa-cloud-showers-heavy';
                        else if (weatherCode >= 600 && weatherCode < 700) iconClass = 'fas fa-snowflake';
                        else if (weatherCode === 800) iconClass = 'fas fa-sun';
                        else if (weatherCode > 800) iconClass = 'fas fa-cloud';
                        else if (weatherCode >= 700 && weatherCode < 800) iconClass = 'fas fa-smog';

                        updateWeatherUI({ temp, desc: desc.charAt(0).toUpperCase() + desc.slice(1), iconClass, location: locationName });
                    },
                    error: function(jqXHR) {
                        let errorMessage = 'Unable to fetch weather data.';
                        if (jqXHR.status === 404) errorMessage = 'City not found in India.';
                        else if (jqXHR.status === 401) errorMessage = 'Invalid API key.';
                        $('#weatherError').text(errorMessage).slideDown(200);
                        $('#weatherIcon').html('<i class="fas fa-exclamation-triangle text-red-500"></i>');
                        $('#weatherLocation').text(`${location}, India`);
                        $('#weatherLocationInput').val('');
                         // No need for timeout, error stays until next search
                    }
                });
            }

            function updateWeatherUI({ temp, desc, iconClass, location }) {
                const displayLocation = location.charAt(0).toUpperCase() + location.slice(1);
                $('#weatherIcon').html(`<i class="${iconClass} ${getWeatherColor(iconClass)}"></i>`);
                $('#weatherTemp').text(`${temp}°C`);
                $('#weatherDesc').text(desc);
                $('#weatherLocation').text(`${displayLocation}, India`);
                $('#weatherLocationInput').val(''); // Clear input after successful search
                $('#weatherError').hide(); // Hide any previous errors
            }

            function getWeatherColor(iconClass) { // Helper for icon colors
                 if (iconClass.includes('sun')) return 'text-orange-400';
                 if (iconClass.includes('cloud') && !iconClass.includes('showers')) return 'text-gray-400';
                 if (iconClass.includes('showers') || iconClass.includes('bolt')) return 'text-blue-400';
                 if (iconClass.includes('snow')) return 'text-cyan-400';
                 if (iconClass.includes('smog')) return 'text-gray-500';
                return 'dark:text-white text-gray-700';
            }

            $('#showWeather').click(() => {
                weatherWidget.fadeToggle(300);
                if (weatherWidget.is(':visible') && $('#weatherLocation').text().toLowerCase().includes('select city')) {
                    fetchWeatherData('Delhi'); // Default city on first open
                }
            });
            $('#closeWeather').click(() => weatherWidget.fadeOut(300));
            $('#searchWeatherButton').click(() => fetchWeatherData($('#weatherLocationInput').val().trim()));
            $('#weatherLocationInput').keypress(function(e) { if (e.which == 13) { $('#searchWeatherButton').click(); return false; } });


            // --- Newsletter Form Submission Placeholder ---
            $('#newsletterForm').submit(function(e) {
                 // Prevent default if you want to handle via AJAX later
                 // e.preventDefault();
                const emailInput = $(this).find('input[type="email"]');
                if (emailInput.val().trim()) {
                     // Allow normal form submission to subscribe.php
                     // You can add an alert here if you want confirmation,
                     // but the backend (subscribe.php) should handle the actual logic.
                     // alert(`Attempting to subscribe ${emailInput.val()}...`);
                } else {
                     e.preventDefault(); // Prevent submission if email is empty
                    alert("Please enter a valid email address.");
                }
            });


            // --- Footer Year ---
            $('#currentYear').text(new Date().getFullYear());

        }); // End document ready
    </script>

</body>

</html>

