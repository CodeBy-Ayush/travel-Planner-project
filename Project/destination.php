<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="en" class="">
<!-- Add 'dark' class here dynamically for dark mode -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Destinations - Incredible India</title>
    <meta name="description" content="Explore diverse travel destinations across Incredible India. Find places perfect for your next adventure.">
    <meta name="keywords" content="India destinations, explore India, travel India, India tourism, North India, South India, beaches, mountains, heritage">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts (from index.html) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500&display=swap">

    <!-- Custom Styles -->
    <style>
        /* Copied CSS Variables from index.html */
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
            --color-bg-light: #ffffff; /* Changed to pure white (MATCH INDEX.PHP) */
            --color-bg-dark: #0f172a; /* slate-900 */
            --color-card-light: #ffffff; /* White cards */
            --color-card-dark: #1e293b; /* slate-800 for dark cards */
            --color-navbar-bg-light: rgba(255, 255, 255, 0.9); /* White scrolled */
            --color-navbar-bg-dark: rgba(30, 41, 59, 0.9); /* slate-800 scrolled */
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            /* Match index.html */
            background-color: var(--color-bg-light); /* MATCH INDEX.PHP (White Background) */
            color: var(--color-text-dark);
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        /* Dark Mode Body */
        /* .dark body {
            background-color: var(--color-bg-dark);
            color: var(--color-text-light);
        } */

        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; } /* Match index.html */

        /* Palette Utilities (from index.php) */
        .bg-indigo-primary { background-color: var(--color-indigo-blue); }
        .bg-orange-accent { background-color: var(--color-sunset-orange); }
        .text-indigo-primary { color: var(--color-indigo-blue); }
        .text-orange-accent { color: var(--color-sunset-orange); }
        .hover\:text-orange-accent:hover { color: var(--color-sunset-orange); }
        .hover\:bg-indigo-primary-darker:hover { background-color: #303F9F; } /* Darker Indigo */
        .hover\:bg-orange-accent-darker:hover { background-color: #F57C00; } /* Darker Orange */
        /* Dark mode palette utils if needed */
        /* .dark .text-indigo-primary-dark { color: #7e8cfa; } */
        /* .dark .text-orange-accent-dark { color: #ff8a63; } */

        /* Neon glow for Orange buttons (from index.php) */
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

        /* Navbar Styling (UPDATED) */
        #navbar {
            transition: background-color 0.4s ease-out, box-shadow 0.4s ease-out, padding 0.3s ease-out;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 50;
            /* --- MODIFICATION START --- */
            /* Add a subtle dark overlay initially for better text contrast */
            background-color: rgba(0, 0, 0, 0.2);
            /* --- MODIFICATION END --- */
        }
        /* Scrolled state styling */
        #navbar.navbar-scrolled {
            background-color: var(--color-navbar-bg-light); /* This overrides the initial background */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        /* Dark mode scrolled state */
        /* .dark #navbar.navbar-scrolled {
            background-color: var(--color-navbar-bg-dark);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        } */

        /* Text/Icon Colors - Initial State (Now on subtle dark background) */
        #navbar .nav-links a,
        #navbar .mobile-menu-button,
        #navbar .navbar-logo-base,
        #navbar .navbar-logo-icon,
        #navbar #darkModeToggle {
             color: white; /* Remain white */
        }
        #navbar .navbar-logo-accent {
            color: var(--color-sunset-orange); /* Remain orange */
        }
        #navbar .nav-links a:hover,
        #navbar #darkModeToggle:hover {
             color: var(--color-orange-accent); /* Orange hover */
        }
        #navbar .login-signup-btn.bg-white { /* Initial login btn */
            background-color: rgba(255, 255, 255, 0.9); /* Make slightly transparent initially if desired */
            color: var(--color-indigo-blue);
        }
         #navbar .login-signup-btn.bg-white:hover {
             background-color: white;
         }
        #navbar .login-signup-btn.bg-indigo-primary, /* Initial signup/logout btn */
        #navbar .login-signup-btn.bg-orange-accent {
             /* Keep solid colors */
        }

        /* Text/Icon Colors on Scroll (Light Mode) */
        #navbar.navbar-scrolled .nav-links a,
        #navbar.navbar-scrolled .mobile-menu-button,
        #navbar.navbar-scrolled .navbar-logo-base,
        #navbar.navbar-scrolled .navbar-logo-icon,
        #navbar.navbar-scrolled #darkModeToggle { /* Added dark mode toggle */
            color: var(--color-text-dark); /* Change to dark text */
        }
         /* Text/Icon Colors on Scroll (Dark Mode) */
        /* .dark #navbar.navbar-scrolled .nav-links a,
        .dark #navbar.navbar-scrolled .mobile-menu-button,
        .dark #navbar.navbar-scrolled .navbar-logo-icon,
        .dark #navbar.navbar-scrolled #darkModeToggle {
             color: var(--color-text-light);
        } */

        /* Logo Accent Color on Scroll (Remains Orange) */
        #navbar.navbar-scrolled .navbar-logo-accent {
            color: var(--color-sunset-orange);
        }
        /* .dark #navbar.navbar-scrolled .navbar-logo-accent {
             color: var(--color-sunset-orange); /* Or a lighter orange if needed */
        /* } */

        /* Link Hover Color on Scroll */
        #navbar.navbar-scrolled .nav-links a:hover,
        #navbar.navbar-scrolled #darkModeToggle:hover { /* Added dark mode toggle */
             color: var(--color-orange-accent); /* Orange hover still works */
        }
        /* .dark #navbar.navbar-scrolled .nav-links a:hover,
        .dark #navbar.navbar-scrolled #darkModeToggle:hover {
             color: #ff8a63; /* Lighter Orange hover for dark mode */
        /*} */

        /* Login/Signup Button Colors on Scroll (Light Mode) */
        #navbar.navbar-scrolled .login-signup-btn {
             /* Adjust background/text if they changed from initial state */
             /* Example: Ensure Login button is solid white on scroll */
             background-color: var(--color-indigo-blue); /* Default for Logout/Signup */
             color: white;
        }
        /* Specifically target the login button if it needs different styling on scroll */
        #navbar.navbar-scrolled .login-signup-btn.bg-white {
             background-color: var(--color-indigo-blue); /* Make login button match signup/logout on scroll */
             color: white;
        }
        #navbar.navbar-scrolled .login-signup-btn:hover {
             background-color: #303F9F; /* Darker indigo */
         }
         /* Login/Signup Button Colors on Scroll (Dark Mode - Add if needed) */
        /* .dark #navbar.navbar-scrolled .login-signup-btn { ... } */


        /* Mobile Menu (Copied from index.php) */
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
            z-index: 100; /* Ensure menu is above content */
            transition: right 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            padding: 2rem;
            overflow-y: auto;
            display: block; /* Always block, control visibility with 'open' class */
        }
        .mobile-menu.open { right: 0; }
        /* .dark .mobile-menu {
            background-color: var(--color-card-dark);
            color: var(--color-text-light);
        } */
        #menu-overlay {
            position: fixed; inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 99;
            opacity: 0; visibility: hidden;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }
        #menu-overlay.open { opacity: 1; visibility: visible; }

        /* Responsive Nav (Copied) */
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .mobile-menu-button { display: block; }
        }
        @media (min-width: 769px) {
            .mobile-menu-button { display: none; }
            .mobile-menu { display: none !important; } /* Force hide on desktop */
        }

        /* Swiper Button Styling */
        .swiper-button-next, .swiper-button-prev {
            color: #fff; /* Default color */
            background-color: rgba(0, 0, 0, 0.3);
            padding: 15px 10px;
            border-radius: 50%;
            width: 40px !important; height: 40px !important;
            transition: background-color 0.3s, color 0.3s;
        }
        .swiper-button-next:after, .swiper-button-prev:after { font-size: 1rem !important; }

        /* Dark mode Swiper button styling if needed */
        /* .dark .swiper-button-next, .dark .swiper-button-prev {
             color: #1f2937;
             background-color: rgba(255, 255, 255, 0.5);
        } */

        /* Quick View Modal Transition */
        #quick-view-modal { transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out; }
        #quick-view-modal.hidden { opacity: 0; visibility: hidden; }
        #quick-view-modal:not(.hidden) { opacity: 1; visibility: visible; }

        /* Wishlist Heart */
        .wishlist-toggle i.fas.text-red-500, #modal-add-wishlist-btn.wishlisted i.fas { color: #ef4444; } /* Red for filled heart */
        .wishlist-toggle:hover i.far, #modal-add-wishlist-btn:not(.wishlisted):hover i.far { color: #f87171; } /* Light red on hover for empty heart */

        /* Animation Styles */
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; animation-delay: 0.2s; opacity: 0; }
        .animate-fade-in-down { animation: fadeInDown 0.6s ease-out forwards; }
        .destination-card.animate-fade-in { animation: fadeIn 0.5s ease-out forwards; opacity: 0; }

        /* Add padding to the top of the main content area to account for FIXED navbar */
         main {
             padding-top: 68px; /* Default navbar height, adjust if needed */
         }

        /* Footer Wave Fill Color (from index.php) */
        .fill-bg-alternate { fill: var(--color-sand-beige); } /* Uses slate-100 */
        /* .dark .fill-bg-alternate { fill: var(--color-card-dark); } /* Uses slate-800 for dark mode wave */

        /* Back to Top Button (from index.php) */
        #scrollToTop {
            transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease, background-color 0.3s ease;
        }
    </style>
    <script>
        // Forcing Light Mode based on index.php structure
        document.documentElement.classList.remove('dark');
        localStorage.removeItem('theme'); // Remove theme preference if forcing light
    </script>
