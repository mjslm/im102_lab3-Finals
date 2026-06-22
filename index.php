<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 50px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            color: #1B5E20;
        }
        .welcome {
            font-size: 1.2em;
            color: #333;
            margin: 20px 0;
        }
        .user-info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .logout-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            font-weight: bold;
        }
        .logout-btn:hover {
            background: #d32f2f;
        }
        .nav-links {
            margin: 20px 0;
        }
        .nav-links a {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .nav-links a:hover {
            background: #388E3C;
        }
        .role-badge {
            display: inline-block;
            padding: 5px 15px;
            background: #2196F3;
            color: white;
            border-radius: 20px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 Inventory System</h1>
        
        <div class="welcome">
            👋 Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
        </div>
        
        <div class="user-info">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>Role:</strong> <span class="role-badge"><?php echo htmlspecialchars($_SESSION['role']); ?></span></p>
            <p><strong>User ID:</strong> <?php echo $_SESSION['user_id']; ?></p>
        </div>
        
        <div class="nav-links">
            <a href="register.php">📝 Register New User</a>
            <a href="login.php">🔐 Login Page</a>
        </div>
        
        <a href="logout.php" class="logout-btn">🚪 Logout</a>
    </div>
</body>
</html>