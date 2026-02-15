<?php
session_start();
include 'db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"]; // Don't escape this, password_verify() needs raw input

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query Failed: " . mysqli_error($conn)); // ✅ Debug query error
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // ✅ Debug password checking
        echo "Entered Password: " . $password . "<br>";
        echo "Stored Hash: " . $row["password"] . "<br>";

        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            echo "<script>alert('Login successful! Redirecting to dashboard...'); window.location.href='dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No account found with this email. Please sign up.');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Incredible India Tours</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Assuming style.css contains the overall styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Assuming tailwind css is defined here -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer"
    />
    <style>
        body {
            font-family: sans-serif;
            background-color: #f8f8f8;
            overflow: hidden;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        /* Style for the video container */
        
        .background-video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
            /* Place it behind other content */
        }
        /* Style for the video */
        
        #background-video {
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            /* Cover the entire viewport */
        }
        
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Adjust for desired darkness */
            z-index: 0;
        }
        
        .login-box {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            z-index: 1;
            /* Ensure it's above the overlay */
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            text-align: center;
        }
        
        .input-field {
            background: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white */
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            border: none;
            width: 100%;
            color: #333;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            outline: none;
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.2);
        }
        
        .login-button {
            background: linear-gradient(to right, #4285f4, #34a853);
            /* Blue to Green, similar to your index.html's CTA */
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            /* To match other buttons */
        }
        
        .login-button:hover {
            background: linear-gradient(to right, #34a853, #4285f4);
            /* Green to Blue */
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        
        .sign-up-link {
            color: #3498db;
            transition: color 0.3s ease;
            font-weight: bold;
        }
        
        .sign-up-link:hover {
            color: #2980b9;
        }
        
        .password-input-container {
            position: relative;
        }
        
        .password-toggle-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">

    <!-- Background Video -->
    <div class="background-video-container">
        <video autoplay loop muted playsinline id="background-video">
            <source src="img/vid.mp4" type="video/mp4">
            <!-- Add more source tags for different video formats if needed -->
            Your browser does not support the video tag.
        </video>
    </div>


    <nav class="absolute top-4 left-4">
        <a href="index.php" class="text-white font-bold text-lg hover:underline">🏠 Home</a>
    </nav>

    <!-- Login Container -->
    <div class="login-box">
        <h2 class="text-2xl font-bold text-white mb-4">Incredible India Tours</h2>
        <p class="text-gray-200 mb-6">Sign in to start planning your adventure.</p>

        <!-- Login Form -->
        <form method="post" action="login.php" id="login-form" class="text-left">
            <div class="mb-4">
                <label for="email" class="block text-white text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email">
            </div>

            <div class="mb-4 password-input-container">
                <label for="password" class="block text-white text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="input-field" placeholder="Enter your password">
                <i id="password-toggle" class="fas fa-eye password-toggle-icon"></i>
            </div>

            <div class="flex items-center justify-between mb-4">
                <label class="inline-flex items-center text-white">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-500">
                    <span class="ml-2 text-sm">Remember me</span>
                </label>
                <a href="#" class="text-blue-300 hover:underline text-sm">Forgot Password?</a>
            </div>

            <!-- Login Button -->
            <button type="submit" class="login-button">
                Login
            </button>
        </form>
        <!-- Create Account -->
        <p class="text-white mt-6">
            Don't have an account?
            <a href="signup.php" class="sign-up-link">Sign Up</a>
        </p>
    </div>

    <script>
       
    </script>
</body>


</html>

