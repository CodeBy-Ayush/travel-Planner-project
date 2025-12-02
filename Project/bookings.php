<?php
session_start(); // Start session MUST be first

// --- Error Reporting (for development only!) ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// -------------------------------------------------

// --- Login Check ---
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php"); // Redirect to your login page
    exit();
}

// --- Database Connection Details ( IMPORTANT: Update with your credential! ) ---
$servername = "localhost"; // Usually localhost
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "travel"; // The name of your database

// --- Initialize variables ---
$booking_successful = false;
$booking_error = '';
$review_successful = false;
$review_error = '';
$completed_bookings = []; // Initialize as empty array
$reviews = [];            // Initialize as empty array

// --- Handle POST Requests ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Establish connection only needed for POST actions
    $conn_post = new mysqli($servername, $username, $password, $dbname);
    if ($conn_post->connect_error) {
        // Use generic error for security in production, but detailed for development
        error_log("Database Connection Failed: " . $conn_post->connect_error); // Log detailed error
        die("Database connection failed. Please try again later."); // User-friendly message
    }
    // Set character set to handle special characters properly
    $conn_post->set_charset("utf8mb4");


    // --- Handle Booking Submission ---
    if (isset($_POST['book_now'])) {
        $user_id = $_SESSION["user_id"]; // Get user ID from session

        // Basic validation
        if (empty($_POST['destination']) || empty($_POST['travelers']) || empty($_POST['travel_dates']) || empty($_POST['package_type'])) {
             $booking_error = "Please fill in all required fields (*).";
        } else {
            // Sanitize and Validate Inputs
            $destination = trim($conn_post->real_escape_string($_POST['destination']));
            $travelers = filter_input(INPUT_POST, 'travelers', FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
            $travel_dates_raw = trim($conn_post->real_escape_string($_POST['travel_dates']));
            $package_type = trim($conn_post->real_escape_string($_POST['package_type']));
            // Use null if empty, otherwise trim and escape
            $special_requests = !empty($_POST['special_requests']) ? trim($conn_post->real_escape_string($_POST['special_requests'])) : null;


            if ($travelers === false || $travelers < 1) {
                $booking_error = "Invalid number of travelers. Please enter a positive number.";
            } elseif (empty($travel_dates_raw)) {
                 $booking_error = "Please select valid travel dates.";
            } else {
                // Extract start date from the range provided by flatpickr
                $dates = explode(' to ', $travel_dates_raw);
                $start_date = $dates[0]; // We only store the start date as per schema

                // Validate the extracted start date format (YYYY-MM-DD)
                if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $start_date)) {
                     $booking_error = "Invalid date format received. Please select dates again.";
                } else {
                    // Prepare SQL Insert Statement
                    $sql = "INSERT INTO bookings (user_id, destination, travelers, travel_dates, package_type, special_requests, status)
                            VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
                    $stmt = $conn_post->prepare($sql);

                    if ($stmt) {
                         // Correct binding types: user_id(int), destination(string), travelers(int), start_date(string), package_type(string), special_requests(string)
                         $stmt->bind_param("isssss", $user_id, $destination, $travelers, $start_date, $package_type, $special_requests);

                         if ($stmt->execute()) {
                             $booking_successful = true;
                             // Optionally clear POST data after successful submission to prevent re-submission on refresh
                             // header("Location: " . $_SERVER['PHP_SELF']); // Redirect to self
                             // exit();
                         } else {
                             $booking_error = "Booking requests failed. Please try again. Error: " . $stmt->error; // Show detailed error during dev
                             error_log("Booking failed: " . $stmt->error); // Log error
                         }
                         $stmt->close();
                     } else {
                         $booking_error = "Database error preparing booking statement: " . $conn_post->error;
                         error_log("DB prepare error (booking): " . $conn_post->error);
                     }
                }
            }
        }
    } // End booking submission


    // --- Handle Review Submission ---
    if (isset($_POST['submit_review'])) {
        $user_id = $_SESSION["user_id"]; // Get user ID from session

        // Basic Validation
        if (empty($_POST['booking_id']) || empty($_POST['user_name']) || empty($_POST['review_text']) || empty($_POST['rating'])) {
            $review_error = "Please fill in all review fields (*).";
        } else {
            // Sanitize and Validate Inputs
            $booking_id = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);
            // Allow user to potentially change displayed name, but escape it
            $user_name = trim($conn_post->real_escape_string($_POST['user_name']));
            $review_text = trim($conn_post->real_escape_string($_POST['review_text']));
            $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 5]]);

            if ($booking_id === false || $rating === false) {
                 $review_error = "Invalid booking selected or rating value. Please check your input.";
            } elseif (empty($user_name)) {
                 $review_error = "Please enter your name for the review.";
            } elseif (empty($review_text)) {
                 $review_error = "Please enter your review text.";
            } else {
                // Check if the user is allowed to review this booking (belongs to them and is completed/past)
                $check_sql = "SELECT booking_id FROM bookings
                              WHERE booking_id = ? AND user_id = ? AND (status = 'Completed' OR travel_dates < CURDATE())";
                $check_stmt = $conn_post->prepare($check_sql);

                if ($check_stmt) {
                    $check_stmt->bind_param("ii", $booking_id, $user_id);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();

                    if ($check_result->num_rows > 0) {
                        // User is allowed, proceed with review insertion
                        $sql = "INSERT INTO review (booking_id, user_id, user_name, review_text, rating) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conn_post->prepare($sql);

                         if ($stmt) {
                             // Bind parameters: booking_id(int), user_id(int), user_name(string), review_text(string), rating(int)
                             $stmt->bind_param("iissi", $booking_id, $user_id, $user_name, $review_text, $rating);

                             if ($stmt->execute()) {
                                 $review_successful = true;
                                  // Optionally clear POST data
                                 // header("Location: " . $_SERVER['PHP_SELF']);
                                 // exit();
                             } else {
                                 // Check for specific duplicate entry error (MySQL error code 1062)
                                 if ($conn_post->errno == 1062) {
                                     $review_error = "You have already submitted a review for this trip.";
                                 } else {
                                     $review_error = "Review submission failed. Please try again. Error: " . $stmt->error; // Dev error
                                     error_log("Review submission failed: " . $stmt->error);
                                 }
                             }
                             $stmt->close();
                        } else {
                             $review_error = "Database error preparing review statement: " . $conn_post->error;
                             error_log("DB prepare error (review): " . $conn_post->error);
                        }
                    } else {
                        // Booking not found, doesn't belong to user, or isn't completed/past
                        $review_error = "Invalid booking selected for review, or the trip is not yet completed/past.";
                    }
                     $check_stmt->close();
                } else {
                     $review_error = "Database error checking booking status: " . $conn_post->error;
                     error_log("DB prepare error (review check): " . $conn_post->error);
                }
            }
        }
    } // End review submission

    $conn_post->close(); // Close the POST connection
} // End POST request handling


