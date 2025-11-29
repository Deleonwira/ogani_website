<?php
session_start();
require_once "../../database/db_connect.php";
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
  header("Location: ../login.php");
  exit();
}

if (!isset($_GET["id"])) {
  die("Product ID not provided.");
}

$id = (int) $_GET["id"];
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
  die("Product not found!");
}

$message = "";

// Ensure CSRF token exists for admin forms
if (!isset($_SESSION['csrf_token'])) {
  try {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  } catch (Exception $e) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
  }
}

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

    $update = $conn->prepare(
      "UPDATE products 
       SET category_id = ?, product_name = ?, price = ?, stock = ?, description = ?, product_image = ?
       WHERE product_id = ?"
    );
    $update->bind_param(
      "isdissi",
      $category_id,
      $name,
      $price,
      $stock,
      $description,
      $image_url,
      $id
    );

    if ($update->execute()) {
      $message = "<div class='alert success'>Product updated successfully!</div>";
      // refresh product data
      $stmt->execute();
      $product = $stmt->get_result()->fetch_assoc();
    } else {
      $message = "<div class='alert error'>Failed to update product.</div>";
    }
  }
}

$categories = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");
$pageTitle = "Edit Product";
$pageDescription = "Adjust product details, assets, and availability.";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Edit Product</title>
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
              <input id="name" type="text" name="name" value="<?= htmlspecialchars(
                $product["product_name"]
              ) ?>" required />
            </div>
            <div>
              <label for="category_id">Category</label>
              <select id="category_id" name="category_id" required>
                <?php while ($row = $categories->fetch_assoc()):
                  $selected = $row["category_id"] == $product["category_id"] ? "selected" : ""; ?>
                  <option value="<?= $row["category_id"] ?>" <?= $selected ?>>
                    <?= htmlspecialchars($row["category_name"]) ?>
                  </option>
                <?php
                endwhile; ?>
              </select>
            </div>
            <div>
              <label for="price">Price</label>
              <input id="price" type="number" name="price" step="0.01" value="<?= $product[
                "price"
              ] ?>" required />
            </div>
            <div>
              <label for="stock">Stock</label>
              <input id="stock" type="number" name="stock" value="<?= $product["stock"] ?>" required />
            </div>
          </div>

          <label for="description">Description</label>
          <textarea id="description" name="description" rows="4"><?= htmlspecialchars(
            $product["description"]
          ) ?></textarea>

          <label for="image_url">Product Image (Link URL)</label>
          <input id="image_url" type="url" name="image_url" value="<?= htmlspecialchars(
            $product["product_image"]
          ) ?>" required />

          <div class="form-actions">
            <button type="submit" class="btn btn-add">Update Product</button>
            <a href="manage_products.php" class="btn btn-cancel">Cancel</a>
          </div>
        </form>
      </section>
    </div>

    <script src="../../assets/js/admin.js"></script>
  </body>
</html>