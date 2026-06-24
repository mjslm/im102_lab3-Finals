<?php
require_once 'config.php';

// Check if user is logged in
$logged_in = isLoggedIn();
$is_admin = isAdmin();

// If logged in as admin, they can add users
// If not logged in, it's a normal registration

// If logged in as non-admin, redirect
if ($logged_in && !$is_admin) {
    header('Location: index.php');
    exit;
}

$message = '';
$username = $email = '';
$is_admin_adding = $logged_in && $is_admin;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'staff';
    
    $errors = [];
    
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $errors[] = "All fields are required!";
    }
    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters!";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email!";
    }
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match!";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters!";
    }
    
    if (empty($errors)) {
        $check = $conn->query("SELECT id FROM users WHERE username = '$username' OR email = '$email'");
        
        if ($check->num_rows > 0) {
            $errors[] = "Username or email already exists!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $user_role = $is_admin_adding ? $role : 'staff';
            
            $sql = "INSERT INTO users (username, email, password_hash, role) VALUES ('$username', '$email', '$hashed', '$user_role')";
            
            if ($conn->query($sql)) {
                if ($is_admin_adding) {
                    header('Location: users.php?added=1');
                    exit;
                } else {
                    header('Location: login.php?registered=1');
                    exit;
                }
            } else {
                $errors[] = "Database error: " . $conn->error;
            }
        }
    }
    
    if (!empty($errors)) {
        $message = "<div class='alert-error'><strong>Please fix:</strong><br>• " . implode("<br>• ", $errors) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $is_admin_adding ? 'Add User' : 'Register' ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php if ($is_admin_adding): ?>
    <?php include 'navbar.php'; ?>
    <div class="container form-page" style="max-width:500px;">
        <h1>Add New User</h1>
        <p style="color:#888; margin-bottom:20px;">Create a new user account</p>
        <?php echo $message; ?>
        <form method="POST" action="register.php">
            <label>Username <span class="required">*</span></label>
            <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required>
            <div class="hint">Min 3 characters</div>
            
            <label>Email <span class="required">*</span></label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
            
            <label>Password <span class="required">*</span></label>
            <input type="password" name="password" required>
            <div class="hint">Min 6 characters</div>
            
            <label>Confirm Password <span class="required">*</span></label>
            <input type="password" name="confirm_password" required>
            
            <label>Role</label>
            <select name="role" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; margin-top:3px;">
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
            </select>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit">Add User</button>
                <a href="users.php" class="cancel">Cancel</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="register-box">
        <h2>Register</h2>
        <?php echo $message; ?>
        <form method="POST" action="register.php">
            <label>Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required>
            <div class="hint">Min 3 characters</div>
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <div class="hint">Min 6 characters</div>
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
            <button type="submit">Register</button>
            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </form>
    </div>
<?php endif; ?>
</body>
</html>