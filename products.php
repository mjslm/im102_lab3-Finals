<?php
require_once 'config.php';
requireLogin();

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "
SELECT p.id, p.name, p.description, p.price, p.stock, p.created_at,
       c.name AS category,
       s.name AS supplier
FROM products p
JOIN categories c ON p.category_id = c.id
JOIN suppliers s ON p.supplier_id = s.id
WHERE 1=1
";

if (!empty($search)) {
    $sql .= " AND (
        p.name LIKE '%" . $conn->real_escape_string($search) . "%'
        OR p.description LIKE '%" . $conn->real_escape_string($search) . "%'
    )";
}

if (!empty($category)) {
    $sql .= " AND c.name = '" . $conn->real_escape_string($category) . "'";
}

$sql .= " ORDER BY p.id ASC";
$result = $conn->query($sql);
$categories = $conn->query("SELECT DISTINCT name FROM categories ORDER BY name");

$stats_sql = "
SELECT COUNT(*) AS total,
       SUM(p.stock) AS total_stock,
       SUM(p.price * p.stock) AS total_value,
       SUM(CASE WHEN p.stock < 20 THEN 1 ELSE 0 END) AS low_stock
FROM products p
JOIN categories c ON p.category_id = c.id
JOIN suppliers s ON p.supplier_id = s.id
WHERE 1=1
";

if (!empty($search)) {
    $stats_sql .= " AND (
        p.name LIKE '%" . $conn->real_escape_string($search) . "%'
        OR p.description LIKE '%" . $conn->real_escape_string($search) . "%'
    )";
}

if (!empty($category)) {
    $stats_sql .= " AND c.name = '" . $conn->real_escape_string($category) . "'";
}

$stats = $conn->query($stats_sql)->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="top-bar">
        <h1>Products</h1>
        <?php if (isAdmin()): ?>
            <a href="add.php" class="btn-add">+ Add Product</a>
        <?php endif; ?>
    </div>

    <form class="search-bar" method="GET">
        <input type="text" name="search" placeholder="Search Product" value="<?= htmlspecialchars($search) ?>">
        <select name="category">
            <option value="">All Categories</option>
            <?php while ($c = $categories->fetch_assoc()): ?>
                <option value="<?= $c['name'] ?>" <?= ($category == $c['name']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Filter / Search</button>
        <?php if (!empty($search) || !empty($category)): ?>
            <a href="products.php" class="clear-btn">Clear</a>
        <?php endif; ?>
    </form>

    <div class="stats-cards">
        <div class="stat-card">
            <span class="stat-icon"></span>
            <div class="stat-value"><?= $stats['total'] ?></div>
            <div class="stat-label">Total Products</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon"></span>
            <div class="stat-value"><?= number_format($stats['total_stock']) ?></div>
            <div class="stat-label">Total Stock</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon"></span>
            <div class="stat-value">₱<?= number_format($stats['total_value'], 2) ?></div>
            <div class="stat-label">Inventory Value</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon"></span>
            <div class="stat-value"><?= $stats['low_stock'] ?></div>
            <div class="stat-label">Low Stock</div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="<?= ($row['stock'] < 20) ? 'low-stock' : '' ?>">
                    <td><?= $row['id'] ?></td>
                    <td>
                        <?= htmlspecialchars($row['name']) ?>
                        <?php if ($row['stock'] < 20): ?>
                            <span class="low-stock-badge">Low Stock</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>₱<?= number_format($row['price'], 2) ?></td>
                    <td><?= $row['stock'] ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['supplier']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <?php if (isAdmin()): ?>
                            <div class="action-buttons">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Delete this product?')">Delete</a>
                            </div>
                        <?php else: ?>
                            <span class="view-only">View only</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <p class="count">Total: <?= $result->num_rows ?> product(s)</p>
</div>
</body>
</html>