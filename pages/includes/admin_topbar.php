<?php
$pageTitle = $pageTitle ?? "Dashboard";
$pageDescription = $pageDescription ?? "Monitor everything that matters to your marketplace.";
$pageActions = $pageActions ?? [];
$currentDate = (new DateTime())->format("D, d M Y");
?>

<header class="admin-topbar" data-animate-stagger>
    <div class="admin-topbar__title">
        <p class="eyebrow">Operations Center</p>
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
        <p class="subtitle"><?= htmlspecialchars($pageDescription) ?></p>
    </div>

    <div class="admin-topbar__actions">
        <div class="admin-search">
            <i class='bx bx-search'></i>
            <input type="search" placeholder="Quick search or type '/'">
        </div>

        <div class="admin-date-chip">
            <i class='bx bx-calendar'></i>
            <span><?= $currentDate ?></span>
        </div>

        <?php if (!empty($pageActions)): ?>
            <div class="admin-action-group">
                <?php foreach ($pageActions as $action):

                  $label = htmlspecialchars($action["label"] ?? "Action");
                  $href = htmlspecialchars($action["href"] ?? "#");
                  $icon = htmlspecialchars($action["icon"] ?? "bx bx-plus");
                  $variant = htmlspecialchars($action["variant"] ?? "primary");
                  ?>
                    <a href="<?= $href ?>" class="admin-btn admin-btn--<?= $variant ?>">
                        <i class='<?= $icon ?>'></i>
                        <span><?= $label ?></span>
                    </a>
                <?php
                endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- User Profile Dropdown -->
        <div class="admin-user-dropdown">
            <button class="admin-user-dropdown__trigger" onclick="toggleUserDropdown()" type="button">
                <div class="admin-user-dropdown__avatar">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="admin-user-dropdown__info">
                    <span class="admin-user-dropdown__name"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
                    <span class="admin-user-dropdown__role">Administrator</span>
                </div>
                <i class='bx bx-chevron-down admin-user-dropdown__chevron'></i>
            </button>
        </div>

        <button class="sidebar-toggle" type="button" data-toggle="sidebar" aria-label="Toggle menu">
            <i class='bx bx-menu'></i>
        </button>
    </div>
</header>

<!-- User Dropdown Menu (Outside topbar to avoid blur inheritance) -->
<div class="admin-user-dropdown__menu" id="userDropdownMenu" style="background: #1a1f2e !important; backdrop-filter: none !important; -webkit-backdrop-filter: none !important;">
    <div class="admin-user-dropdown__header">
        <div class="admin-user-dropdown__avatar-large">
            <i class='bx bxs-user'></i>
        </div>
        <div>
            <div class="admin-user-dropdown__menu-name"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></div>
            <div class="admin-user-dropdown__menu-email">admin@ogani.com</div>
        </div>
    </div>

    <div class="admin-user-dropdown__divider"></div>

    <a href="profile.php" class="admin-user-dropdown__item">
        <i class='bx bx-user'></i>
        <span>Profile</span>
    </a>
    <a href="settings.php" class="admin-user-dropdown__item">
        <i class='bx bx-cog'></i>
        <span>Settings</span>
    </a>
    <a href="help.php" class="admin-user-dropdown__item">
        <i class='bx bx-help-circle'></i>
        <span>Help & Support</span>
    </a>

    <div class="admin-user-dropdown__divider"></div>

    <a href="javascript:void(0)" onclick="openLogoutModal()" class="admin-user-dropdown__item admin-user-dropdown__item--danger">
        <i class='bx bx-log-out'></i>
        <span>Logout</span>
    </a>
</div>

<style>
/* User Dropdown Styles */
.admin-user-dropdown {
    position: relative;
    margin-right: 16px;
}

.admin-user-dropdown__trigger {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 12px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.admin-user-dropdown__trigger:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(127, 173, 57, 0.5);
    box-shadow: 0 0 12px rgba(127, 173, 57, 0.2);
}

.admin-user-dropdown__avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #7fad39, #4dd082);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.admin-user-dropdown__info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 2px;
}

.admin-user-dropdown__name {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-light);
}

.admin-user-dropdown__role {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.admin-user-dropdown__chevron {
    font-size: 1.2rem;
    color: var(--text-muted);
    transition: transform 0.3s ease;
}

.admin-user-dropdown__trigger.active .admin-user-dropdown__chevron {
    transform: rotate(180deg);
}

.admin-user-dropdown__menu {
    position: fixed !important;
    top: 0;
    right: 0;
    width: 280px;
    background: #1a1f2e !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(127, 173, 57, 0.15);
    padding: 12px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 10000 !important;
}

.admin-user-dropdown__menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.admin-user-dropdown__header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #151b29 !important;
    border-radius: 12px;
    margin-bottom: 8px;
}

.admin-user-dropdown__avatar-large {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #7fad39, #4dd082);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.admin-user-dropdown__menu-name {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 2px;
}

.admin-user-dropdown__menu-email {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.admin-user-dropdown__divider {
    height: 1px;
    background: rgba(255, 255, 255, 0.08);
    margin: 8px 0;
}

.admin-user-dropdown__item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 8px;
    color: var(--text-light);
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.admin-user-dropdown__item:hover {
    background: rgba(127, 173, 57, 0.15);
    color: #7fad39;
}

.admin-user-dropdown__item i {
    font-size: 1.2rem;
    width: 20px;
    text-align: center;
}

.admin-user-dropdown__item--danger {
    color: #ff6b6b;
}

.admin-user-dropdown__item--danger:hover {
    background: rgba(231, 76, 60, 0.15);
    color: #e74c3c;
}
</style>

<script>
function toggleUserDropdown() {
    const menu = document.getElementById('userDropdownMenu');
    const trigger = document.querySelector('.admin-user-dropdown__trigger');
    
    if (!menu || !trigger) return;
    
    const isShowing = menu.classList.contains('show');
    
    if (!isShowing) {
        // Calculate position before showing
        const rect = trigger.getBoundingClientRect();
        menu.style.top = (rect.bottom + 8) + 'px';
        menu.style.right = (window.innerWidth - rect.right) + 'px';
        
        // Show dropdown and lock scroll
        menu.classList.add('show');
        trigger.classList.add('active');
        document.body.style.overflow = 'hidden';
    } else {
        // Hide dropdown and unlock scroll
        menu.classList.remove('show');
        trigger.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.querySelector('.admin-user-dropdown');
    const menu = document.getElementById('userDropdownMenu');
    const trigger = document.querySelector('.admin-user-dropdown__trigger');
    
    if (dropdown && !dropdown.contains(e.target) && !menu.contains(e.target)) {
        menu?.classList.remove('show');
        trigger?.classList.remove('active');
        document.body.style.overflow = '';
    }
});

// Close dropdown on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const menu = document.getElementById('userDropdownMenu');
        const trigger = document.querySelector('.admin-user-dropdown__trigger');
        
        if (menu?.classList.contains('show')) {
            menu.classList.remove('show');
            trigger?.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
});
</script>

