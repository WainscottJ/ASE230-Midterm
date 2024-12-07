<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$pageTitle = "Login";
include 'header.php';

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
            header('Location: userdashboard.php'); // Redirect to user dashboard
        }
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<h1>Login</h1>

<?php if (isset($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<form action="login.php" method="post">
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
<p><a href="signup.php">Don't have an account? Sign up!</a></p>

<?php include 'footer.php'; ?>
