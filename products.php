<?php
require_once 'config.php';

// Add requireLogin() to protect this page
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
$categories = $conn->query("
SELECT DISTINCT name
FROM categories
ORDER BY name
");

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
    <title>Products - Inventory System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">

    <div class="top-bar">
        <h1>Products</h1>
        <a href="add.php" class="btn-add">Add New Product</a>
    </div>

    <form class="search-bar" method="GET">
        <input
            type="text"
            name="search"
            placeholder="Search Product"
            value="<?= htmlspecialchars($search) ?>">

        <select name="category">
            <option value="">All Categories</option>

            <?php while ($c = $categories->fetch_assoc()): ?>
                <option value="<?= $c['name'] ?>"
                    <?= ($category == $c['name']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Filter / Search</button>
        <?php if (!empty($search) || !empty($category)): ?>
            <a href="products.php" class="clear-btn" style="padding:9px 20px; background:#9e9e9e; color:white; text-decoration:none; border-radius:4px;">Clear</a>
        <?php endif; ?>
    </form>

    <div class="stats-cards">
        <div class="stat-card green">
            <span class="stat-icon"></span>
            <div class="stat-value"><?= $stats['total'] ?></div>
            <div class="stat-label">Total Products</div>
        </div>
        <div class="stat-card blue">
            <span class="stat-icon"></span>
            <div class="stat-value"><?= number_format($stats['total_stock']) ?></div>
            <div class="stat-label">Total Stock</div>
        </div>
        <div class="stat-card purple">
            <span class="stat-icon"></span>
            <div class="stat-value">₱<?= number_format($stats['total_value'], 2) ?></div>
            <div class="stat-label">Inventory Value</div>
        </div>
        <div class="stat-card red">
            <span class="stat-icon"></span>
            <div class="stat-value"><?= $stats['low_stock'] ?></div>
            <div class="stat-label">Low Stock Items</div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
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
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                            <a href="delete.php?id=<?= $row['id'] ?>"
                               class="btn-delete"
                               onclick="return confirm('Are you sure you want to delete this product?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align:center; color:#999; padding:30px;">
                            No products found matching your search.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <p class="count">
        Total: <?= $result->num_rows ?> product(s)
        <?php if (!empty($search) || !empty($category)): ?>
            (filtered)
        <?php endif; ?>
    </p>
</div>

</body>
</html>