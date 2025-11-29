<?php
$currentPage = basename(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)); ?>

<aside class="sidebar">
    <div class="sidebar__brand">
        <div class="sidebar__logo">
            <span>Ogani</span>
            <small>Admin Console</small>
        </div>
        <p class="sidebar__subtext">Monitor products, orders, and categories in real-time.</p>
    </div>

    <nav class="sidebar__menu">
        <a href="dashboard.php" class="sidebar__link <?php echo $currentPage === "dashboard.php"
          ? "active"
          : ""; ?>">
            <i class='bx bxs-dashboard'></i>
            <span>Dashboard</span>
        </a>
        <a href="manage_products.php" class="sidebar__link <?php echo $currentPage ===
        "manage_products.php"
          ? "active"
          : ""; ?>">
            <i class='bx bxs-package'></i>
            <span>Products</span>
        </a>
        <a href="manage_categories.php" class="sidebar__link <?php echo $currentPage ===
        "manage_categories.php"
          ? "active"
          : ""; ?>">
            <i class='bx bx-category'></i>
            <span>Categories</span>
        </a>
        <a href="manage_orders.php" class="sidebar__link <?php echo $currentPage ===
        "manage_orders.php"
          ? "active"
          : ""; ?>">
            <i class='bx bx-cart'></i>
            <span>Orders</span>
        </a>
    </nav>

    <div class="sidebar__footer">
        <!-- Logout Button -->
        <a href="javascript:void(0)" class="sidebar__logout" onclick="openLogoutModal()">
            <i class='bx bx-log-out'></i>
            <span>Logout</span>
        </a>
        
        <div class="sidebar__status">
            <span class="status-dot"></span>
            <span>System Healthy</span>
        </div>
        <small class="sidebar__copyright">© <?= date("Y") ?> Ogani</small>
    </div>
</aside>

<!-- Logout Confirmation Modal -->
<div class="logout-modal" id="logoutModal" style="display: none;">
    <div class="logout-modal__overlay"></div>
    <div class="logout-modal__content">
        <div class="logout-modal__header">
            <div class="logout-modal__icon">
                <i class='bx bx-log-out'></i>
            </div>
            <h3>Confirm Logout</h3>
            <p>Are you sure you want to logout from admin console?</p>
        </div>
        
        <div class="logout-modal__footer">
            <button type="button" class="btn-cancel" onclick="closeLogoutModal()">Cancel</button>
            <a href="../../database/logout_process.php" class="btn-danger">Logout</a>
        </div>
    </div>
</div>

<style>
.sidebar__logout {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    margin-bottom: 16px;
    background: linear-gradient(135deg, rgba(231, 76, 60, 0.15), rgba(192, 57, 43, 0.15));
    border: 1px solid rgba(231, 76, 60, 0.3);
    border-radius: 12px;
    color: #e74c3c;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.sidebar__logout:hover {
    background: linear-gradient(135deg, rgba(231, 76, 60, 0.25), rgba(192, 57, 43, 0.25));
    border-color: rgba(231, 76, 60, 0.5);
    color: #ff6b5a;
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2);
}

.sidebar__logout i {
    font-size: 1.3rem;
}

/* Logout Modal Styles */
.logout-modal {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    z-index: 2000 !important;
}

.logout-modal__overlay {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(8px);
    animation: fadeIn 0.3s ease;
    z-index: 2000 !important;
}

.logout-modal__content {
    position: fixed !important;
    top: 50vh !important;
    left: 50vw !important;
    transform: translate(-50%, -50%) translateX(65px) !important;
    
    background: rgba(14, 19, 35, 0.95);
    border: 1px solid rgba(255, 99, 72, 0.3);
    border-radius: 24px;
    padding: 0;
    max-width: 420px;
    width: 90%;
    box-shadow: 0 25px 50px rgba(231, 76, 60, 0.4);
    animation: slideUp 0.3s ease;
    overflow: hidden;
    z-index: 2001 !important;
}

.logout-modal__header {
    padding: 32px 28px;
    text-align: center;
}

.logout-modal__icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, rgba(231, 76, 60, 0.2), rgba(192, 57, 43, 0.2));
    border: 2px solid rgba(231, 76, 60, 0.4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.4);
    }
    50% {
        box-shadow: 0 0 0 12px rgba(231, 76, 60, 0);
    }
}

.logout-modal__icon i {
    font-size: 2rem;
    color: #e74c3c;
}

.logout-modal__header h3 {
    margin: 0 0 12px 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-light);
}

.logout-modal__header p {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.95rem;
    line-height: 1.5;
}

.logout-modal__footer {
    padding: 24px 28px;
    display: flex;
    gap: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.btn-cancel {
    flex: 1;
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 12px;
    color: var(--text-light);
    font-weight: 500;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
}

.btn-danger {
    flex: 1;
    padding: 12px 24px;
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    border: 1px solid rgba(231, 76, 60, 0.5);
    border-radius: 12px;
    color: white;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
}

.btn-danger:hover {
    background: linear-gradient(135deg, #ff6b5a, #e74c3c);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translate(-50%, -45%) translateX(65px);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%) translateX(65px);
    }
}
</style>

<script>
function openLogoutModal() {
    const modal = document.getElementById('logoutModal');
    const content = document.querySelector('.content');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        if (content) content.style.overflow = 'hidden';
    }
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');
    const content = document.querySelector('.content');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        if (content) content.style.overflow = '';
    }
}

// Close on overlay click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('logout-modal__overlay')) {
        closeLogoutModal();
    }
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLogoutModal();
    }
});
</script>