<?php
session_start();
include "config.php";

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header("Location: login.php");
    exit;
}

// Redirect to cart if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Get logged in user details
$user_id = (int) $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE id = $user_id AND status = 1 LIMIT 1";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

if (!$user) {
    // User not found or inactive, logout and redirect
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    header("Location: login.php");
    exit;
}

// Get cart products from database
$cart_items = [];
$subtotal = 0;
$placeholder_image = 'assets/images/products/placeholder.webp';

$product_ids = array_keys($_SESSION['cart']);
$ids_string = implode(',', array_map('intval', $product_ids));

$cart_query = "SELECT * FROM products WHERE id IN ($ids_string) AND status = 1";
$cart_result = mysqli_query($conn, $cart_query);

while ($product = mysqli_fetch_assoc($cart_result)) {
    $product_id = $product['id'];
    $quantity = $_SESSION['cart'][$product_id]['quantity'] ?? 1;
    $product['quantity'] = $quantity;
    $product['image'] = !empty($product['image']) ? $product['image'] : $placeholder_image;
    
    // Calculate price (use sale_price if available)
    $price = !empty($product['sale_price']) && $product['sale_price'] < $product['price']
        ? $product['sale_price']
        : $product['price'];
    $product['final_price'] = $price;
    $product['subtotal'] = $price * $quantity;
    $subtotal += $product['subtotal'];
    
    $cart_items[] = $product;
}

