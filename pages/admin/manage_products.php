<?php
require_once "../../database/db_connect.php";
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
  header("Location: ../login.php");
  exit();
}

// Ensure CSRF token exists for admin forms
if (!isset($_SESSION['csrf_token'])) {
  try {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  } catch (Exception $e) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
  }
}

$pageTitle = "Product Catalogue";
$pageDescription = "Manage assortment, pricing, and availability across every category.";
$pageActions = [
  [
    "label" => "Add Product",
    "href" => "add_product.php",
    "icon" => "bx bx-plus",
    "variant" => "primary",
  ],
];

$products = $conn->query(
  "SELECT p.*, c.category_name 
   FROM products p 
   JOIN categories c ON p.category_id = c.category_id 
   ORDER BY p.product_id DESC",
);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Manage Products | Ogani Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/admin.css" />
  </head>
  <body>
    <?php require_once "../includes/sidebar.php"; ?>

    <div class="content">
      <?php include_once "../includes/admin_topbar.php"; ?>

      <section class="panel" data-animate-stagger>
        <div class="panel__header">
          <div>
            <h4>Catalogue Overview</h4>
            <p class="panel__description">Full visibility into SKU performance</p>
          </div>
        </div>
        <div class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Image</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                  <td><?= $row["product_id"] ?></td>
                  <td><?= htmlspecialchars($row["product_name"]) ?></td>
                  <td><?= htmlspecialchars($row["category_name"]) ?></td>
                  <td><?= "Rp" . number_format($row["price"], 0, ",", ".") ?></td>
                  <td>
                    <?= $row["stock"] ?>
                    <?= $row["stock"] < 20
                      ? "<span class='status-badge pending ms-2'>Low</span>"
                      : "" ?>
                  </td>
                  <td>
                    <img
                      src="<?= htmlspecialchars($row["product_image"]) ?>"
                      width="52"
                      height="52"
                      style="object-fit: cover; border-radius: 12px"
                      alt="Product image"
                    />
                  </td>
                  <td>
                    <div class="d-flex gap-2">
                      <a href="edit_product.php?id=<?= $row[
                        "product_id"
                      ] ?>" class="btn-action view btn-sm">Edit</a>
                      <form method="POST" action="delete_product.php" style="display:inline">
                        <input type="hidden" name="product_id" value="<?= intval($row['product_id']) ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>

    <script src="../../assets/js/admin.js"></script>
  </body>
</html>
