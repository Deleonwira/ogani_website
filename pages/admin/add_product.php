<?php
include '../../database/db_connect.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url']; 

    // insert ke database
    $stmt = $conn->prepare("INSERT INTO products (category_id, product_name, price, stock, description, product_image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdiss", $category_id, $name, $price, $stock, $description, $image_url);

    if ($stmt->execute()) {
        $message = "<div class='alert success'>✅ Product added successfully!</div>";
    } else {
        $message = "<div class='alert error'>❌ Failed to add product.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
<div class="container">
    <h2>Add New Product</h2>
    <?= $message; ?>
    <form action="" method="POST" class="form-card">
        <label>Product Name</label>
        <input type="text" name="name" required>

        <label>Category</label>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php
            $cat = $conn->query("SELECT * FROM categories");
            while ($row = $cat->fetch_assoc()):
            ?>
                <option value="<?= $row['category_id']; ?>"><?= htmlspecialchars($row['category_name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Price</label>
        <input type="number" name="price" step="0.01" required>

        <label>Stock</label>
        <input type="number" name="stock" required>

        <label>Description</label>
        <textarea name="description" rows="4"></textarea>

        <label>Product Image (Link URL)</label>
        <input type="url" name="image_url" placeholder="https://example.com/image.jpg" required>

        <div class="form-actions">
            <button type="submit" class="btn btn-add">Save</button>
            <a href="manage_products.php" class="btn btn-cancel">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
