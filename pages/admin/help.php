<?php
session_start();
require_once "../../database/db_connect.php";

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
  header("Location: ../login.php");
  exit();
}

$pageTitle = "Help & Support";
$pageDescription = "Find answers and get support for your admin dashboard.";
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
            <div class="grid-2">
                <div class="admin-card">
                    <div class="admin-card__body">
                        <h3 style="color: var(--text-light); margin-bottom: 20px;">Frequently Asked Questions</h3>
                        <div class="faq-list">
                            <div class="faq-item">
                                <div class="faq-question">
                                    <i class='bx bx-help-circle'></i>
                                    <strong>How do I add a new product?</strong>
                                </div>
                                <p>Navigate to Products page and click "Add Product" button in the top right corner.</p>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <i class='bx bx-help-circle'></i>
                                    <strong>How to update order status?</strong>
                                </div>
                                <p>Go to Orders page, click the edit button on any order, and select the new status from the dropdown.</p>
                            </div>
                            
                            <div class="faq-item">
                                <div class="faq-question">
                                    <i class='bx bx-help-circle'></i>
                                    <strong>How to manage categories?</strong>
                                </div>
                                <p>Visit the Categories page to add, edit, or remove product categories.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="admin-card">
                    <div class="admin-card__body">
                        <h3 style="color: var(--text-light); margin-bottom: 20px;">Contact Support</h3>
                        <div class="contact-info">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class='bx bx-envelope'></i>
                                </div>
                                <div>
                                    <strong>Email Support</strong>
                                    <p>support@ogani.com</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class='bx bx-phone'></i>
                                </div>
                                <div>
                                    <strong>Phone Support</strong>
                                    <p>+1 (555) 123-4567</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class='bx bx-time'></i>
                                </div>
                                <div>
                                    <strong>Support Hours</strong>
                                    <p>Mon-Fri, 9AM-6PM EST</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        
        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .faq-item {
            padding: 16px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
        }
        
        .faq-question {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-light);
            margin-bottom: 8px;
        }
        
        .faq-question i {
            font-size: 1.4rem;
            color: var(--accent);
        }
        
        .faq-item p {
            color: var(--text-muted);
            margin: 0;
            padding-left: 32px;
        }
        
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .contact-item {
            display: flex;
            gap: 16px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
        }
        
        .contact-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #7fad39, #4dd082);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .contact-icon i {
            font-size: 1.5rem;
            color: white;
        }
        
        .contact-item strong {
            display: block;
            color: var(--text-light);
            margin-bottom: 4px;
        }
        
        .contact-item p {
            color: var(--text-muted);
            margin: 0;
        }
    </style>
</body>
</html>
