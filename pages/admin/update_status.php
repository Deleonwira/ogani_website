<?php
require_once '../../database/db_connect.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $order_status = $_POST['order_status'];

    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $order_status, $order_id);

    if ($stmt->execute()) {
        header("Location: manage_orders.php?msg=updated");
    } else {
        header("Location: manage_orders.php?error=db");
    }
    exit;
}
?>
