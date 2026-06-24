<?php
$current_page = basename($_SERVER['PHP_SELF']);
$role_color = isAdmin() ? '#FF9800' : '#4CAF50'; // Orange for admin, Green for staff
?>
<nav>
    <a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Home</a>
    <a href="products.php" class="<?= $current_page == 'products.php' ? 'active' : '' ?>">Products</a>
    <a href="report.php" class="<?= $current_page == 'report.php' ? 'active' : '' ?>">Reports</a>

    <?php if (isAdmin()) : ?>
        <a href="add.php" class="<?= $current_page == 'add.php' ? 'active' : '' ?>">Add</a>
        <a href="users.php" class="<?= $current_page == 'users.php' ? 'active' : '' ?>">Users</a>
    <?php endif; ?>

    <span style="margin-left:auto;">
        <?= getUsername() ?>
        <span style="background: <?= $role_color ?>; color: white; padding: 2px 10px; border-radius: 12px; font-size: 0.75em; font-weight: bold;">
            <?= $_SESSION['role'] ?>
        </span>
        <a href="logout.php">Logout</a>
    </span>
</nav>