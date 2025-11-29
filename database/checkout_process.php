<?php
require_once __DIR__ . "/db_connect.php";
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION["user_id"])) {
  header("Location: ../../pages/login.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../pages/customer/checkout.php");
  exit();
}

// CSRF validation
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
  echo "<script>alert('Token CSRF tidak valid. Silakan muat ulang halaman dan coba lagi.'); window.history.back();</script>";
  exit();
}

$user_id = intval($_SESSION["user_id"]);
$receiver_name = trim($_POST["receiver_name"] ?? "");
$receiver_phone = trim($_POST["receiver_phone"] ?? "");
$shipping_address = trim($_POST["shipping_address"] ?? "");

if ($receiver_name === "" || $receiver_phone === "" || $shipping_address === "") {
  echo "<script>alert('Harap lengkapi data pengiriman.'); window.history.back();</script>";
  exit();
}

$invoice_code = "INV-" . strtoupper(uniqid());

$sql_cart = "SELECT c.product_id, c.quantity, p.price, p.stock
             FROM cart c
             JOIN products p ON c.product_id = p.product_id
             WHERE c.user_id = ?
             FOR UPDATE";
$stmt_cart = $conn->prepare($sql_cart);
$stmt_cart->bind_param("i", $user_id);

$conn->begin_transaction();

$stmt_cart->execute();
$result_cart = $stmt_cart->get_result();

if ($result_cart->num_rows === 0) {
  $conn->rollback();
  echo "<script>alert('Keranjang masih kosong.'); window.location.href='../pages/customer/shopping-cart.php';</script>";
  exit();
}

$cartItems = [];
$calculated_total = 0;

while ($row = $result_cart->fetch_assoc()) {
  if ($row["quantity"] > $row["stock"]) {
    $conn->rollback();
    echo "<script>alert('Stok produk tidak mencukupi untuk " .
      htmlspecialchars($row["product_id"]) .
      ".'); window.location.href='../pages/customer/shopping-cart.php';</script>";
    exit();
  }

  $row["subtotal"] = $row["price"] * $row["quantity"];
  $calculated_total += $row["subtotal"];
  $cartItems[] = $row;
}

$sql_order = "INSERT INTO orders (invoice_code, user_id, total_price, order_status, order_time, receiver_name, receiver_phone, shipping_address)
              VALUES (?, ?, ?, 'pending', NOW(), ?, ?, ?)";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param(
  "sidsss",
  $invoice_code,
  $user_id,
  $calculated_total,
  $receiver_name,
  $receiver_phone,
  $shipping_address
);

try {
$stmt_order->execute();
$order_id = $stmt_order->insert_id;

$sql_detail = "INSERT INTO order_details (order_id, product_id, quantity, price_at_order, subtotal)
               VALUES (?, ?, ?, ?, ?)";
$stmt_detail = $conn->prepare($sql_detail);

  $stmt_update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");

  foreach ($cartItems as $item) {
    $stmt_detail->bind_param(
    "iiidd",
    $order_id,
      $item["product_id"],
      $item["quantity"],
      $item["price"],
      $item["subtotal"]
  );
  $stmt_detail->execute();

    $stmt_update_stock->bind_param("ii", $item["quantity"], $item["product_id"]);
    $stmt_update_stock->execute();
}

  $stmt_clear_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
  $stmt_clear_cart->bind_param("i", $user_id);
  $stmt_clear_cart->execute();

  $conn->commit();
echo "<script>alert('Pesanan berhasil dibuat!'); window.location.href='../pages/customer/order-detail.php?id=$order_id';</script>";
  exit();
} catch (Exception $e) {
  $conn->rollback();
  error_log("Checkout error: " . $e->getMessage());
  echo "<script>alert('Terjadi kesalahan saat memproses pesanan.'); window.location.href='../pages/customer/shopping-cart.php';</script>";
  exit();
}
