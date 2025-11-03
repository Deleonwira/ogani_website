<?php require_once '../../database/db_connect.php'; ?>

<!DOCTYPE html>
<html lang="zxx">


<?php include '../includes/head.php'?>


<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Humberger Begin -->
    <?php include '../includes/hamburger.php'?>
    <!-- Humberger End -->

    <!-- Header Section Begin -->
    <?php include '../includes/header.php'?>
    <!-- Header Section End -->

    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <?php include '../includes/categories.php'?>
                    </div>
                </div>
                <div class="col-lg-9">
                    <!-- <div class="hero__search">
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
                    </div> -->
                    <div class="hero__item set-bg" data-setbg="../../assets/img/hero/banner.jpg">
                        <div class="hero__text">
                            <span>FRUIT FRESH</span>
                            <h2>Vegetable <br />100% Organic</h2>
                            <p>Free Pickup and Delivery Available</p>
                            <a href="#" class="primary-btn">SHOP NOW</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Categories Section Begin -->
    <section class="categories">
        <div class="container">
            <div class="row">
                <div class="categories__slider owl-carousel">
                    
                <?php
                $sql2 = "SELECT * FROM categories";
                $result2 = $conn->query($sql2);
                
                if ($result2->num_rows > 0) {
                    while($row = $result2->fetch_assoc()) {
                        echo '
                        <div class="col-lg-3">
                            <div class="categories__item set-bg" data-setbg="' . htmlspecialchars($row['image']) . '">
                                <h5><a href="shop-grid.php?category=' . htmlspecialchars($row['category_name'])  . '">' . htmlspecialchars($row['category_name']) . '</a></h5>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p>No categories found.</p>';
                }
                ?>
            </div>
        </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Categories Section End -->

    <!-- Featured Section Begin -->
    <section class="featured spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Featured Product</h2>
                    </div>
                    <div class="featured__controls">
                        <ul>
                            <li class="active" data-filter="*">All</li>
                            <?php
                           
                            $sqlCategories = "SELECT * FROM categories";
                            $resultCategories = $conn->query($sqlCategories);
                                    
                            if ($resultCategories->num_rows > 0) {
                                while($row = $resultCategories->fetch_assoc()) {
                                    
                                    $slug = strtolower(str_replace(' ', '-', $row['category_name']));
                                
                                    echo '<li data-filter=".' . htmlspecialchars($slug) . '">' 
                                        . htmlspecialchars($row['category_name']) . 
                                        '</li>';
                                }
                            } else {
                                echo '<li>No categories found</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row featured__filter">
    <?php
    
    $sqlItems = "
        SELECT products.*, categories.category_name 
        FROM products
        JOIN categories ON products.category_id = categories.category_id
    ";
    $resultItems = $conn->query($sqlItems);

    if ($resultItems->num_rows > 0) {
        while ($row = $resultItems->fetch_assoc()) {
            $categorySlug = strtolower(str_replace(' ', '-', $row['category_name']));
            $itemName = htmlspecialchars($row['product_name']);
            $itemPrice = htmlspecialchars(number_format($row['price'], 2));
            $itemImage = htmlspecialchars($row['product_image']);

            echo '
            <div class="col-lg-3 col-md-4 col-sm-6 mix ' . $categorySlug . '">
                <div class="featured__item">
                    <div class="featured__item__pic set-bg" data-setbg="' . $itemImage . '">
                        <ul class="featured__item__pic__hover">
                            <li><a href="#"><i class="fa fa-heart"></i></a></li>
                            <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                            <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                        </ul>
                    </div>
                    <div class="featured__item__text">
                        <h6><a href="shop-details.php?product_id=' . $row['product_id'] . '">' . $itemName . '</a></h6>
                        <h5>Rp' . $itemPrice . '</h5>
                    </div>
                </div>
            </div>';
        }
    } else {
        echo '<p>No featured items found.</p>';
    }
    ?>
</div>

        </div>
    </section>
    <!-- Featured Section End -->

    <!-- Banner Begin -->
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic">
                        <img src="../../assets/img/banner/banner-1.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic">
                        <img src="../../assets/img/banner/banner-2.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    

   

    <!-- Footer Section Begin -->
    <footer class="footer spad">
            <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__about__logo">
                            <a href="./index.php"><img src="../../assets/img/logo.png" alt=""></a>
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
    <?php include '../includes/js.php' ?>



</body>

</html>

<?php $conn->close(); ?>