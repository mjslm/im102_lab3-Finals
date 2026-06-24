<?php
require_once 'config.php';
requireLogin();
$username = getUsername();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="dashboard-container">
    <h1>Inventory System</h1>
    <div class="welcome">Welcome, <strong><?= htmlspecialchars($username) ?></strong>!</div>
    <div class="user-info">
        <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
        <p><strong>Role:</strong> <span class="role-badge"><?= htmlspecialchars($_SESSION['role']) ?></span></p>
        <p><strong>User ID:</strong> <?= $_SESSION['user_id'] ?></p>
    </div>
    <a href="products.php" class="dashboard-btn">View Products</a>
    <a href="report.php" class="dashboard-btn">View Reports</a>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>
</body>
</html>