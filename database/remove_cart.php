<?php
session_start();
require_once "./db_connect.php";

// Hanya terima POST untuk operasi yang mengubah state
if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
  header("Location: ../pages/customer/shopping-cart.php");
  exit();
}

// Validasi CSRF token
$postedToken = $_POST['csrf_token'] ?? '';
if (!hash_equals((string)($_SESSION['csrf_token'] ?? ''), (string)$postedToken)) {
  // Token tidak valid — tolak permintaan
  header("Location: ../pages/customer/shopping-cart.php?error=csrf");
  exit();
}

$cart_id = isset($_POST["cart_id"]) ? intval($_POST["cart_id"]) : 0;
$user_id = isset($_SESSION["user_id"]) ? intval($_SESSION["user_id"]) : 0;

if ($cart_id > 0 && $user_id > 0) {
  $sql = "DELETE FROM cart WHERE cart_id = ? AND user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $cart_id, $user_id);
  $stmt->execute();
}

header("Location: ../pages/customer/shopping-cart.php");
exit();
?>
