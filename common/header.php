<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get cart count
$cart_count = count($_SESSION['cart']);

// Get cart items for dropdown (limit to 3 for display)
$cart_dropdown_items = [];
$cart_dropdown_total = 0;
$placeholder_image = 'assets/images/products/placeholder.webp';

if (!empty($_SESSION['cart'])) {
    if (!isset($conn)) {
        include "config.php";
    }
    $product_ids = array_keys($_SESSION['cart']);
    $ids_string = implode(',', array_map('intval', array_slice($product_ids, 0, 3)));
    
    $cart_query = "SELECT * FROM products WHERE id IN ($ids_string) AND status = 1";
    $cart_result = mysqli_query($conn, $cart_query);
    
    while ($cart_product = mysqli_fetch_assoc($cart_result)) {
        $product_id = $cart_product['id'];
        $quantity = $_SESSION['cart'][$product_id]['quantity'] ?? 1;
        $cart_product['quantity'] = $quantity;
        $cart_product['image'] = !empty($cart_product['image']) ? $cart_product['image'] : $placeholder_image;
        
        $price = !empty($cart_product['sale_price']) && $cart_product['sale_price'] < $cart_product['price']
            ? $cart_product['sale_price']
            : $cart_product['price'];
        $cart_product['final_price'] = $price;
        $cart_product['subtotal'] = $price * $quantity;
        $cart_dropdown_total += $cart_product['subtotal'];
        
        $cart_dropdown_items[] = $cart_product;
    }
}
?>
<header class="header">
    <div class="header-top text-uppercase">
        <div class="container">
            <div class="header-left">
                <div class="header-dropdown mr-3 pr-1">
                    <a href="#" class="pl-0">INR</a>
                    <div class="header-menu">
                        <ul>
                            <li><a href="#">INR</a></li>
                            <li><a href="#">USD</a></li>
                        </ul>
                    </div>
                    <!-- End .header-menu -->
                </div>
                <!-- End .header-dropown -->

                <div class="header-dropdown mr-auto">
                    <a href="#" class="pl-0"><i class="flag-us flag"></i>ENG</a>
                    <div class="header-menu">
                        <ul>
                            <li><a href="#"><i class="flag-us flag mr-2"></i>ENG</a>
                            </li>
                            <li><a href="#"><i class="flag-in flag mr-2"></i>HIN</a></li>
                        </ul>
                    </div>
                    <!-- End .header-menu -->
                </div>
                <!-- End .header-dropown -->
            </div>
            <!-- End .header-left -->

            <div class="header-right header-dropdowns ml-0 ml-sm-auto">
                <div class="header-dropdown dropdown-expanded mr-3">
                    <div class="header-menu">
                        <ul>
                            <li><a href="wishlist.php">My Wishlist</a></li>
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="contact.php">Contact Us</a></li>
                            <li><a href="cart.php">Cart</a></li>
                            <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) { ?>
                            <li><a href="logout.php">Logout</a></li>
                            <?php } else { ?>
                            <li><a href="login.php" class="login-link">Log In</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- End .header-menu -->
                </div>
                <!-- End .header-dropown -->

                <span class="separator d-none d-lg-inline-block"></span>

                <div class="social-icons">
                    <a href="#" class="social-icon social-facebook icon-facebook" target="_blank"></a>
                    <a href="#" class="social-icon social-twitter icon-twitter" target="_blank"></a>
                    <a href="#" class="social-icon social-instagram icon-instagram mr-1" target="_blank"></a>
                </div>
                <!-- End .social-icons -->
            </div>
            <!-- End .header-right -->
        </div>
        <!-- End .container -->
    </div>
    <!-- End .header-top -->

    <div class="header-middle sticky-header">
        <div class="container">
            <div class="header-left">
                <button class="mobile-menu-toggler" type="button">
                    <i class="fas fa-bars"></i>
                </button>

                <a href="index.php" class="logo w-100">
                    <img src="assets/images/logo.png" alt="MySmartSCart">
                </a>

                <nav class="main-nav w-100">
                    <ul class="menu">
                        <li>
                            <a href="index.php">Home</a>
                        </li>
                        <li>
                            <a href="shop.php">Shop</a>
                        </li>
                        <li>
                            <a href="about.php">About Us</a>
                        </li>
                        <li>
                            <a href="contact.php">Contact</a>
                        </li>
                    </ul>
                </nav>

                <div class="header-search header-search-popup header-search-category d-none d-lg-block ml-xl-5">
                    <a href="#" class="search-toggle" role="button"><i class="icon-magnifier"></i></a>
                    <form action="shop.php" method="get">
                        <div class="header-search-wrapper">
                            <input type="search" class="form-control bg-white" name="q" id="q"
                                placeholder="Search products..." required="">
                            <div class="select-custom bg-white">
                                <select id="cat" name="cat">
                                    <option value="">All Categories</option>
                                    <?php
                                    if (isset($conn)) {
                                        $cat_query = "SELECT * FROM categories WHERE status = 1 ORDER BY sort_order ASC";
                                        $cat_result = mysqli_query($conn, $cat_query);
                                        while ($cat = mysqli_fetch_assoc($cat_result)) {
                                            echo '<option value="' . $cat['id'] . '">' . htmlspecialchars($cat['name']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- End .select-custom -->
                            <button class="btn bg-white icon-search-3" type="submit"></button>
                        </div>
                        <!-- End .header-search-wrapper -->
                    </form>
                </div>
            </div>
            <!-- End .header-left -->

            <div class="header-right">
                <div class="header-contact d-none d-lg-flex pl-4 pr-4">
                    <img alt="phone" src="assets/images/phone.png" width="30" height="30" class="pb-1">
                    <h6><span>Contact Us</span><a href="contact.php" class="text-dark font1">Get in Touch</a></h6>
                </div>

                <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) { ?>
                <a href="logout.php" class="header-icon header-icon-user d-lg-none d-block" title="logout"><i
                        class="icon-user-2"></i></a>
                <?php } else { ?>
                <a href="login.php" class="header-icon header-icon-user d-lg-none d-block" title="login"><i
                        class="icon-user-2"></i></a>
                <?php } ?>

                <a href="wishlist.php" class="header-icon d-lg-none d-block" title="wishlist"><i
                        class="icon-wishlist-2"></i></a>

                <span class="separator d-lg-inline-block d-none"></span>

                <div class="dropdown cart-dropdown">
                    <a href="cart.php" title="Cart" class="dropdown-toggle dropdown-arrow cart-toggle" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                        <i class="icon-shopping-cart"></i>
                        <span class="cart-count badge-circle"><?php echo $cart_count; ?></span>
                    </a>

                    <div class="cart-overlay"></div>

                    <div class="dropdown-menu mobile-cart">
                        <a href="#" title="Close (Esc)" class="btn-close">×</a>

                        <div class="dropdownmenu-wrapper custom-scrollbar">
                            <div class="dropdown-cart-header">Shopping Cart</div>
                            <!-- End .dropdown-cart-header -->

                            <div class="dropdown-cart-products">
                                <?php if (empty($cart_dropdown_items)) { ?>
                                <div class="text-center p-3">
                                    <p class="mb-0">Your cart is empty</p>
                                </div>
                                <?php } else { 
                                    foreach ($cart_dropdown_items as $cart_item) {
                                        $item_price = number_format($cart_item['final_price'], 2);
                                ?>
                                <div class="product">
                                    <div class="product-details">
                                        <h4 class="product-title">
                                            <a href="product.php?slug=<?php echo htmlspecialchars($cart_item['slug']); ?>"><?php echo htmlspecialchars($cart_item['name']); ?></a>
                                        </h4>

                                        <span class="cart-product-info">
                                            <span class="cart-product-qty"><?php echo $cart_item['quantity']; ?></span> × ₹<?php echo $item_price; ?>
                                        </span>
                                    </div>
                                    <!-- End .product-details -->

                                    <figure class="product-image-container">
                                        <a href="product.php?slug=<?php echo htmlspecialchars($cart_item['slug']); ?>" class="product-image">
                                            <img src="<?php echo htmlspecialchars($cart_item['image']); ?>" alt="<?php echo htmlspecialchars($cart_item['name']); ?>" width="80"
                                                height="80">
                                        </a>

                                        <a href="cart.php?action=remove&id=<?php echo $cart_item['id']; ?>" class="btn-remove" title="Remove Product"><span>×</span></a>
                                    </figure>
                                </div>
                                <!-- End .product -->
                                <?php } } ?>
                            </div>
                            <!-- End .cart-product -->

                            <?php if (!empty($cart_dropdown_items)) { ?>
                            <div class="dropdown-cart-total">
                                <span>SUBTOTAL:</span>

                                <span class="cart-total-price float-right">₹<?php echo number_format($cart_dropdown_total, 2); ?></span>
                            </div>
                            <!-- End .dropdown-cart-total -->

                            <div class="dropdown-cart-action">
                                <a href="cart.php" class="btn btn-gray btn-block view-cart">View
                                    Cart</a>
                                <a href="checkout.php" class="btn btn-dark btn-block">Checkout</a>
                            </div>
                            <!-- End .dropdown-cart-total -->
                            <?php } else { ?>
                            <div class="dropdown-cart-action">
                                <a href="shop.php" class="btn btn-dark btn-block">Continue Shopping</a>
                            </div>
                            <?php } ?>
                        </div>
                        <!-- End .dropdownmenu-wrapper -->
                    </div>
                    <!-- End .dropdown-menu -->
                </div>
                <!-- End .dropdown -->
            </div>
            <!-- End .header-right -->
        </div>
        <!-- End .container -->
    </div>
    <!-- End .header-middle -->
</header>