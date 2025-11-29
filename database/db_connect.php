<?php
// Read database credentials from environment variables with safe fallbacks.
$host = getenv('DB_HOST') ?: 'mysql';
$user = getenv('DB_USER') ?: 'appuser';
$pass = getenv('DB_PASSWORD') ?: 'password';
$db   = getenv('DB_NAME') ?: 'ogani_app';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Intentionally omit closing PHP tag to avoid accidental output/newline issues.
