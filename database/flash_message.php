<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Set a flash message
 * @param string $type 'success', 'danger', 'warning', 'info'
 * @param string $message The message content
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Display the flash message if it exists, then clear it.
 * Uses Bootstrap alert classes.
 */
function displayFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $type = $_SESSION['flash']['type'];
        $message = $_SESSION['flash']['message'];
        
        // Map 'error' to 'danger' for Bootstrap compatibility if needed
        if ($type === 'error') $type = 'danger';

        echo '<div class="alert alert-' . htmlspecialchars($type) . ' alert-dismissible fade show" role="alert">
                ' . htmlspecialchars($message) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        
        unset($_SESSION['flash']);
    }
}
?>
