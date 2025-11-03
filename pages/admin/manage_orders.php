<?php
require_once '../../database/db_connect.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders | Ogani Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
<?php require_once '../includes/sidebar.php'; ?>

<div class="content">
    <h3>Manage Orders</h3>

    <?php
    $sql = "SELECT o.order_id, o.invoice_code, u.username, o.total_price, 
                   o.order_status, o.order_time, 
                   o.receiver_name, o.receiver_phone, o.shipping_address
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.user_id
            ORDER BY o.order_time DESC";
    $result = $conn->query($sql);
    ?>

    <table class="admin-table table table-bordered">
        <thead class="table-success">
            <tr>
                <th>ID</th>
                <th>Invoice</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Receiver</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['order_id']; ?></td>
                <td><?= htmlspecialchars($row['invoice_code']); ?></td>
                <td><?= htmlspecialchars($row['username'] ?? 'Unknown'); ?></td>
                <td>Rp<?= number_format($row['total_price'], 0, ',', '.'); ?></td>
                <td>
                    <span class="badge bg-<?= 
                        $row['order_status'] === 'Delivered' ? 'success' :
                        ($row['order_status'] === 'Pending' ? 'secondary' :
                        ($row['order_status'] === 'Cancelled' ? 'danger' :
                        ($row['order_status'] === 'Shipped' ? 'info' : 'warning')))
                    ?>">
                        <?= htmlspecialchars($row['order_status']); ?>
                    </span>
                </td>
                <td><?= date('d M Y H:i', strtotime($row['order_time'])); ?></td>
                <td><?= htmlspecialchars($row['receiver_name']); ?></td>
                <td><?= htmlspecialchars($row['receiver_phone']); ?></td>
                <td><?= htmlspecialchars($row['shipping_address']); ?></td>
                <td>
                    <button class="btn btn-sm btn-warning" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editModal<?= $row['order_id']; ?>">
                        <i class="bx bx-edit"></i> Edit
                    </button>
                </td>
            </tr>

            <!-- MODAL -->
            <div class="modal fade" id="editModal<?= $row['order_id']; ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form method="POST" action="update_status.php">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Order Status (Invoice: <?= htmlspecialchars($row['invoice_code']); ?>)</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="order_id" value="<?= $row['order_id']; ?>">
                      <div class="mb-3">
                        <label for="statusSelect<?= $row['order_id']; ?>" class="form-label">Order Status</label>
                        <select name="order_status" id="statusSelect<?= $row['order_id']; ?>" class="form-select" required>
                          <?php
                          $statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
                          foreach ($statuses as $status) {
                              $selected = ($row['order_status'] === $status) ? 'selected' : '';
                              echo "<option value='$status' $selected>$status</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
