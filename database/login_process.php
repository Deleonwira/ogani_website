<?php
    require_once './db_connect.php';
    session_start();


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

       
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                
                if ($user['role'] === 'admin') {
                    header("Location: ../pages/admin/dashboard.php");
                } else {
                    header("Location: ../pages/customer/home.php");
                }
                exit;
            } else {
                echo "<script>alert('Password salah!'); window.location.href='../pages/login.php';</script>";
            }
        } else {
            echo "<script>alert('Akun tidak ditemukan!'); window.location.href='../pages/login.php  ';</script>";
        }
    }
    ?>