<?php
session_start();
require_once "../../database/db_connect.php";
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

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // CSRF validation
  if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $message = "<div class='alert error'>Token CSRF tidak valid.</div>";
  } else {
    $name = trim($_POST["name"] ?? "");
    $category_id = intval($_POST["category_id"] ?? 0);
    $price = floatval($_POST["price"] ?? 0);
    $stock = intval($_POST["stock"] ?? 0);
    $description = trim($_POST["description"] ?? "");
    $image_url = trim($_POST["image_url"] ?? "");

    $stmt = $conn->prepare("INSERT INTO products (category_id, product_name, price, stock, description, product_image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdiss", $category_id, $name, $price, $stock, $description, $image_url);

    if ($stmt->execute()) {
      $message = "<div class='alert success'>Product added successfully!</div>";
    } else {
      $message = "<div class='alert error'>Failed to add product.</div>";
    }
  }
}

$categories = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");
$pageTitle = "New Product Blueprint";
$pageDescription = "Define attributes, imagery, and stock thresholds for a new SKU.";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/admin.css" />
  </head>
  <body>
    <?php require_once "../includes/sidebar.php"; ?>

    <div class="content">
      <?php include_once "../includes/admin_topbar.php"; ?>

      <?= $message ?>
      <section class="panel" data-animate-stagger>
        <form action="" method="POST" class="form-card">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
          <div class="form-grid">
            <div>
              <label for="name">Product Name</label>
              <input id="name" type="text" name="name" required />
            </div>
            <div>
              <label for="category_id">Category</label>
              <select id="category_id" name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php while ($row = $categories->fetch_assoc()): ?>
                  <option value="<?= $row["category_id"] ?>"><?= htmlspecialchars(
  $row["category_name"],
) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div>
              <label for="price">Price</label>
              <input id="price" type="number" name="price" step="0.01" required />
            </div>
            <div>
              <label for="stock">Stock</label>
              <input id="stock" type="number" name="stock" required />
            </div>
          </div>

          <label>Description</label>
          <textarea name="description" rows="4" placeholder="Tell shoppers why they'll love it"></textarea>

          <label>Product Image (Link URL)</label>
          <input type="url" name="image_url" placeholder="https://example.com/image.jpg" required />

          <div class="form-actions">
            <button type="submit" class="btn btn-add">Save Product</button>
            <a href="manage_products.php" class="btn btn-cancel">Cancel</a>
          </div>
        </form>
      </section>
    </div>

    <script src="../../assets/js/admin.js"></script>
  </body>
</html>