$shipping = 0; // Free shipping
$total = $subtotal + $shipping;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Checkout - MySmartSCart | Complete Your Order</title>

    <meta name="keywords" content="MySmartSCart, Checkout, Order, Secure Payment" />
    <meta name="description" content="Complete your order at MySmartSCart. Secure checkout with fast delivery across India.">
    <meta name="author" content="MySmartSCart.in">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/icons/favicon.png">

    <script>
        WebFontConfig = {
            google: {
                families: ['Open+Sans:300,400,600,700,800', 'Poppins:300,400,500,600,700', 'Shadows+Into+Light:400']
            }
        };
        (function (d) {
            var wf = d.createElement('script'),
                s = d.scripts[0];
            wf.src = 'assets/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/demo7.min.css">
    <link rel="stylesheet" href="assets/css/style.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/css/optimizations.css">
</head>

<body>
    <div class="page-wrapper">
        <?php include "common/top-notice.php"; ?>
        <?php include "common/header.php"; ?>

        <main class="main main-test">
            <div class="container checkout-container">
                <?php if (isset($_SESSION['checkout_errors'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($_SESSION['checkout_errors'] as $error) {
                            echo '<li>' . htmlspecialchars($error) . '</li>';
                        }
                        unset($_SESSION['checkout_errors']); ?>
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>
                
                <?php if (isset($_SESSION['checkout_error'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['checkout_error']; unset($_SESSION['checkout_error']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>
                
                <ul class="checkout-progress-bar d-flex justify-content-center flex-wrap">
                    <li>
                        <a href="cart.php">Shopping Cart</a>
                    </li>
                    <li class="active">
                        <a href="checkout.php">Checkout</a>
                    </li>
                    <li class="disabled">
                        <a href="#">Order Complete</a>
                    </li>
                </ul>

                <div class="row">
                    <div class="col-lg-7">
                        <ul class="checkout-steps">
                            <li>
                                <h2 class="step-title">Billing details</h2>

                                <form action="checkout-handler.php" method="POST" id="checkout-form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>First name
                                                    <abbr class="required" title="required">*</abbr>
                                                </label>
                                                <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Last name
                                                    <abbr class="required" title="required">*</abbr></label>
                                                <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Company name (optional)</label>
                                        <input type="text" class="form-control" name="company" />
                                    </div>

                                    <div class="select-custom">
                                        <label>Country / Region
                                            <abbr class="required" title="required">*</abbr></label>
                                        <select name="country" class="form-control" required>
                                            <option value="India" <?php echo (($user['country'] ?? 'India') == 'India') ? 'selected' : ''; ?>>India</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-1 pb-2">
                                        <label>Street address
                                            <abbr class="required" title="required">*</abbr></label>
                                        <input type="text" class="form-control" name="address_line1"
                                            placeholder="House number and street name" value="<?php echo htmlspecialchars($user['address_line1'] ?? ''); ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" name="address_line2"
                                            placeholder="Apartment, suite, unit, etc. (optional)" value="<?php echo htmlspecialchars($user['address_line2'] ?? ''); ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label>Town / City
                                            <abbr class="required" title="required">*</abbr></label>
                                        <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required />
                                    </div>

                                    <div class="select-custom">
                                        <label>State <abbr class="required" title="required">*</abbr></label>
                                        <select name="state" class="form-control" required>
                                            <option value="">Select State</option>
                                            <?php
                                            $states = ['Uttarakhand', 'Uttar Pradesh', 'Delhi', 'Haryana', 'Punjab', 'Himachal Pradesh', 'Rajasthan', 'Maharashtra', 'Gujarat', 'Karnataka', 'Tamil Nadu', 'West Bengal', 'Other'];
                                            $user_state = $user['state'] ?? '';
                                            foreach ($states as $state) {
                                                $selected = ($user_state == $state) ? 'selected' : '';
                                                echo "<option value=\"$state\" $selected>$state</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Postcode / ZIP
                                            <abbr class="required" title="required">*</abbr></label>
                                        <input type="text" class="form-control" name="postcode" pattern="[0-9]{6}" value="<?php echo htmlspecialchars($user['postcode'] ?? ''); ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label>Phone <abbr class="required" title="required">*</abbr></label>
                                        <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label>Email address
                                            <abbr class="required" title="required">*</abbr></label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required />
                                    </div>

                                    <div class="form-group">
                                        <label class="order-comments">Order notes (optional)</label>
                                        <textarea class="form-control" name="order_notes"
                                            placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <!-- End .col-lg-7 -->

                    <div class="col-lg-5">
                        <div class="order-summary">
                            <h3>YOUR ORDER</h3>

                            <table class="table table-mini-cart">
                                <thead>
                                    <tr>
                                        <th colspan="2">Product</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_items as $item) {
                                        $item_price = number_format($item['final_price'], 2);
                                        $item_subtotal = number_format($item['subtotal'], 2);
                                    ?>
                                    <tr>
                                        <td class="product-col">
                                            <h3 class="product-title">
                                                <?php echo htmlspecialchars($item['name']); ?> ×
                                                <span class="product-qty"><?php echo $item['quantity']; ?></span>
                                            </h3>
                                        </td>

                                        <td class="price-col">
                                            <span>₹<?php echo $item_subtotal; ?></span>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr class="cart-subtotal">
                                        <td>
                                            <h4>Subtotal</h4>
                                        </td>

                                        <td class="price-col">
                                            <span>₹<?php echo number_format($subtotal, 2); ?></span>
                                        </td>
                                    </tr>
                                    <tr class="order-shipping">
                                        <td class="text-left" colspan="2">
                                            <h4 class="m-b-sm">Shipping</h4>

                                            <div class="form-group form-group-custom-control">
                                                <div class="custom-control custom-radio d-flex">
                                                    <input type="radio" class="custom-control-input" name="shipping_method" value="free" checked />
                                                    <label class="custom-control-label">Free Shipping</label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr class="order-total">
                                        <td>
                                            <h4>Total</h4>
                                        </td>
                                        <td>
                                            <b class="total-price"><span>₹<?php echo number_format($total, 2); ?></span></b>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="payment-methods">
                                <h4 class="">Payment methods</h4>
                                <div class="info-box with-icon p-0">
                                    <p>
                                        Payment will be processed after order confirmation. We accept Cash on Delivery (COD) and online payment methods.
                                    </p>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-dark btn-place-order" form="checkout-form">
                                Place order
                            </button>
                        </div>
                        <!-- End .order-summary -->
                    </div>
                    <!-- End .col-lg-5 -->
                </div>
                <!-- End .row -->
            </div>
            <!-- End .container -->
        </main>
        <!-- End .main -->

        <!-- Start .footer -->
        <?php include "common/footer.php"; ?>
        <!-- End .footer -->
    </div>
    <!-- End .page-wrapper -->

    <div class="loading-overlay">
        <div class="bounce-loader">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>

    <?php include "common/mobile-menu.php"; ?>

    <a id="scroll-top" href="#top" title="Top" role="button"><i class="icon-angle-up"></i></a>

    <!-- Plugins JS File -->
    <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/plugins.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.min.js"></script>
    <script>(function () { function c() { var b = a.contentDocument || a.contentWindow.document; if (b) { var d = b.createElement('script'); d.innerHTML = "window.__CF$cv$params={r:'9a48e19e9da7e1dd',t:'MTc2NDE1NDgxOA=='};var a=document.createElement('script');a.src='../../cdn-cgi/challenge-platform/h/b/scripts/jsd/13c98df4ef2d/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);"; b.getElementsByTagName('head')[0].appendChild(d) } } if (document.body) { var a = document.createElement('iframe'); a.height = 1; a.width = 1; a.style.position = 'absolute'; a.style.top = 0; a.style.left = 0; a.style.border = 'none'; a.style.visibility = 'hidden'; document.body.appendChild(a); if ('loading' !== document.readyState) c(); else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c); else { var e = document.onreadystatechange || function () { }; document.onreadystatechange = function (b) { e(b); 'loading' !== document.readyState && (document.onreadystatechange = e, c()) } } } })();</script>
    <script defer
        src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}'
        crossorigin="anonymous"></script>
</body>

</html>
