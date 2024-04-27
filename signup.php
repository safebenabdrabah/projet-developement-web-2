<?php
// Start session
session_start();

// Include database connection
require_once('connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM user WHERE username_user = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Username already exists, display an error message
        $_SESSION['registration_error'] = "Username already exists. Please choose a different username.";
        header("Location: signup.php");
        exit;
    }

    // Prepare and execute the SQL statement to insert the user into the database
    $stmt = $conn->prepare("INSERT INTO user (username_user, email_user, password_user) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Check if the user was successfully inserted
    if ($stmt->rowCount() > 0) {
        // User registration successful
        $_SESSION['registration_success'] = true;
        header("Location: login.php"); // Redirect to login page
        exit;
    } else {
        // User registration failed
        $_SESSION['registration_error'] = "Registration failed. Please try again.";
        header("Location: signup.php"); // Redirect back to signup page
        exit;
    }
} else {
    // If the form was not submitted, redirect back to signup page
    header("Location: signup.php");
    exit;
}
?>
