<?php
session_start();
require_once "../../database/db_connect.php";

if (!isset($_SESSION["user_id"])) {
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION["user_id"];

$sql2 = "SELECT order_id, invoice_code, total_price, order_status, order_time 
        FROM orders 
        WHERE user_id = ?
        ORDER BY order_time DESC";
$stmt = $conn->prepare($sql2);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result2 = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan | Ogani</title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../../assets/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../../assets/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="../../assets/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="../../assets/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="../../assets/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="../../assets/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="../../assets/css/style.css" type="text/css">
    <style>
        
        .order-container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            padding: 25px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .status-badge {
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-badge.pending { background-color: #ffeeba; color: #856404; }
        .status-badge.processing { background-color: #bee5eb; color: #0c5460; }
        .status-badge.shipped { background-color: #b8daff; color: #004085; }
        .status-badge.delivered { background-color: #c3e6cb; color: #155724; }
        .status-badge.cancelled { background-color: #f5c6cb; color: #721c24; }
        .btn-detail {
            background-color: #7fad39;
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn-detail:hover {
            background-color: #7fad39;
        }
    </style>
</head>
<body>
<?php include "../includes/header.php"; ?>
<div class="order-container">
    <div class="order-header">
        <h3 class="fw-semibold">Riwayat Pesanan</h3>
        <a href="./home.php" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    <?php if ($result2->num_rows > 0): ?>
        <table class="table table-bordered align-middle">
            <thead style="background-color: #7fad39;">
                <tr>
                    <th style="color: white;">Kode Invoice</th>
                    <th style="color: white;">Tanggal</th>
                    <th style="color: white;">Total Harga</th>
                    <th style="color: white;">Status</th>
                    <th style="color: white;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result2->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row["invoice_code"]) ?></td>
                    <td><?= date("d M Y H:i", strtotime($row["order_time"])) ?></td>
                    <td>Rp<?= number_format($row["total_price"], 0, ",", ".") ?></td>
                    <td>
                        <span class="status-badge <?= strtolower($row["order_status"]) ?>">
                            <?= htmlspecialchars($row["order_status"]) ?>
                        </span>
                    </td>
                    <td>
                        <a href="order-detail.php?id=<?= $row["order_id"] ?>" class="btn-detail">
                            <i class='bx bx-show'></i> Detail
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-muted">Belum ada riwayat pesanan.</p>
    <?php endif; ?>
</div>

</body>
</html>
