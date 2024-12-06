<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check for admin credentials
    if ($username === 'admin' && $password === 'admin') {
        // Login successful for admin
        $_SESSION['user_id'] = 'admin'; // Set admin user ID
        $_SESSION['username'] = 'admin';
        $_SESSION['role'] = 'admin';
        header('Location: admin.php'); // Redirect to admin dashboard
        exit;
    }

    // Load users from JSON
    $usersFile = 'users.json';
    if (!file_exists($usersFile)) {
        die("Error: Users file not found.");
    }

    $users = json_decode(file_get_contents($usersFile), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error: Invalid JSON data in users file.");
    }

    $loginSuccessful = false;

    // Check user credentials
    foreach ($users as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            // Login successful for a regular user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $loginSuccessful = true;
            break;
        }
    }

    if ($loginSuccessful) {
        // Redirect based on role
        if ($_SESSION['role'] === 'admin') {
            header('Location: admin.php'); // Redirect to admin dashboard
        } else {
            header('Location: user_dashboard.php'); // Redirect to user dashboard
        }
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Corrected the CSS link -->
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        
        <?php if (isset($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <div class="mt-3">
            <a href="signup.php">Don't have an account? Signup!</a>
        </div>
        <a href="index.php" class="btn btn-secondary mt-3">Return to Home</a>
    </div>
</body>
</html>
