<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include helper functions for SEO-friendly URLs
if (!function_exists('getCategoryUrl')) {
    require_once __DIR__ . '/../includes/site-settings.php';
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get cart count
$mobile_cart_count = count($_SESSION['cart']);

// Get categories for mobile menu
$mobile_categories = [];
if (isset($conn)) {
    $mobile_cat_query = "SELECT c.*, COUNT(DISTINCT pc.product_id) as product_count
                         FROM categories c
                         LEFT JOIN product_categories pc ON c.id = pc.category_id
                         LEFT JOIN products p ON pc.product_id = p.id AND p.status = 1
                         WHERE c.status = 1 AND c.parent_id = 0
                         GROUP BY c.id
                         HAVING product_count > 0
                         ORDER BY c.sort_order ASC, c.name ASC
                         LIMIT 15";
    $mobile_cat_result = mysqli_query($conn, $mobile_cat_query);
    if ($mobile_cat_result && mysqli_num_rows($mobile_cat_result) > 0) {
        while ($cat = mysqli_fetch_assoc($mobile_cat_result)) {
            $mobile_categories[] = $cat;
        }
    }
}
?>
<div class="mobile-menu-overlay"></div>

<div class="mobile-menu-container">
    <div class="mobile-menu-wrapper">
        <span class="mobile-menu-close"><i class="fa fa-times"></i></span>
        <nav class="mobile-nav">
            <ul class="mobile-menu">
                <li><a href="/mysmartscart/">Home</a></li>
                <li>
                    <a href="shop">Shop</a>
                    <?php if (!empty($mobile_categories)) { ?>
                    <ul>
                        <li><a href="shop">All Products</a></li>
                        <?php foreach ($mobile_categories as $cat) { ?>
                        <li><a href="<?php echo getCategoryUrl($cat['slug']); ?>"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </li>
                <li><a href="about">About Us</a></li>
                <li><a href="contact">Contact Us</a></li>
                <li>
                    <a href="#">Pages</a>
                    <ul>
                        <li><a href="wishlist">Wishlist</a></li>
                        <li><a href="cart">Shopping Cart</a></li>
                        <li><a href="checkout">Checkout</a></li>
                        <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) { ?>
                        <li><a href="dashboard">Dashboard</a></li>
                        <li><a href="logout">Logout</a></li>
                        <?php } else { ?>
                        <li><a href="login">Login</a></li>
                        <li><a href="forgot-password">Forgot Password</a></li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>

            <ul class="mobile-menu mt-2 mb-2">
                <li class="border-0">
                    <a href="shop">
                        <i class="fas fa-tag mr-2"></i> Shop Now
                    </a>
                </li>
            </ul>

            <ul class="mobile-menu">
                <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) { ?>
                <li><a href="dashboard">My Account</a></li>
                <?php } else { ?>
                <li><a href="login">My Account</a></li>
                <?php } ?>
                <li><a href="contact">Contact Us</a></li>
                <li><a href="wishlist">My Wishlist</a></li>
                <li><a href="cart">Cart</a></li>
                <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) { ?>
                <li><a href="logout">Log Out</a></li>
                <?php } else { ?>
                <li><a href="login" class="login-link">Log In</a></li>
                <?php } ?>
            </ul>
        </nav>
        <!-- End .mobile-nav -->

        <form class="search-wrapper mb-2" action="shop" method="get">
            <input type="text" class="form-control mb-0" name="q" placeholder="Search products..." required />
            <button class="btn icon-search text-white bg-transparent p-0" type="submit"></button>
        </form>

        <div class="social-icons">
            <a href="#" class="social-icon social-facebook icon-facebook" target="_blank"></a>
            <a href="#" class="social-icon social-twitter icon-twitter" target="_blank"></a>
            <a href="#" class="social-icon social-instagram icon-instagram" target="_blank"></a>
        </div>
    </div>
    <!-- End .mobile-menu-wrapper -->
</div>
<!-- End .mobile-menu-container -->

<div class="sticky-navbar">
    <div class="sticky-info">
        <a href="/mysmartscart/">
            <i class="icon-home"></i>Home
        </a>
    </div>
    <div class="sticky-info">
        <a href="shop" class="">
            <i class="icon-bars"></i>Shop
        </a>
    </div>
    <div class="sticky-info">
        <a href="wishlist" class="">
            <i class="icon-wishlist-2"></i>Wishlist
        </a>
    </div>
    <div class="sticky-info">
        <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) { ?>
        <a href="dashboard" class="">
            <i class="icon-user-2"></i>Account
        </a>
        <?php } else { ?>
        <a href="login" class="">
            <i class="icon-user-2"></i>Login
        </a>
        <?php } ?>
    </div>
    <div class="sticky-info">
        <a href="cart" class="">
            <i class="icon-shopping-cart position-relative">
                <span class="cart-count badge-circle"><?php echo $mobile_cart_count; ?></span>
            </i>Cart
        </a>
    </div>
</div>

