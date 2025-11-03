<?php
require_once '../database/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan konfirmasi password tidak cocok!'); window.history.back();</script>";
        exit;
    }

    
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar! Silakan gunakan email lain.'); window.history.back();</script>";
        exit;
    }

   
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

   
    $stmt = $conn->prepare("INSERT INTO users (username, email, phone_number, password, role) VALUES (?, ?, ?, ?, 'customer')");
    $stmt->bind_param("ssss", $username, $email, $phone, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Akun berhasil dibuat! Silakan login.'); window.location.href='../pages/login.php';</script>";
    } else {
        echo "<script>alert('Gagal mendaftar! Coba lagi.'); window.history.back();</script>";
    }

    $stmt->close();
}
?>
