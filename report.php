<?php
require_once 'config.php';

// Require login and admin access
requireLogin();
requireAdmin();

// Get username from session
$username = getUsername();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .report-body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .report-container {
            max-width: 600px;
            width: 100%;
            margin: 20px;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .report-container h1 {
            color: #1B5E20;
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 3px solid #4CAF50;
        }
        .report-info {
            background: #f5f5f5;
            padding: 15px 20px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: left;
        }
        .report-info p {
            margin: 6px 0;
        }
        .admin-only {
            background: #fff3e0;
            border-left: 4px solid #FF9800;
            padding: 12px 15px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: left;
        }
        .staff-info {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 12px 15px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: left;
        }
        /* Report Buttons */
        .report-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin: 25px 0;
        }
        .report-btn {
            display: block;
            padding: 14px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1em;
            transition: all 0.3s ease;
            text-align: center;
            border: none;
            cursor: pointer;
        }
        .report-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .report-btn.green {
            background: #4CAF50;
            color: white;
        }
        .report-btn.green:hover {
            background: #388E3C;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        }
        .report-btn.blue {
            background: #2196F3;
            color: white;
        }
        .report-btn.blue:hover {
            background: #1976D2;
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
        }
        .report-btn.orange {
            background: #FF9800;
            color: white;
        }
        .report-btn.orange:hover {
            background: #F57C00;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.4);
        }
        .report-btn.purple {
            background: #9C27B0;
            color: white;
        }
        .report-btn.purple:hover {
            background: #7B1FA2;
            box-shadow: 0 4px 12px rgba(156, 39, 176, 0.4);
        }
        .report-btn.red {
            background: #f44336;
            color: white;
        }
        .report-btn.red:hover {
            background: #d32f2f;
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
        }
        .back-btn {
            display: inline-block;
            padding: 12px 35px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            background: #388E3C;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        }
        .section-title {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.1em;
        }
        .badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .badge-admin {
            background: #FF9800;
            color: white;
        }
        .badge-staff {
            background: #2196F3;
            color: white;
        }
    </style>
</head>
<body class="report-body">
    <div class="report-container">
        <h1>Reports Dashboard</h1>
        
        <div class="report-info">
            <p><strong>User:</strong> <?php echo htmlspecialchars($username); ?></p>
            <p><strong>Role:</strong> 
                <span class="badge <?php echo isAdmin() ? 'badge-admin' : 'badge-staff'; ?>">
                    <?php echo htmlspecialchars($_SESSION['role']); ?>
                </span>
            </p>
            <p><strong>Access Time:</strong> <?php echo date('F j, Y - g:i A'); ?></p>
        </div>
        
        <?php if (isAdmin()): ?>
            <div class="admin-only">
                <strong>Admin Access:</strong> You have full access to all reports.
            </div>
        <?php else: ?>
            <div class="staff-info">
                <strong>ℹStaff Access:</strong> You have limited access to reports.
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="back-btn">← Back to Dashboard</a>
    </div>
</body>
</html>