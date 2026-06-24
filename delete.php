<?php
require_once 'config.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->query("DELETE FROM products WHERE id = $id");
    header('Location: products.php?deleted=1');
    exit;
}

$result = $conn->query("
    SELECT p.*, c.name AS category
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.id = $id
");

$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container" style="text-align:center; max-width:500px;">
    <h1>Delete Product</h1>
    
    <p style="font-size:1em; margin:25px 0 10px 0; color:#555;">Are you sure you want to delete:</p>
    
    <p style="font-size:1.4em; font-weight:bold; color:#333; margin:10px 0;">
        <?= htmlspecialchars($product['name']) ?>
    </p>
    
    <p style="color:#888; font-size:0.95em; margin:5px 0 20px 0;">
        <?= htmlspecialchars($product['category']) ?> 
        | ₱<?= number_format($product['price'], 2) ?> 
        | Stock: <?= $product['stock'] ?>
    </p>
    
    <p style="color:#d32f2f; margin:20px 0 30px 0; font-weight:bold;">
        This action cannot be undone.
    </p>

    <form method="POST" style="display:inline;">
        <button type="submit" style="padding:10px 30px; background:#d32f2f; color:white; border:none; border-radius:5px; cursor:pointer; font-size:0.95em; font-weight:bold;">
            Yes, Delete
        </button>
    </form>
    
    <a href="products.php" style="display:inline-block; padding:10px 30px; background:#e0e0e0; color:#555; text-decoration:none; border-radius:5px; margin-left:10px; font-size:0.95em;">
        Cancel
    </a>
</div>
</body>
</html>