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
    $loginSuccessful = false;

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
        header('Location: index.php'); // Redirect to home page for regular users
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
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
    
    <div class="mt-3">
        <a href="signup.php">Don't have an account? Signup!</a>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">Return to Home</a>
</body>
</html>
