<?php
session_start();
$usersFile = 'users.json';

// Check if the users file exists
if (!file_exists($usersFile)) {
    die("Error: Users file not found.");
}

// Load users from the JSON file
$users = json_decode(file_get_contents($usersFile), true);
if ($users === null) {
    die("Error: Invalid JSON data in users file.");
}

// Handle signup form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Check if the username already exists
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $error = "Username already exists!";
            break;
        }
    }

    // If username is unique, add the new user
    if (!isset($error)) {
        $newUser = [
            'id' => count($users) + 1,
            'username' => $username,
            'password' => $password,
            'role' => 'user' // Default role
        ];
        $users[] = $newUser;
        
        // Save the updated users array back to the JSON file
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

        // Set session variables for the new user
        $_SESSION['user_id'] = $newUser['id'];
        $_SESSION['username'] = $newUser['username'];
        $_SESSION['role'] = $newUser['role'];

        // Redirect to the user dashboard after signup
        header('Location: user_dashboard.php'); // Redirect to user dashboard
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Sign Up</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form action="" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>

        <div class="mt-3">
            <a href="login.php">Already have an account? Login</a>
        </div>

        <a href="index.php" class="btn btn-secondary mt-3">Return to Home</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
