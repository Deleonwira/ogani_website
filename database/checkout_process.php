<?php
require_once './db_connect.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../pages/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$receiver_name = $_POST['receiver_name'];
$receiver_phone = $_POST['receiver_phone'];
$shipping_address = $_POST['shipping_address'];
$total_price = $_POST['total_price'];
$order_notes = $_POST['order_notes'] ?? '';

$invoice_code = 'INV-' . strtoupper(uniqid());


$sql_order = "INSERT INTO orders (invoice_code, user_id, total_price, order_status, order_time, receiver_name, receiver_phone, shipping_address)
              VALUES (?, ?, ?, 'pending', NOW(), ?, ?, ?)";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("sidsss", $invoice_code, $user_id, $total_price, $receiver_name, $receiver_phone, $shipping_address);
$stmt_order->execute();
$order_id = $stmt_order->insert_id;


$sql_cart = "SELECT c.product_id, c.quantity, p.price 
             FROM cart c
             JOIN products p ON c.product_id = p.product_id
             WHERE c.user_id = ?";
$stmt_cart = $conn->prepare($sql_cart);
$stmt_cart->bind_param("i", $user_id);
$stmt_cart->execute();
$result_cart = $stmt_cart->get_result();


$sql_detail = "INSERT INTO order_details (order_id, product_id, quantity, price_at_order, subtotal)
               VALUES (?, ?, ?, ?, ?)";
$stmt_detail = $conn->prepare($sql_detail);

while ($row = $result_cart->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $stmt_detail->bind_param("iiidd", $order_id, $row['product_id'], $row['quantity'], $row['price'], $subtotal);
    $stmt_detail->execute();

    
    $conn->query("UPDATE products SET stock = stock - {$row['quantity']} WHERE product_id = {$row['product_id']}");
}


$conn->query("DELETE FROM cart WHERE user_id = $user_id");

echo "<script>alert('Pesanan berhasil dibuat!'); window.location.href='../pages/customer/order-detail.php?id=$order_id';</script>";
