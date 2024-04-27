<?php
require_once('connection.php');
require_once('User.php'); // Assuming you've saved the classes in a file named UserManagement.php

// Start session
session_start();

// Check if the user is already logged in, redirect to dashboard if so
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Create a new instance of UserManagement class
    $userManagement = new UserManagement($conn);

    // Check if the user exists with the provided credentials
    $user = $userManagement->getUserByUsernameAndPassword($username, $password);
    
    if ($user) {
        // User exists, start a session and store user information
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username_user'];
        $_SESSION['email'] = $user['email_user'];

        // Redirect to dashboard or some other page
        header("Location: dashboard.php");
        exit;
    } else {
        // Invalid credentials, display an error message
        $error_message = "Invalid username or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #4C0000, #003300, #000000); /* Dark red, Dark green, Black */
            color: white;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            margin: 0;
        }

        .login-form {
            background-color: white; /* White */
            padding: 30px;
            border-radius: 10px;
            margin-top: 50px;
            width: 100%;
            max-width: 400px;
            color: black; /* Black text */
        }

        .login-heading {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group label {
            color: #A9A9A9; /* Light gray */
        }

        .form-control {
            background-color: rgba(0, 0, 0, 0.1); /* Semi-transparent black */
            color: black; /* Black text */
        }

        .btn-primary {
            background-color: #B22222; /* Dark red */
            border-color: #B22222; /* Dark red */
        }

        .btn-primary:hover {
            background-color: #8B0000; /* Darker red on hover */
            border-color: #8B0000; /* Darker red on hover */
        }

        .login-link h5 {
            text-align: center;
            margin-top: 20px;
            color: #A9A9A9; /* Light gray */
        }

        .login-link a {
            color: #FFD700; /* Gold */
        }

        .login-link a:hover {
            color: #DAA520; /* Darker gold on hover */
        }

        .signup-image {
            display: block;
            margin: 0 auto 20px; /* Center the image */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-form">
                    <img src="palestineflag.jpg" alt="Login Image" class="signup-image" width="80px">
                    <h2 class="login-heading">Login</h2>
                    <?php if(isset($error_message)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error_message; ?>
                        </div>
                    <?php } ?>
                    <form class="login-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                    <div class="login-link">
                        <h5>If you don't have an account, sign up <a href="signup.html">here</a>.</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
