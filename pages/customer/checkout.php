<?php 
require_once "../../database/db_connect.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="zxx">

<?php include "../includes/head.php"; ?>

<body>
    <!-- Page Preloder -->
    <!-- <div id="preloder">
        <div class="loader"></div>
    </div> -->

    <!-- Humberger Begin -->
    <?php include "../includes/hamburger.php"; ?>
    <!-- Humberger End -->

    <!-- Header Section Begin -->
    <?php include "../includes/header.php"; ?>
    <!-- Header Section End -->

    <!-- Hero Section Begin -->
    <section class="hero hero-normal">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>All departments</span>
                        </div>
                        <ul>
                            <li><a href="#">Fresh Meat</a></li>
                            <li><a href="#">Vegetables</a></li>
                            <li><a href="#">Fruit & Nut Gifts</a></li>
                            <li><a href="#">Fresh Berries</a></li>
                            <li><a href="#">Ocean Foods</a></li>
                            <li><a href="#">Butter & Eggs</a></li>
                            <li><a href="#">Fastfood</a></li>
                            <li><a href="#">Fresh Onion</a></li>
                            <li><a href="#">Papayaya & Crisps</a></li>
                            <li><a href="#">Oatmeal</a></li>
                            <li><a href="#">Fresh Bananas</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form action="#">
                                <div class="hero__search__categories">
                                    All Categories
                                    <span class="arrow_carrot-down"></span>
                                </div>
                                <input type="text" placeholder="What do yo u need?">
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>
                        <div class="hero__search__phone">
                            <div class="hero__search__phone__icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="hero__search__phone__text">
                                <h5>+65 11.188.888</h5>
                                <span>support 24/7 time</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="../../assets/img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Checkout</h2>
                        <div class="breadcrumb__option">
                            <a href="./index.html">Home</a>
                            <span>Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Checkout Section Begin -->
    <?php
    if (!isset($_SESSION["user_id"])) {
      header("Location: ../login.php");
      exit();
    }

    $user_id = $_SESSION["user_id"];

    $sql = "SELECT c.product_id, c.quantity, p.product_name, p.price, p.product_image
                FROM cart c
                JOIN products p ON c.product_id = p.product_id
                WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = [];
    $total = 0;

    while ($row = $result->fetch_assoc()) {
      $subtotal = $row["price"] * $row["quantity"];
      $row["subtotal"] = $subtotal;
      $total += $subtotal;
      $cartItems[] = $row;
    }

    if (empty($cartItems)) {
      echo "<script>alert('Keranjang masih kosong!'); window.location.href='shop-grid.php';</script>";
      exit();
    }
    ?>
    <section class="checkout spad">
        <div class="container">
            <div class="row">
            </div>
            <div class="checkout__form">
                <h4>Billing Details</h4>
                <form action="../../database/checkout_process.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <div class="row">
                       
                        <div class="col-lg-8 col-md-6">
                            <div class="checkout__input">
                                <p>Receiver Name<span>*</span></p>
                                <input type="text" name="receiver_name" required>
                            </div>

                            <div class="checkout__input">
                                <p>Phone Number<span>*</span></p>
                                <input type="text" name="receiver_phone" required>
                            </div>

                            <div class="checkout__input">
                                <p>Shipping Address<span>*</span></p>
                                <input type="text" name="shipping_address" placeholder="Street Address" class="checkout__input__add" required>
                            </div>

                            <div class="checkout__input">
                                <p>Order Notes (optional)</p>
                                <input type="text" name="order_notes" placeholder="Special instructions for delivery.">
                            </div>
                        </div>

                        
                        <div class="col-lg-4 col-md-6">
                            <div class="checkout__order">
                                <h4>Your Order</h4>
                                <div class="checkout__order__products">Products <span>Total</span></div>
                                <ul>
                                    <?php foreach ($cartItems as $item): ?>
                                        <li>
                                            <?= htmlspecialchars($item["product_name"]) ?>
                                            <span>Rp<?= number_format(
                                              $item["subtotal"],
                                              2,
                                            ) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <div class="checkout__order__subtotal">Subtotal <span>Rp<?= number_format(
                                  $total,
                                  2,
                                ) ?></span></div>
                                <div class="checkout__order__total">Total <span>Rp<?= number_format(
                                  $total,
                                  2,
                                ) ?></span></div>

                                <input type="hidden" name="total_price" value="<?= $total ?>">
                                <button type="submit" class="site-btn w-100 mt-3">PLACE ORDER</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    



    <!-- Checkout Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__about__logo">
                            <a href="./index.html"><img src="../../assets/img/logo.png" alt=""></a>
                        </div>
                        <ul>
                            <li>Address: 60-49 Road 11378 New York</li>
                            <li>Phone: +65 11.188.888</li>
                            <li>Email: hello@colorlib.com</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 offset-lg-1">
                    <div class="footer__widget">
                        <h6>Useful Links</h6>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">About Our Shop</a></li>
                            <li><a href="#">Secure Shopping</a></li>
                            <li><a href="#">Delivery infomation</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Our Sitemap</a></li>
                        </ul>
                        <ul>
                            <li><a href="#">Who We Are</a></li>
                            <li><a href="#">Our Services</a></li>
                            <li><a href="#">Projects</a></li>
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">Innovation</a></li>
                            <li><a href="#">Testimonials</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="footer__widget">
                        <h6>Join Our Newsletter Now</h6>
                        <p>Get E-mail updates about our latest shop and special offers.</p>
                        <form action="#">
                            <input type="text" placeholder="Enter your mail">
                            <button type="submit" class="site-btn">Subscribe</button>
                        </form>
                        <div class="footer__widget__social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer__copyright">
                        <div class="footer__copyright__text"><p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p></div>
                        <div class="footer__copyright__payment"><img src="../../assets/img/payment-item.png" alt=""></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <?php include "../includes/js.php"; ?>

 

</body>

</html>