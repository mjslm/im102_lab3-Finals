<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = ''; 
$db   = 'im102_lab3_salomon'; // Your database name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Include authentication functions
require_once 'auth.php';
?>