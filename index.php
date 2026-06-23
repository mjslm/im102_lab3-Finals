<?php
require_once 'config.php';

// Add requireLogin() to the top - now this page is protected
requireLogin();

// Get username from session
$username = getUsername();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin: 25px 0;
        }
        .dashboard-btn {
            display: inline-block;
            padding: 12px 35px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1em;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            min-width: 150px;
            text-align: center;
        }
        /* Report Button - Green */
        .report-btn {
            background: #4CAF50;
            color: white;
        }
        .report-btn:hover {
            background: #388E3C;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        }
        /* Add Product Button - Green */
        .add-btn {
            background: #4CAF50;
            color: white;
        }
        .add-btn:hover {
            background: #388E3C;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        }
        /* Products Button - Green (same as report) */
        .products-btn {
            background: #4CAF50;
            color: white;
        }
        .products-btn:hover {
            background: #388E3C;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        }
        .logout-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background: #d32f2f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
        }
        .dashboard-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .dashboard-container h1 {
            color: #1B5E20;
            margin-top: 0;
        }
        .welcome {
            font-size: 1.2em;
            color: #333;
            margin: 20px 0;
        }
        .user-info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .user-info p {
            margin: 8px 0;
        }
        .role-badge {
            display: inline-block;
            padding: 5px 15px;
            background: #2196F3;
            color: white;
            border-radius: 20px;
            font-size: 0.9em;
        }
        .role-badge.admin {
            background: #FF9800;
        }
        .dashboard-body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
    </style>
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <h1>Inventory System</h1>
        
        <div class="welcome">
            Welcome, <strong><?php echo htmlspecialchars($username); ?></strong>!
        </div>
        
        <div class="user-info">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
            <p><strong>Role:</strong> <span class="role-badge <?php echo ($_SESSION['role'] == 'admin') ? 'admin' : ''; ?>"><?php echo htmlspecialchars($_SESSION['role']); ?></span></p>
            <p><strong>User ID:</strong> <?php echo $_SESSION['user_id']; ?></p>
        </div>
        
        <!-- Dashboard Action Buttons - Centered -->
        <div class="dashboard-actions">
            <a href="products.php" class="dashboard-btn products-btn">Products</a>
            <a href="report.php" class="dashboard-btn report-btn">Reports</a>
            <?php if (isAdmin()): ?>
                <a href="add.php" class="dashboard-btn add-btn">Add Product</a>
            <?php endif; ?>
        </div>
        
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>