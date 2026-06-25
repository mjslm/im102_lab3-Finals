<?php
$host = 'localhost';
$user = 'root';
$pass = ''; 
$db   = 'im102_lab3_salomon';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

require_once 'auth.php';
?>