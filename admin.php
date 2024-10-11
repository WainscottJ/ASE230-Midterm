<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

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

// Handle user management
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create user
    if (isset($_POST['create_user'])) {
        $username = htmlspecialchars(trim($_POST['username']));
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = htmlspecialchars(trim($_POST['role']));

        $newUser = [
            'id' => count($users) + 1,
            'username' => $username,
            'password' => $password,
            'role' => $role
        ];
        $users[] = $newUser;

        // Save the updated users array back to the JSON file
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    }

    // Delete user
    if (isset($_POST['delete_user'])) {
        $id = intval($_POST['id']);
        $users = array_filter($users, fn($user) => $user['id'] != $id);
        file_put_contents($usersFile, json_encode(array_values($users), JSON_PRETTY_PRINT));
    }

    // Create item
    if (isset($_POST['create_item'])) {
        $title = htmlspecialchars(trim($_POST['title']));
        $description = htmlspecialchars(trim($_POST['description']));
        $file = null;

        // Handle file upload
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file = uniqid() . '-' . basename($_FILES['file']['name']);
            move_uploaded_file($_FILES['file']['tmp_name'], "uploads/$file");
        }

        $newItem = [
            'id' => count($items) + 1,
            'title' => $title,
            'description' => $description,
            'file' => $file
        ];
        $items[] = $newItem;

        // Save the updated items array back to the JSON file
        file_put_contents($itemsFile, json_encode($items, JSON_PRETTY_PRINT));
    }

    // Edit item
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

    // Delete item
    if (isset($_POST['delete_item'])) {
        $id = intval($_POST['id']);
        $items = array_filter($items, fn($item) => $item['id'] != $id);
        file_put_contents($itemsFile, json_encode(array_values($items), JSON_PRETTY_PRINT));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style2.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Admin Dashboard</h1>
        <p>Hello, <?= htmlspecialchars($_SESSION['username']); ?>!</p>

        <h2>Manage Users</h2>
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

        <h2>Create User</h2>
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

        <h2>Manage Items</h2>
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
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editItemModal<?= $item['id'] ?>">Edit</button>
                            <form action="" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <button type="submit" name="delete_item" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Item Modal -->
                    <div class="modal fade" id="editItemModal<?= $item['id'] ?>" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="" method="post">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title</label>
                                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($item['title']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" name="description"><?= htmlspecialchars($item['description']) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="edit_item" class="btn btn-primary">Save changes</button>
                                    </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Create Item</h2>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
