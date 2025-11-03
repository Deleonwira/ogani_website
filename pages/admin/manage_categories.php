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
    <title>Manage Categories | Ogani Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
<?php require_once '../includes/sidebar.php'; ?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Manage Categories</h3>
        <a href="add_category.php" class="btn btn-add">Add Category</a>
    </div>
    <?php
    $result = $conn->query("SELECT * FROM categories");
    ?>
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-success">
            <tr> 
                <th>ID</th>
                <th>Category Name</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['category_id']; ?></td>
                <td><?= htmlspecialchars($row['category_name']); ?></td>
                <td>
                     <?php if (!empty($row['image'])): ?>
                         <img src="<?= htmlspecialchars($row['image']); ?>" alt="Category Image" width="60" height="60" style="object-fit:cover; border-radius:8px;">
                     <?php else: ?>
                         <span class="text-muted">No image</span>
                     <?php endif; ?>
                </td>
                <td>
                    <a href="edit_category.php?id=<?= $row['category_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete_category.php?id=<?= $row['category_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this category?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
