<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = ''; 
$db   = 'im102_lab3'; // Change this to your database name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Start session for login
session_start();
?>