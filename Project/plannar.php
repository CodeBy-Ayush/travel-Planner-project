<?php
session_start(); 

// --- Optional: Login Check -----
// Uncomment this block if users MUST be logged in to use the planner
// /*
// if (!isset($_SESSION["user_id"])) {
//     header("Location: login.php");
//     exit();
// }
// */
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Travel Planner - Incredible India</title>
    <meta name="description" content="Generate a personalized travel itinerary for your trip to India using our AI Travel Architect.">
    <meta name="keywords" content="AI travel planner, India itinerary generator, travel plan India, AI trip planner, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'guest'; ?>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
     <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500&display=swap">
    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        /* --- Consistent Styles from index.php/dashboard.php --- */
        :root {
            --color-sunset-orange: #FF6B3D; --color-indigo-blue: #3F51B5; --color-sand-beige: #f1f5f9; --color-himalaya-gray: #E0E0E0; --color-forest-green: #388E3C; --color-text-light: #e2e8f0; --color-text-dark: #334155; --color-heading-dark: #1e293b; --color-heading-light: #cbd5e1; --color-bg-light: #ffffff; --color-bg-dark: #0f172a; --color-card-light: #ffffff; --color-card-dark: #1e293b; --color-navbar-bg-light: rgba(255, 255, 255, 0.9); --color-navbar-bg-dark: rgba(30, 41, 59, 0.9);
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            transition: background-color 0.4s ease, color 0.4s ease;
            background-color: var(--color-bg-light); /* White background likes index */
            color: var(--color-text-dark);
        }
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }
        .bg-indigo-primary { background-color: var(--color-indigo-blue); } .bg-orange-accent { background-color: var(--color-sunset-orange); } .text-indigo-primary { color: var(--color-indigo-blue); } .text-orange-accent { color: var(--color-sunset-orange); } .hover\:text-orange-accent:hover { color: var(--color-sunset-orange); } .hover\:bg-indigo-primary-darker:hover { background-color: #303F9F; } .hover\:bg-orange-accent-darker:hover { background-color: #F57C00; }
        .dark-mode-transition { transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease, fill 0.4s ease, box-shadow 0.4s ease; }
        .pulse-hover:hover { animation: pulse 1.2s infinite; } @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.03); } 100% { transform: scale(1); } }
        @media (max-width: 768px) { .nav-links { display: none; } .mobile-menu-button { display: block; } } @media (min-width: 769px) { .mobile-menu-button { display: none; } .mobile-menu { display: none !important; } }

        /* Navbar Style (Consistent - Fixed) */
        #navbar {
            transition: background-color 0.4s ease-out, box-shadow 0.4s ease-out, padding 0.3s ease-out;
            position: fixed; top: 0; left: 0; width: 100%; z-index: 50;
        }
        #navbar.navbar-scrolled {
            background-color: var(--color-navbar-bg-light); box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding-top: 0.75rem; padding-bottom: 0.75rem;
        }
        #navbar.navbar-scrolled .nav-links a, #navbar.navbar-scrolled .mobile-menu-button, #navbar.navbar-scrolled .navbar-logo-base, #navbar.navbar-scrolled .navbar-logo-icon { color: var(--color-text-dark); }
        #navbar.navbar-scrolled .navbar-logo-accent{ color: var(--color-sunset-orange); }
        #navbar.navbar-scrolled .nav-links a:hover { color: var(--color-orange-accent); }
        #navbar.navbar-scrolled .login-signup-btn { background-color: var(--color-indigo-blue); color: white; }
        #navbar.navbar-scrolled .login-signup-btn.login-btn { background-color: var(--color-indigo-blue); color: white; }
        #navbar.navbar-scrolled .login-signup-btn.signup-btn { background-color: var(--color-orange-accent); color: white; }
        #navbar.navbar-scrolled .login-signup-btn:hover { background-color: #303F9F; }
        #navbar.navbar-scrolled .login-signup-btn.signup-btn:hover { background-color: #F57C00; }
        #navbar.navbar-scrolled .nav-links a[aria-current="page"] { color: var(--color-orange-accent); font-weight: 600; border-bottom: 2px solid var(--color-orange-accent); padding-bottom: 2px; }

        /* Mobile Menu */
        .mobile-menu { background-color: var(--color-card-light); color: var(--color-text-dark); box-shadow: -5px 0 15px rgba(0, 0, 0, 0.2); position: fixed; top: 0; right: -100%; width: 75%; max-width: 300px; height: 100%; z-index: 100; transition: right 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); padding: 2rem; overflow-y: auto; display: block; } .mobile-menu.open { right: 0; }
        #menu-overlay { position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 99; opacity: 0; visibility: hidden; transition: opacity 0.4s ease, visibility 0.4s ease; } #menu-overlay.open { opacity: 1; visibility: visible; }
        .mobile-menu a[aria-current="page"] { color: var(--color-orange-accent); font-weight: 600; }

        /* Footer Wave */
        .footer-wave path { transition: fill 0.4s ease; }
        .fill-bg-light { fill: var(--color-bg-light); }

        /* Back to Top */
        #scrollToTop { transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease, background-color 0.3s ease; }

        /* Form input styling (consistent) */
        .form-input, .form-select { border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.625rem 1rem; font-size: 1rem; width: 100%; transition: border-color 0.2s ease, box-shadow 0.2s ease; color: var(--color-text-dark); background-color: var(--color-bg-light); box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .form-input:focus, .form-select:focus { outline: none; border-color: var(--color-indigo-blue); box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.2); }
        .form-select { appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 20 20%22 fill=%22%236b7280%22><path fill-rule=%22evenodd%22 d=%22M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z%22 clip-rule=%22evenodd%22 /></svg>'); background-repeat: no-repeat; background-position: right 0.7rem center; background-size: 1.5em 1.5em; padding-right: 2.5rem; }
        .form-checkbox { border-radius: 0.25rem; border-color: #d1d5db; color: var(--color-indigo-blue); width: 1rem; height: 1rem; transition: border-color 0.2s ease; }
        .form-checkbox:focus { outline: none; box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.2); border-color: var(--color-indigo-blue); }
        select:required:invalid { color: #6b7280; } option[value=""][disabled] { display: none; } option { color: var(--color-text-dark); }

        /* Simple CSS Spinner */
        .loader { border: 5px solid #f1f5f9; border-top: 5px solid var(--color-indigo-blue); border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 20px auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Hide elements initially */
        #loading, #results-title, #download-section, #error-message { display: none; }
        #error-message { opacity: 0; transition: opacity 0.3s ease-in-out; }

        /* Day card styling - ENHANCED */
        .day-card { background-color: #f9fafb; border-radius: 0.75rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.04); padding: 1.5rem; margin-bottom: 1.5rem; border-left: 6px solid var(--color-indigo-blue); transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1); position: relative; overflow: hidden; }
        .day-card:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); transform: translateY(-2px); }
        .day-card h3 { color: var(--color-indigo-blue); font-size: 1.5rem; font-weight: 600; margin-bottom: 0.75rem; }
        .day-card p { color: var(--color-text-dark); margin-bottom: 1rem; line-height: 1.6; }
        .day-card ul { list-style-type: none; padding-left: 0; space-y: 0.5rem; }
        .day-card li { color: var(--color-text-dark); padding-left: 1.5rem; position: relative; font-size: 0.95rem; }
        .day-card li::before { content: "\f00c"; font-family: 'Font Awesome 6 Free'; font-weight: 900; color: var(--color-forest-green); position: absolute; left: 0; top: 2px; font-size: 0.8rem; }
        .day-card-initial { opacity: 0; transform: translateY(20px) scale(0.98); }

        /* Main content padding */
        main { padding-top: 80px; }

        /* Form Card Style */
        .form-card { background-color: var(--color-card-light); border-radius: 1rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1); transition: all 0.3s ease-in-out; }
        .form-card:hover { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); }

        /* Print Specific Styles (Adjusted for new card style) */
        @media print { /* Same print styles as before */ }
    </style>
    <script>
        // Force light mode always
        document.documentElement.classList.remove('dark');
        localStorage.removeItem('theme');
    </script>