// --- Fetch data needed for the page (Runs on every page load, GET or POST) ---
$conn_get = new mysqli($servername, $username, $password, $dbname);
if ($conn_get->connect_error) {
    error_log("Database Connection Failed (GET): " . $conn_get->connect_error);
    die("Database connection failed. Please try again later.");
}
$conn_get->set_charset("utf8mb4");

$user_id = $_SESSION["user_id"]; // Get user ID again for fetching data

// Fetch completed bookings for the review dropdown
$sql_completed = "SELECT booking_id, destination, travel_dates FROM bookings
                  WHERE user_id = ? AND (status = 'Completed' OR travel_dates < CURDATE())
                  ORDER BY travel_dates DESC";
$stmt_completed = $conn_get->prepare($sql_completed);

if($stmt_completed) {
    $stmt_completed->bind_param("i", $user_id);
    $stmt_completed->execute();
    $result_completed = $stmt_completed->get_result();
    if ($result_completed->num_rows > 0) {
        while ($row = $result_completed->fetch_assoc()) {
            $completed_bookings[] = $row;
        }
    }
    $stmt_completed->close();
} else {
    error_log("Error preparing completed bookings fetch: " . $conn_get->error);
    // Optionally display an error to the user, or just log it
    $review_error .= " Error fetching completed trips."; // Append to existing errors if any
}


