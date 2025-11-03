
<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
        require_once '../../database/db_connect.php';
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../pages/login.php');
            exit;
        } 

$total_price = 0;
$total_items = 0;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT c.quantity, p.price 
            FROM cart c 
            JOIN products p ON c.product_id = p.product_id 
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $total_items += $row['quantity'];
        $total_price += $row['price'] * $row['quantity'];
    }
}
?>

<header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__left">
                            <ul>
                                <li><i class="fa fa-envelope"></i> ogani@website.com</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            
                            
                            <div class="header__top__right__language">
                                    <?php if (isset($_SESSION['username']) && $_SESSION['role'] === 'customer'): ?>
                                        <div href="#"><i class="fa fa-user"></i> <?= htmlspecialchars($_SESSION['username']); ?></div>
                                        <span class="arrow_carrot-down"></span>
                                        <ul>
                                            <li>
                                                <a href="../logout.php" style="margin-left: 10px; color: red;">
                                                 <i class="fa fa-sign-out"></i> Logout
                                                </a>
                                            </li>
                                        
                                        </ul>
                                         <?php else: ?>
                                        <a href="../../pages/login.php"><i class="fa fa-user"></i> Login</a>
                                    <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header__logo">
                        <a href="./index.php"><img src="../../assets/img/logo.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li <?php if (basename($_SERVER['REQUEST_URI']) === 'home.php') { echo 'class="active"'; } ?>><a href="./home.php">Home</a></li>
                            <li <?php if (basename($_SERVER['REQUEST_URI']) === 'shop-grid.php') { echo 'class="active"'; } ?>><a href="./shop-grid.php">Shop</a></li>
                            <li <?php if (basename($_SERVER['REQUEST_URI']) === 'contact.php') { echo 'class="active"'; } ?>><a href="./contact.php">Contact</a></li>
                            <li <?php if (basename($_SERVER['REQUEST_URI']) === 'order-history.php') { echo 'class="active"'; } ?>><a href="./order-history.php">Orders</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>
                           
                            <li>
                            <a href="./shoping-cart.php">
                                <i class="fa fa-shopping-bag"></i>
                                <span><?= $total_items ?></span>
                            </a>
</li>

                        </ul>
                        <div class="header__cart__price">
                            item: <span>Rp<?= number_format($total_price, 2, ',', '.'); ?></span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>