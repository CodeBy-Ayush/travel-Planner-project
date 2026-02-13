<?php
session_start(); 
// --- Login Check (Keep this active for a dashboard) ---
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// --- Database Fetch (Keep commented as per original file state) ---

// Database connection details (reuse if you have a separate config files)
$servername = "localhost";
$username = "root"; // Your DB username
$password = "";     // Your DB password
$dbname = "travel"; // Your DB name

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    // Consider logging the error instead of dying in production
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];
$current_date = date("Y-m-d"); // Today’s date for comparisons

// Fetch Upcoming Trips (status = 'Confirmed' or 'Pending', AND future or current travel_dates)
$upcoming_sql = "SELECT booking_id, destination, travel_dates, status FROM bookings
                 WHERE user_id = ?
                 AND (status IN ('Pending', 'Confirmed')) AND travel_dates >= ?
                 ORDER BY travel_dates ASC";
$stmt_upcoming = $conn->prepare($upcoming_sql);
$stmt_upcoming->bind_param("is", $user_id, $current_date);
$stmt_upcoming->execute();
$upcoming_result = $stmt_upcoming->get_result();
$upcoming_bookings = $upcoming_result->fetch_all(MYSQLI_ASSOC);
$stmt_upcoming->close();


// Fetch Past Trips (status = 'Completed' OR past travel_dates)
$past_sql = "SELECT booking_id, destination, travel_dates, status FROM bookings
             WHERE user_id = ?
             AND (status = 'Completed' OR travel_dates < ?)
             ORDER BY travel_dates DESC LIMIT 5"; // Limit past bookings displayed initially
$stmt_past = $conn->prepare($past_sql);
$stmt_past->bind_param("is", $user_id, $current_date);
$stmt_past->execute();
$past_result = $stmt_past->get_result();
$past_bookings = $past_result->fetch_all(MYSQLI_ASSOC);
$stmt_past->close();

$conn->close();


// --- Set empty arrays if PHP is commented out ---
$upcoming_bookings = $upcoming_bookings ?? [];
$past_bookings = $past_bookings ?? [];

