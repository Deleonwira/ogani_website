<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
require_once __DIR__ . "/db_connect.php";
require_once __DIR__ . "/flash_message.php";

if (!isset($_SESSION["user_id"])) {
  setFlashMessage('warning', 'Anda harus login terlebih dahulu!');
  header("Location: ../pages/login.php");
  exit();
}

$user_id = intval($_SESSION["user_id"]);
$product_id = isset($_POST["product_id"]) ? intval($_POST["product_id"]) : 0;
$quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;

// Require POST for state-changing action and validate CSRF token
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  setFlashMessage('danger', 'Metode permintaan tidak valid.');
  header("Location: " . $_SERVER['HTTP_REFERER']);
  exit();
}

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
  setFlashMessage('danger', 'Token CSRF tidak valid. Silakan coba lagi.');
  header("Location: " . $_SERVER['HTTP_REFERER']);
  exit();
}

if ($product_id <= 0) {
  setFlashMessage('danger', 'Produk tidak ditemukan.');
  header("Location: " . $_SERVER['HTTP_REFERER']);
  exit();
}

if ($quantity <= 0) {
  $quantity = 1;
}

$productStmt = $conn->prepare("SELECT product_id, stock FROM products WHERE product_id = ?");
$productStmt->bind_param("i", $product_id);
$productStmt->execute();
$product = $productStmt->get_result()->fetch_assoc();

if (!$product) {
  setFlashMessage('danger', 'Produk tidak tersedia.');
  header("Location: " . $_SERVER['HTTP_REFERER']);
  exit();
}

if ($product["stock"] <= 0) {
  setFlashMessage('danger', 'Stok produk sedang habis.');
  header("Location: " . $_SERVER['HTTP_REFERER']);
  exit();
}

$conn->begin_transaction();

$cartStmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ? FOR UPDATE");
$cartStmt->bind_param("ii", $user_id, $product_id);
$cartStmt->execute();
$existingCart = $cartStmt->get_result()->fetch_assoc();

$newQuantity = $quantity;
if ($existingCart) {
  $newQuantity += intval($existingCart["quantity"]);
}

if ($newQuantity > $product["stock"]) {
  $conn->rollback();
  setFlashMessage('warning', 'Jumlah melebihi stok yang tersedia.');
  header("Location: " . $_SERVER['HTTP_REFERER']);
  exit();
}

try {
  if ($existingCart) {
    $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $updateStmt->bind_param("iii", $newQuantity, $user_id, $product_id);
    $updateStmt->execute();
} else {
    $insertStmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insertStmt->bind_param("iii", $user_id, $product_id, $quantity);
    $insertStmt->execute();
}

  $conn->commit();
  setFlashMessage('success', 'Produk berhasil ditambahkan ke keranjang!');
  header("Location: ../pages/customer/shopping-cart.php");
  exit();
} catch (Exception $e) {
  $conn->rollback();
  setFlashMessage('danger', 'Terjadi kesalahan saat menambahkan keranjang.');
  header("Location: " . $_SERVER['HTTP_REFERER']);
  exit();
}
?>
