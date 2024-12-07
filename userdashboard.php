<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Set username and role with fallbacks
$username = htmlspecialchars($_SESSION['username'] ?? 'Guest');
$role = htmlspecialchars($_SESSION['role'] ?? 'User');

$pageTitle = "User Dashboard";
include 'header.php'; // Consistent header inclusion
?>

<div class="container mt-4">
    <h1 class="text-primary">Welcome, <?= $username; ?>!</h1>
    <p class="text-muted">Role: <?= $role; ?></p>
    <hr class="border-secondary">

    <h3 class="text-secondary">Recent Activities</h3>
    <table class="table table-striped table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Activity</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Logged in</td>
                <td><?= date('Y-m-d H:i:s'); ?></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Placeholder for additional activities</td>
                <td>---</td>
            </tr>
        </tbody>
    </table>

    <h3 class="text-secondary">Quick Links</h3>
    <div class="row mt-3">
        <div class="col-md-4">
            <a href="profile.php" class="btn btn-outline-primary w-100">Edit Profile</a>
        </div>
        <div class="col-md-4">
            <a href="settings.php" class="btn btn-outline-primary w-100">Account Settings</a>
        </div>
    </div>

    <div class="mt-4">
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <a href="index.php" class="btn btn-secondary">Return to Home</a>
    </div>
</div>

<?php include 'footer.php'; // Consistent footer inclusion ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
