<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$pageTitle = "Admin Dashboard";
include 'header.php';

// Load users from JSON
$usersFile = 'users.json';
if (!file_exists($usersFile)) {
    file_put_contents($usersFile, json_encode([])); // Create the file if it doesn't exist
}
$users = json_decode(file_get_contents($usersFile), true);
if ($users === null) {
    die("Error: Invalid JSON data in users file.");
}

// Load items from JSON
$itemsFile = 'items.json';
if (!file_exists($itemsFile)) {
    file_put_contents($itemsFile, json_encode([])); // Create the file if it doesn't exist
}
$items = json_decode(file_get_contents($itemsFile), true);
if ($items === null) {
    die("Error: Invalid JSON data in items file.");
}

// Handle form submissions for user and item management
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // User and item management logic from the functionality script
    if (isset($_POST['create_user'])) {
        $username = htmlspecialchars(trim($_POST['username']));
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = htmlspecialchars(trim($_POST['role']));
        $newUser = ['id' => count($users) + 1, 'username' => $username, 'password' => $password, 'role' => $role];
        $users[] = $newUser;
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    }

    if (isset($_POST['delete_user'])) {
        $id = intval($_POST['id']);
        $users = array_filter($users, fn($user) => $user['id'] != $id);
        file_put_contents($usersFile, json_encode(array_values($users), JSON_PRETTY_PRINT));
    }

    if (isset($_POST['create_item'])) {
        $title = htmlspecialchars(trim($_POST['title']));
        $description = htmlspecialchars(trim($_POST['description']));
        $file = null;
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file = uniqid() . '-' . basename($_FILES['file']['name']);
            move_uploaded_file($_FILES['file']['tmp_name'], "uploads/$file");
        }
        $newItem = ['id' => count($items) + 1, 'title' => $title, 'description' => $description, 'file' => $file];
        $items[] = $newItem;
        file_put_contents($itemsFile, json_encode($items, JSON_PRETTY_PRINT));
    }

    if (isset($_POST['edit_item'])) {
        $id = intval($_POST['id']);
        $title = htmlspecialchars(trim($_POST['title']));
        $description = htmlspecialchars(trim($_POST['description']));
        foreach ($items as &$item) {
            if ($item['id'] === $id) {
                $item['title'] = $title;
                $item['description'] = $description;
                break;
            }
        }
        file_put_contents($itemsFile, json_encode($items, JSON_PRETTY_PRINT));
    }

    if (isset($_POST['delete_item'])) {
        $id = intval($_POST['id']);
        $items = array_filter($items, fn($item) => $item['id'] != $id);
        file_put_contents($itemsFile, json_encode(array_values($items), JSON_PRETTY_PRINT));
    }
}
?>

<h1>Admin Dashboard</h1>
<p>Hello, <?= htmlspecialchars($_SESSION['username']); ?>!</p>
<hr>

<h3>Manage Users</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                        <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Create User</h3>
<form action="" method="post">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <input type="text" class="form-control" name="role" required>
    </div>
    <button type="submit" name="create_user" class="btn btn-success">Create User</button>
</form>

<hr>
<h3>Manage Items</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>File</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['id']) ?></td>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><?= htmlspecialchars($item['description']) ?></td>
                <td>
                    <?php if (!empty($item['file'])): ?>
                        <a href="uploads/<?= htmlspecialchars($item['file']) ?>" target="_blank">View File</a>
                    <?php else: ?>
                        No File
                    <?php endif; ?>
                </td>
                <td>
                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <button type="submit" name="delete_item" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Create Item</h3>
<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" name="title" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description (Optional)</label>
        <textarea class="form-control" name="description"></textarea>
    </div>
    <div class="mb-3">
        <label for="file" class="form-label">File Upload (Optional)</label>
        <input type="file" class="form-control" name="file">
    </div>
    <button type="submit" name="create_item" class="btn btn-success">Create Item</button>
</form>

<a href="logout.php" class="btn btn-danger mt-3">Logout</a>
<a href="index.php" class="btn btn-secondary mt-3">Return to Home</a>

<?php include 'footer.php'; ?>