// Fetch existing reviews (limit for display - fetch from all users)
$sql_reviews = "SELECT user_name, review_text, rating, created_at FROM reviews ORDER BY created_at DESC LIMIT 5";
$result_reviews = $conn_get->query($sql_reviews); // Simple query, no user input

if ($result_reviews && $result_reviews->num_rows > 0) {
    while ($row = $result_reviews->fetch_assoc()) {
        $reviews[] = $row;
    }
} elseif (!$result_reviews) {
     error_log("Error fetching recent reviews: " . $conn_get->error);
}

$conn_get->close(); // Close the GET connection

// --- No need for the explicit empty array check anymore ---
// $completed_bookings = $completed_bookings ?? []; // Now populated above
// $reviews = $reviews ?? []; // Now populated above

?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Trip & Manage Bookings - Incredible India</title>
    <meta name="description" content="Book your dream vacation to India, manage your existing bookings, and leave reviews for your past trips.">
    <!-- Use htmlspecialchars for session data in meta tags -->
    <meta name="keywords" content="book india trip, india booking, travel booking, manage bookings, india travel review, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'traveler'; ?>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500&display=swap">
    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Custom Styles (Keep as is) -->
    <style>
        /* --- Styles Copied & Adapted from dashboard.php --- */
        :root { /* Palette variables */
            --color-sunset-orange: #FF6B3D; --color-indigo-blue: #3F51B5; --color-sand-beige: #f1f5f9; --color-himalaya-gray: #E0E0E0; --color-forest-green: #388E3C; --color-text-light: #e2e8f0; --color-text-dark: #334155; --color-heading-dark: #1e293b; --color-heading-light: #cbd5e1; --color-bg-light: #ffffff; --color-bg-dark: #0f172a; --color-card-light: #ffffff; --color-card-dark: #1e293b; --color-navbar-bg-light: rgba(255, 255, 255, 0.9); --color-navbar-bg-dark: rgba(30, 41, 59, 0.9);
        }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; transition: background-color 0.4s ease, color 0.4s ease; background-color: var(--color-sand-beige); color: var(--color-text-dark); } /* Body uses slate-100 */
        /* .dark body { background-color: var(--color-bg-dark); color: var(--color-text-light); } */
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }
        .bg-indigo-primary { background-color: var(--color-indigo-blue); } .bg-orange-accent { background-color: var(--color-sunset-orange); } .text-indigo-primary { color: var(--color-indigo-blue); } .text-orange-accent { color: var(--color-sunset-orange); } .hover\:text-orange-accent:hover { color: var(--color-sunset-orange); } .hover\:bg-indigo-primary-darker:hover { background-color: #303F9F; } .hover\:bg-orange-accent-darker:hover { background-color: #F57C00; }
        .dark-mode-transition { transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease, fill 0.4s ease, box-shadow 0.4s ease; }
        .pulse-hover:hover { animation: pulse 1.2s infinite; } @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.03); } 100% { transform: scale(1); } }
        @media (max-width: 768px) { .nav-links { display: none; } .mobile-menu-button { display: block; } } @media (min-width: 769px) { .mobile-menu-button { display: none; } .mobile-menu { display: none !important; } }
        #navbar { transition: background-color 0.4s ease-out, box-shadow 0.4s ease-out, padding 0.3s ease-out; position: sticky; top: 0; z-index: 50; background-color: var(--color-navbar-bg-light); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }
        /* .dark #navbar { background-color: var(--color-navbar-bg-dark); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4); } */
        #navbar .nav-links a, #navbar .mobile-menu-button, /*#navbar #darkModeToggle,*/ #navbar .navbar-logo-icon, #navbar .navbar-logo-base { color: var(--color-text-dark); }
        /* .dark #navbar .nav-links a, .dark #navbar .mobile-menu-button, .dark #navbar #darkModeToggle, .dark #navbar .navbar-logo-icon, .dark #navbar .navbar-logo-base { color: var(--color-text-light); } */
        #navbar .navbar-logo-accent{ color: var(--color-sunset-orange); }
        /* .dark #navbar .navbar-logo-accent{ color: #ff8a63; } */
        #navbar .nav-links a:hover /*, #navbar #darkModeToggle:hover i.fa-sun*/ { color: var(--color-orange-accent); }
        /* .dark #navbar .nav-links a:hover, .dark #navbar #darkModeToggle:hover i.fa-sun { color: #ff8a63; } */
        #navbar .login-signup-btn { background-color: var(--color-orange-accent); color: white; }
        #navbar .login-signup-btn:hover { background-color: #F57C00; }
        #navbar .nav-links a[aria-current="page"] { color: var(--color-orange-accent); font-weight: 600; border-bottom: 2px solid var(--color-orange-accent); padding-bottom: 2px; }
        /* .dark #navbar .nav-links a[aria-current="page"] { color: #ff8a63; border-bottom-color: #ff8a63; } */
        .mobile-menu { background-color: var(--color-card-light); color: var(--color-text-dark); box-shadow: -5px 0 15px rgba(0, 0, 0, 0.2); position: fixed; top: 0; right: -100%; width: 75%; max-width: 300px; height: 100%; z-index: 100; transition: right 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); padding: 2rem; overflow-y: auto; display: block; } .mobile-menu.open { right: 0; }
        /* .dark .mobile-menu { background-color: var(--color-card-dark); color: var(--color-text-light); } */
        #menu-overlay { position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 99; opacity: 0; visibility: hidden; transition: opacity 0.4s ease, visibility 0.4s ease; } #menu-overlay.open { opacity: 1; visibility: visible; }
        .mobile-menu a[aria-current="page"] { color: var(--color-orange-accent); font-weight: 600; }
        /* .dark .mobile-menu a[aria-current="page"] { color: #ff8a63; } */
        .footer-wave path { transition: fill 0.4s ease; }
        .bg-section-light { background-color: var(--color-bg-light); } .bg-section-alternate { background-color: var(--color-sand-beige); }
        #scrollToTop { transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease, background-color 0.3s ease; }
        .dashboard-card { background-color: var(--color-card-light); border-radius: 0.75rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -1px rgba(0, 0, 0, 0.04); transition: all 0.3s ease-in-out; overflow: hidden; border: 1px solid #e5e7eb; }
        /* .dark .dashboard-card { background-color: var(--color-card-dark); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.2); border-color: #374151; } */
        .dashboard-card:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); transform: translateY(-3px); }
        /* .dark .dashboard-card:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -2px rgba(0, 0, 0, 0.3); } */
        .dashboard-card-header { border-bottom: 1px solid #e5e7eb; padding: 1rem 1.5rem; }
        /* .dark .dashboard-card-header { border-bottom-color: #374151; } */
        .dashboard-card-title { font-size: 1.125rem; font-weight: 600; color: var(--color-heading-dark); }
        /* .dark .dashboard-card-title { color: var(--color-heading-light); } */

        /* --- Booking Page Specific Styles --- */
        .flatpickr-input { background-color: var(--color-card-light); border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; line-height: 1.25rem; width: 100%; transition: border-color 0.2s ease, box-shadow 0.2s ease; }
        /* .dark .flatpickr-input { background-color: #374151; border-color: #4b5563; color: var(--color-text-light); } */
        .flatpickr-input:focus { outline: none; border-color: var(--color-indigo-blue); box-shadow: 0 0 0 2px rgba(63, 81, 181, 0.3); }
        /* .dark .flatpickr-input:focus { border-color: #7e8cfa; box-shadow: 0 0 0 2px rgba(126, 140, 250, 0.4); } */
        .form-input, .form-select, .form-textarea { background-color: var(--color-card-light); border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; width: 100%; transition: border-color 0.2s ease, box-shadow 0.2s ease; color: var(--color-text-dark); }
        /* .dark .form-input, .dark .form-select, .dark .form-textarea { background-color: #374151; border-color: #4b5563; color: var(--color-text-light); } */
        .form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: var(--color-indigo-blue); box-shadow: 0 0 0 2px rgba(63, 81, 181, 0.3); }
        /* .dark .form-input:focus, .dark .form-select:focus, .dark .form-textarea:focus { border-color: #7e8cfa; box-shadow: 0 0 0 2px rgba(126, 140, 250, 0.4); } */
        input[list]::-webkit-calendar-picker-indicator { display: none; }
        .traveler-btn { background-color: #e5e7eb; color: #374151; font-weight: 600; padding: 0.5rem 1rem; transition: background-color 0.2s ease; cursor: pointer; } /* Added cursor */
        .traveler-btn:hover { background-color: #d1d5db; }
        /* .dark .traveler-btn { background-color: #4b5563; color: #d1d5db; } */
        /* .dark .traveler-btn:hover { background-color: #6b7280; } */
        .review-card { background-color: rgba(255, 255, 255, 0.5); border-left: 4px solid var(--color-indigo-blue); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; }
        /* .dark .review-card { background-color: rgba(30, 41, 59, 0.5); border-left-color: #7e8cfa; } */
        .review-stars .fa-star { color: #f59e0b; } .review-stars .fa-star-half-alt { color: #f59e0b; } .review-stars .far.fa-star { color: #9ca3af; }
        /* .dark .review-stars .far.fa-star { color: #6b7280; } */
        .fill-body-bg { fill: var(--color-sand-beige); } /* Match body bg */
        /* .dark .fill-body-bg { fill: var(--color-bg-dark); } */
        /* Add styles for alert messages */
        .alert-success { background-color: #d1fae5; border-color: #a7f3d0; color: #065f46; }
        .alert-danger { background-color: #fee2e2; border-color: #fecaca; color: #991b1b; }
        /* .dark .alert-success { background-color: #064e3b; border-color: #10b981; color: #a7f3d0; } */
        /* .dark .alert-danger { background-color: #7f1d1d; border-color: #ef4444; color: #fecaca; } */
        .alert { padding: 1rem; border-width: 1px; border-radius: 0.375rem; margin-bottom: 1.5rem; font-size: 0.875rem; line-height: 1.25rem; }
        .alert i { margin-right: 0.5rem; }
    </style>
    <script>
        // Force light mode always (as per original request)
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
                <a href="dashboard.php" class="hover:text-orange-accent transition-colors font-medium">Dashboard</a>
                <a href="explore.php" class="hover:text-orange-accent transition-colors font-medium">Destinations</a>
                <a href="index.php#ai-planner-section" class="hover:text-orange-accent transition-colors font-medium">AI Planner</a>
                <!-- Forum Removed -->
                <a href="bookings.php" aria-current="page" class="transition-colors font-medium">Bookings</a> <!-- Active Link -->
                <!-- Profile Removed -->
            </div>
            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle REMOVED -->
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
                 <a href="dashboard.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Dashboard</a>
                 <a href="explore.php" class="block py-2 hover:text-orange-500 transition-colors text-lg">Destinations</a>
                 <a href="index.php#ai-planner-section" class="block py-2 hover:text-orange-500 transition-colors text-lg">AI Planner</a>
                 <!-- Forum Removed -->
                 <a href="bookings.php" aria-current="page" class="block py-2 text-orange-500 font-semibold text-lg">Bookings</a> <!-- Active Link -->
                 <!-- Profile Removed -->
                 <a href="logout.php" class="mt-6 w-full bg-orange-accent px-6 py-3 text-white rounded-full font-semibold hover:shadow-lg transition-all text-center">Logout</a>
            </div>
        </div>
        <div id="menu-overlay" class="fixed inset-0 bg-black/50 z-90 hidden"></div>
    </nav>

    <!-- =============================== -->
    <!--          Main Content           -->
    <!-- =============================== -->
    <main class="container mx-auto px-4 py-8 md:py-12">

        <header class="mb-10 text-center" data-aos="fade-down">
             <h1 class="text-3xl md:text-4xl font-bold text-heading-dark dark:text-heading-light mb-2">Book Your Indian Adventure</h1>
             <p class="text-lg text-gray-600 dark:text-gray-400">Fill in the details below to request your personalized trip booking.</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            <!-- Booking Form Section (Left Column) -->
            <section class="lg:col-span-3" data-aos="fade-right">
                <div class="dashboard-card p-6 md:p-8"> <!-- Using dashboard card style -->
                    <h2 class="dashboard-card-title mb-6">
                        <i class="fas fa-edit mr-2 text-indigo-500 dark:text-indigo-400"></i> Plan Your Trip
                    </h2>

                    <!-- Display Booking Success/Error Messages -->
                    <?php if ($booking_successful): ?>
                        <div class="alert alert-success" role="alert">
                             <i class="fas fa-check-circle"></i> Booking request submitted successfully! We'll contact you shortly.
                        </div>
                    <?php elseif (!empty($booking_error)): ?>
                         <div class="alert alert-danger" role="alert">
                             <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($booking_error); ?>
                        </div>
                    <?php endif; ?>

                    <form id="booking-form" method="POST" action="bookings.php" novalidate> <!-- Add novalidate to rely on server-side validation -->
                        <div class="space-y-5">
                            <div>
                                <label for="destination" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Destination <span class="text-red-500">*</span></label>
                                <input type="text" id="destination" name="destination" class="form-input" placeholder="e.g., Goa, Kerala, Jaipur" list="destinations" required>
                                <!-- Datalist provides suggestions -->
                                <datalist id="destinations">
                                    <option value="Goa">
                                    <option value="Kerala Backwaters">
                                    <option value="Jaipur (Pink City)">
                                    <option value="Agra (Taj Mahal)">
                                    <option value="Leh-Ladakh">
                                    <option value="Rishikesh">
                                    <option value="Mumbai (Bombay)">
                                    <option value="Hampi">
                                    <option value="Varanasi">
                                    <option value="Andaman & Nicobar">
                                    <option value="Darjeeling">
                                    <option value="Shillong & Cherrapunji">
                                    <option value="Kaziranga National Park">
                                    <option value="Udaipur (City of Lakes)">
                                </datalist>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="travelers" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Travelers <span class="text-red-500">*</span></label>
                                    <div class="flex items-center">
                                        <button type="button" id="decrease-travelers" class="traveler-btn rounded-l-md">-</button>
                                        <input type="number" id="travelers" name="travelers" class="form-input rounded-none text-center w-16 border-l-0 border-r-0 focus:z-10" value="1" min="1" required>
                                        <button type="button" id="increase-travelers" class="traveler-btn rounded-r-md">+</button>
                                    </div>
                                </div>
                                <div>
                                    <label for="travel-dates" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Travel Dates <span class="text-red-500">*</span></label>
                                    <input type="text" id="travel-dates" name="travel_dates" class="form-input flatpickr-input" placeholder="Select Date Range" required>
                                </div>
                            </div>

                            <div>
                                <label for="package-type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preferred Package Type <span class="text-red-500">*</span></label>
                                <select id="package-type" name="package_type" class="form-select" required>
                                    <option value="" disabled selected>-- Select Package --</option>
                                    <option value="Budget">Budget Friendly</option>
                                    <option value="Standard">Standard Comfort</option>
                                    <option value="Luxury">Luxury Experience</option>
                                    <option value="Custom">Custom (Specify below)</option>
                                </select>
                            </div>

                            <div>
                                <label for="special-requests" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Special Requests / Notes</label>
                                <textarea id="special-requests" name="special_requests" rows="3" class="form-textarea" placeholder="e.g., Specific hotel preference, accessibility needs, dietary restrictions..."></textarea>
                            </div>

                            <div class="pt-3 text-right">
                                <!-- Hidden input to identify form -->
                                <input type="hidden" name="book_now" value="1">
                                <button type="submit" class="bg-orange-accent hover:bg-orange-accent-darker text-white font-bold py-2.5 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 dark:focus:ring-offset-slate-800 transition-colors duration-300 shadow-md hover:shadow-lg">
                                    <i class="fas fa-paper-plane mr-2"></i> Submit Booking Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Review Section (Right Column) -->
            <section class="lg:col-span-2 space-y-8">

                <!-- Display Recent Reviews -->
                <div class="dashboard-card" data-aos="fade-left"> <!-- Using dashboard card style -->
                    <div class="dashboard-card-header">
                        <h2 class="dashboard-card-title">
                             <i class="fas fa-star mr-2 text-yellow-500 dark:text-yellow-400"></i> Recent Traveler Reviews
                        </h2>
                    </div>
                    <div class="p-4 md:p-6 max-h-72 overflow-y-auto"> <!-- Limited height for reviews -->
                        <?php if (!empty($reviews)): ?>
                            <?php foreach ($reviews as $review): ?>
                                <div class="review-card">
                                    <div class="flex justify-between items-start mb-1">
                                        <span class="font-semibold text-sm text-gray-800 dark:text-gray-100"><?php echo htmlspecialchars($review['user_name']); ?></span>
                                        <div class="review-stars text-xs flex items-center" title="<?php echo intval($review['rating']); ?> Stars">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="<?php echo ($i <= $review['rating']) ? 'fas' : 'far'; ?> fa-star"></i>
                                            <?php endfor; ?>
                                             <span class="ml-1 text-gray-500 dark:text-gray-400">(<?php echo intval($review['rating']); ?>/5)</span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 italic mb-2">"<?php echo nl2br(htmlspecialchars($review['review_text'])); ?>"</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 text-right">Reviewed on: <?php echo date("d M Y", strtotime($review['created_at'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-gray-500 dark:text-gray-400 py-4">Be the first to leave a review!</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Add Review Form -->
                <div class="dashboard-card p-6 md:p-8" data-aos="fade-left" data-aos-delay="100"> <!-- Using dashboard card style -->
                     <h2 class="dashboard-card-title mb-6">
                         <i class="fas fa-pencil-alt mr-2 text-green-500 dark:text-green-400"></i> Leave a Review for a Past Trip
                    </h2>

                     <!-- Display Review Success/Error Messages -->
                     <?php if ($review_successful): ?>
                         <div class="alert alert-success" role="alert">
                             <i class="fas fa-check-circle"></i> Review submitted successfully! Thank you for your feedback.
                         </div>
                     <?php elseif (!empty($review_error)): ?>
                          <div class="alert alert-danger" role="alert">
                              <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($review_error); ?>
                         </div>
                     <?php endif; ?>

                    <form id="review-form" method="POST" action="bookings.php" novalidate> <!-- Add novalidate -->
                         <div class="space-y-5">
                             <div>
                                 <label for="booking_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Trip to Review <span class="text-red-500">*</span></label>
                                 <select id="booking_id" name="booking_id" class="form-select" required>
                                     <option value="" disabled selected>-- Select a Completed Trip --</option>
                                     <?php if (!empty($completed_bookings)): ?>
                                         <?php foreach ($completed_bookings as $booking): ?>
                                             <option value="<?php echo $booking['booking_id']; ?>">
                                                 <?php echo htmlspecialchars($booking['destination']) . ' (' . date("d M Y", strtotime($booking['travel_dates'])) . ')'; ?>
                                             </option>
                                         <?php endforeach; ?>
                                     <?php else: ?>
                                          <option value="" disabled>No completed trips found to review</option>
                                     <?php endif; ?>
                                 </select>
                                 <?php if (empty($completed_bookings) && empty($review_error) && !isset($_POST['submit_review'])): // Show message only if no trips and no error occurred yet ?>
                                      <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">You don't have any completed trips eligible for review yet.</p>
                                 <?php endif; ?>
                             </div>

                             <div>
                                  <label for="user_name_review" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Name (for display) <span class="text-red-500">*</span></label>
                                  <!-- Pre-fill name from session, ensure htmlspecialchars -->
                                  <input type="text" id="user_name_review" name="user_name" class="form-input" value="<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>" required>
                              </div>

                             <div>
                                 <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Overall Rating <span class="text-red-500">*</span></label>
                                 <select id="rating" name="rating" class="form-select" required>
                                        <option value="" disabled selected>-- Rate 1 to 5 --</option>
                                        <option value="5">★★★★★ (Excellent)</option>
                                        <option value="4">★★★★☆ (Very Good)</option>
                                        <option value="3">★★★☆☆ (Good)</option>
                                        <option value="2">★★☆☆☆ (Fair)</option>
                                        <option value="1">★☆☆☆☆ (Poor)</option>
                                    </select>
                             </div>

                              <div>
                                 <label for="review-text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Review <span class="text-red-500">*</span></label>
                                 <textarea id="review-text" name="review_text" rows="4" class="form-textarea" placeholder="Share your experience..." required></textarea>
                             </div>

                             <div class="pt-3 text-right">
                                 <!-- Hidden input/button name to identify form -->
                                 <input type="hidden" name="submit_review" value="1">
                                 <button type="submit" class="bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white font-bold py-2.5 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-slate-800 transition-colors duration-300 shadow-md hover:shadow-lg">
                                     <i class="fas fa-check mr-2"></i> Submit Review
                                 </button>
                             </div>
                        </div>
                    </form>
                </div>

            </section>

        </div>
    </main>

    <!-- =============================== -->
    <!--      Footer (Consistent)        -->
    <!-- =============================== -->
    <footer class="bg-indigo-900 dark:bg-slate-900 text-indigo-100 dark:text-gray-300 pt-16 pb-10 relative mt-16">
        <!-- Wave SVG -->
        <div class="absolute top-0 left-0 w-full overflow-hidden leading-none -translate-y-px">
            <svg class="relative block w-full h-[80px] md:h-[100px] dark-mode-transition footer-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                 <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-body-bg dark:fill-bg-dark"></path> <!-- Fill matches body bg -->
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
                         <li><a href="dashboard.php" class="text-indigo-200 hover:text-orange-300 transition-colors">Dashboard</a></li>
                         <li><a href="explore.php" class="text-indigo-200 hover:text-orange-300 transition-colors">Destinations</a></li>
                         <li><a href="index.php#ai-planner-section" class="text-indigo-200 hover:text-orange-300 transition-colors">AI Planner</a></li>
                         <!-- Forum Removed -->
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


    <!-- JavaScript (Keep as is, but adjusted message hiding) -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Easing for Scroll to Top -->
    <script>jQuery.extend(jQuery.easing,{easeInOutExpo:(x,t,b,c,d)=>(t==0)?b:(t==d)?b+c:(t/=d/2)<1?c/2*Math.pow(2,10*(t-1))+b:c/2*(-Math.pow(2,-10*--t)+2)+b});</script>
    <script>
    $(document).ready(function() {
        AOS.init({
            duration: 600,
            once: true,
            offset: 50
        });

        // --- Navbar JS (Consistent) ---
        const nav = $('#navbar');
        function handleScroll() {
            const scrollTop = $(window).scrollTop();
            $('#scrollToTop').toggleClass('opacity-100 visible scale-100', scrollTop > 300)
                             .toggleClass('opacity-0 invisible scale-90', scrollTop <= 300);
            updateNavbarActiveLink(); // Ensure active link style persists
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
             // Allow scrolling after menu item click if it's a link to the same page section
             if (!$(this).is('button') && $(this).attr('href').startsWith('#')) {
                 // Don't immediately re-enable scroll if it's a same-page link
             } else {
                 toggleMenu(false);
             }
         });


        // --- Flatpickr Initialization ---
        flatpickr("#travel-dates", {
            mode: "range",
            dateFormat: "Y-m-d", // MUST match PHP date processing logic
            minDate: "today", // Prevent selecting past dates
            altInput: true, // Shows a human-readable date
            altFormat: "F j, Y", // Format for the visible input
        });

        // --- Traveler Count Buttons ---
        const travelersInput = $('#travelers');
        $('#decrease-travelers').on('click', () => {
            let count = parseInt(travelersInput.val()) || 1;
            if (count > 1) {
                travelersInput.val(count - 1);
            }
        });
        $('#increase-travelers').on('click', () => {
            let count = parseInt(travelersInput.val()) || 0;
            // Optional: Add a max limit if desired
            // if (count < 20) { // Example limit
                 travelersInput.val(count + 1);
            // }
        });

        // Footer Year
        $('#currentYear').text(new Date().getFullYear());

        // --- Hide success/error messages after a delay ---
        $('.alert-success, .alert-danger').delay(6000).fadeOut(500); // Increased delay slightly

    });
    </script>

</body>

</html>

