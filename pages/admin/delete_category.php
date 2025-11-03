<?php
include '../../database/db_connect.php'; 


if (!isset($_GET['id'])) {
    die("Category ID not provided.");
}

$id = intval($_GET['id']);


$check = $conn->prepare("SELECT * FROM categories WHERE category_id = ?");
$check->bind_param("i", $id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    die("Category not found.");
}

$stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: manage_categories.php?msg=deleted");
    exit;
} else {
    echo "Failed to delete category.";
}
?>
