<?php
// Start session at the top
session_start();

/**
 * Require user to be logged in
 * Redirects to login.php if not logged in
 */
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        die("Access denied");
    }
}

function getUsername() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
}
?>