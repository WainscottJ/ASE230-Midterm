<?php
session_start();
$pageTitle = "Sign Up";
include 'header.php';

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

<div class="container mt-4">
    <h1>Sign Up</h1>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form action="signup.php" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Sign Up</button>
    </form>

    <p class="mt-3"><a href="login.php">Already have an account? Log in!</a></p>
</div>

<?php include 'footer.php'; ?>
