<?php
require_once __DIR__ . "/db_connect.php";
require_once __DIR__ . "/flash_message.php";

if (session_status() === PHP_SESSION_NONE) {
session_start();
}

// Detect which login page the request came from
$isAdminLogin = isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/admin/login.php') !== false;
$loginPage = $isAdminLogin ? '../pages/admin/login.php' : '../pages/login.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: $loginPage");
  exit();
}

// CSRF validation
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
  setFlashMessage('Invalid CSRF token. Please reload the page and try again.', 'danger');
  header("Location: $loginPage");
  exit();
}

$username = trim($_POST["username"] ?? "");
$password = trim($_POST["password"] ?? "");

if ($username === "" || $password === "") {
  setFlashMessage('Username and password are required.', 'warning');
  header("Location: $loginPage");
  exit();
}

  $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $username);
  $stmt->execute();
  $result = $stmt->get_result();

if ($result->num_rows !== 1) {
  setFlashMessage('Account not found!', 'danger');
  header("Location: $loginPage");
  exit();
}

    $user = $result->fetch_assoc();

// Support both hashed and legacy plaintext passwords, with automatic upgrade
$storedPassword = (string) $user["password"];
$isValid = false;
$needsRehash = false;

// 1) Try as hashed password first
if (password_verify($password, $storedPassword)) {
  $isValid = true;
  $needsRehash = password_needs_rehash($storedPassword, PASSWORD_DEFAULT);
} elseif (hash_equals($storedPassword, $password)) {
  // 2) Fallback: legacy plaintext stored in DB
  $isValid = true;
  // always upgrade legacy password to hash
  $needsRehash = true;
}

if (!$isValid) {
  setFlashMessage('Incorrect password!', 'danger');
  header("Location: $loginPage");
  exit();
}

if ($needsRehash) {
  $newHash = password_hash($password, PASSWORD_DEFAULT);
  $update = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
  $update->bind_param("si", $newHash, $user["user_id"]);
  $update->execute();
}

// Validate role matches login page
$userRole = $user["role"];

// Admin trying to login from customer page
if ($userRole === "admin" && !$isAdminLogin) {
  // Use URL parameter as fallback since session might not persist
  header("Location: $loginPage?warning=admin_only");
  exit();
}

// Customer trying to login from admin page
if ($userRole !== "admin" && $isAdminLogin) {
  header("Location: $loginPage?error=customer_blocked");
  exit();
}

// Regenerate session id to prevent session fixation
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
session_regenerate_id(true);

      $_SESSION["user_id"] = $user["user_id"];
      $_SESSION["username"] = $user["username"];
      $_SESSION["role"] = $user["role"];

      if ($user["role"] === "admin") {
        header("Location: ../pages/admin/dashboard.php");
      exit();
}

header("Location: ../pages/customer/home.php");
exit();
?>
