<?php
session_start();

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
  try {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  } catch (Exception $e) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
  }
}

// If already logged in and is admin, redirect to dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
  header('Location: dashboard.php');
  exit();
}

// If already logged in but not admin, redirect to customer home
if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
  header('Location: ../customer/home.php');
  exit();
}

require_once "../../database/flash_message.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Ogani Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <style>
    :root {
      --primary: #7fad39;
      --primary-dark: #648c30;
      --accent: #4dd082;
      --bg-deep: #05070f;
      --text-light: #f8fbff;
      --text-muted: #9ea7c0;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: radial-gradient(circle at top, #1f2937 0%, #0b1120 45%, #05070f 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-light);
      position: relative;
      overflow: hidden;
    }

    body::before,
    body::after {
      content: "";
      position: fixed;
      width: 420px;
      height: 420px;
      border-radius: 50%;
      filter: blur(100px);
      z-index: 0;
      pointer-events: none;
    }

    body::before {
      top: -120px;
      right: -160px;
      background: radial-gradient(circle, rgba(127, 173, 57, 0.25), transparent 70%);
    }

    body::after {
      bottom: -160px;
      left: -120px;
      background: radial-gradient(circle, rgba(93, 173, 255, 0.15), transparent 70%);
    }

    .login-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 450px;
      padding: 20px;
    }

    .login-card {
      background: rgba(14, 19, 35, 0.85);
      backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 24px;
      padding: 48px 40px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.7);
      animation: slideUp 0.6s ease;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .logo {
      text-align: center;
      margin-bottom: 32px;
    }

    .logo h1 {
      font-size: 2rem;
      font-weight: 700;
      background: linear-gradient(120deg, var(--primary), var(--accent));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 8px;
    }

    .logo p {
      color: var(--text-muted);
      font-size: 0.9rem;
      letter-spacing: 0.1em;
      text-transform: uppercase;
    }

    .form-group {
      margin-bottom: 24px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      font-size: 0.9rem;
      color: var(--text-light);
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 1.2rem;
    }

    .form-control {
      width: 100%;
      padding: 14px 16px 14px 48px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 12px;
      color: var(--text-light);
      font-size: 0.95rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(77, 208, 130, 0.2);
      background: rgba(255, 255, 255, 0.08);
    }

    .form-control::placeholder {
      color: var(--text-muted);
    }

    .btn-login {
      width: 100%;
      padding: 14px;
      background: linear-gradient(120deg, var(--primary), var(--accent));
      border: none;
      border-radius: 12px;
      color: white;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 15px 30px rgba(127, 173, 57, 0.35);
      margin-top: 8px;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 20px 40px rgba(127, 173, 57, 0.45);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .divider {
      text-align: center;
      margin: 32px 0;
      position: relative;
    }

    .divider::before {
      content: "";
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 1px;
      background: rgba(255, 255, 255, 0.1);
    }

    .divider span {
      background: rgba(14, 19, 35, 0.85);
      padding: 0 16px;
      position: relative;
      color: var(--text-muted);
      font-size: 0.85rem;
    }

    .customer-link {
      text-align: center;
      margin-top: 24px;
    }

    .customer-link a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .customer-link a:hover {
      color: var(--primary);
      gap: 10px;
    }

    .alert {
      padding: 12px 16px;
      border-radius: 12px;
      margin-bottom: 24px;
      font-size: 0.9rem;
      animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-success {
      background: linear-gradient(120deg, rgba(39, 174, 96, 0.2), rgba(46, 204, 113, 0.2));
      border: 1px solid rgba(46, 204, 113, 0.4);
      color: #2ecc71;
    }

    .alert-danger {
      background: linear-gradient(120deg, rgba(235, 77, 75, 0.2), rgba(255, 99, 72, 0.2));
      border: 1px solid rgba(255, 99, 72, 0.4);
      color: #ff6348;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="logo">
        <h1>OGANI</h1>
        <p>Admin Console</p>
      </div>

      <form action="../../database/login_process.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        
        <div class="form-group">
          <label for="username">Email or Username</label>
          <div class="input-wrapper">
            <i class='bx bx-user'></i>
            <input 
              type="text" 
              id="username" 
              name="username" 
              class="form-control" 
              placeholder="admin@example.com"
              required
              autocomplete="username"
            >
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <i class='bx bx-lock-alt'></i>
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="form-control" 
              placeholder="Enter your password"
              required
              autocomplete="current-password"
            >
          </div>
        </div>

        <button type="submit" class="btn-login">
          Sign In to Dashboard
        </button>
      </form>

      <div class="divider">
        <span>or</span>
      </div>

      <div class="customer-link">
        <a href="../login.php">
          <i class='bx bx-arrow-back'></i>
          Customer Login
        </a>
      </div>
    </div>
  </div>

  <!-- Warning Modal -->
  <div id="warningModal" class="warning-modal" style="display: none;">
    <div class="warning-modal__overlay"></div>
    <div class="warning-modal__content">
      <div class="warning-modal__icon" id="modalIcon">
        <i class='bx bx-error'></i>
      </div>
      <h3 id="modalTitle">Warning</h3>
      <p id="modalMessage"></p>
      <button onclick="closeWarningModal()" class="modal-btn">Got it</button>
    </div>
  </div>

  <style>
    .warning-modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 10000;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .warning-modal__overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      backdrop-filter: blur(10px);
      animation: fadeIn 0.3s ease;
    }

    .warning-modal__content {
      position: relative;
      background: rgba(14, 19, 35, 0.95);
      border: 1px solid rgba(255, 99, 72, 0.3);
      border-radius: 24px;
      padding: 40px 36px;
      max-width: 450px;
      width: 90%;
      text-align: center;
      box-shadow: 0 25px 60px rgba(231, 76, 60, 0.5);
      animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      z-index: 10001;
    }

    .warning-modal__icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 24px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: pulse 2s ease-in-out infinite;
    }

    .warning-modal__icon.danger {
      background: linear-gradient(135deg, rgba(231, 76, 60, 0.25), rgba(192, 57, 43, 0.25));
      border: 2px solid rgba(231, 76, 60, 0.5);
      box-shadow: 0 0 30px rgba(231, 76, 60, 0.3);
    }

    @keyframes pulse {
      0%, 100% {
        box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.4);
      }
      50% {
        box-shadow: 0 0 0 15px rgba(231, 76, 60, 0);
      }
    }

    .warning-modal__icon i {
      font-size: 2.5rem;
      color: #e74c3c;
    }

    .warning-modal__content h3 {
      margin: 0 0 16px 0;
      font-size: 1.6rem;
      font-weight: 700;
      color: var(--text-light);
    }

    .warning-modal__content p {
      margin: 0 0 32px 0;
      font-size: 1rem;
      color: var(--text-muted);
      line-height: 1.6;
    }

    .modal-btn {
      width: 100%;
      padding: 14px 28px;
      background: linear-gradient(135deg, #e74c3c, #c0392b);
      border: 1px solid rgba(231, 76, 60, 0.5);
      border-radius: 12px;
      color: white;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
    }

    .modal-btn:hover {
      background: linear-gradient(135deg, #ff6b5a, #e74c3c);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(231, 76, 60, 0.5);
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }
  </style>

  <script>
    let modalOpen = false;

    function closeWarningModal() {
      document.getElementById('warningModal').style.display = 'none';
      document.body.style.overflow = '';
      modalOpen = false;
      
      // Clean URL
      if (window.location.search) {
        window.history.replaceState({}, document.title, window.location.pathname);
      }
    }

    function showWarningModal(type, message, title) {
      const modal = document.getElementById('warningModal');
      const icon = document.getElementById('modalIcon');
      const titleEl = document.getElementById('modalTitle');
      const messageEl = document.getElementById('modalMessage');
      
      if (!modal) return;
      
      // Set content
      messageEl.textContent = message;
      titleEl.textContent = title;
      icon.className = 'warning-modal__icon ' + type;
      
      // Show modal
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
      modalOpen = true;
    }

    // Check URL parameters
    window.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      
      if (urlParams.has('error')) {
        const error = urlParams.get('error');
        
        if (error === 'customer_blocked') {
          setTimeout(function() {
            showWarningModal('danger', 'Customer accounts cannot access Admin Console.', 'Access Denied');
          }, 200);
        }
      }
    });

    // Close on overlay click
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('warning-modal__overlay')) {
        closeWarningModal();
      }
    });

    // Close on Escape
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && modalOpen) {
        closeWarningModal();
      }
    });
  </script>
</body>
</html>
