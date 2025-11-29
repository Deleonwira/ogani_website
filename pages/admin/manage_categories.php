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

$pageTitle = "Category Architecture";
$pageDescription = "Curate collection groupings and imagery that guide customer discovery.";
$pageActions = [
  [
    "label" => "Add Category",
    "href" => "add_category.php",
    "icon" => "bx bx-plus",
    "variant" => "primary",
  ],
];
$categories = $conn->query("SELECT * FROM categories ORDER BY category_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Manage Categories | Ogani Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/admin.css" />
  </head>
  <body>
    <?php require_once "../includes/sidebar.php"; ?>

    <div class="content">
      <?php include_once "../includes/admin_topbar.php"; ?>

      <section class="panel" data-animate-stagger>
        <div class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Visual</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $categories->fetch_assoc()): ?>
                <tr>
                  <td><?= $row["category_id"] ?></td>
                  <td><?= htmlspecialchars($row["category_name"]) ?></td>
                  <td>
                    <?php if (!empty($row["image"])): ?>
                      <img
                        src="<?= htmlspecialchars($row["image"]) ?>"
                        alt="Category visual"
                        width="60"
                        height="60"
                        style="object-fit: cover; border-radius: 12px"
                      />
                    <?php else: ?>
                      <span class="text-muted">No image</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="d-flex gap-2">
                      <a href="edit_category.php?id=<?= $row[
                        "category_id"
                      ] ?>" class="btn-action view btn-sm">Edit</a>
                      <form method="POST" action="delete_category.php" style="display:inline">
                        <input type="hidden" name="category_id" value="<?= intval($row['category_id']) ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this category?')">Delete</button>
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
