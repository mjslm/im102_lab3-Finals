<?php
require_once 'config.php';

// If already logged in, redirect away from login page
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$message = '';

// Check if user just registered
if (isset($_GET['registered'])) {
    $message = "<div class='alert-success'>Registration successful! Please login below.</div>";
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $message = "<div class='alert-error'>Please enter both username and password!</div>";
    } else {
        // Find the user in database
        $sql = "SELECT id, username, email, password_hash, role 
                FROM users 
                WHERE username = '$username' OR email = '$username'";
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
        
        // Verify password against stored hash using password_verify()
        if ($user && password_verify($password, $user['password_hash'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect on success
            header('Location: index.php');
            exit;
        } else {
            // Same error message - don't reveal which part failed
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