</head>

<body class="bg-bg-light min-h-screen font-sans text-gray-800 dark-mode-transition"> <!-- Match index.php body bg -->

    <!-- =============================== -->
    <!--      Navbar (Consistent)        -->
    <!-- =============================== -->
    <nav class="fixed top-0 left-0 w-full z-50 transition-all duration-300" id="navbar"> <!-- JS will add .navbar-scrolled -->
        <div class="container mx-auto px-4 flex justify-between items-center"> <!-- Padding adjusted by .navbar-scrolled -->
            <a href="index.php" class="flex items-center pulse-hover">
                <span class="text-2xl md:text-3xl font-bold">
                    <!-- Icons and text color will be controlled by JS adding .navbar-scrolled -->
                    <i class="fas fa-map-marked-alt mr-2 navbar-logo-icon transition-colors duration-300"></i>
                    <span class="navbar-logo-base transition-colors duration-300">Incredible</span><span class="navbar-logo-accent transition-colors duration-300">India</span>
                </span>
            </a>
            <div class="nav-links hidden md:flex items-center space-x-6">
                 <a href="index.php#home" class="hover:text-orange-accent transition-colors font-medium">Home</a>
                 <?php if (isset($_SESSION['user_id'])): ?>
                 <a href="dashboard.php" class="hover:text-orange-accent transition-colors font-medium">Dashboard</a>
                 <?php endif; ?>
                <a href="destination.php" class="hover:text-orange-accent transition-colors font-medium">Destinations</a>
                <a href="Plannar.php" aria-current="page" class="transition-colors font-medium">AI Planner</a> <!-- Active Link -->
                <!-- Forum Removed -->
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="bookings.php" class="hover:text-orange-accent transition-colors font-medium">Bookings</a>
                <!-- Profile Removed -->
                <?php endif; ?>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle REMOVED -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="px-5 py-2 text-white rounded-full font-semibold hover:shadow-lg transition-all text-sm login-signup-btn signup-btn"> <!-- Added signup-btn class -->
                        Logout
                    </a>
                <?php else: ?>
                    <a href="login.php" class="px-5 py-2 rounded-full font-semibold transition-all text-sm login-signup-btn login-btn">
                        Login
                    </a>
                     <a href="signup.php" class="hidden sm:inline-block px-5 py-2 text-white rounded-full font-semibold transition-all text-sm login-signup-btn signup-btn">
                        Sign Up
                    </a>
                <?php endif; ?>
                <button class="mobile-menu-button md:hidden text-2xl transition-colors duration-300"> <!-- Color controlled by JS -->
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div class="mobile-menu dark-mode-transition">
            <button id="close-menu-btn" class="absolute top-4 right-4 p-2 text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-white text-2xl">×</button>
            <div class="mt-12 space-y-4">
                 <a href="index.php#home" class="block py-2 hover:text-orange-500 transition-colors text-lg">Home</a>
                 <?php if (isset($_SESSION['user_id'])): ?>
                 <a href="dashboard.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Dashboard</a>
                 <?php endif; ?>
                <a href="explore.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Destinations</a>
                <a href="Plannar.php" aria-current="page" class="block py-2 text-orange-500 font-semibold text-lg">AI Planner</a> <!-- Active -->
                 <!-- Forum Removed -->
                 <?php if (isset($_SESSION['user_id'])): ?>
                <a href="bookings.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Bookings</a>
                 <!-- Profile Removed -->
                 <a href="logout.php" class="mt-6 w-full bg-orange-accent px-6 py-3 text-white rounded-full font-semibold hover:shadow-lg transition-all text-center">Logout</a>
                <?php else: ?>
                 <a href="login.php" class="mt-6 w-full bg-indigo-600 px-6 py-3 text-white rounded-full font-semibold hover:shadow-lg transition-all text-center">Login</a>
                 <a href="signup.php" class="mt-4 w-full bg-orange-accent px-6 py-3 text-white rounded-full font-semibold hover:shadow-lg transition-all text-center">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
        <div id="menu-overlay" class="fixed inset-0 bg-black/50 z-90 hidden"></div>
    </nav>

    <!-- =============================== -->
    <!--          Main Content           -->
    <!-- =============================== -->
    <main> <!-- Added padding-top via CSS -->
        <div class="container mx-auto p-4 sm:p-6 md:p-10 max-w-4xl">

            <header class="text-center mb-10 md:mb-16" data-aos="fade-down">
                <!-- Use site's primary icon color -->
                <i class="fas fa-robot fa-3x mx-auto mb-4 text-indigo-primary"></i>
                <h1 class="text-4xl md:text-5xl font-bold text-heading-dark mb-3 tracking-tight">AI Travel Architect</h1>
                <p class="text-lg md:text-xl text-indigo-600">Craft your perfect Indian journey, intelligently.</p>
            </header>

            <!-- Form Card -->
            <div id="travel-form-card" class="form-card p-6 md:p-8 mb-10" data-aos="fade-up"> <!-- Wrapped form in styled div -->
                <form id="travel-form">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <!-- Destination -->
                        <div>
                            <label for="destination" class="block text-sm font-semibold text-gray-700 mb-1">Destination <span class="text-red-500">*</span></label>
                            <input type="text" id="destination" name="destination" required placeholder="e.g., Jaipur, Kerala, Ladakh" class="form-input"> <!-- Apply consistent class -->
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration" class="block text-sm font-semibold text-gray-700 mb-1">Number of Days <span class="text-red-500">*</span></label>
                            <select id="duration" name="duration" required class="form-select"> <!-- Apply consistent class -->
                                <option value="" disabled selected>Select duration</option>
                                <option value="1">1 Day</option>
                                <option value="2">2 Days</option>
                                <option value="3">3 Days</option>
                                <option value="4">4 Days</option>
                                <option value="5">5 Days</option>
                                <option value="6">6 Days</option>
                                <option value="7">7 Days</option>
                                <option value="10">10 Days</option>
                                <option value="14">14 Days</option>
                            </select>
                        </div>

                        <!-- Budget -->
                        <div>
                            <label for="budget" class="block text-sm font-semibold text-gray-700 mb-1">Budget <span class="text-red-500">*</span></label>
                            <select id="budget" name="budget" required class="form-select"> <!-- Apply consistent class -->
                                <option value="" disabled selected>Select budget level</option>
                                <option value="Low">Budget-Friendly (Backpacker/Hostels)</option>
                                <option value="Medium">Mid-Range (Comfortable Hotels)</option>
                                <option value="High">Luxury (Premium Stays)</option>
                            </select>
                        </div>

                        <!-- Season -->
                        <div>
                            <label for="season" class="block text-sm font-semibold text-gray-700 mb-1">Season of Travel <span class="text-red-500">*</span></label>
                            <select id="season" name="season" required class="form-select"> <!-- Apply consistent class -->
                                <option value="" disabled selected>Select season</option>
                                <option value="Spring">Spring (Mar-May)</option>
                                <option value="Summer">Summer (Jun-Aug)</option>
                                <option value="Monsoon">Monsoon (Jul-Sep)</option>
                                <option value="Autumn">Autumn/Fall (Oct-Nov)</option>
                                <option value="Winter">Winter (Dec-Feb)</option>
                                <option value="Any">Any / Flexible</option>
                            </select>
                        </div>
                    </div>

                    <!-- Interests -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Interests & Vibe (Select what fits best)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-x-4 gap-y-3">
                            <label class="flex items-center space-x-2.5 cursor-pointer group">
                                <input type="checkbox" name="interests[]" value="Adventure" class="form-checkbox group-hover:border-indigo-400">
                                <span class="text-gray-700 text-sm group-hover:text-indigo-800 transition duration-150 ease-in-out">Adventure</span>
                            </label>
                             <label class="flex items-center space-x-2.5 cursor-pointer group">
                                <input type="checkbox" name="interests[]" value="Culture" class="form-checkbox group-hover:border-indigo-400">
                                <span class="text-gray-700 text-sm group-hover:text-indigo-800 transition duration-150 ease-in-out">Culture & History</span>
                            </label>
                             <label class="flex items-center space-x-2.5 cursor-pointer group">
                                <input type="checkbox" name="interests[]" value="Nature" class="form-checkbox group-hover:border-indigo-400">
                                <span class="text-gray-700 text-sm group-hover:text-indigo-800 transition duration-150 ease-in-out">Nature & Outdoors</span>
                            </label>
                             <label class="flex items-center space-x-2.5 cursor-pointer group">
                                <input type="checkbox" name="interests[]" value="Food" class="form-checkbox group-hover:border-indigo-400">
                                <span class="text-gray-700 text-sm group-hover:text-indigo-800 transition duration-150 ease-in-out">Food & Culinary</span>
                            </label>
                             <label class="flex items-center space-x-2.5 cursor-pointer group">
                                <input type="checkbox" name="interests[]" value="Relaxation" class="form-checkbox group-hover:border-indigo-400">
                                <span class="text-gray-700 text-sm group-hover:text-indigo-800 transition duration-150 ease-in-out">Relaxation</span>
                            </label>
                             <label class="flex items-center space-x-2.5 cursor-pointer group">
                                <input type="checkbox" name="interests[]" value="Spiritual" class="form-checkbox group-hover:border-indigo-400">
                                <span class="text-gray-700 text-sm group-hover:text-indigo-800 transition duration-150 ease-in-out">Spiritual</span>
                            </label>
                            <label class="flex items-center space-x-2.5 cursor-pointer group">
                                <input type="checkbox" name="interests[]" value="Shopping" class="form-checkbox group-hover:border-indigo-400">
                                <span class="text-gray-700 text-sm group-hover:text-indigo-800 transition duration-150 ease-in-out">Shopping</span>
                            </label>
                             <label class="flex items-center space-x-2.5 cursor-pointer group">
                                <input type="checkbox" name="interests[]" value="Wildlife" class="form-checkbox group-hover:border-indigo-400">
                                <span class="text-gray-700 text-sm group-hover:text-indigo-800 transition duration-150 ease-in-out">Wildlife</span>
                            </label>
                             <label class="flex items-center space-x-2.5 cursor-pointer group">
                                <input type="checkbox" name="interests[]" value="Photography" class="form-checkbox group-hover:border-indigo-400">
                                <span class="text-gray-700 text-sm group-hover:text-indigo-800 transition duration-150 ease-in-out">Photography</span>
                            </label>
                             <label class="flex items-center space-x-2.5 cursor-pointer group">
                                <input type="checkbox" name="interests[]" value="Art" class="form-checkbox group-hover:border-indigo-400">
                                <span class="text-gray-700 text-sm group-hover:text-indigo-800 transition duration-150 ease-in-out">Art & Museums</span>
                            </label>
                            <label class="flex items-center space-x-2.5 cursor-pointer group">
                                <input type="checkbox" name="interests[]" value="Nightlife" class="form-checkbox group-hover:border-indigo-400">
                                <span class="text-gray-700 text-sm group-hover:text-indigo-800 transition duration-150 ease-in-out">Nightlife</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 pt-5 text-center">
                        <!-- Use theme button style -->
                        <button type="submit" id="submit-btn" class="inline-flex items-center justify-center px-10 py-3 border border-transparent text-base font-semibold rounded-full shadow-lg text-white bg-indigo-primary hover:bg-indigo-primary-darker focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 ease-in-out transform hover:scale-105 active:scale-95">
                            <i class="fas fa-robot mr-2"></i> <!-- Changed icon -->
                            Generate Itinerary
                        </button>
                    </div>
                </form>
            </div><!-- End Form Card -->

            <!-- Loading Indicator -->
            <div id="loading" class="text-center py-10" aria-live="polite">
                <div class="loader"></div>
                <p class="text-indigo-700 mt-4 text-lg font-medium">Crafting your adventure... Hang tight!</p>
                <p class="text-gray-600 text-sm">The AI is exploring options based on your preferences.</p>
            </div>

            <!-- Results Area -->
            <div class="mt-12">
                <h2 id="results-title" class="text-3xl font-bold text-heading-dark mb-6 text-center">Your Trip Plan</h2>
                <div id="results" class="space-y-6">
                    <!-- Itinerary cards will be dynamically inserted here by script.js -->
                </div>
            </div>

            <!-- Download Button Section -->
            <div id="download-section" class="text-center mt-8">
                <!-- Secondary button style -->
                <button id="download-pdf-btn" class="inline-flex items-center justify-center px-6 py-2.5 border border-indigo-primary text-base font-medium rounded-full shadow-sm text-indigo-primary bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out transform hover:scale-105">
                    <i class="fas fa-download mr-2"></i> <!-- Changed icon -->
                    Download as PDF
                </button>
                <p class="text-xs text-gray-500 mt-2">(Uses Print Dialog: Choose 'Save as PDF' as Destination)</p>
            </div>


            <!-- Error Message Area -->
            <div id="error-message" role="alert" class="hidden mt-8 p-5 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-lg shadow-md text-center" aria-live="assertive">
                <strong class="font-bold">Oops! Something went wrong.</strong>
                <span class="block sm:inline ml-2" id="error-text">Could not generate the itinerary. Please try again.</span>
            </div>

        </div> <!-- End Container -->
    </main>

    <!-- =============================== -->
    <!--      Footer (Consistent)        -->
    <!-- =============================== -->
     <footer class="bg-indigo-900 dark:bg-slate-900 text-indigo-100 dark:text-gray-300 pt-16 pb-10 relative mt-16">
        <!-- Wave SVG -->
        <div class="absolute top-0 left-0 w-full overflow-hidden leading-none -translate-y-px">
            <svg class="relative block w-full h-[80px] md:h-[100px] dark-mode-transition footer-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                 <!-- Fill matches body background (white) -->
                 <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-bg-light dark:fill-bg-dark"></path> <!-- Changed fill class -->
             </svg>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                 <!-- About -->
                <div>
                    <h3 class="text-2xl font-bold mb-6 text-white">Incredible<span class="text-orange-400">India</span></h3>
                    <p class="text-indigo-200 dark:text-gray-400 mb-6 text-sm leading-relaxed"> Your AI-powered guide to exploring India's diverse beauty. Plan smarter, travel better. </p>
                    <div class="flex space-x-4">
                         <a href="https://wa.me/919876543210" target="_blank" aria-label="WhatsApp" class="text-indigo-200 hover:text-green-500 transition-colors"><i class="fab fa-whatsapp fa-lg"></i></a>
                         <a href="#" target="_blank" aria-label="Instagram" class="text-indigo-200 hover:text-pink-500 transition-colors"><i class="fab fa-instagram fa-lg"></i></a>
                         <a href="#" target="_blank" aria-label="Twitter" class="text-indigo-200 hover:text-blue-400 transition-colors"><i class="fab fa-twitter fa-lg"></i></a>
                         <a href="#" target="_blank" aria-label="YouTube" class="text-indigo-200 hover:text-red-600 transition-colors"><i class="fab fa-youtube fa-lg"></i></a>
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
                         <li><a href="destination.php" class="text-indigo-200 hover:text-orange-300 transition-colors">Destinations</a></li>
                         <li><a href="Plannar.php" class="text-orange-300 transition-colors">AI Planner</a></li> <!-- Active -->
                          <?php if (isset($_SESSION['user_id'])): ?>
                         <li><a href="bookings.php" class="text-indigo-200 hover:text-orange-300 transition-colors">Bookings</a></li>
                          <?php endif; ?>
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


    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Easing for Scroll to Top -->
    <script>jQuery.extend(jQuery.easing,{easeInOutExpo:(x,t,b,c,d)=>(t==0)?b:(t==d)?b+c:(t/=d/2)<1?c/2*Math.pow(2,10*(t-1))+b:c/2*(-Math.pow(2,-10*--t)+2)+b});</script>
    <!-- Theme JS (Navbar, Mobile Menu, ScrollTop) -->
    <script>
        $(document).ready(function() {
             AOS.init({
                duration: 600,
                once: true,
                offset: 50
            });

             // --- Navbar JS (Apply scrolled state immediately for this page) ---
             const nav = $('#navbar');

             function applyScrolledNavbarState() {
                nav.addClass('navbar-scrolled'); // Add the class directly
                updateNavbarActiveLink(); // Style the active link
             }

             function handleScroll() {
                // Only handle scroll-to-top button visibility on this page
                const scrollTop = $(window).scrollTop();
                $('#scrollToTop').toggleClass('opacity-100 visible scale-100', scrollTop > 300)
                                 .toggleClass('opacity-0 invisible scale-90', scrollTop <= 300);
            }

            function updateNavbarActiveLink() {
                // Style active link directly (since navbar is always scrolled state)
                const activeColor = 'var(--color-orange-accent)';
                const defaultLinkColor = 'var(--color-text-dark)';

                // Reset others first
                 $('#navbar .nav-links a').not('[aria-current="page"]').css('color', defaultLinkColor).css('border-bottom','none').css('font-weight','500');
                // Apply active styles
                $('#navbar .nav-links a[aria-current="page"]')
                    .css('color', activeColor)
                    .css('border-bottom','2px solid var(--color-orange-accent)')
                    .css('font-weight','600');
                 // Also style mobile menu active link
                  $('.mobile-menu a').not('[aria-current="page"]').css('color', '').css('font-weight', 'normal');
                  $('.mobile-menu a[aria-current="page"]').css('color', activeColor).css('font-weight', '600');
            }

            applyScrolledNavbarState(); // Apply scrolled state on load
            $(window).scroll(handleScroll); // Handle scroll for Back-to-Top button
            handleScroll(); // Initial check for scroll-to-top

            $('#scrollToTop').click(() => $('html, body').animate({ scrollTop: 0 }, 600, 'easeInOutExpo'));

             // --- Mobile Menu Toggle (Consistent) ---
            const mobileMenu = $('.mobile-menu');
            const menuOverlay = $('#menu-overlay');
            const burgerIcon = $('.mobile-menu-button i');
            function toggleMenu(open) {
                mobileMenu.toggleClass('open', open);
                menuOverlay.toggleClass('open', open);
                burgerIcon.toggleClass('fa-bars fa-times', open);
                $('body').toggleClass('overflow-hidden', open);
                 // Ensure active link color is correct when menu opens
                if(open) updateNavbarActiveLink();
            }
            $('.mobile-menu-button').click(() => toggleMenu(!mobileMenu.hasClass('open')));
            $('#close-menu-btn').click(() => toggleMenu(false));
            menuOverlay.click(() => toggleMenu(false));
            $('.mobile-menu a, .mobile-menu button').not('#close-menu-btn').click(() => {
                 toggleMenu(false);
             });

             // Select Placeholder Styling Enhancement
             document.querySelectorAll('select[required]').forEach(select => {
                const updateColor = () => {
                    select.classList.toggle('text-gray-500', select.value === ""); // Use gray for placeholder
                    select.classList.toggle('text-gray-900', select.value !== ""); // Use default text color for selection
                };
                updateColor();
                select.addEventListener('change', updateColor);
            });

            // Footer Year
            $('#currentYear').text(new Date().getFullYear());

        });
    </script>
    <!-- Link to your ACTUAL AI Planner Script -->
    <script src="script.js"></script>
    <!-- Make sure script.js targets the correct element IDs:
        #travel-form
        #submit-btn
        #loading
        #results-title
        #results
        #download-section
        #download-pdf-btn
        #error-message
        #error-text
        And that it handles the result display (e.g., creating .day-card elements inside #results)
    -->

</body>


</html>


