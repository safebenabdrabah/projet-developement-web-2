<?php
require_once('connection.php');
require_once('user.php');

// Start session
session_start();

// Check if the user is logged in, redirect to login page if not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Initialize the UserManagement class with the database connection
$userManagement = new UserManagement($conn);

// Fetch the user information to display in the form
$user = $userManagement->getUserById($_SESSION['user_id']);

// Check if the form is submitted for updating user information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Get the updated user information from the form
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $email = $_POST['email'];
    
    // Update the user information
    $userManagement->updateUser($_SESSION['user_id'], $username, $password, $email);
}

// Check if the form is submitted for deleting the user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    // Delete the user
    $userManagement->deleteUser($_SESSION['user_id']);
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #4C0000, #003300, #000000);
            color: white;
            font-family: Arial, sans-serif;
        }
        .container {
            padding-top: 50px;
        }
        .profile-form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .profile-form h2 {
            color: black;
        }
        .form-group label {
            color: #A9A9A9;
        }
        .form-control {
            background-color: rgba(0, 0, 0, 0.1);
            color: black;
        }
        .btn-primary, .btn-secondary, .btn-danger {
            background-color: #B22222;
            border-color: #B22222;
        }
        .btn-primary:hover, .btn-secondary:hover, .btn-danger:hover {
            background-color: #8B0000;
            border-color: #8B0000;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="my-5 text-center">Manage User</h1>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="profile-form">
                <h2 class="text-center mb-4">Update Your Information</h2>
                <!-- Update Your Information Form -->
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username_user']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email_user']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="update">Update</button>
                </form>

            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <button type="submit" class="btn btn-danger btn-block mt-3" name="delete" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">Delete Account</button>
            </form>
            <div class="text-center mt-3">
                <a href="manage_user.php?logout=true" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

