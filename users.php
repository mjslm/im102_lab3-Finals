<?php
require_once 'config.php';
requireAdmin();

$users = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .btn-edit-user {
            display: inline-block;
            padding: 3px 12px;
            background: #e8f5e9;
            color: #1B5E20;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 600;
            min-width: 50px;
            text-align: center;
        }
        .btn-edit-user:hover {
            background: #c8e6c9;
        }
        .btn-delete-user {
            display: inline-block;
            padding: 3px 12px;
            background: #ffebee;
            color: #c62828;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 600;
            min-width: 50px;
            text-align: center;
        }
        .btn-delete-user:hover {
            background: #ffcdd2;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        .success-msg {
            color: #1B5E20;
            background: #e8f5e9;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #4CAF50;
        }
        .error-msg {
            color: #c62828;
            background: #ffebee;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #ef5350;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="top-bar">
        <h1>User Management</h1>
        <a href="register.php" class="btn-add">+ Add User</a>
    </div>
    
    <?php if (isset($_GET['added'])): ?>
        <div class="success-msg">User added successfully!</div>
    <?php endif; ?>
    
    <?php if (isset($_GET['deleted'])): ?>
        <div class="success-msg">User deleted successfully!</div>
    <?php endif; ?>
    
    <?php if (isset($_GET['updated'])): ?>
        <div class="success-msg">User updated successfully!</div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error']) && $_GET['error'] == 'self'): ?>
        <div class="error-msg">You cannot delete your own account!</div>
    <?php endif; ?>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <span class="role-badge" style="background: <?= $user['role'] == 'admin' ? '#FF9800' : '#4CAF50'; ?>; color:white; padding:3px 12px; border-radius:15px; font-size:0.8em;">
                            <?= htmlspecialchars($user['role']) ?>
                        </span>
                    </td>
                    <td><?= $user['created_at'] ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn-edit-user">Edit</a>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn-delete-user">Delete</a>
                            <?php else: ?>
                                <span style="color:#999; font-size:0.75em;">(You)</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <p class="count">Total: <?= $users->num_rows ?> user(s)</p>
    <a href="index.php" class="back-btn" style="margin-top:10px; display:inline-block;">Back to Dashboard</a>
</div>
</body>
</html>