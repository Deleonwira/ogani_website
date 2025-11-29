<?php
require_once __DIR__ . "/../../database/db_connect.php";
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
  header("Location: ../login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: manage_products.php');
  exit();
}

// CSRF validation
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
  header('Location: manage_products.php?error=invalid_csrf');
  exit();
}

$id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
if ($id <= 0) {
  header('Location: manage_products.php?error=invalid_id');
  exit();
}

$stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
if (!$stmt) {
  die("SQL Error: " . $conn->error);
}
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  header("Location: manage_products.php?msg=deleted");
  exit();
} else {
  die("Failed to delete product: " . $conn->error);
}
?>
