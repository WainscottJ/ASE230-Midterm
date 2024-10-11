<?php
session_start();

// Function to register a user
function register_user($username, $password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    file_put_contents('users.txt', "$username|$hashed_password\n", FILE_APPEND);
}

// Function to check if a user exists and password is correct
function validate_user($username, $password) {
    $users = file('users.txt', FILE_IGNORE_NEW_LINES);
    foreach ($users as $user) {
        list($stored_user, $stored_pass) = explode('|', $user);
        if ($username === $stored_user && password_verify($password, $stored_pass)) {
            return true;
        }
    }
    return false;
}

// Function to check if user is admin
function is_admin($username) {
    return $username === 'admin';
}
?>
