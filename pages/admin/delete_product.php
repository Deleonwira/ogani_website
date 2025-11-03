<?php
include '../../database/db_connect.php';

if (!isset($_GET['id'])) {
    die("Product ID not provided.");
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: manage_products.php?msg=deleted");
    exit;
} else {
    die("Failed to delete product: " . $conn->error);
}
?>
