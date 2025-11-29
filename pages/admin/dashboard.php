<?php
session_start();
require_once "../../database/db_connect.php";

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
  header("Location: ../login.php");
  exit();
}

$productCount = (int) $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()[
  "total"
];
$categoryCount = (int) $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()[
  "total"
];
$orderCount = (int) $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()["total"];

$totalRevenueRow = $conn
  ->query("SELECT COALESCE(SUM(total_price), 0) AS total FROM orders")
  ->fetch_assoc();
$totalRevenue = (float) $totalRevenueRow["total"];

$pendingOrdersRow = $conn
  ->query("SELECT COUNT(*) AS total FROM orders WHERE order_status IN ('pending','processing')")
  ->fetch_assoc();
$pendingOrders = (int) $pendingOrdersRow["total"];

$completedOrdersRow = $conn
  ->query("SELECT COUNT(*) AS total FROM orders WHERE order_status IN ('completed','shipped')")
  ->fetch_assoc();
$completedOrders = (int) $completedOrdersRow["total"];

$lowStockRow = $conn
  ->query("SELECT COUNT(*) AS total FROM products WHERE stock < 20")
  ->fetch_assoc();
$lowStockCount = (int) $lowStockRow["total"];

$recentOrders = $conn->query(
  "SELECT o.invoice_code, u.username, o.total_price, o.order_status, o.order_time 
   FROM orders o 
   LEFT JOIN users u ON u.user_id = o.user_id 
   ORDER BY o.order_time DESC 
   LIMIT 6",
);

$lowStockProducts = $conn->query(
  "SELECT product_name, stock FROM products ORDER BY stock ASC LIMIT 5",
);

$topCategories = $conn->query(
  "SELECT c.category_name, COUNT(p.product_id) AS total 
   FROM categories c 
   LEFT JOIN products p ON p.category_id = c.category_id 
   GROUP BY c.category_id 
   ORDER BY total DESC 
   LIMIT 4",
);

$pageTitle = "Executive Dashboard";
$pageDescription = "Track end-to-end commerce health, inventory, and fulfillment in real-time.";
$pageActions = [
  [
    "label" => "Add Product",
    "href" => "add_product.php",
    "icon" => "bx bx-plus",
    "variant" => "primary",
  ],
  [
    "label" => "Generate Report",
    "href" => "#",
    "icon" => "bx bx-file",
    "variant" => "ghost",
  ],
];

function formatCurrency(float $number): string
{
  return "Rp" . number_format($number, 0, ",", ".");
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard | Ogani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/admin.css" />
  </head>
  <body>
    <?php require_once "../includes/sidebar.php"; ?>

    <div class="content">
      <?php include "../includes/admin_topbar.php"; ?>

      <section class="metric-grid">
        <article class="metric-card" data-animate-stagger>
          <span>Gross Revenue</span>
          <strong><?= formatCurrency($totalRevenue) ?></strong>
          <p class="metric-trend"><i class="bx bx-trending-up"></i> +12.4% vs last month</p>
        </article>
        <article class="metric-card" data-animate-stagger>
          <span>Active Orders</span>
          <strong><?= $pendingOrders ?></strong>
          <p class="metric-trend" style="color: var(--warning)"><i class="bx bx-time"></i> In queue</p>
        </article>
        <article class="metric-card" data-animate-stagger>
          <span>Products Live</span>
          <strong><?= $productCount ?></strong>
          <p class="metric-trend"><i class="bx bx-package"></i> <?= $categoryCount ?> categories</p>
        </article>
        <article class="metric-card" data-animate-stagger>
          <span>Fulfilled Orders</span>
          <strong><?= $completedOrders ?></strong>
          <p class="metric-trend" style="color: var(--success)"><i class="bx bx-check-circle"></i> Completed</p>
        </article>
      </section>

      <section class="panel" data-animate-stagger>
        <div class="panel__header">
          <div>
            <h4>Recent Orders</h4>
            <p class="panel__description">Latest transactions flowing through Ogani</p>
          </div>
        </div>
        <div class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th>Invoice</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Timestamp</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($order = $recentOrders->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($order["invoice_code"]) ?></td>
                  <td><?= htmlspecialchars($order["username"] ?? "Guest") ?></td>
                  <td><?= formatCurrency((float) $order["total_price"]) ?></td>
                  <td>
                    <span class="status-badge <?= strtolower($order["order_status"]) ?>">
                      <?= htmlspecialchars($order["order_status"]) ?>
                    </span>
                  </td>
                  <td><?= date("d M Y H:i", strtotime($order["order_time"])) ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </section>

      <section class="panel panel--split" data-animate-stagger>
        <div>
          <div class="panel__header">
            <div>
              <h4>Inventory Health</h4>
              <p class="panel__description">Products nearing replenishment threshold</p>
            </div>
            <span class="status-badge <?= $lowStockCount > 0 ? "pending" : "completed" ?>">
              <?= $lowStockCount ?> items
            </span>
          </div>
          <ul class="insight-list">
            <?php while ($lowStock = $lowStockProducts->fetch_assoc()): ?>
              <li>
                <div class="insight-label">
                  <strong><?= htmlspecialchars($lowStock["product_name"]) ?></strong>
                  <span>Critical threshold: 20 pcs</span>
                </div>
                <div class="insight-value"><?= (int) $lowStock["stock"] ?> pcs</div>
              </li>
            <?php endwhile; ?>
            <?php if ($lowStockCount === 0): ?>
              <li><span class="insight-value">Inventory looks healthy 🎉</span></li>
            <?php endif; ?>
          </ul>
        </div>

        <div>
          <div class="panel__header">
            <div>
              <h4>Category Momentum</h4>
              <p class="panel__description">Top performing categories by product volume</p>
            </div>
          </div>
          <ul class="insight-list">
            <?php while ($category = $topCategories->fetch_assoc()): ?>
              <li>
                <div class="insight-label">
                  <strong><?= htmlspecialchars($category["category_name"]) ?></strong>
                  <span>Catalog depth</span>
                </div>
                <div class="insight-value"><?= (int) $category["total"] ?> SKUs</div>
              </li>
            <?php endwhile; ?>
          </ul>
        </div>
      </section>
    </div>

    <script src="../../assets/js/admin.js"></script>
  </body>
</html>
