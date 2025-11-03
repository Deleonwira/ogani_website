<?php
include '../../database/db_connect.php';

$message = "";


if (!isset($_GET['id'])) {
    die("Product ID not provided.");
}
$id = $_GET['id'];


$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];

    $update = $conn->prepare("
        UPDATE products 
        SET category_id = ?, product_name = ?, price = ?, stock = ?, description = ?, product_image = ?
        WHERE product_id = ?
    ");
    if (!$update) {
        die("SQL Error: " . $conn->error);
    }
    $update->bind_param("isdissi", $category_id, $name, $price, $stock, $description, $image_url, $id);

    if ($update->execute()) {
        $message = "<div class='alert success'>✅ Product updated successfully!</div>";
       
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
    } else {
        $message = "<div class='alert error'>❌ Failed to update product.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
<div class="container">
    <h2>Edit Product</h2>
    <?= $message; ?>
    <form action="" method="POST" class="form-card">
        <label>Product Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['product_name']); ?>" required>

        <label>Category</label>
        <select name="category_id" required>
            <?php
            $cat = $conn->query("SELECT * FROM categories");
            while ($row = $cat->fetch_assoc()):
                $selected = $row['category_id'] == $product['category_id'] ? 'selected' : '';
            ?>
                <option value="<?= $row['category_id']; ?>" <?= $selected; ?>>
                    <?= htmlspecialchars($row['category_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Price</label>
        <input type="number" name="price" step="0.01" value="<?= $product['price']; ?>" required>

        <label>Stock</label>
        <input type="number" name="stock" value="<?= $product['stock']; ?>" required>

        <label>Description</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($product['description']); ?></textarea>

        <label>Product Image (Link URL)</label>
        <input type="url" name="image_url" value="<?= htmlspecialchars($product['product_image']); ?>" required>

        <div class="form-actions">
            <button type="submit" class="btn btn-add">Update</button>
            <a href="manage_products.php" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
