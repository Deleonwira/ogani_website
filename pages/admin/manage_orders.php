<?php
require_once "../../database/db_connect.php";
session_start();
// Ensure CSRF token exists for admin forms
if (!isset($_SESSION['csrf_token'])) {
  try {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  } catch (Exception $e) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
  }
}
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
  header("Location: ../login.php");
  exit();
}

$pageTitle = "Order Control Tower";
$pageDescription =
  "Oversee every fulfillment touchpoint, update statuses, and keep customers informed.";
$pageActions = [
  [
    "label" => "Export CSV",
    "href" => "#",
    "icon" => "bx bx-download",
    "variant" => "ghost",
  ],
];

$orders = $conn->query(
  "SELECT o.order_id, o.invoice_code, u.username, o.total_price, 
          o.order_status, o.order_time, 
          o.receiver_name, o.receiver_phone, o.shipping_address
   FROM orders o
   LEFT JOIN users u ON o.user_id = u.user_id
   ORDER BY o.order_time DESC",
);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Manage Orders | Ogani Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/admin.css" />
  </head>
  <body>
    <?php 
    require_once "../../database/flash_message.php";
    require_once "../includes/sidebar.php"; 
    ?>

    <div class="content">
      <?php include_once "../includes/admin_topbar.php"; ?>
      
      <div class="container-fluid mt-3">
        <?php displayFlashMessage(); ?>
      </div>

      <section class="panel" data-animate-stagger>
        <div class="table-wrapper">
          <table class="data-table">
            <thead>
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
              <?php 
              // First pass: render table rows and collect order data
              $ordersData = [];
              while ($row = $orders->fetch_assoc()): 
                $ordersData[] = $row; // Store for modal rendering later
              ?>
                <tr>
                  <td><?= $row["order_id"] ?></td>
                  <td><?= htmlspecialchars($row["invoice_code"]) ?></td>
                  <td><?= htmlspecialchars($row["username"] ?? "Unknown") ?></td>
                  <td><?= "Rp" . number_format($row["total_price"], 0, ",", ".") ?></td>
                  <td>
                    <span class="status-badge <?= strtolower($row["order_status"]) ?>">
                      <?= htmlspecialchars($row["order_status"]) ?>
                    </span>
                  </td>
                  <td><?= date("d M Y H:i", strtotime($row["order_time"])) ?></td>
                  <td><?= htmlspecialchars($row["receiver_name"]) ?></td>
                  <td><?= htmlspecialchars($row["receiver_phone"]) ?></td>
                  <td><?= htmlspecialchars($row["shipping_address"]) ?></td>
                  <td>
                    <button
                      class="btn btn-sm btn-warning"
                      onclick="openModal('editModal<?= $row['order_id'] ?>')"
                      type="button"
                    >
                      <i class="bx bx-edit"></i> Edit
                    </button>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>

    <!-- Modals Container - Outside scrollable content -->
    <?php foreach ($ordersData as $row): ?>
      <!-- Custom Admin Modal -->
      <div class="admin-modal" id="editModal<?= $row["order_id"] ?>" style="display: none;">
        <div class="admin-modal__overlay"></div>
        <div class="admin-modal__content">
          <form method="POST" action="update_status.php">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <input type="hidden" name="order_id" value="<?= $row["order_id"] ?>" />
            
            <div class="admin-modal__header">
              <h3>Update Order Status</h3>
              <p class="text-muted">Invoice: <?= htmlspecialchars($row["invoice_code"]) ?></p>
              <button type="button" class="admin-modal__close" onclick="closeModal('editModal<?= $row["order_id"] ?>')">
                <i class="bx bx-x"></i>
              </button>
            </div>
            
            <div class="admin-modal__body">
              <label for="statusSelect<?= $row["order_id"] ?>" class="form-label">Order Status</label>
              <select name="order_status" id="statusSelect<?= $row["order_id"] ?>" class="admin-select" required>
                <?php
                $statuses = ["Pending", "Processing", "Shipped", "Completed", "Cancelled"];
                foreach ($statuses as $status) {
                  $selected = $row["order_status"] === $status ? "selected" : "";
                  echo "<option value='$status' $selected>$status</option>";
                }
                ?>
              </select>
            </div>
            
            <div class="admin-modal__footer">
              <button type="button" class="btn-cancel" onclick="closeModal('editModal<?= $row["order_id"] ?>')">Cancel</button>
              <button type="submit" class="btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    <?php endforeach; ?>

    <style>
      .admin-modal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        z-index: 1000 !important;
      }
      
      .admin-modal__overlay {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(8px);
        animation: fadeIn 0.3s ease;
        z-index: 1000 !important;
      }
      
      .admin-modal__content {
        /* Use viewport positioning for true fixed centering */
        position: fixed !important;
        top: 50vh !important;
        left: 50vw !important;
        transform: translate(-50%, -50%) translateX(65px) !important;
        margin: 0 !important;
        
        background: rgba(14, 19, 35, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 24px;
        padding: 0;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.7);
        animation: slideUp 0.3s ease;
        overflow: auto;
        z-index: 1001;
      }
      
      .admin-modal__header {
        padding: 24px 28px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        position: relative;
      }
      
      .admin-modal__header h3 {
        margin: 0 0 8px 0;
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--text-light);
      }
      
      .admin-modal__header p {
        margin: 0;
        font-size: 0.9rem;
        color: var(--text-muted);
      }
      
      .admin-modal__close {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        background: rgba(255, 255, 255, 0.08);
        color: var(--text-light);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.2s ease;
      }
      
      .admin-modal__close:hover {
        background: rgba(255, 107, 107, 0.2);
        color: var(--danger);
      }
      
      .admin-modal__body {
        padding: 24px 28px;
      }
      
      .admin-modal__body label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        color: var(--text-light);
      }
      
      .admin-select {
        width: 100%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 12px 14px;
        color: var(--text-light);
        font-size: 1rem;
        transition: border 0.2s ease, box-shadow 0.2s ease;
      }
      
      .admin-select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(77, 208, 130, 0.2);
      }
      
      .admin-modal__footer {
        padding: 20px 28px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        gap: 12px;
        justify-content: flex-end;
      }
      
      @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
      }
      
      @keyframes slideUp {
        from {
          opacity: 0;
          transform: translateY(30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
    </style>

    <script>
      function openModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = document.querySelector('.content');
        if (modal) {
          // Force fixed positioning via inline style (highest priority)
          const modalContent = modal.querySelector('.admin-modal__content');
          if (modalContent) {
            modalContent.style.cssText = `
              position: fixed !important;
              top: 50vh !important;
              left: 50vw !important;
              transform: translate(-50%, -50%) translateX(65px) !important;
              z-index: 1001 !important;
            `;
          }
          
          modal.style.display = 'block';
          // Lock scroll on both body and content
          document.body.style.overflow = 'hidden';
          if (content) content.style.overflow = 'hidden';
        }
      }
      
      function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = document.querySelector('.content');
        if (modal) {
          modal.style.display = 'none';
          // Unlock scroll
          document.body.style.overflow = '';
          if (content) content.style.overflow = '';
        }
      }
      
      // Close modal when clicking overlay
      document.addEventListener('click', function(e) {
        if (e.target.classList.contains('admin-modal__overlay')) {
          const modal = e.target.closest('.admin-modal');
          const content = document.querySelector('.content');
          if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            if (content) content.style.overflow = '';
          }
        }
      });
      
      // Close modal on Escape key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          const content = document.querySelector('.content');
          const openModals = document.querySelectorAll('.admin-modal[style*="display: block"]');
          openModals.forEach(modal => {
            modal.style.display = 'none';
          });
          document.body.style.overflow = '';
          if (content) content.style.overflow = '';
        }
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/admin.js"></script>
  </body>
</html>
