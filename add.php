<?php
require_once 'config.php';

// Protect this page - require login
requireLogin();

$message = '';
$name = $description = $price = $stock = $category_id = $supplier_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = $conn->real_escape_string(trim($_POST['name'] ?? ''));
    $description = $conn->real_escape_string(trim($_POST['description'] ?? ''));
    $price       = $_POST['price'] ?? '';
    $stock       = $_POST['stock'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $supplier_id = $_POST['supplier_id'] ?? '';

    if (empty($name) || empty($category_id) || empty($supplier_id)) {
        $message = '<div class="alert-error">Name, category, and supplier are required.</div>';
    } elseif (!is_numeric($price) || (float)$price < 0) {
        $message = '<div class="alert-error">Please enter a valid price.</div>';
    } elseif (!is_numeric($stock) || (int)$stock < 0) {
        $message = '<div class="alert-error">Please enter a valid stock quantity.</div>';
    } else {
        $price_val = (float)$price;
        $stock_val = (int)$stock;
        $cat_val   = (int)$category_id;
        $sup_val   = (int)$supplier_id;

        $sql = "INSERT INTO products (name, description, price, stock, category_id, supplier_id)
                VALUES ('$name', '$description', $price_val, $stock_val, $cat_val, $sup_val)";

        if ($conn->query($sql)) {
            echo '<div class="alert-success" style="font-size:1.2em; text-align:center;">Product added! Redirecting...</div>';
            header('Refresh: 2; URL=index.php');
            exit;
        } else {
            $message = '<div class="alert-error">Error: ' . $conn->error . '</div>';
        }
    }
}

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
$suppliers  = $conn->query("SELECT id, name FROM suppliers ORDER BY name");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Additional styles for add page */
        .form-page {
            max-width: 550px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-page h1 {
            color: #1B5E20;
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 3px solid #4CAF50;
        }
        .form-page label {
            display: block;
            margin-top: 18px;
            font-weight: bold;
            color: #333;
            font-size: 0.95em;
        }
        .form-page input,
        .form-page select,
        .form-page textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            margin-top: 5px;
            font-size: 15px;
            transition: border-color 0.3s;
        }
        .form-page input:focus,
        .form-page select:focus,
        .form-page textarea:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }
        .form-page textarea {
            resize: vertical;
            font-family: Arial, sans-serif;
        }
        .form-page .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
            align-items: center;
        }
        .form-page .btn-submit {
            padding: 12px 35px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            transition: background 0.3s;
        }
        .form-page .btn-submit:hover {
            background: #388E3C;
        }
        .form-page .cancel {
            color: #666;
            text-decoration: none;
            font-size: 1em;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .form-page .cancel:hover {
            background: #f5f5f5;
            color: #333;
        }
        .form-page .required {
            color: #f44336;
            margin-left: 2px;
        }
        .form-page .field-hint {
            font-size: 0.8em;
            color: #888;
            margin-top: 3px;
        }
        .form-page select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 35px;
        }
        .alert-success {
            color: #1B5E20;
            background: #e8f5e9;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #4CAF50;
        }
        .alert-error {
            color: #c62828;
            background: #ffebee;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #f44336;
        }
        @media (max-width: 600px) {
            .form-page {
                padding: 20px;
                margin: 10px;
            }
            .form-page .form-actions {
                flex-direction: column;
            }
            .form-page .btn-submit {
                width: 100%;
            }
            .form-page .cancel {
                text-align: center;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <div class="form-page">
            <h1>Add New Product</h1>

            <?php if ($message): ?>
                <?= $message ?>
            <?php endif; ?>

            <form method="POST" action="add.php">
                <label>
                    Product Name <span class="required">*</span>
                </label>
                <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required placeholder="e.g. Wireless Mouse">
                <div class="field-hint">Enter the product name</div>

                <label>Description</label>
                <textarea name="description" rows="3" placeholder="e.g. 2.4GHz cordless mouse with optical sensor"><?= htmlspecialchars($description) ?></textarea>
                <div class="field-hint">Optional: Provide a brief description</div>

                <label>
                    Price (₱) <span class="required">*</span>
                </label>
                <input type="number" name="price" value="<?= htmlspecialchars($price) ?>" step="0.01" min="0" required placeholder="e.g. 499.00">
                <div class="field-hint">Enter the product price in Philippine Pesos</div>

                <label>
                    Stock <span class="required">*</span>
                </label>
                <input type="number" name="stock" value="<?= htmlspecialchars($stock) ?>" min="0" required placeholder="e.g. 50">
                <div class="field-hint">Enter the available quantity in stock</div>

                <label>
                    Category <span class="required">*</span>
                </label>
                <select name="category_id" required>
                    <option value="">Select Category --</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= ($category_id == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <div class="field-hint">Select the product category</div>

                <label>
                    Supplier <span class="required">*</span>
                </label>
                <select name="supplier_id" required>
                    <option value="">-- Select Supplier --</option>
                    <?php while ($sup = $suppliers->fetch_assoc()): ?>
                        <option value="<?= $sup['id'] ?>"
                            <?= ($supplier_id == $sup['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sup['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <div class="field-hint">Select the product supplier</div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Add Product</button>
                    <a href="index.php" class="cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>