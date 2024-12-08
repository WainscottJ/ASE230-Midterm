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

// Check if the request has been successfully submitted
$submitted = isset($_GET['submitted']) && $_GET['submitted'] == 'true';
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

    <!-- Show Thank You message after submission -->
    <?php if ($submitted): ?>
        <div class="alert alert-success" role="alert">
            Your item request has been submitted successfully! Thank you.
        </div>
    <?php endif; ?>

    <!-- Add Request an Item Feature -->
    <h3 class="text-secondary">Request an Item</h3>
    <form action="request_item.php" method="POST" class="mb-3">
        <div class="mb-3">
            <label for="itemName" class="form-label">Item Name</label>
            <input type="text" id="itemName" name="item_name" class="form-control" placeholder="Enter item name" required>
        </div>
        <div class="mb-3">
            <label for="itemDetails" class="form-label">Item Details</label>
            <textarea id="itemDetails" name="item_details" class="form-control" placeholder="Provide additional details about the item" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Request</button>
    </form>

    <h4>Your Requests</h4>
    <ul class="list-group">
        <?php
        // Fetch user's requests from JSON file
        $requestsFile = 'item_requests.json';
        if (!file_exists($requestsFile)) {
            file_put_contents($requestsFile, json_encode([])); // Create the file if it doesn't exist
        }
        $requests = json_decode(file_get_contents($requestsFile), true);
        $userRequests = array_filter($requests, fn($req) => $req['username'] === $username);

        if (empty($userRequests)) {
            echo "<li class='list-group-item'>You have no requests.</li>";
        } else {
            foreach ($userRequests as $request) {
                $status = $request['status'] ?? 'Pending';
                echo "<li class='list-group-item'>
                        <strong>{$request['item_name']}</strong> (Request #{$request['id']}) - Status: $status
                      </li>";
            }
        }
        ?>
    </ul>

    <div class="mt-4">
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <a href="index.php" class="btn btn-secondary">Return to Home</a>
    </div>
</div>

<?php include 'footer.php'; // Consistent footer inclusion ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
