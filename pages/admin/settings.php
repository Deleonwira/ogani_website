<?php
session_start();
require_once "../../database/db_connect.php";

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
  header("Location: ../login.php");
  exit();
}

$pageTitle = "Settings";
$pageDescription = "Configure your dashboard preferences and system settings.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | Ogani Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body class="admin-layout">
    <?php include "../includes/sidebar.php"; ?>
    
    <main class="content">
        <?php include "../includes/admin_topbar.php"; ?>
        
        <div class="admin-container">
            <div class="admin-card">
                <div class="admin-card__body">
                    <div class="settings-section">
                        <div class="settings-group">
                            <h3>Notifications</h3>
                            
                            <div class="setting-item">
                                <div class="setting-info">
                                    <strong>Email Notifications</strong>
                                    <p>Receive email alerts for new orders and updates</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            
                            <div class="setting-item">
                                <div class="setting-info">
                                    <strong>Order Alerts</strong>
                                    <p>Get notified when new orders are placed</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="settings-group">
                            <h3>Appearance</h3>
                            
                            <div class="setting-item">
                                <div class="setting-info">
                                    <strong>Compact Mode</strong>
                                    <p>Show more content in a smaller space</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .settings-section {
            max-width: 800px;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        
        .settings-group {
            padding-bottom: 32px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .settings-group h3 {
            color: var(--text-light);
            margin-bottom: 20px;
        }
        
        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            margin-bottom: 12px;
        }
        
        .setting-info strong {
            display: block;
            color: var(--text-light);
            margin-bottom: 4px;
        }
        
        .setting-info p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin: 0;
        }
        
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.1);
            transition: 0.3s;
            border-radius: 26px;
        }
        
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }
        
        .toggle-switch input:checked + .toggle-slider {
            background: linear-gradient(135deg, #7fad39, #4dd082);
        }
        
        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(24px);
        }
    </style>
</body>
</html>
