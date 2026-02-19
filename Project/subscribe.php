<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "travel";

// Create database connections
$conn = new mysqli($host, $username, $password, $database);

// Check connections
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

// Validate emails
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format.'); window.history.back();</script>";
    exit;
}

// Insert email into database
$stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    echo "<script>alert('Thank you for subscribing!'); window.location.href='index.php';</script>";
} else {
    if ($conn->errno == 1062) {
        echo "<script>alert('You are already subscribed!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error: Could not subscribe.'); window.history.back();</script>";
    }
}

$stmt->close();
$conn->close();
?>



