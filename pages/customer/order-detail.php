<?php
session_start();
require_once "../../database/db_connect.php";

if (!isset($_SESSION["user_id"])) {
  header("Location: ../login.php");
  exit();
}

$order_id = 0;
if (!isset($_GET["id"])) {
    header("Location: order_history.php");
    exit();
}

$order_id = intval($_GET["id"]);
$user_id = $_SESSION["user_id"];

$sql = "SELECT o.*, u.username 
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.user_id
        WHERE o.order_id = ? AND o.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
  echo "<p style='color:red; text-align:center;'>Order tidak ditemukan atau bukan milik Anda.</p>";
  exit();
}

$sql_items = "SELECT p.product_name, p.product_image, od.quantity, od.price_at_order
              FROM order_details od
              JOIN products p ON od.product_id = p.product_id
              WHERE od.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?= htmlspecialchars($order["invoice_code"]) ?> | Ogani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .order-detail-container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            padding: 25px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 6px;
            font-weight: 600;
            text-transform: capitalize;
        }
        .status-badge.pending { background: #fff3cd; color: #856404; }
        .status-badge.processing { background: #d1ecf1; color: #0c5460; }
        .status-badge.shipped { background: #b8daff; color: #004085; }
        .status-badge.delivered { background: #c3e6cb; color: #155724; }
        .status-badge.cancelled { background: #f5c6cb; color: #721c24; }
        .product-img { width: 70px; border-radius: 6px; }
        .back-btn { text-decoration: none; color: #0d6efd; }
    </style>
</head>
<body>

<div class="order-detail-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Detail Pesanan</h3>
        <a href="order-history.php" class="back-btn"><i class='bx bx-arrow-back'></i> Kembali</a>
    </div>

    <div class="mb-3">
        <h5>Kode Invoice: <strong><?= htmlspecialchars($order["invoice_code"]) ?></strong></h5>
        <p class="mb-1">Tanggal Pemesanan: <?= date(
          "d M Y H:i",
          strtotime($order["order_time"]),
        ) ?></p>
        <p class="mb-1">Status:
            <span class="status-badge <?= strtolower($order["order_status"]) ?>">
                <?= htmlspecialchars($order["order_status"]) ?>
            </span>
        </p>
        <p class="mb-1">Nama Penerima: <?= htmlspecialchars($order["receiver_name"]) ?></p>
        <p class="mb-1">Telepon: <?= htmlspecialchars($order["receiver_phone"]) ?></p>
        <p>Alamat Pengiriman: <?= htmlspecialchars($order["shipping_address"]) ?></p>
    </div>

    <hr>

    <h5 class="mb-3">Produk yang Dipesan</h5>
    <table class="table table-bordered align-middle">
        <thead class="table-success">
            <tr>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Kuantitas</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $total = 0;
        while ($item = $items->fetch_assoc()):

          $subtotal = $item["price_at_order"] * $item["quantity"];
          $total += $subtotal;
          ?>
            <tr>
                <td><img src="<?= htmlspecialchars(
                  $item["product_image"],
                ) ?>" class="product-img"></td>
                <td><?= htmlspecialchars($item["product_name"]) ?></td>
                <td><?= $item["quantity"] ?></td>
                <td>Rp<?= number_format($item["price_at_order"], 0, ",", ".") ?></td>
                <td>Rp<?= number_format($subtotal, 0, ",", ".") ?></td>
            </tr>
        <?php
        endwhile;
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Total:</th>
                <th>Rp<?= number_format($total, 0, ",", ".") ?></th>
            </tr>
        </tfoot>
    </table>
</div>

</body>
</html>
