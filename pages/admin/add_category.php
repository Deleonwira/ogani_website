<?php
session_start();
require_once "../../database/db_connect.php";
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
  header("Location: ../login.php");
  exit();
}

$message = "";
// Ensure CSRF token exists
if (!isset($_SESSION['csrf_token'])) {
  try {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  } catch (Exception $e) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
  }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $message = "<div class='alert error'>Token CSRF tidak valid.</div>";
  } else {
    $category_name = trim($_POST["category_name"]);
    $image = trim($_POST["image"]);

    if ($category_name === "" || $image === "") {
      $message = "<div class='alert error'>Please fill all fields.</div>";
    } else {
      $stmt = $conn->prepare("INSERT INTO categories (category_name, image) VALUES (?, ?)");
      $stmt->bind_param("ss", $category_name, $image);
      if ($stmt->execute()) {
        $message = "<div class='alert success'>Category added successfully!</div>";
      } else {
        $message = "<div class='alert error'>Failed to add category.</div>";
      }
    }
  }
}

$pageTitle = "Add Category";
$pageDescription = "Create a new merchandising lane with inspiring imagery.";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Add Category</title>
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
          <label for="category_name">Category Name</label>
          <input id="category_name" type="text" name="category_name" placeholder="e.g., Superfoods" required />

          <label for="image">Image URL</label>
          <input id="image" type="url" name="image" placeholder="https://example.com/image.jpg" required />

          <div class="form-actions">
            <button type="submit" class="btn btn-add">Save Category</button>
            <a href="manage_categories.php" class="btn btn-cancel">Cancel</a>
          </div>
        </form>
      </section>
    </div>

    <script src="../../assets/js/admin.js"></script>
  </body>
</html>
