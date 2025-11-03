<?php
session_start();
require_once './db_connect.php';

if (isset($_GET['cart_id']) && isset($_SESSION['user_id'])) {
    $cart_id = $_GET['cart_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM cart WHERE cart_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $cart_id, $user_id);
    $stmt->execute();
}

header('Location: ../pages/customer/shoping-cart.php');
exit;
?>
