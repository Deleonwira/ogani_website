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
  header('Location: manage_categories.php');
  exit();
}

// CSRF validation
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
  header('Location: manage_categories.php?error=invalid_csrf');
  exit();
}

$id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
if ($id <= 0) {
  header('Location: manage_categories.php?error=invalid_id');
  exit();
}

$check = $conn->prepare("SELECT * FROM categories WHERE category_id = ?");
$check->bind_param("i", $id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
  header('Location: manage_categories.php?error=not_found');
  exit();
}

$stmt = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
if (!$stmt) {
  die("SQL Error: " . $conn->error);
}
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  header("Location: manage_categories.php?msg=deleted");
  exit();
} else {
  echo "Failed to delete category.";
}
?>
