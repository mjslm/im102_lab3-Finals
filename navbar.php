<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav>
    <a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Products</a>
    <a href="report.php" class="<?= $current_page == 'report.php' ? 'active' : '' ?>">Reports</a>

    <?php if (isAdmin()): ?>
        <a href="add.php" class="<?= $current_page == 'add.php' ? 'active' : '' ?>">Add</a>
    <?php endif; ?>

    <?php if (isAdmin()): ?>
        <a href="users.php" class="<?= $current_page == 'users.php' ? 'active' : '' ?>">Users</a>
    <?php endif; ?>

    <?php if (isLoggedIn()): ?>
        <span style="margin-left:auto;">
            <?= getUsername() ?>
            <span style="background: <?= isAdmin() ? '#FF9800' : '#4CAF50'; ?>; color: white; padding: 2px 10px; border-radius: 12px; font-size: 0.75em; font-weight: bold;">
                <?= $_SESSION['role'] ?>
            </span>
            <a href="logout.php">Logout</a>
        </span>
    <?php else: ?>
        <span style="margin-left:auto;">
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </span>
    <?php endif; ?>
</nav>