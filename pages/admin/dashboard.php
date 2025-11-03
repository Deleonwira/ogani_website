<?php
session_start();
require_once '../../database/db_connect.php'; 

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}


$productCount = 0;
$categoryCount = 0;
$orderCount = 0;


$result = $conn->query("SELECT COUNT(*) AS total FROM products");
if ($row = $result->fetch_assoc()) {
    $productCount = $row['total'];
}


$result = $conn->query("SELECT COUNT(*) AS total FROM categories");
if ($row = $result->fetch_assoc()) {
    $categoryCount = $row['total'];
}


$result = $conn->query("SELECT COUNT(*) AS total FROM orders");
if ($row = $result->fetch_assoc()) {
    $orderCount = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Ogani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>

    <!-- Sidebar -->
    <?php require_once '../includes/sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <nav class="navbar navbar-light mb-4 p-3 d-flex justify-content-between align-items-center">
            <h3 class="mb-0 fw-semibold">Dashboard</h3>
            <div>
                <span class="me-3">👤 <?= htmlspecialchars($_SESSION['username']); ?></span>
                <a href="../logout.php" class="logout-btn">Logout</a>
            </div>
        </nav>

        <!-- Dashboard Cards -->
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card p-3 text-center shadow-sm border-0">
                    <i class='bx bxs-package card-icon text-success fs-1'></i>
                    <h5 class="mt-2">Products</h5>
                    <p class="text-muted mb-1"><?= $productCount; ?> items</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 text-center shadow-sm border-0">
                    <i class='bx bx-cart card-icon text-primary fs-1'></i>
                    <h5 class="mt-2">Orders</h5>
                    <p class="text-muted mb-1"><?= $orderCount; ?> orders</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 text-center shadow-sm border-0">
                    <i class='bx bx-category card-icon text-warning fs-1'></i>
                    <h5 class="mt-2">Categories</h5>
                    <p class="text-muted mb-1"><?= $categoryCount; ?> categories</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
