<?php
session_start();
include "config.php";

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'wishlist.php';
    header("Location: login");
    exit;
}

// Initialize wishlist if not exists
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Get wishlist products from database
$wishlist_items = [];
$placeholder_image = 'assets/images/products/placeholder.webp';

if (!empty($_SESSION['wishlist'])) {
    $product_ids = array_keys($_SESSION['wishlist']);
    $ids_string = implode(',', array_map('intval', $product_ids));
    
    $wishlist_query = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                       FROM products p 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.id IN ($ids_string) AND p.status = 1";
    $wishlist_result = mysqli_query($conn, $wishlist_query);
    
    while ($product = mysqli_fetch_assoc($wishlist_result)) {
        $product['image'] = !empty($product['image']) ? $product['image'] : $placeholder_image;
        $product['price'] = number_format($product['price'], 2);
        $product['sale_price'] = !empty($product['sale_price']) ? number_format($product['sale_price'], 2) : null;
        $wishlist_items[] = $product;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>My Wishlist - MySmartSCart | Saved Products</title>

    <meta name="keywords" content="MySmartSCart, Wishlist, Saved Products, Shopping" />
    <meta name="description" content="View your wishlist of saved products at MySmartSCart.">
    <meta name="author" content="MySmartSCart.in">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/icons/favicon.png">


    <script>
        WebFontConfig = {
            google: { families: ['Open+Sans:300,400,600,700,800', 'Poppins:300,400,500,600,700', 'Shadows+Into+Light:400'] }
        };
        (function (d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = 'assets/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/demo7.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/css/optimizations.css">
</head>

<body>
    <div class="page-wrapper">
        <?php include "common/top-notice.php"; ?>
        <?php include "common/header.php"; ?>

        <main class="main">
            <div class="page-header">
                <div class="container d-flex flex-column align-items-center">
                    <nav aria-label="breadcrumb" class="breadcrumb-nav">
                        <div class="container">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Wishlist
                                </li>
                            </ol>
                        </div>
                    </nav>

                    <h1>Wishlist</h1>
                </div>
            </div>

            <div class="container">
                <?php if (isset($_SESSION['wishlist_message'])) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['wishlist_message']; unset($_SESSION['wishlist_message']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>
                
                <?php if (isset($_SESSION['wishlist_error'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['wishlist_error']; unset($_SESSION['wishlist_error']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php } ?>
                
                <div class="wishlist-title">
                    <h2 class="p-2">My Wishlist</h2>
                </div>
                <div class="wishlist-table-container">
                    <?php if (empty($wishlist_items)) { ?>
                    <div class="text-center py-5">
                        <i class="icon-wishlist-2" style="font-size: 64px; color: #ccc;"></i>
                        <h3 class="mt-3">Your wishlist is empty</h3>
                        <p class="mb-4">Start adding products to your wishlist to save them for later.</p>
                        <a href="shop.php" class="btn btn-dark">Continue Shopping</a>
                    </div>
                    <?php } else { ?>
                    <table class="table table-wishlist mb-0">
                        <thead>
                            <tr>
                                <th class="thumbnail-col"></th>
                                <th class="product-col">Product</th>
                                <th class="price-col">Price</th>
                                <th class="status-col">Stock Status</th>
                                <th class="action-col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($wishlist_items as $product) {
                                $product_link = getProductUrl($product['slug']);
                                $final_price = !empty($product['sale_price']) && $product['sale_price'] < $product['price'] 
                                    ? $product['sale_price'] 
                                    : $product['price'];
                                $stock_status = ($product['stock'] > 0) ? 'In stock' : 'Out of stock';
                                $stock_class = ($product['stock'] > 0) ? 'text-success' : 'text-danger';
                            ?>
                            <tr class="product-row">
                                <td>
                                    <figure class="product-image-container">
                                        <a href="<?php echo $product_link; ?>" class="product-image">
                                            <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                                 width="100" height="100">
                                        </a>
                                        <a href="wishlist-handler.php?action=remove&id=<?php echo $product['id']; ?>" 
                                           class="btn-remove icon-cancel" 
                                           title="Remove Product"></a>
                                    </figure>
                                </td>
                                <td>
                                    <h5 class="product-title">
                                        <a href="<?php echo $product_link; ?>">
                                            <?php echo htmlspecialchars($product['name']); ?>
                                        </a>
                                    </h5>
                                    <?php if (!empty($product['category_name'])) { ?>
                                    <p class="text-muted mb-0">
                                        <small><?php echo htmlspecialchars($product['category_name']); ?></small>
                                    </p>
                                    <?php } ?>
                                </td>
                                <td class="price-box">
                                    <?php if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']) { ?>
                                    <span class="old-price">₹<?php echo $product['price']; ?></span>
                                    <span class="product-price">₹<?php echo $product['sale_price']; ?></span>
                                    <?php } else { ?>
                                    <span class="product-price">₹<?php echo $product['price']; ?></span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <span class="stock-status <?php echo $stock_class; ?>">
                                        <?php echo $stock_status; ?>
                                    </span>
                                </td>
                                <td class="action">
                                    <a href="<?php echo $product_link; ?>" 
                                       class="btn btn-quickview mt-1 mt-md-0" 
                                       title="View Product">
                                        View Product
                                    </a>
                                    <?php if ($product['stock'] > 0) { ?>
                                    <a href="cart.php?action=add&id=<?php echo $product['id']; ?>&qty=1" 
                                       class="btn btn-dark btn-add-cart product-type-simple btn-shop">
                                        ADD TO CART
                                    </a>
                                    <?php } else { ?>
                                    <button class="btn btn-dark btn-add-cart btn-shop" disabled>
                                        OUT OF STOCK
                                    </button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } ?>
                </div><!-- End .wishlist-table-container -->
            </div><!-- End .container -->
        </main><!-- End .main -->

        <!-- Start .footer -->
        <?php include "common/footer.php"; ?>
        <!-- End .footer -->
    </div><!-- End .page-wrapper -->

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
    <script defer
        src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}'
        crossorigin="anonymous"></script>
    <script>(function () { function c() { var b = a.contentDocument || a.contentWindow.document; if (b) { var d = b.createElement('script'); d.innerHTML = "window.__CF$cv$params={r:'9a48e16abb2ee1dd',t:'MTc2NDE1NDgxMA=='};var a=document.createElement('script');a.src='../../cdn-cgi/challenge-platform/h/b/scripts/jsd/13c98df4ef2d/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);"; b.getElementsByTagName('head')[0].appendChild(d) } } if (document.body) { var a = document.createElement('iframe'); a.height = 1; a.width = 1; a.style.position = 'absolute'; a.style.top = 0; a.style.left = 0; a.style.border = 'none'; a.style.visibility = 'hidden'; document.body.appendChild(a); if ('loading' !== document.readyState) c(); else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c); else { var e = document.onreadystatechange || function () { }; document.onreadystatechange = function (b) { e(b); 'loading' !== document.readyState && (document.onreadystatechange = e, c()) } } } })();</script>
</body>

</html>