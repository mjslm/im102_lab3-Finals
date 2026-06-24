<?php
require_once 'config.php';
requireLogin();

$username = getUsername();

// ── Overall Summary ──────────────────────────────────────────
$summary = $conn->query("
    SELECT
        COUNT(*)                        AS total_products,
        SUM(p.stock)                    AS total_stock,
        SUM(p.price * p.stock)          AS total_value,
        AVG(p.price)                    AS avg_price,
        SUM(CASE WHEN p.stock < 20 THEN 1 ELSE 0 END) AS low_stock
    FROM products p
")->fetch_assoc();

// ── Per-Category Breakdown ───────────────────────────────────
$by_category = $conn->query("
    SELECT
        c.name                          AS category,
        COUNT(p.id)                     AS product_count,
        COALESCE(SUM(p.stock), 0)       AS total_stock,
        COALESCE(SUM(p.price * p.stock), 0) AS total_value,
        COALESCE(AVG(p.price), 0)       AS avg_price
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id
    GROUP BY c.id, c.name
    ORDER BY total_value DESC
");

// ── Per-Supplier Breakdown ───────────────────────────────────
$by_supplier = $conn->query("
    SELECT
        s.name                          AS supplier,
        s.contact_person,
        s.phone,
        COUNT(p.id)                     AS product_count,
        COALESCE(SUM(p.stock), 0)       AS total_stock,
        COALESCE(SUM(p.price * p.stock), 0) AS total_value
    FROM suppliers s
    LEFT JOIN products p ON s.id = p.supplier_id
    GROUP BY s.id, s.name, s.contact_person, s.phone
    ORDER BY total_value DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports – Inventory System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        .card {
            background: white;
            padding: 18px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
            border-top: 4px solid #4CAF50;
        }
        .card-value {
            font-size: 1.6em;
            font-weight: bold;
            color: #1B5E20;
            margin: 0 0 4px 0;
        }
        .card-label {
            color: #888;
            font-size: 0.78em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .section-header {
            margin: 25px 0 10px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #eee;
        }
        .section-header h2 {
            margin: 0;
            font-size: 1em;
            color: #1B5E20;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .report-table th {
            background: #f5f5f5;
            color: #333;
            padding: 10px 12px;
            text-align: left;
            font-size: 0.82em;
            border-bottom: 2px solid #ddd;
        }
        .report-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #eee;
            font-size: 0.9em;
        }
        .report-table tr:hover {
            background: #f9f9f9;
        }
        .val {
            font-weight: bold;
            color: #1B5E20;
        }
        .muted {
            color: #bbb;
            font-style: italic;
        }
        .back-btn {
            display: inline-block;
            padding: 8px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .back-btn:hover {
            background: #388E3C;
        }
        .report-footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #eee;
            text-align: center;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">

    <div class="top-bar">
        <h1>Inventory Reports</h1>
    </div>

    <!-- Summary Cards - All Green -->
    <div class="summary-cards">
        <div class="card">
            <p class="card-value"><?= $summary['total_products'] ?></p>
            <p class="card-label">Total Products</p>
        </div>
        <div class="card">
            <p class="card-value"><?= number_format($summary['total_stock']) ?></p>
            <p class="card-label">Total Stock Units</p>
        </div>
        <div class="card">
            <p class="card-value">₱<?= number_format($summary['total_value'], 2) ?></p>
            <p class="card-label">Total Inventory Value</p>
        </div>
        <div class="card">
            <p class="card-value">₱<?= number_format($summary['avg_price'], 2) ?></p>
            <p class="card-label">Average Price</p>
        </div>
    </div>

    <!-- Per-Category Breakdown -->
    <div class="section-header">
        <h2>By Category</h2>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Category</th>
                <th>Products</th>
                <th>Total Stock</th>
                <th>Average Price</th>
                <th>Total Value</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $by_category->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td>
                    <?php if ($row['product_count'] == 0): ?>
                        <span class="muted">No products</span>
                    <?php else: ?>
                        <?= $row['product_count'] ?>
                    <?php endif; ?>
                </td>
                <td><?= number_format($row['total_stock']) ?></td>
                <td>
                    <?= $row['product_count'] > 0
                        ? '₱' . number_format($row['avg_price'], 2)
                        : '—' ?>
                </td>
                <td class="val">
                    <?= $row['product_count'] > 0
                        ? '₱' . number_format($row['total_value'], 2)
                        : '—' ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Per-Supplier Breakdown -->
    <div class="section-header">
        <h2>By Supplier</h2>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Supplier</th>
                <th>Contact Person</th>
                <th>Phone</th>
                <th>Products</th>
                <th>Total Stock</th>
                <th>Total Value</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $by_supplier->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['supplier']) ?></td>
                <td><?= htmlspecialchars($row['contact_person']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td>
                    <?php if ($row['product_count'] == 0): ?>
                        <span class="muted">No products</span>
                    <?php else: ?>
                        <?= $row['product_count'] ?>
                    <?php endif; ?>
                </td>
                <td><?= number_format($row['total_stock']) ?></td>
                <td class="val">
                    <?= $row['product_count'] > 0
                        ? '₱' . number_format($row['total_value'], 2)
                        : '—' ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="report-footer">
        <a href="index.php" class="back-btn">Back to Dashboard</a>
    </div>
</div>

</body>
</html>