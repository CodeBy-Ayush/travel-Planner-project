<?php
include 'db.php';  // Include database connections

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

    // Check if passwords matches
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    } else {
        // ✅ Hash the password for security purpose
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // ✅ Check if email already exists
        $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $checkEmailQuery);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Email already registered. Please use a different email.');</script>";
        } else {
            // ✅ Insert new user into the database
            $sql = "INSERT INTO users (username, email, password) VALUES ('$fullName', '$email', '$hashedPassword')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Signup successful! Redirecting to login page...'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Incredible India Tours</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: sans-serif;
            background-color: #f8f8f8;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .background-video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        #background-video {
            width: 100vw;
            height: 100vh;
            object-fit: cover;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .signup-box {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            text-align: center;
            z-index: 1;
        }

        .input-field {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            border: none;
            width: 100%;
            color: #333;
        }

        .signup-button {
            background: linear-gradient(to right, #4285f4, #34a853);
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
        }

        .signup-button:hover {
            background: linear-gradient(to right, #34a853, #4285f4);
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
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

<body>
    <div class="background-video-container">
        <video autoplay loop muted playsinline id="background-video">
            <source src="img/vid.mp4" type="video/mp4">
        </video>
    </div>

    <nav class="absolute top-4 left-4">
        <a href="index.php" class="text-white font-bold text-lg hover:underline">🏠 Home</a>
    </nav>

    <div class="signup-box">
        <h2 class="text-2xl font-bold text-white mb-4">Create Account</h2>
        <p class="text-gray-200 mb-6">Join our community and start your adventure.</p>

        <form id="signup-form" method="post" action="signup.php">
            <div>
                <label class="block text-white text-sm font-bold mb-2">Full Name</label>
                <input type="text" name="fullName" class="input-field" placeholder="Enter your full name" required>
            </div>

            <div>
                <label class="block text-white text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" class="input-field" placeholder="Enter your email" required>
            </div>

            <div>
                <label class="block text-white text-sm font-bold mb-2">Password</label>
                <div class="password-input-container">
                    <input type="password" id="password" name="password" class="input-field" placeholder="Enter your password" required>
                    <i id="password-toggle" class="fas fa-eye password-toggle-icon"></i>
                </div>
            </div>

            <div>
                <label class="block text-white text-sm font-bold mb-2">Confirm Password</label>
                <input type="password" name="confirmPassword" class="input-field" placeholder="Confirm your password" required>
            </div>

            <button type="submit" class="signup-button">Sign Up</button>
        </form>

        <p class="text-white mt-6">
            Already have an account?
            <a href="login.php" class="text-blue-400 hover:underline">Login</a>
        </p>
    </div>
</body>
</html>


