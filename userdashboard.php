<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Hi, <?= htmlspecialchars($username); ?>!</h1>
        <p>This is your personal page.</p>
        
        <div class="mt-4">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <div class="mt-3">
            <a href="index.php" class="btn btn-secondary">Return to Home</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>