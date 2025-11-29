<?php
session_start();
session_unset(); // Hapus semua variabel session
session_destroy(); // Hapus session sepenuhnya
header("Location: ./login.php"); // Arahkan kembali ke halaman login
exit();
?>
