<?php
session_start();
require_once "../../database/db_connect.php";

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
  header("Location: ../login.php");
  exit();
}

$pageTitle = "Profile";
$pageDescription = "Manage your profile information and security settings.";
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
                    <div class="profile-section">
                        <div class="profile-avatar-section">
                            <div class="profile-avatar-large">
                                <i class='bx bxs-user'></i>
                            </div>
                            <button class="admin-btn admin-btn--secondary">Change Avatar</button>
                        </div>
                        
                        <form class="profile-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['username']) ?>" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label>Role</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['role']) ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" class="form-control" value="admin@ogani.com" placeholder="Enter your email">
                            </div>
                            
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" class="form-control" placeholder="Enter your phone number">
                            </div>
                            
                            <div class="form-divider"></div>
                            
                            <h3 style="margin-bottom: 16px; color: var(--text-light);">Change Password</h3>
                            
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" class="form-control" placeholder="Enter current password">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" placeholder="Enter new password">
                                </div>
                                
                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input type="password" class="form-control" placeholder="Confirm new password">
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="button" class="admin-btn admin-btn--secondary">Cancel</button>
                                <button type="submit" class="admin-btn admin-btn--primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .profile-section {
            max-width: 800px;
        }
        
        .profile-avatar-section {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 40px;
            padding-bottom: 32px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .profile-avatar-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7fad39, #4dd082);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .profile-avatar-large i {
            font-size: 3.5rem;
            color: white;
        }
        
        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-light);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            color: var(--text-light);
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            background: rgba(255, 255, 255, 0.08);
        }
        
        .form-control:read-only {
            background: rgba(255, 255, 255, 0.02);
            cursor: not-allowed;
        }
        
        .form-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
            margin: 16px 0;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 16px;
        }
    </style>
</body>
</html>
