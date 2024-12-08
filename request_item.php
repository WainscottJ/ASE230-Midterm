<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if item_requests.json exists and create it if not
$requestsFile = 'item_requests.json';
if (!file_exists($requestsFile)) {
    file_put_contents($requestsFile, json_encode([]));
}

$requests = json_decode(file_get_contents($requestsFile), true);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $itemName = htmlspecialchars(trim($_POST['item_name']));
    $itemDetails = htmlspecialchars(trim($_POST['item_details']));
    $username = $_SESSION['username'];

    // Create new request
    $newRequest = [
        'id' => count($requests) + 1,
        'username' => $username,
        'item_name' => $itemName,
        'item_details' => $itemDetails,
        'status' => 'Pending', // Default status
        'request_date' => date('Y-m-d H:i:s'),
    ];

    // Add the new request to the existing requests
    $requests[] = $newRequest;

    // Save requests back to the file
    file_put_contents($requestsFile, json_encode($requests, JSON_PRETTY_PRINT));

    // Redirect to user dashboard with success flag
    header('Location: userdashboard.php?submitted=true');
    exit;  // Always call exit after a header redirect to prevent further code execution
}
?>