</head>

<body class="bg-white font-sans dark-mode-transition"> <!-- Ensure body uses the light background variable from CSS -->

    <!-- =============================== -->
    <!--      Navbar (MATCHES INDEX.PHP) -->
    <!-- =============================== -->
    <!-- Initial background is now handled by CSS #navbar rule -->
    <nav class="fixed top-0 left-0 w-full z-50 transition-all duration-300 py-4" id="navbar">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center pulse-hover"> <!-- Link back to index.php -->
                <span class="text-2xl md:text-3xl font-bold">
                    <!-- Icons and text color are handled by CSS now -->
                    <i class="fas fa-map-marked-alt mr-2 navbar-logo-icon transition-colors duration-300"></i>
                    <span class="navbar-logo-base transition-colors duration-300">Incredible</span><span class="navbar-logo-accent transition-colors duration-300">India</span>
                </span>
            </a>
            <div class="nav-links hidden md:flex items-center space-x-6">
                 <!-- Text color is handled by CSS rules -->
                 <a href="index.php#home" class="hover:text-orange-400 transition-colors font-medium">Home</a>
                 <?php if (isset($_SESSION['user_id'])): ?>
                 <a href="dashboard.php" class="hover:text-orange-400 transition-colors font-medium">Dashboard</a>
                 <?php endif; ?>
                <a href="explore.php" class="text-orange-400 border-b-2 border-orange-400 font-semibold transition-colors">Destinations</a> <!-- Highlight current page -->
                <!-- <a href="index.php#deals" class="hover:text-orange-400 transition-colors font-medium">Packages</a> --> <!-- Removed Deals Link from Nav -->
                <a href="index.php#ai-planner-section" class="hover:text-orange-400 transition-colors font-medium">AI Planner</a>
                <!-- <a href="forum.php" class="hover:text-orange-400 transition-colors font-medium">Forum</a> --> <!-- REMOVED Forum Link -->
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="bookings.php" class="hover:text-orange-400 transition-colors font-medium">Bookings</a>
                <!-- <a href="profile.php" class="hover:text-orange-400 transition-colors font-medium">Profile</a> --> <!-- REMOVED Profile Link -->
                <?php endif; ?>
                <!-- <a href="index.php#contact" class="hover:text-orange-400 transition-colors font-medium">Contact</a> --> <!-- REMOVED Contact Link from Nav -->
            </div>
            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle Placeholder - REMOVED as per light mode style -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Button colors handled by CSS -->
                    <a href="logout.php" class="bg-orange-accent px-5 py-2 text-white rounded-full font-semibold hover:bg-orange-accent-darker hover:shadow-lg transition-all text-sm login-signup-btn">
                        Logout
                    </a>
                <?php else: ?>
                    <!-- Button colors handled by CSS -->
                    <a href="login.php" class="bg-white px-5 py-2 text-indigo-600 rounded-full font-semibold hover:bg-gray-100 transition-all text-sm login-signup-btn">
                        Login
                    </a>
                     <a href="signup.php" class="hidden sm:inline-block bg-indigo-primary px-5 py-2 text-white rounded-full font-semibold hover:bg-indigo-primary-darker transition-all text-sm login-signup-btn">
                        Sign Up
                    </a>
                <?php endif; ?>
                 <!-- Mobile button color handled by CSS -->
                <button class="mobile-menu-button md:hidden text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div class="mobile-menu dark-mode-transition"> <!-- Keeps transition class for potential future use -->
            <button id="close-menu-btn" class="absolute top-4 right-4 p-2 text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-white text-2xl">×</button>
            <div class="mt-12 space-y-4">
                 <a href="index.php#home" class="block py-2 hover:text-orange-500 transition-colors text-lg">Home</a>
                 <?php if (isset($_SESSION['user_id'])): ?>
                 <a href="dashboard.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Dashboard</a>
                 <?php endif; ?>
                <a href="explore.php" class="block py-2 text-orange-500 font-semibold transition-colors text-lg">Destinations</a> <!-- Highlight current -->
                <!-- <a href="index.php#deals" class="block py-2 hover:text-orange-500 transition-colors text-lg">Packages</a> -->
                <a href="index.php#ai-planner-section" class="block py-2 hover:text-orange-500 transition-colors text-lg">AI Planner</a>
                <!-- <a href="forum.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Forum</a> --> <!-- REMOVED Forum Link -->
                 <?php if (isset($_SESSION['user_id'])): ?>
                <a href="bookings.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Bookings</a>
                <!-- <a href="profile.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Profile</a> --> <!-- REMOVED Profile Link -->
                 <a href="logout.php" class="mt-6 w-full neon-button-orange px-6 py-3 text-white rounded-full font-semibold hover:shadow-lg transition-all text-center">Logout</a>
                <?php else: ?>
                 <a href="login.php" class="mt-6 w-full bg-indigo-600 px-6 py-3 text-white rounded-full font-semibold hover:shadow-lg transition-all text-center">Login</a>
                 <a href="signup.php" class="mt-4 w-full neon-button-orange px-6 py-3 text-white rounded-full font-semibold hover:shadow-lg transition-all text-center">Sign Up</a>
                <?php endif; ?>
                <!-- <a href="index.php#contact" class="block py-2 hover:text-orange-500 transition-colors text-lg">Contact</a> -->
            </div>
        </div>
        <div id="menu-overlay" class="fixed inset-0 bg-black/50 z-90 hidden"></div>
    </nav>

    <!-- =============================== -->
    <!--          Main Content           -->
    <!-- =============================== -->
    <main> <!-- Added padding-top in CSS to clear the fixed navbar -->

        <!-- =============================== -->
        <!--          Hero Section           -->
        <!-- =============================== -->
        <section class="relative h-[50vh] md:h-[60vh] bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800 text-white flex items-center justify-center">
            <!-- Removed background image, added gradient -->
            <div class="absolute inset-0 bg-black/30"></div> <!-- Optional darker overlay -->
            <div class="relative z-10 text-center px-4">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-4 animate-fade-in-down">Explore India's Diversity</h1>
                <p class="text-lg md:text-2xl mb-6 animate-fade-in-up">Find the perfect destination for your next adventure</p> <!-- Simplified text -->
                <div class="text-sm breadcrumbs text-gray-300 animate-fade-in">
                    <!-- Use index.php for home link -->
                </div>
            </div>
        </section>

        <!-- Filter Panel Removed -->

        <!-- =============================== -->
        <!--       Destination Grid        -->
        <!-- =============================== -->
        <section id="destination-grid-section" class="py-12 md:py-16 bg-white"> <!-- Ensure background matches body -->
            <div class="container mx-auto px-4">
                <div id="destination-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    <!-- Destination Cards will be loaded here by JavaScript -->
                    <!-- Placeholder for loading indicator -->
                    <div id="loading-indicator" class="hidden text-center col-span-full p-10">
                        <i class="fas fa-spinner fa-spin text-4xl text-indigo-500"></i>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Loading destinations...</p>
                    </div>
                    <!-- Placeholder for 'No Results' message -->
                    <div id="no-results" class="hidden text-center col-span-full p-10">
                        <i class="fas fa-map-signs text-4xl text-gray-400 dark:text-gray-500"></i>
                        <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">Could not load destinations.</p>
                    </div>
                </div>
                <!-- Lazy Load / Show More Button -->
                <div class="text-center mt-10">
                    <button id="show-more-btn" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-300 hidden">
                        Show More
                    </button>
                </div>
            </div>
        </section>


        <!-- =============================== -->
        <!-- Recommended/Trending Carousel -->
        <!-- =============================== -->
        <section class="py-12 md:py-16 bg-gray-100 dark:bg-slate-800"> <!-- Alternate bg like index.php -->
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-8 text-gray-900 dark:text-white">Trending Destinations</h2>
                <div class="swiper trending-swiper">
                    <div class="swiper-wrapper pb-8"> <!-- Added padding bottom for pagination -->
                        <!-- Slides will be populated from a subset of data -->
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev text-indigo-primary dark:text-indigo-primary-dark"></div> <!-- Themed buttons -->
                    <div class="swiper-button-next text-indigo-primary dark:text-indigo-primary-dark"></div>
                </div>
            </div>
        </section>


        <!-- =============================== -->
        <!--          CTA Banner           -->
        <!-- =============================== -->
        <section class="bg-indigo-700 dark:bg-indigo-900 text-white py-12">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-4">Need Help Planning Your Trip?</h2>
                <p class="mb-6 max-w-2xl mx-auto">Use our AI planner for personalized itineraries and recommendations!</p>
                <!-- Link back to index.php's AI section -->
                <a href="index.php#ai-planner-section" class="bg-orange-accent hover:bg-orange-accent-darker text-white font-bold py-3 px-8 rounded-lg transition-colors duration-300 text-lg shadow-md inline-block">
                    <i class="fas fa-robot mr-2"></i> Try AI Trip Planner
                </a>
            </div>
        </section>

    </main>

    <!-- =============================== -->
    <!--      Footer (MATCHES INDEX.PHP) -->
    <!-- =============================== -->
    <footer class="bg-indigo-900 dark:bg-slate-900 text-indigo-100 dark:text-gray-300 pt-24 pb-10 relative">
        <!-- Wave SVG -->
        <div class="absolute top-0 left-0 w-full overflow-hidden leading-none">
            <svg class="relative block w-full h-[80px] md:h-[120px] dark-mode-transition footer-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                 <!-- Fill color uses slate-100 (from var --color-sand-beige) -->
                 <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-bg-alternate dark:fill-slate-800"></path> <!-- Adjusted fill class -->
             </svg>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                 <!-- About -->
                <div>
                    <h3 class="text-2xl font-bold mb-6 text-white">Incredible<span class="text-orange-400">India</span></h3>
                    <p class="text-indigo-200 dark:text-gray-400 mb-6 text-sm leading-relaxed"> Your AI-powered guide to exploring India's diverse beauty. Plan smarter, travel better. </p>
                     <!-- Social Links -->
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
                         <li><a href="index.php#home" class="text-indigo-200 hover:text-orange-300 transition-colors">Home</a></li>
                         <?php if (isset($_SESSION['user_id'])): ?>
                         <li><a href="dashboard.php" class="text-indigo-200 hover:text-orange-300 transition-colors">Dashboard</a></li>
                         <?php endif; ?>
                        <li><a href="explore.php" class="text-orange-300 transition-colors">Destinations</a></li> <!-- Highlight current -->
                        <li><a href="index.php#ai-planner-section" class="text-indigo-200 hover:text-orange-300 transition-colors">AI Planner</a></li>
                        <!-- <li><a href="forum.php" class="text-indigo-200 hover:text-orange-300 transition-colors">Forum</a></li> --> <!-- REMOVED -->
                    </ul>
                </div>
                 <!-- Support -->
                <div>
                    <h4 class="text-xl font-semibold mb-6 text-white">Support</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-indigo-200 hover:text-orange-300 transition-colors">FAQs</a></li>
                        <li><a href="index.php#contact" class="text-indigo-200 hover:text-orange-300 transition-colors">Contact Us</a></li>
                        <li><a href="#" class="text-indigo-200 hover:text-orange-300 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-indigo-200 hover:text-orange-300 transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
                 <!-- Get In Touch -->
                <div>
                    <h4 class="text-xl font-semibold mb-6 text-white">Get In Touch</h4>
                    <ul class="space-y-4 text-sm text-indigo-200">
                        <li class="flex items-start"> <i class="fas fa-map-marker-alt mt-1 mr-3 text-orange-400 shrink-0"></i> <span>123 Yatra Marg, 122002</span> </li>
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


    <!-- =============================== -->
    <!--      Quick View Modal           -->
    <!-- =============================== -->
    <div id="quick-view-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-[100] p-4 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 md:p-8 max-w-3xl w-full max-h-[90vh] overflow-y-auto relative">
            <button id="close-modal" class="absolute top-4 right-4 text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white text-2xl z-10">×</button>

            <div id="modal-content" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Image Slider (Swiper) -->
                <div class="modal-swiper swiper w-full h-64 md:h-auto rounded-lg overflow-hidden relative"> <!-- Added relative -->
                    <div class="swiper-wrapper">
                        <!-- Slides will be loaded here by JS -->
                    </div>
                    <div class="swiper-pagination !bottom-2"></div> <!-- Adjusted position -->
                    <div class="swiper-button-prev !left-2 !text-white !bg-black/30 !w-8 !h-8 after:!text-xs"></div> <!-- Custom styles -->
                    <div class="swiper-button-next !right-2 !text-white !bg-black/30 !w-8 !h-8 after:!text-xs"></div> <!-- Custom styles -->
                </div>

                <!-- Details -->
                <div class="flex flex-col">
                    <h3 id="modal-title" class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Destination Name</h3>
                    <p id="modal-description" class="text-sm text-gray-600 dark:text-gray-400 mb-4 flex-grow">Full description goes here...</p>

                    <h4 class="font-semibold mb-2 text-gray-800 dark:text-gray-300">Highlights:</h4>
                    <ul id="modal-highlights" class="list-disc list-inside text-sm space-y-1 text-gray-600 dark:text-gray-400 mb-6">
                        <!-- Highlights loaded by JS -->
                    </ul>

                    <div class="flex flex-col sm:flex-row gap-3 mt-auto"> <!-- mt-auto pushes buttons down -->
                        <!-- View Packages Button REMOVED -->
                        <!-- <a id="modal-view-packages-btn" href="#" class="flex-1 ...">View Packages</a> -->
                        <!-- Wishlist Button - Make it full width -->
                        <button id="modal-add-wishlist-btn" class="w-full text-center bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300 flex items-center justify-center gap-2"
                            data-id="">
                            <i class="far fa-heart"></i> <span>Add to Wishlist</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- =============================== -->
    <!--          JavaScript             -->
    <!-- =============================== -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <!-- Easing for Scroll to Top -->
    <script>
        jQuery.extend(jQuery.easing, {
            easeInOutExpo: (x, t, b, c, d) => (t == 0) ? b : (t == d) ? b + c : (t /= d / 2) < 1 ? c / 2 * Math.pow(2, 10 * (t - 1)) + b : c / 2 * (-Math.pow(2, -10 * --t) + 2) + b
        });
    </script>
    <!-- Custom Script -->
    <script>
        $(document).ready(function() {

            // --- Wishlist Helper Functions (Copied) ---
            const WISHLIST_KEY = 'incredibleIndiaWishlist';
            function getWishlist() {
                const storedList = localStorage.getItem(WISHLIST_KEY);
                return storedList ? JSON.parse(storedList) : [];
            }
            function saveWishlist(list) {
                const uniqueList = [...new Set(list)];
                localStorage.setItem(WISHLIST_KEY, JSON.stringify(uniqueList));
                // NOTE: In a real app, you'd also send this update to the backend here
                // if the user is logged in.
                console.log("Wishlist updated (localStorage):", uniqueList);
            }
            function updateMainCardWishlistIcon(destinationName, isWishlisted) {
                const card = $(`#destination-grid .destination-card`).filter(function() {
                    return $(this).find('h3').text().trim() === destinationName;
                });
                if (card.length) {
                    const icon = card.find('.wishlist-toggle i');
                    icon.toggleClass('fas text-red-500', isWishlisted).toggleClass('far', !isWishlisted);
                }
            }
            function updateModalWishlistButton(destinationName, isWishlisted) {
                const button = $('#modal-add-wishlist-btn');
                const icon = button.find('i');
                const textSpan = button.find('span');
                button.toggleClass('wishlisted bg-red-100 dark:bg-red-900/30 border border-red-500', isWishlisted);
                icon.toggleClass('fas text-red-500', isWishlisted).toggleClass('far', !isWishlisted);
                textSpan.text(isWishlisted ? 'In Wishlist' : 'Add to Wishlist');
            }

            // --- Sample Data (Inline for this example - SAME AS BEFORE) ---
            const destinations = [{
                id: 'agra',
                name: 'Agra (Taj Mahal)',
                region: 'North',
                price: 15000,
                rating: 4.5,
                interests: ['Heritage', 'Spiritual', 'Culture'],
                seasons: ['Winter', 'Summer'],
                description: 'Home to the iconic Taj Mahal, a UNESCO World Heritage site and symbol of eternal love. Explore grand Mughal architecture like Agra Fort and Fatehpur Sikri nearby.',
                short_description: 'Iconic symbol of love, UNESCO site.',
                image: 'https://images.unsplash.com/photo-1564507592333-c60657eea523?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.0.3&q=80&w=1080',
                images: ['https://images.unsplash.com/photo-1564507592333-c60657eea523?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&ixlib=rb-4.0.3&q=80&w=1080', 'https://images.unsplash.com/photo-1548013146-72479768bada?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1176&q=80', 'img/t3.jpg'],
                highlights: ['Visit Taj Mahal at Sunrise', 'Explore Agra Fort', 'Discover Fatehpur Sikri']
            }, {
                id: 'kerala',
                name: 'Kerala Backwaters',
                region: 'South',
                price: 25000,
                rating: 4.8,
                interests: ['Nature', 'Relaxation', 'Culture', 'Beaches'],
                seasons: ['Winter', 'Monsoon'],
                description: 'Serene network of interconnected canals, rivers, lakes, and inlets. Enjoy traditional houseboat (Kettuvallam) cruises, lush paddy fields, and unique village life.',
                short_description: 'Relaxing houseboat cruises, lush scenery.',
                image: 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1035&q=80',
                images: ['https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1035&q=80', 'img/kerela.jpeg', 'img/Kerela.jpg'],
                highlights: ['Overnight Houseboat Stay', 'Watch a Kathakali Performance', 'Indulge in Ayurvedic Massage', 'Explore Alleppey']
            }, {
                id: 'ladakh',
                name: 'Leh-Ladakh',
                region: 'North',
                price: 38000,
                rating: 4.7,
                interests: ['Adventure', 'Spiritual', 'Nature', 'Culture'],
                seasons: ['Summer'],
                description: 'High-altitude cold desert known for dramatic lunar landscapes, stunning monasteries perched on hills, clear blue skies, and thrilling adventure opportunities.',
                short_description: 'Majestic mountains & monasteries.',
                image: 'img/lad.jpeg',
                images: ['img/lad.jpeg', 'img/Ladak2.jpg', 'img/Ladak1.jpg'],
                highlights: ['Visit Pangong Tso Lake', 'Drive Khardung La Pass', 'Explore Nubra Valley (Camels)', 'Tour Hemis Monastery']
            }, {
                id: 'goa',
                name: 'Goa',
                region: 'West',
                price: 18000,
                rating: 4.3,
                interests: ['Beaches', 'Nightlife', 'Adventure', 'Culture'],
                seasons: ['Winter', 'Summer', 'Monsoon'],
                description: 'India\'s beach capital, famous for its golden sands, lively shacks, Portuguese architecture, water sports, bustling nightlife, and relaxed vibe.',
                short_description: 'Sun, sand, and vibrant nightlife.',
                image: 'img/Goa1.jpg',
                images: ['img/Goa1.jpg', 'img/Goa4.jpg', 'img/Goa3.jpg'],
                highlights: ['Relax on Baga or Palolem Beach', 'Engage in Water Sports', 'Visit Old Goa Churches', 'Explore Anjuna Flea Market']
            }, {
                id: 'varanasi',
                name: 'Varanasi',
                region: 'North',
                price: 12000,
                rating: 4.6,
                interests: ['Spiritual', 'Heritage', 'Culture'],
                seasons: ['Winter'],
                description: 'One of the world\'s oldest living cities and the spiritual heart of India. Experience ancient ghats, mesmerizing Ganga Aarti, boat rides, and narrow alleys.',
                short_description: 'Spiritual ghats, Ganga aarti.',
                image: 'https://images.unsplash.com/photo-1561359313-0639aad49ca6?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80',
                images: ['https://images.unsplash.com/photo-1561359313-0639aad49ca6?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80', 'img/varanasi1.jpg'],
                highlights: ['Witness Evening Ganga Aarti', 'Sunrise Boat Ride on Ganges', 'Explore Kashi Vishwanath Temple', 'Walk the Ghats']
            }, {
                id: 'jaipur',
                name: 'Jaipur (Pink City)',
                region: 'North',
                price: 16000,
                rating: 4.4,
                interests: ['Heritage', 'Culture', 'Shopping', 'City Life'],
                seasons: ['Winter', 'Spring'],
                description: 'Capital of Rajasthan, famed for its pink-hued buildings, majestic forts like Amer Fort, intricate palaces (Hawa Mahal, City Palace), and vibrant markets.',
                short_description: 'Royal forts, palaces, vibrant markets.',
                image: 'img/Jaipur 2.jpg',
                images: ['img/Jaipur 2.jpg', 'img/Jaipur 3.jpg', 'img/Jaipur 5.jpg'],
                highlights: ['Explore Amer Fort (Amber Palace)', 'Admire Hawa Mahal (Wind Palace)', 'Visit City Palace Complex', 'Shop in Johari Bazaar']
            }, {
                id: 'mumbai',
                name: 'Mumbai (Bombay)',
                region: 'West',
                price: 20000,
                rating: 4.2,
                interests: ['City Life', 'Culture', 'Heritage', 'Nightlife'],
                seasons: ['Winter', 'Monsoon', 'Year-Round'],
                description: 'India\'s bustling financial capital and home to Bollywood. Offers a mix of colonial architecture, vibrant street life, seaside promenades, and diverse culinary scenes.',
                short_description: 'Bollywood, colonial architecture, street food.',
                image: 'https://images.unsplash.com/photo-1566552881560-0be862a7c445?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1032&q=80',
                images: ['https://images.unsplash.com/photo-1566552881560-0be862a7c445?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format=fit=crop&w=1032&q=80', 'https://images.unsplash.com/photo-1570168007204-dfb528c6958f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format=fit=crop&w=1170&q=80', 'img/mumbai 1.jpg'],
                highlights: ['Visit Gateway of India', 'Walk along Marine Drive', 'Explore Chhatrapati Shivaji Terminus', 'Experience Dharavi Slum Tour (optional)']
            }, {
                id: 'rishikesh',
                name: 'Rishikesh',
                region: 'North',
                price: 14000,
                rating: 4.5,
                interests: ['Spiritual', 'Adventure', 'Nature', 'Yoga'],
                seasons: ['Winter', 'Summer', 'Spring'],
                description: 'Known as the "Yoga Capital of the World," nestled in the Himalayan foothills beside the Ganges River. Famous for ashrams, yoga retreats, and adventure sports like rafting.',
                short_description: 'Yoga capital, Ganges river, adventure sports.',
                image: 'img/rishikesh 3.jpg',
                images: ['img/rishikesh 3.jpg', 'img/rishikesh 1.jpg', 'img/rishikesh 2.jpg'],
                highlights: ['Attend Ganga Aarti at Triveni Ghat', 'Practice Yoga/Meditation', 'White Water Rafting', 'Visit Beatles Ashram']
            }, {
                id: 'andaman',
                name: 'Andaman & Nicobar',
                region: 'Islands',
                price: 45000,
                rating: 4.7,
                interests: ['Beaches', 'Adventure', 'Nature', 'Relaxation'],
                seasons: ['Winter', 'Summer', 'Year-Round'],
                description: 'Archipelago in the Bay of Bengal known for its pristine beaches, coral reefs, scuba diving, snorkeling, lush forests, and historical sites like Cellular Jail.',
                short_description: 'Pristine beaches, coral reefs, scuba diving.',
                image: 'img/Andaman 1.jpg',
                images: ['img/Andaman 1.jpg', 'img/andaman 4.jpg', 'img/andaman 3.jpg'],
                highlights: ['Visit Radhanagar Beach (Havelock)', 'Scuba Diving or Snorkeling', 'Explore Cellular Jail', 'Relax at Laxmanpur Beach (Neil Island)']
            
            }];

            const regionColors = {
                'North': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                'South': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'East': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                'West': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                'Northeast': 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
                'Islands': 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200',
                'Default': 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
            };

            const grid = $('#destination-grid');
            const loadingIndicator = $('#loading-indicator');
            const noResults = $('#no-results');
            const showMoreBtn = $('#show-more-btn');
            const itemsPerLoad = 6; // Number of items to load initially and per click
            let itemsCurrentlyShown = 0;
            // Since filters are removed, use all destinations
            const allDestinationsData = [...destinations]; // Use a copy

            // --- Function to render destination cards (SAME AS BEFORE) ---
            function renderDestinations(dataToRender, append = false) {
                if (!append) {
                    grid.empty();
                    itemsCurrentlyShown = 0;
                    noResults.addClass('hidden');
                    loadingIndicator.removeClass('hidden'); // Show loading initially
                }

                // Simulate loading time
                 setTimeout(() => {
                    loadingIndicator.addClass('hidden');

                    const wishlist = getWishlist();
                    const startIndex = itemsCurrentlyShown;
                    const endIndex = Math.min(startIndex + itemsPerLoad, dataToRender.length);

                    if (startIndex === 0 && endIndex === 0 && !append) {
                        noResults.removeClass('hidden');
                        showMoreBtn.addClass('hidden');
                        return;
                    }

                    dataToRender.slice(startIndex, endIndex).forEach((dest, index) => {
                        const ratingStars = generateRatingStars(dest.rating);
                        const isWishlisted = wishlist.includes(dest.name);
                        const heartIconClass = isWishlisted ? 'fas fa-heart text-red-500' : 'far fa-heart';
                        const cardHtml = `
                            <div class="destination-card bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden transition-all duration-300 ease-in-out hover:shadow-2xl group transform hover:-translate-y-1 opacity-0 animate-fade-in flex flex-col"
                                style="animation-delay: ${index * 0.05}s;"
                                data-id="${dest.id}">
                                <div class="relative">
                                    <img src="${dest.image}" alt="${dest.name}" class="w-full h-52 object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                                    <div class="absolute top-2 right-2">
                                        <button class="wishlist-toggle text-white bg-black bg-opacity-40 rounded-full p-2 hover:bg-opacity-60 transition-colors duration-200" title="${isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist'}">
                                            <i class="${heartIconClass}"></i>
                                        </button>
                                    </div>
                                    <span class="region-badge absolute bottom-2 left-2 text-xs font-semibold px-2 py-1 rounded ${regionColors[dest.region] || regionColors['Default']}">${dest.region}</span>
                                </div>
                                <div class="p-5 flex flex-col flex-grow">
                                    <h3 class="text-xl font-bold mb-1 text-gray-900 dark:text-white">${dest.name}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2 flex-grow">${dest.short_description}</p>
                                    <div class="flex justify-between items-center mb-4 mt-auto">
                                        <div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block">From</span>
                                            <span class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">₹${dest.price.toLocaleString('en-IN')}</span>
                                        </div>
                                        <div class="flex items-center text-yellow-400" title="${dest.rating} out of 5 stars">
                                            ${ratingStars}
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(${dest.rating})</span>
                                        </div>
                                    </div>
                                    <!-- Buttons Section - Only Quick View -->
                                    <div class="pt-2 border-t border-gray-100 dark:border-gray-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out">
                                        <!-- Details Button REMOVED -->
                                        <!-- <button class="view-details-btn ...">Details</button> -->
                                        <!-- Quick View Button - Made full width -->
                                        <button class="quick-view-btn w-full bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-800 dark:text-white text-sm py-2 px-3 rounded-md transition-colors duration-300" data-destination-id="${dest.id}">Quick View</button>
                                    </div>
                                </div>
                            </div>`;
                        grid.append(cardHtml);
                    });

                    itemsCurrentlyShown = endIndex;
                    showMoreBtn.toggleClass('hidden', itemsCurrentlyShown >= dataToRender.length);
                 }, 150); // Short delay to show loading
            }

            // --- Function to generate star ratings (Copied - SAME AS BEFORE) ---
            function generateRatingStars(rating) {
                let stars = '';
                const fullStars = Math.floor(rating);
                const halfStar = rating % 1 >= 0.4;
                const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);
                for (let i = 0; i < fullStars; i++) stars += '<i class="fas fa-star text-sm"></i>';
                if (halfStar) stars += '<i class="fas fa-star-half-alt text-sm"></i>';
                for (let i = 0; i < emptyStars; i++) stars += '<i class="far fa-star text-sm text-gray-300"></i>';
                return stars;
            }

            // --- Function to populate Trending Carousel (Copied - SAME AS BEFORE) ---
            function populateTrendingCarousel() {
                const trendingWrapper = $('.trending-swiper .swiper-wrapper');
                trendingWrapper.empty();
                const trendingSubset = destinations.filter(d => d.rating >= 4.5).sort(() => 0.5 - Math.random()).slice(0, 8);
                trendingSubset.forEach(dest => {
                    const slideHtml = `
                         <div class="swiper-slide">
                             <a href="details.html?id=${dest.id}" class="block relative rounded-lg overflow-hidden shadow-md group h-64"> <!-- Link to details page (if it exists) -->
                                 <img src="${dest.image}" alt="${dest.name}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                                 <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                                 <div class="absolute bottom-0 left-0 p-4 text-white">
                                     <h4 class="font-semibold text-lg">${dest.name}</h4>
                                     <p class="text-sm line-clamp-1">${dest.short_description}</p>
                                 </div>
                             </a>
                         </div>`;
                    trendingWrapper.append(slideHtml);
                });
                new Swiper('.trending-swiper', {
                    loop: trendingSubset.length > 4,
                    slidesPerView: 1.2, spaceBetween: 15, centeredSlides: false,
                    autoplay: { delay: 4000, disableOnInteraction: false },
                    pagination: { el: '.swiper-pagination', clickable: true },
                    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                    breakpoints: { 640: { slidesPerView: 2.5, spaceBetween: 20 }, 1024: { slidesPerView: 3.5, spaceBetween: 30 } }
                });
            }

            // --- Show More Button Logic (Still relevant - SAME AS BEFORE) ---
            showMoreBtn.on('click', function() {
                renderDestinations(allDestinationsData, true); // Render next batch of all data
            });

            // --- Navbar JS (MATCHES INDEX.PHP, HANDLES SCROLL CLASS) ---
            const nav = $('#navbar');
            $(window).scroll(function() {
                const scrolled = $(window).scrollTop() > 80; // Use same threshold as index
                nav.toggleClass('navbar-scrolled', scrolled);
                nav.toggleClass('py-4', !scrolled).toggleClass('py-3', scrolled);

                // Highlight active nav link based on scroll (NO CHANGE NEEDED HERE, CSS handles colors)
                const isScrolled = nav.hasClass('navbar-scrolled');
                $('.nav-links a').removeClass('border-b-2 border-orange-400 font-semibold'); // Reset all first
                // Re-apply highlight based on CSS rules (no JS color change needed)
                $('.nav-links a[href="explore.php"]').addClass('border-b-2 border-orange-400 font-semibold');


            });
            // Initial check for scroll position and highlight
             $(window).trigger('scroll');


            // Scroll to Top Button Visibility (from index.php - SAME AS BEFORE)
            $('#scrollToTop').click(() => $('html, body').animate({ scrollTop: 0 }, 800, 'easeInOutExpo'));
            $(window).scroll(function() {
                 $('#scrollToTop').toggleClass('opacity-100 visible scale-100', $(window).scrollTop() > 500)
                     .toggleClass('opacity-0 invisible scale-90', $(window).scrollTop() <= 500);
            });
             if ($(window).scrollTop() > 500) $('#scrollToTop').addClass('opacity-100 visible scale-100').removeClass('opacity-0 invisible scale-90');


            // Mobile Menu Toggle (from index.php - SAME AS BEFORE)
            const mobileMenu = $('.mobile-menu');
            const menuOverlay = $('#menu-overlay');
            const burgerIcon = $('.mobile-menu-button i');
            function toggleMenu(open) {
                mobileMenu.toggleClass('open', open);
                menuOverlay.toggleClass('open', open);
                // Burger icon color is handled by CSS #navbar rules now
                burgerIcon.toggleClass('fa-bars fa-times', open);
                $('body').css('overflow', open ? 'hidden' : '');
            }
            $('.mobile-menu-button').click(() => toggleMenu(!mobileMenu.hasClass('open')));
            $('#close-menu-btn').click(() => toggleMenu(false));
            menuOverlay.click(() => toggleMenu(false));
            $('.mobile-menu a, .mobile-menu button').not('#close-menu-btn').click(() => toggleMenu(false));


            // --- Wishlist Toggle on Card (Copied - SAME AS BEFORE) ---
            grid.on('click', '.wishlist-toggle', function(e) {
                e.stopPropagation(); // Prevent card click event
                const button = $(this);
                const card = button.closest('.destination-card');
                const destinationName = card.find('h3').text().trim();
                if (!destinationName) return;
                let currentWishlist = getWishlist();
                const index = currentWishlist.indexOf(destinationName);
                let isNowWishlisted = index === -1;
                if (isNowWishlisted) {
                    currentWishlist.push(destinationName);
                } else {
                    currentWishlist.splice(index, 1);
                }
                saveWishlist(currentWishlist); // Save to localStorage
                updateMainCardWishlistIcon(destinationName, isNowWishlisted);
                button.attr('title', isNowWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist');
                 // Update modal button if open
                if (!$('#quick-view-modal').hasClass('hidden') && $('#modal-add-wishlist-btn').data('name') === destinationName) {
                    updateModalWishlistButton(destinationName, isNowWishlisted);
                }
                 // Optional: Add visual feedback
                 button.find('i').addClass('transform scale-125').delay(150).queue(function(next){ $(this).removeClass('transform scale-125'); next(); });
            });

            // --- View Details Button Event Listener REMOVED ---
            // grid.on('click', '.view-details-btn', function() { ... });

            // --- Quick View Modal (Copied & Adjusted - SAME AS BEFORE) ---
            let modalSwiperInstance = null;
            grid.on('click', '.quick-view-btn', function() {
                const destinationId = $(this).data('destination-id');
                const destData = destinations.find(d => d.id === destinationId);
                if (destData) {
                    $('#modal-title').text(destData.name);
                    $('#modal-description').text(destData.description);
                    const highlightsList = $('#modal-highlights').empty();
                    destData.highlights.forEach(hl => highlightsList.append(`<li>${hl}</li>`));
                    const modalSwiperWrapper = $('#quick-view-modal .swiper-wrapper').empty();
                    destData.images.forEach(imgUrl => modalSwiperWrapper.append(`<div class="swiper-slide"><img src="${imgUrl}" alt="${destData.name} Image" class="w-full h-full object-cover" loading="lazy"></div>`));

                    // View Packages Button link REMOVED
                    // $('#modal-view-packages-btn').attr('href', ...);

                    const modalWishlistBtn = $('#modal-add-wishlist-btn');
                    modalWishlistBtn.data('id', destData.id);
                    modalWishlistBtn.data('name', destData.name); // Store name
                    const isWishlisted = getWishlist().includes(destData.name);
                    updateModalWishlistButton(destData.name, isWishlisted);
                    if (modalSwiperInstance) modalSwiperInstance.destroy(true, true);
                    $('#quick-view-modal').removeClass('hidden');
                    $('body').addClass('overflow-hidden');
                    setTimeout(() => {
                        modalSwiperInstance = new Swiper('.modal-swiper', {
                            loop: destData.images.length > 1,
                            pagination: { el: '.swiper-pagination', clickable: true },
                            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                            grabCursor: true,
                        });
                    }, 50);
                } else { console.error("Destination data not found for Quick View:", destinationId); }
            });

            // Wishlist Toggle on Modal Button (Copied - SAME AS BEFORE)
            $('#modal-add-wishlist-btn').on('click', function() {
                const button = $(this);
                const destinationName = button.data('name');
                if (!destinationName) return;
                let currentWishlist = getWishlist();
                const index = currentWishlist.indexOf(destinationName);
                let isNowWishlisted = index === -1;
                if (isNowWishlisted) {
                    currentWishlist.push(destinationName);
                } else {
                    currentWishlist.splice(index, 1);
                }
                saveWishlist(currentWishlist); // Save to localStorage
                updateModalWishlistButton(destinationName, isNowWishlisted);
                updateMainCardWishlistIcon(destinationName, isNowWishlisted); // Update corresponding card icon too
                 // Optional: Add visual feedback
                 button.find('i').addClass('transform scale-110').delay(150).queue(function(next){ $(this).removeClass('transform scale-110'); next(); });
            });

            // Close Modal (Copied - SAME AS BEFORE)
            $('#close-modal, #quick-view-modal').on('click', function(e) {
                if (e.target === this || $(e.target).is('#close-modal')) {
                    $('#quick-view-modal').addClass('hidden');
                    $('body').removeClass('overflow-hidden');
                }
            });

            // --- Initial Load ---
            populateTrendingCarousel(); // Populate and init trending swiper
            renderDestinations(allDestinationsData); // Load initial grid data (first batch)

            // Footer Year
            $('#currentYear').text(new Date().getFullYear());

            // Initial Nav state trigger AFTER other setup
            $(window).trigger('scroll');


        }); // End document ready
    </script>

</body>


</html>
