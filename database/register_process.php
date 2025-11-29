<?php
require_once __DIR__ . "/db_connect.php";
require_once __DIR__ . "/flash_message.php";

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // CSRF validation
  if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    setFlashMessage('danger', 'Token CSRF tidak valid. Silakan muat ulang halaman dan coba lagi.');
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
  }

  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $phone = trim($_POST["phone"]);
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  if ($password !== $confirm_password) {
    setFlashMessage('warning', 'Password dan konfirmasi password tidak cocok!');
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
  }

  $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    setFlashMessage('warning', 'Email sudah terdaftar! Silakan gunakan email lain.');
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
  }

  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $stmt = $conn->prepare(
    "INSERT INTO users (username, email, phone_number, password, role) VALUES (?, ?, ?, ?, 'customer')"
  );
  $stmt->bind_param("ssss", $username, $email, $phone, $hashed_password);

  if ($stmt->execute()) {
    setFlashMessage('success', 'Akun berhasil dibuat! Silakan login.');
    header("Location: ../pages/login.php");
  } else {
    setFlashMessage('danger', 'Gagal mendaftar! Coba lagi.');
    header("Location: " . $_SERVER['HTTP_REFERER']);
  }

  $stmt->close();
}
?>
