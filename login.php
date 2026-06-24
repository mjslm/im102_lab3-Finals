<?php
require_once 'config.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$message = '';

if (isset($_GET['registered'])) {
    $message = "<div class='alert-success'>Registration successful! Please login below.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $message = "<div class='alert-error'>Please enter both username and password!</div>";
    } else {
        $sql = "SELECT id, username, email, password_hash, role 
                FROM users 
                WHERE username = '$username' OR email = '$username'";
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
            exit;
        } else {
            $message = "<div class='alert-error'>Invalid username or password!</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
    <div class="login-box">
        <h2>Login</h2>
        <?php echo $message; ?>
        <form method="POST" action="login.php">
            <label>Username or Email</label>
            <input type="text" name="username" placeholder="Enter username or email" required>
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required>
            <button type="submit">Login</button>
            <div class="register-link">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
        </form>
    </div>
</body>
</html>