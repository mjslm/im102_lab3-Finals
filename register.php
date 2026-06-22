<?php
require_once 'config.php';

$message = '';
$username = $email = '';

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // VALIDATION - Check all fields
    $errors = array();
    
    // 1. Check if any field is empty
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required!";
    }
    
    // 2. Check username length
    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters!";
    }
    if (strlen($username) > 50) {
        $errors[] = "Username is too long!";
    }
    
    // 3. Check email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address!";
    }
    
    // 4. Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match!";
    }
    
    // 5. Check password length
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters!";
    }
    
    // If no errors, save to database
    if (empty($errors)) {
        // Check if username or email already exists
        $check = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
        $result = $conn->query($check);
        
        if ($result->num_rows > 0) {
            $errors[] = "Username or email already exists!";
        } else {
            // Hash the password (encrypt it)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $sql = "INSERT INTO users (username, email, password_hash) 
                    VALUES ('$username', '$email', '$hashed_password')";
            
            if ($conn->query($sql)) {
                // Success!
                header('Location: login.php?registered=1');
                exit;
            } else {
                $errors[] = "Database error: " . $conn->error;
            }
        }
    }
    
    // Show errors if any
    if (!empty($errors)) {
        $message = "<div class='alert-error'>";
        foreach ($errors as $error) {
            $message .= "• " . $error . "<br>";
        }
        $message .= "</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
    <div class="register-box">
        <h2>Register Account</h2>
        
        <?php echo $message; ?>
        
        <form method="POST" action="register.php">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            <div class="hint">Min 3 characters</div>
            
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            
            <label>Password</label>
            <input type="password" name="password" required>
            <div class="hint">Min 6 characters</div>
            
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
            
            <button type="submit">Register</button>
        
            </div>
        </form>
    </div>
</body>
</html>