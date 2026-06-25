<?php
require_once 'config.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$result = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string(trim($_POST['username'] ?? ''));
    $email = $conn->real_escape_string(trim($_POST['email'] ?? ''));
    $role = $_POST['role'] ?? 'staff';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($email)) {
        $message = "<div class='alert-error'>Username and email are required.</div>";
    } else {
        // Check if username/email already exists for other users
        $check = $conn->query("SELECT id FROM users WHERE (username = '$username' OR email = '$email') AND id != $id");
        
        if ($check->num_rows > 0) {
            $message = "<div class='alert-error'>Username or email already exists!</div>";
        } else {
            if (!empty($password)) {
                // Update with new password
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET username='$username', email='$email', password_hash='$hashed', role='$role' WHERE id=$id";
            } else {
                // Update without changing password
                $sql = "UPDATE users SET username='$username', email='$email', role='$role' WHERE id=$id";
            }
            
            if ($conn->query($sql)) {
                header('Location: users.php?updated=1');
                exit;
            } else {
                $message = "<div class='alert-error'>Error: " . $conn->error . "</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container form-page" style="max-width:500px;">
    <h1>Edit User</h1>
    <?= $message ?>
    <form method="POST" action="edit_user.php?id=<?= $id ?>">
        <label>Username <span class="required">*</span></label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        
        <label>Email <span class="required">*</span></label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        
        <label>Role</label>
        <select name="role" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px; margin-top:3px;">
            <option value="staff" <?= $user['role'] == 'staff' ? 'selected' : '' ?>>Staff</option>
            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
        
        <label>New Password (leave blank to keep current)</label>
        <input type="password" name="password" placeholder="Enter new password if you want to change">
        <div class="hint">Min 6 characters</div>
        
        <div class="form-actions">
            <button type="submit" class="btn-submit">Update User</button>
            <a href="users.php" class="cancel">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>