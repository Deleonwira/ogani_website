<div class="sidebar">
        <h4 class="text-center mb-4"> Ogani Admin</h4>
        <a href="dashboard.php" class="<?php if (basename($_SERVER['REQUEST_URI']) === 'dashboard.php') { echo 'active'; } ?>"><i class='bx bxs-dashboard'></i> Dashboard</a>
        <a href="manage_products.php" class="<?php if (basename($_SERVER['REQUEST_URI']) === 'manage_products.php') { echo 'active'; } ?>"><i class='bx bxs-package'></i> Products</a>
        <a href="manage_categories.php" class="<?php if (basename($_SERVER['REQUEST_URI']) === 'manage_categories.php') { echo 'active'; } ?>"><i class='bx bx-category'></i> Categories</a>
        <a href="manage_orders.php" class="<?php if (basename($_SERVER['REQUEST_URI']) === 'manage_orders.php') { echo 'active'; } ?>"><i class='bx bx-cart'></i> Orders</a>
       
</div>