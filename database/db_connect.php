<?php
$host = "mysql";       // NAMA SERVICE DI DOCKER COMPOSE
$user = "root";
$pass = "password";    // password yang kamu set di compose
$db   = "myapp";   // nama database
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
