<?php
require_once "../../database/db_connect.php";
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
  header("Location: ../login.php");
  exit();
}

$pageTitle = "User Directory";
$pageDescription = "Manage marketplace operators and shoppers with precision access control.";
$users = $conn->query("SELECT * FROM users ORDER BY user_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Manage Users | Ogani Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/admin.css" />
  </head>
  <body>
    <?php require_once "../includes/sidebar.php"; ?>

    <div class="content">
      <?php include "../includes/admin_topbar.php"; ?>

      <section class="panel" data-animate-stagger>
        <div class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $users->fetch_assoc()): ?>
                <tr>
                  <td><?= $row["user_id"] ?></td>
                  <td><?= htmlspecialchars($row["username"]) ?></td>
                  <td><?= htmlspecialchars($row["email"]) ?></td>
                  <td>
                    <span class="status-badge <?= $row["role"] === "admin"
                      ? "info"
                      : "completed" ?>">
                      <?= ucfirst($row["role"]) ?>
                    </span>
                  </td>
                  <td>
                    <div class="d-flex gap-2">
                      <a href="edit_user.php?id=<?= $row[
                        "user_id"
                      ] ?>" class="btn-action view btn-sm">Edit</a>
                      <a
                        href="delete_user.php?id=<?= $row["user_id"] ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Delete this user?')"
                        >Delete</a
                      >
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
