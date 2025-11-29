<?php
session_start();

// Destroy all session data
$_SESSION = [];

// Delete the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Redirect to appropriate login page
// Check if user was admin based on referrer or default to customer login
$isAdmin = isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/admin/') !== false;

if ($isAdmin) {
    header('Location: ../pages/admin/login.php');
} else {
    header('Location: ../pages/login.php');
}
exit();
