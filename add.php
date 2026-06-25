<?php
require_once 'config.php';
requireAdmin(); // Only admin can add products

$message = '';
$name = $description = $price = $stock = $category_id = $supplier_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string(trim($_POST['name'] ?? ''));
    $description = $conn->real_escape_string(trim($_POST['description'] ?? ''));
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $supplier_id = $_POST['supplier_id'] ?? '';
    $added_by = $_SESSION['user_id']; // Current user's ID

    if (empty($name) || empty($category_id) || empty($supplier_id)) {
        $message = "<div class='alert-error'>Name, category, and supplier are required.</div>";
    } elseif (!is_numeric($price) || (float)$price < 0) {
        $message = "<div class='alert-error'>Please enter a valid price.</div>";
    } elseif (!is_numeric($stock) || (int)$stock < 0) {
        $message = "<div class='alert-error'>Please enter a valid stock quantity.</div>";
    } else {
        $price_val = (float)$price;
        $stock_val = (int)$stock;
        $cat_val = (int)$category_id;
        $sup_val = (int)$supplier_id;

        $sql = "INSERT INTO products (name, description, price, stock, category_id, supplier_id, added_by)
                VALUES ('$name', '$description', $price_val, $stock_val, $cat_val, $sup_val, $added_by)";

        if ($conn->query($sql)) {
            header('Location: index.php?added=1');
            exit;
        } else {
            $message = "<div class='alert-error'>Error: " . $conn->error . "</div>";
        }
    }
}

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
$suppliers = $conn->query("SELECT id, name FROM suppliers ORDER BY name");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container form-page">
    <h1>Add Product</h1>
    <?= $message ?>
    <form method="POST" action="add.php">
        <label>Product Name <span class="required">*</span></label>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>

        <label>Description</label>
        <textarea name="description" rows="3"><?= htmlspecialchars($description) ?></textarea>

        <label>Price (₱) <span class="required">*</span></label>
        <input type="number" name="price" value="<?= htmlspecialchars($price) ?>" step="0.01" min="0" required>

        <label>Stock <span class="required">*</span></label>
        <input type="number" name="stock" value="<?= htmlspecialchars($stock) ?>" min="0" required>

        <label>Category <span class="required">*</span></label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?= $cat['id'] ?>" <?= ($category_id == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Supplier <span class="required">*</span></label>
        <select name="supplier_id" required>
            <option value="">-- Select Supplier --</option>
            <?php while ($sup = $suppliers->fetch_assoc()): ?>
                <option value="<?= $sup['id'] ?>" <?= ($supplier_id == $sup['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($sup['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Add Product</button>
            <a href="index.php" class="cancel">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>