<?php
require_once "../../database/db_connect.php";
session_start();

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
  header("Location: ../login.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  require_once "../../database/flash_message.php";
  
  // CSRF validation
  if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    setFlashMessage("Invalid security token. Please try again.", "danger");
    header("Location: manage_orders.php");
    exit();
  }

  $order_id = intval($_POST["order_id"]);
  $order_status = $_POST["order_status"];

  // Whitelist allowed statuses to prevent invalid values
  $allowed_statuses = ['Pending', 'Processing', 'Shipped', 'Completed', 'Cancelled'];
  if (!in_array($order_status, $allowed_statuses, true)) {
    setFlashMessage("Invalid order status selected.", "danger");
    header("Location: manage_orders.php");
    exit();
  }

  $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
  $stmt->bind_param("si", $order_status, $order_id);

  require_once "../../database/flash_message.php";
  
  if ($stmt->execute()) {
    setFlashMessage("Order status updated successfully!", "success");
    header("Location: manage_orders.php");
  } else {
    setFlashMessage("Failed to update order status. Please try again.", "danger");
    header("Location: manage_orders.php");
  }
  exit();
}
?>