?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Use htmlspecialchars for user-provided content -->
    <title>Dashboard - <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?> - Incredible India</title>
    <meta name="description" content="Manage your Incredible India trips, view bookings, access your wishlist, and discover special offers.">
    <meta name="keywords" content="dashboard, user profile, travel bookings, india trips, wishlist, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'traveler'; ?>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500&display=swap">
    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom Styles (Consistent with index.php and explore.php) -->
    <style>
        /* --- Styles Copied & Adapted from index.php --- */
        :root {
            --color-sunset-orange: #FF6B3D;
            --color-indigo-blue: #3F51B5;
            --color-sand-beige: #f1f5f9; /* slate-100 (Used for Dashboard Body) */
            --color-himalaya-gray: #E0E0E0;
            --color-forest-green: #388E3C;
            --color-text-light: #e2e8f0; /* slate-200 */
            --color-text-dark: #334155; /* slate-700 */
            --color-heading-dark: #1e293b; /* slate-800 */
            --color-heading-light: #cbd5e1; /* slate-300 */
            --color-bg-light: #ffffff; /* white */
            --color-bg-dark: #0f172a; /* slate-900 */
            --color-card-light: #ffffff;
            --color-card-dark: #1e293b; /* slate-800 */
            --color-navbar-bg-light: rgba(255, 255, 255, 0.9);
            --color-navbar-bg-dark: rgba(30, 41, 59, 0.9); /* slate-800 */
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            transition: background-color 0.4s ease, color 0.4s ease;
            background-color: var(--color-sand-beige); /* Use alternate bg (slate-100) for dashboard body */
            color: var(--color-text-dark);
        }
        /* Dark Mode Body (Optional) */
        /* .dark body { background-color: var(--color-bg-dark); color: var(--color-text-light); } */
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }

        /* Palette Utilities */
        .bg-indigo-primary { background-color: var(--color-indigo-blue); }
        .bg-orange-accent { background-color: var(--color-sunset-orange); }
        .text-indigo-primary { color: var(--color-indigo-blue); }
        .text-orange-accent { color: var(--color-sunset-orange); }
        .hover\:text-orange-accent:hover { color: var(--color-sunset-orange); }
        .hover\:bg-indigo-primary-darker:hover { background-color: #303F9F; }
        .hover\:bg-orange-accent-darker:hover { background-color: #F57C00; }

        /* Basic Transition */
        .dark-mode-transition { transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease, fill 0.4s ease, box-shadow 0.4s ease; }
        /* Pulse hover */
        .pulse-hover:hover { animation: pulse 1.2s infinite; }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.03); } 100% { transform: scale(1); } }
        /* Responsive Nav */
        @media (max-width: 768px) { .nav-links { display: none; } .mobile-menu-button { display: block; } }
        @media (min-width: 769px) { .mobile-menu-button { display: none; } .mobile-menu { display: none !important; } }

        /* Navbar Styling (Uses 'scrolled' state always visually) */
        #navbar {
            transition: background-color 0.4s ease-out, box-shadow 0.4s ease-out, padding 0.3s ease-out;
            position: sticky; /* Sticky for Dashboard */
            top: 0;
            z-index: 50;
            background-color: var(--color-navbar-bg-light); /* Always slightly transparent white */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        /* Dark Mode Navbar (Optional) */
        /* .dark #navbar { background-color: var(--color-navbar-bg-dark); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4); } */

        /* Navbar text/icon colors (effectively 'scrolled' style) */
        #navbar .nav-links a,
        #navbar .mobile-menu-button,
        #navbar .navbar-logo-base,
        #navbar .navbar-logo-icon { color: var(--color-text-dark); }
        /* .dark #navbar .nav-links a, .dark #navbar .mobile-menu-button, .dark #nav .navbar-logo-base, .dark #navbar .navbar-logo-icon { color: var(--color-text-light); } */

        #navbar .navbar-logo-accent{ color: var(--color-sunset-orange); }
        /* .dark #navbar .navbar-logo-accent{ color: #ff8a63; } */

        #navbar .nav-links a:hover { color: var(--color-orange-accent); }
        /* .dark #navbar .nav-links a:hover { color: #ff8a63; } */

        #navbar .login-signup-btn { background-color: var(--color-orange-accent); /* Use orange for logout */ color: white; }
        /* .dark #navbar .login-signup-btn { background-color: #ff8a63; color: var(--color-heading-dark); } */
        #navbar .login-signup-btn:hover { background-color: #F57C00; } /* Darker orange */
        /* .dark #navbar .login-signup-btn:hover { background-color: #ffa726; } */

        /* Highlight active nav link */
        #navbar .nav-links a[aria-current="page"] {
            color: var(--color-orange-accent);
            font-weight: 600; /* Semibold */
            border-bottom: 2px solid var(--color-orange-accent);
            padding-bottom: 2px;
        }
        /* .dark #navbar .nav-links a[aria-current="page"] { color: #ff8a63; border-bottom-color: #ff8a63; } */


        /* Mobile Menu */
        .mobile-menu { background-color: var(--color-card-light); color: var(--color-text-dark); box-shadow: -5px 0 15px rgba(0, 0, 0, 0.2); position: fixed; top: 0; right: -100%; width: 75%; max-width: 300px; height: 100%; z-index: 100; transition: right 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); padding: 2rem; overflow-y: auto; display: block; }
        .mobile-menu.open { right: 0; }
        /* .dark .mobile-menu { background-color: var(--color-card-dark); color: var(--color-text-light); } */
        #menu-overlay { position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 99; opacity: 0; visibility: hidden; transition: opacity 0.4s ease, visibility 0.4s ease; }
        #menu-overlay.open { opacity: 1; visibility: visible; }
        /* Active link in mobile menu */
        .mobile-menu a[aria-current="page"] {
             color: var(--color-orange-accent);
             font-weight: 600;
        }
         /* .dark .mobile-menu a[aria-current="page"] { color: #ff8a63; } */

        /* Footer Wave */
        .footer-wave path { transition: fill 0.4s ease; }
        /* Section Backgrounds */
        .bg-section-light { background-color: var(--color-bg-light); } /* white */
        .bg-section-alternate { background-color: var(--color-sand-beige); } /* slate-100 */
        /* .dark .bg-section-light { background-color: var(--color-bg-dark); } */
        /* .dark .bg-section-alternate { background-color: #1e293b; } /* slate-800 */

        /* Back to Top */
        #scrollToTop { transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease, background-color 0.3s ease; }

        /* --- Dashboard Specific Styles --- */
        .dashboard-card {
            background-color: var(--color-card-light);
            border-radius: 0.75rem; /* rounded-xl */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -1px rgba(0, 0, 0, 0.04); /* Softer shadow */
            transition: all 0.3s ease-in-out;
            overflow: hidden; /* Ensure content doesn't overflow rounded corners */
            border: 1px solid #e5e7eb; /* Subtle border */
        }
        /* .dark .dashboard-card { background-color: var(--color-card-dark); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.2); border-color: #374151; } */
        .dashboard-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: translateY(-3px);
        }
        /* .dark .dashboard-card:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -2px rgba(0, 0, 0, 0.3); } */
        .dashboard-card-header { border-bottom: 1px solid #e5e7eb; padding: 1rem 1.5rem; }
        /* .dark .dashboard-card-header { border-bottom-color: #374151; } */
        .dashboard-card-title { font-size: 1.125rem; font-weight: 600; color: var(--color-heading-dark); }
        /* .dark .dashboard-card-title { color: var(--color-heading-light); } */

        .booking-item, .wishlist-item { padding: 0.75rem 1rem; border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s ease; }
        /* .dark .booking-item, .dark .wishlist-item { border-bottom-color: #374151; } */
        .booking-item:last-child, .wishlist-item:last-child { border-bottom: none; }
        .booking-item:hover, .wishlist-item:hover { background-color: #f9fafb; }
        /* .dark .booking-item:hover, .dark .wishlist-item:hover { background-color: #374151; } */

        /* Status Badges */
        .status-badge { font-size: 0.75rem; font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.05em; }
        .status-Confirmed { background-color: #d1fae5; color: #065f46; }
        /* .dark .status-Confirmed { background-color: #065f46; color: #a7f3d0; } */
        .status-Pending { background-color: #fef3c7; color: #92400e; }
        /* .dark .status-Pending { background-color: #92400e; color: #fde68a; } */
        .status-Completed { background-color: #e5e7eb; color: #4b5563; }
        /* .dark .status-Completed { background-color: #4b5563; color: #d1d5db; } */
        .status-Cancelled { background-color: #fee2e2; color: #991b1b; }
        /* .dark .status-Cancelled { background-color: #991b1b; color: #fecaca; } */

        /* Wishlist specific */
        .wishlist-item img { width: 40px; height: 40px; object-fit: cover; border-radius: 0.375rem; }

        /* Offer Card */
        .offer-card { border: 1px solid var(--color-indigo-blue); transition: all 0.3s ease; }
         /* .dark .offer-card { border-color: #7e8cfa; } */
         .offer-card:hover { background-color: #eef2ff; transform: scale(1.03); }
         /* .dark .offer-card:hover { background-color: rgba(63, 81, 181, 0.2); } */

         /* Profile Image */
         .profile-image-lg { width: 80px; height: 80px; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
         /* .dark .profile-image-lg { border-color: var(--color-card-dark); } */

         /* Footer Wave Fill */
         .fill-body-bg { fill: var(--color-sand-beige); } /* Match body bg (slate-100) */
         /* .dark .fill-body-bg { fill: var(--color-bg-dark); } */

    </style>
    <script>
        // Force light mode always
        document.documentElement.classList.remove('dark');
        localStorage.removeItem('theme');
    </script>
</head>

<body class="bg-section-alternate dark:bg-bg-dark font-sans dark-mode-transition"> <!-- Body uses slate-100 -->

    <!-- =============================== -->
    <!--      Navbar (Consistent)        -->
    <!-- =============================== -->
    <nav class="w-full dark-mode-transition" id="navbar"> <!-- sticky, always 'scrolled' style -->
        <div class="container mx-auto px-4 flex justify-between items-center py-3">
            <a href="index.php" class="flex items-center pulse-hover">
                <span class="text-2xl md:text-3xl font-bold">
                    <i class="fas fa-map-marked-alt mr-2 navbar-logo-icon"></i>
                    <span class="navbar-logo-base">Incredible</span><span class="navbar-logo-accent">India</span>
                </span>
            </a>
            <div class="nav-links hidden md:flex items-center space-x-6">
                <a href="index.php#home" class="hover:text-orange-accent transition-colors font-medium">Home</a>
                <a href="dashboard.php" aria-current="page" class="transition-colors font-medium">Dashboard</a> <!-- Active Link -->
                <a href="destination.php" class="hover:text-orange-accent transition-colors font-medium">Destinations</a>
                <a href="index.php#ai-planner-section" class="hover:text-orange-accent transition-colors font-medium">AI Planner</a>
                <!-- Forum Link Removed -->
                <a href="bookings.php" class="hover:text-orange-accent transition-colors font-medium">Bookings</a>
                <!-- Profile Link Removed -->
            </div>
            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle REMOVED -->
                <!-- Logout Button -->
                 <a href="logout.php" class="bg-orange-accent px-5 py-2 text-white rounded-full font-semibold hover:bg-orange-accent-darker hover:shadow-lg transition-all text-sm login-signup-btn">
                    Logout
                </a>
                <button class="mobile-menu-button md:hidden text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div class="mobile-menu dark-mode-transition">
            <button id="close-menu-btn" class="absolute top-4 right-4 p-2 text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-white text-2xl">×</button>
            <div class="mt-12 space-y-4">
                 <a href="index.php#home" class="block py-2 hover:text-orange-500 transition-colors text-lg">Home</a>
                 <a href="dashboard.php" aria-current="page" class="block py-2 text-orange-500 font-semibold text-lg">Dashboard</a> <!-- Active Link -->
                 <a href="destination.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Destinations</a>
                 <a href="index.php#ai-planner-section" class="block py-2 hover:text-orange-500 transition-colors text-lg">AI Planner</a>
                 <!-- Forum Link Removed -->
                 <a href="bookings.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Bookings</a>
                 <!-- Profile Link Removed -->
                 <a href="logout.php" class="mt-6 w-full bg-orange-accent px-6 py-3 text-white rounded-full font-semibold hover:shadow-lg transition-all text-center">Logout</a>
            </div>
        </div>
        <div id="menu-overlay" class="fixed inset-0 bg-black/50 z-90 hidden"></div>
    </nav>

    <!-- =============================== -->
    <!--          Main Content           -->
    <!-- =============================== -->
    <main class="container mx-auto px-4 py-8 md:py-12">

        <!-- Welcome Header -->
        <header class="mb-10" data-aos="fade-down">
             <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                 <div class="flex items-center gap-4">
                      <!-- Use ui-avatars or a default image -->
                      <img src="https://ui-avatars.com/api/?name=<?php echo isset($_SESSION['username']) ? urlencode(substr($_SESSION['username'], 0, 2)) : 'U'; ?>&background=random&color=fff&size=80&rounded=true&bold=true" alt="Profile" class="profile-image-lg rounded-full">
                      <div>
                           <h1 class="text-3xl md:text-4xl font-bold text-heading-dark dark:text-heading-light">
                                Welcome Back,
                            </h1>
                             <!-- Display username safely -->
                             <p class="text-xl text-indigo-600 dark:text-indigo-400 font-medium"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Traveler'; ?>!</p>
                             <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ready to plans your next adventure?</p>
                      </div>
                 </div>
                 <div class="flex gap-3 mt-4 sm:mt-0">
                       <!-- Profile Edit Button Removed -->
                     <a href="Plannar.php" class="bg-indigo-primary hover:bg-indigo-primary-darker text-white text-sm font-medium py-2 px-4 rounded-lg shadow-sm transition-colors">
                         <i class="fas fa-route mr-1"></i> Plan New Trip
                     </a>
                 </div>
             </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column (Bookings & Wishlist) -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Upcoming Bookings Card -->
                <section class="dashboard-card" data-aos="fade-up">
                    <div class="dashboard-card-header flex justify-between items-center">
                        <h2 class="dashboard-card-title">
                            <i class="fas fa-plane-departure mr-2 text-indigo-500 dark:text-indigo-400"></i> Upcoming Trips
                        </h2>
                        <a href="bookings.php" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">View All</a>
                    </div>
                    <div class="p-4 space-y-3">
                        <?php if (!empty($upcoming_bookings)): ?>
                            <?php foreach ($upcoming_bookings as $booking): ?>
                                <div class="booking-item flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div>
                                        <h4 class="font-semibold text-gray-800 dark:text-gray-100"><?php echo htmlspecialchars($booking['destination']); ?></h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <i class="far fa-calendar-alt mr-1"></i> <?php echo date("d M, Y", strtotime($booking['travel_dates'])); ?>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="status-badge status-<?php echo htmlspecialchars($booking['status']); ?>">
                                            <?php echo htmlspecialchars($booking['status']); ?>
                                        </span>
                                        <!-- <a href="booking_details.php?id=<?php echo $booking['booking_id']; ?>" class="text-xs bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 px-3 py-1 rounded-md font-medium">Details</a> -->
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Message when PHP code is commented out or returns no results -->
                            <p class="text-center text-gray-500 dark:text-gray-400 py-4">No upcoming trips found. Time to plan one!</p>
                             <div class="text-center pb-2">
                                  <a href="explore.php" class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-sm font-medium py-2 px-4 rounded-lg shadow-sm transition-colors hover:bg-green-200 dark:hover:bg-green-800">
                                     Explore Destinations
                                 </a>
                             </div>
                        <?php endif; ?>
                    </div>
                </section>

                 <!-- My Wishlist Card -->
                <section class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="dashboard-card-header flex justify-between items-center">
                        <h2 class="dashboard-card-title">
                             <i class="fas fa-heart mr-2 text-red-500 dark:text-red-400"></i> My Wishlist
                        </h2>
                        <span id="wishlist-count" class="text-xs font-medium bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded-full">0 items</span>
                    </div>
                    <div id="wishlist-items" class="p-4 space-y-3 max-h-60 overflow-y-auto"> <!-- Added max-height and scroll -->
                        <!-- Wishlist items will be loaded here by JS -->
                        <p id="wishlist-empty-msg" class="text-center text-gray-500 dark:text-gray-400 py-4">Your wishlist is empty. Add destinations you love!</p>
                    </div>
                     <div class="px-4 pb-4 text-center border-t border-gray-100 dark:border-gray-700 pt-3">
                          <a href="destination.php" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Browse Destinations</a>
                     </div>
                </section>

                <!-- Past Bookings Card -->
                 <section class="dashboard-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="dashboard-card-header flex justify-between items-center">
                        <h2 class="dashboard-card-title">
                           <i class="fas fa-history mr-2 text-gray-500 dark:text-gray-400"></i> Recent Past Trips
                        </h2>
                        <a href="bookings.php" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">View All History</a>
                    </div>
                    <div class="p-4 space-y-3">
                         <?php if (!empty($past_bookings)): ?>
                             <?php foreach ($past_bookings as $booking): ?>
                                 <div class="booking-item flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 opacity-80">
                                     <div>
                                         <h4 class="font-semibold text-gray-700 dark:text-gray-200"><?php echo htmlspecialchars($booking['destination']); ?></h4>
                                         <p class="text-sm text-gray-500 dark:text-gray-400">
                                             <i class="far fa-calendar-alt mr-1"></i> <?php echo date("d M, Y", strtotime($booking['travel_dates'])); ?>
                                         </p>
                                     </div>
                                      <div class="flex items-center gap-3">
                                         <span class="status-badge status-<?php echo htmlspecialchars($booking['status']); ?>">
                                            <?php echo htmlspecialchars($booking['status']); ?>
                                         </span>
                                         <!-- Assuming a review page exists -->
                                         <a href="review.php?booking_id=<?php echo $booking['booking_id']; ?>" class="text-xs bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 px-3 py-1 rounded-md font-medium">Review</a>
                                     </div>
                                 </div>
                             <?php endforeach; ?>
                         <?php else: ?>
                             <!-- Message when PHP code is commented out or returns no results -->
                             <p class="text-center text-gray-500 dark:text-gray-400 py-4">No past trip history yet.</p>
                         <?php endif; ?>
                    </div>
                </section>

            </div>

            <!-- Right Column (Quick Actions/Offers) -->
            <div class="lg:col-span-1 space-y-8">

                 <!-- Quick Actions -->
                 <section class="dashboard-card" data-aos="fade-left" data-aos-delay="150">
                      <div class="dashboard-card-header">
                           <h2 class="dashboard-card-title"><i class="fas fa-bolt mr-2 text-yellow-500"></i> Quick Actions</h2>
                      </div>
                      <div class="p-4 grid grid-cols-2 gap-3">
                           <a href="Plannar.php" class="flex flex-col items-center justify-center text-center p-3 bg-indigo-50 dark:bg-indigo-900 hover:bg-indigo-100 dark:hover:bg-indigo-800 rounded-lg transition-colors">
                               <i class="fas fa-route text-2xl text-indigo-600 dark:text-indigo-400 mb-1"></i>
                               <span class="text-xs font-medium text-indigo-800 dark:text-indigo-200">AI Planners</span>
                           </a>
                           <a href="explore.php" class="flex flex-col items-center justify-center text-center p-3 bg-green-50 dark:bg-green-900 hover:bg-green-100 dark:hover:bg-green-800 rounded-lg transition-colors">
                                <i class="fas fa-map-signs text-2xl text-green-600 dark:text-green-400 mb-1"></i>
                                <span class="text-xs font-medium text-green-800 dark:text-green-200">Explore</span>
                            </a>
                            <!-- Forum Button Removed -->
                             <a href="bookings.php" class="flex flex-col items-center justify-center text-center p-3 bg-purple-50 dark:bg-purple-900 hover:bg-purple-100 dark:hover:bg-purple-800 rounded-lg transition-colors col-span-2"> <!-- Made full width -->
                                 <i class="fas fa-suitcase-rolling text-2xl text-purple-600 dark:text-purple-400 mb-1"></i>
                                 <span class="text-xs font-medium text-purple-800 dark:text-purple-200">My Bookings</span>
                             </a>
                      </div>
                 </section>

                <!-- Special Offers Card -->
                <section class="dashboard-card" data-aos="fade-left" data-aos-delay="250">
                    <div class="dashboard-card-header">
                        <h2 class="dashboard-card-title">
                            <i class="fas fa-tags mr-2 text-orange-500 dark:text-orange-400"></i> Special Offers
                        </h2>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="offer-card p-3 rounded-lg text-sm">
                            <h4 class="font-semibold text-indigo-700 dark:text-indigo-300 mb-1">Get ₹500 OFF your first booking!</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Use code: <code class="font-mono bg-gray-200 dark:bg-gray-600 px-1 rounded">WELCOME500</code></p>
                        </div>
                        <div class="offer-card p-3 rounded-lg text-sm">
                            <h4 class="font-semibold text-indigo-700 dark:text-indigo-300 mb-1">15% OFF on Kerala Houseboats</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Book before end of month.</p>
                        </div>
                         <div class="offer-card p-3 rounded-lg text-sm">
                            <h4 class="font-semibold text-indigo-700 dark:text-indigo-300 mb-1">Early Bird Discount for Winter Trips</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Plan ahead and save!</p>
                        </div>
                    </div>
                </section>

            </div>
        </div>

    </main>

    <!-- =============================== -->
    <!--      Footer (Consistent)        -->
    <!-- =============================== -->
    <footer class="bg-indigo-900 dark:bg-slate-900 text-indigo-100 dark:text-gray-300 pt-16 pb-10 relative mt-16">
        <!-- Wave SVG -->
        <div class="absolute top-0 left-0 w-full overflow-hidden leading-none -translate-y-px">
            <svg class="relative block w-full h-[80px] md:h-[100px] dark-mode-transition footer-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                 <!-- Fill matches body background (slate-100) -->
                 <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-body-bg dark:fill-bg-dark"></path>
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
                         <li><a href="dashboard.php" class="text-orange-300 transition-colors">Dashboard</a></li> <!-- Active -->
                         <li><a href="explore.php" class="text-indigo-200 hover:text-orange-300 transition-colors">Destinations</a></li>
                         <li><a href="index.php#ai-planner-section" class="text-indigo-200 hover:text-orange-300 transition-colors">AI Planner</a></li>
                         <!-- Forum Link Removed -->
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
    <script>
        jQuery.extend(jQuery.easing, {
            easeInOutExpo: (x, t, b, c, d) => (t == 0) ? b : (t == d) ? b + c : (t /= d / 2) < 1 ? c / 2 * Math.pow(2, 10 * (t - 1)) + b : c / 2 * (-Math.pow(2, -10 * --t) + 2) + b
        });
    </script>
    <script>
    $(document).ready(function() {
        AOS.init({
            duration: 600,
            once: true,
            offset: 50
        });

        // --- Navbar JS (Dashboard specific - sticky, active link) ---
        const nav = $('#navbar');
        function handleScroll() {
            // Only handle scroll-to-top button visibility
            const scrollTop = $(window).scrollTop();
            $('#scrollToTop').toggleClass('opacity-100 visible scale-100', scrollTop > 300)
                             .toggleClass('opacity-0 invisible scale-90', scrollTop <= 300);
            // Update active link styling (in case of dynamic changes, though unlikely here)
            updateNavbarActiveLink();
        }

        function updateNavbarActiveLink() {
             // Style active link directly
            const activeColor = 'var(--color-orange-accent)'; // Orange accent
            // Reset others
             $('#navbar .nav-links a').not('[aria-current="page"]').css('color', '').css('border-bottom','none').css('font-weight','500');
             // Apply active styles
             $('#navbar .nav-links a[aria-current="page"]').css('color', activeColor).css('border-bottom','2px solid var(--color-orange-accent)').css('font-weight','600');
        }

        $(window).scroll(handleScroll);
        handleScroll(); // Initial check for scroll-to-top
        updateNavbarActiveLink(); // Initial active link styling

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
        }
        $('.mobile-menu-button').click(() => toggleMenu(!mobileMenu.hasClass('open')));
        $('#close-menu-btn').click(() => toggleMenu(false));
        menuOverlay.click(() => toggleMenu(false));
        $('.mobile-menu a, .mobile-menu button').not('#close-menu-btn').click(() => {
             toggleMenu(false);
         });

        // --- Dark Mode Toggle Logic Removed ---

        // --- Wishlist Logic ---
        const WISHLIST_KEY = 'incredibleIndiaWishlist'; // Same key as explore.php
        const wishlistContainer = $('#wishlist-items');
        const wishlistEmptyMsg = $('#wishlist-empty-msg');
        const wishlistCountSpan = $('#wishlist-count');

        function getWishlist() {
            const storedList = localStorage.getItem(WISHLIST_KEY);
            return storedList ? JSON.parse(storedList) : [];
        }
        function saveWishlist(list) {
            const uniqueList = [...new Set(list)];
            localStorage.setItem(WISHLIST_KEY, JSON.stringify(uniqueList));
            renderWishlist(); // Re-render after saving
            // Add AJAX call here to sync with backend if user is logged in
        }

        function renderWishlist() {
            const wishlist = getWishlist();
            wishlistContainer.empty(); // Clear previous items

            if (wishlist.length === 0) {
                wishlistContainer.append(wishlistEmptyMsg.removeClass('hidden'));
                wishlistCountSpan.text(`0 items`);
            } else {
                wishlistEmptyMsg.addClass('hidden');
                wishlistCountSpan.text(`${wishlist.length} item${wishlist.length > 1 ? 's' : ''}`);

                wishlist.forEach(itemName => {
                    const itemHtml = `
                        <div class="wishlist-item flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 overflow-hidden mr-2"> <!-- Added overflow hidden -->
                                <!-- Placeholder Image using ui-avatars -->
                                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(itemName.substring(0,2))}&background=random&color=fff&size=40&rounded=true&bold=true" alt="${itemName.substring(0,2)}" class="w-10 h-10 object-cover rounded-md flex-shrink-0"> <!-- Flex shrink 0 -->
                                <span class="font-medium text-sm text-gray-800 dark:text-gray-100 truncate" title="${htmlspecialchars(itemName)}">${htmlspecialchars(itemName)}</span> <!-- Added truncate & title -->
                            </div>
                            <button class="remove-wishlist-item text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-xs font-medium flex-shrink-0" data-name="${htmlspecialchars(itemName)}" title="Remove from Wishlist">
                                <i class="fas fa-trash-alt mr-1"></i> Remove
                            </button>
                        </div>
                    `;
                    wishlistContainer.append(itemHtml);
                });
            }
        }

        // Helper function for JS HTML encoding (simple version)
        function htmlspecialchars(str) {
            return String(str).replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>').replace(/"/g, '"');
        }


        // Event listener for removing items from wishlist
        wishlistContainer.on('click', '.remove-wishlist-item', function() {
            const itemName = $(this).data('name');
            let currentWishlist = getWishlist();
            currentWishlist = currentWishlist.filter(item => item !== itemName);
            saveWishlist(currentWishlist); // This will trigger re-render
        });

        // Initial render of wishlist on page load
        renderWishlist();


        // Footer Year
        $('#currentYear').text(new Date().getFullYear());

    });
    </script>
</body>

</html>


