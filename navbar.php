<?php
// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);

// Check if user is logged in
$logged_in = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
?>
<nav class="navbar">
    <div class="nav-brand">Inventory System</div>
    <div class="nav-links">
        <?php if ($logged_in): ?>
            <a href="index.php" class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">Products</a>
            <a href="report.php" class="nav-link <?php echo $current_page == 'report.php' ? 'active' : ''; ?>">Reports</a>
            <a href="add.php" class="nav-link <?php echo $current_page == 'add.php' ? 'active' : ''; ?>">+ Add</a>
            <span class="nav-separator">|</span>
            <span class="nav-user"><?php echo htmlspecialchars($username); ?></span>
            <a href="logout.php" class="nav-logout">Logout</a>
        <?php else: ?>
            <a href="login.php" class="nav-link-login <?php echo $current_page == 'login.php' ? 'active' : ''; ?>">Login</a>
            <a href="register.php" class="nav-link-login <?php echo $current_page == 'register.php' ? 'active' : ''; ?>">Register</a>
        <?php endif; ?>
    </div>
</nav>