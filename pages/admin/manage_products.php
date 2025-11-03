<?php
require_once '../../database/db_connect.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products | Ogani Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
<?php require_once '../includes/sidebar.php'; ?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Manage Products</h3>
        <a href="add_product.php" class="btn btn-add"><i class="bx bx-plus"></i> Add Product</a>
    </div>

    <?php
    $sql = "SELECT p.*, c.category_name FROM products p JOIN categories c ON p.category_id = c.category_id";
    $result = $conn->query($sql);
    ?>
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-success">
            <tr>
                <th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Image</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['product_id']; ?></td>
                <td><?= htmlspecialchars($row['product_name']); ?></td>
                <td><?= htmlspecialchars($row['category_name']); ?></td>
                <td>Rp<?= number_format($row['price'], 0, ',', '.'); ?></td>
                <td><?= $row['stock']; ?></td>
                <td><img src="<?= $row['product_image']; ?>" width="50"></td>
                <td>
                    <a href="edit_product.php?id=<?= $row['product_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_product.php?id=<?= $row['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
