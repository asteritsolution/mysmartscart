<?php 
include "config.php";

// Get product slug from URL (supports both old and new SEO-friendly URLs)
// Old format: product.php?slug=apple-cider-vinegar
// New format: product/apple-cider-vinegar
$product_slug = '';

// Check if slug is in GET parameter (old format)
if (isset($_GET['slug']) && !empty($_GET['slug'])) {
    $product_slug = mysqli_real_escape_string($conn, $_GET['slug']);
} 
// Check if slug is in PATH_INFO (new SEO-friendly format)
elseif (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) {
    $path = trim($_SERVER['PATH_INFO'], '/');
    $product_slug = mysqli_real_escape_string($conn, $path);
}
// Check REQUEST_URI for SEO-friendly format
elseif (isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
    // Extract slug from /mysmartscart/product/slug format
    if (preg_match('#/product/([a-z0-9-]+)/?$#i', $uri, $matches)) {
        $product_slug = mysqli_real_escape_string($conn, $matches[1]);
    }
}

// Fetch product details from database
$product = null;
if (!empty($product_slug)) {
    $product_query = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                      FROM products p 
                      LEFT JOIN categories c ON p.category_id = c.id 
                      WHERE p.slug = '$product_slug' AND p.status = 1 
                      LIMIT 1";
    $product_result = mysqli_query($conn, $product_query);
    
    if (mysqli_num_rows($product_result) > 0) {
        $product = mysqli_fetch_assoc($product_result);
        
        // Placeholder image path
        $placeholder_image = 'assets/images/products/placeholder.webp';
        
        // Default image if not set
        $product_image = !empty($product['image']) ? $product['image'] : $placeholder_image;
        
        // Get gallery images
        $gallery_images = [];
        if (!empty($product['gallery_images'])) {
            $gallery_images = json_decode($product['gallery_images'], true);
        }
        // Add main image to gallery if not already there
        if (!empty($product_image) && !in_array($product_image, $gallery_images)) {
            array_unshift($gallery_images, $product_image);
        }
        if (empty($gallery_images)) {
            $gallery_images = [$product_image];
        }
        
        // Calculate discount
        $discount = 0;
        if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']) {
            $discount = round((($product['price'] - $product['sale_price']) / $product['price']) * 100);
        }
        
        // Format prices
        $price = number_format($product['price'], 2);
        $sale_price = !empty($product['sale_price']) ? number_format($product['sale_price'], 2) : null;
        
        // Get previous and next products in same category
        $prev_product = null;
        $next_product = null;
        if (!empty($product['category_id'])) {
            // Previous product
            $prev_query = "SELECT id, name, slug, image FROM products 
                          WHERE category_id = {$product['category_id']} 
                          AND id < {$product['id']} 
                          AND status = 1 
                          ORDER BY id DESC LIMIT 1";
            $prev_result = mysqli_query($conn, $prev_query);
            if (mysqli_num_rows($prev_result) > 0) {
                $prev_product = mysqli_fetch_assoc($prev_result);
            }
            
            // Next product
            $next_query = "SELECT id, name, slug, image FROM products 
                          WHERE category_id = {$product['category_id']} 
                          AND id > {$product['id']} 
                          AND status = 1 
                          ORDER BY id ASC LIMIT 1";
            $next_result = mysqli_query($conn, $next_query);
            if (mysqli_num_rows($next_result) > 0) {
                $next_product = mysqli_fetch_assoc($next_result);
            }
        }
        
        // Get related products (same category, excluding current product)
        $related_products = [];
        if (!empty($product['category_id'])) {
            $related_query = "SELECT p.*, 
                            GROUP_CONCAT(c.name) AS category_names
                            FROM products p
                            LEFT JOIN product_categories pc ON p.id = pc.product_id
                            LEFT JOIN categories c ON pc.category_id = c.id
                            WHERE (p.category_id = {$product['category_id']} OR pc.category_id = {$product['category_id']})
                            AND p.id != {$product['id']}
                            AND p.status = 1
                            GROUP BY p.id
                            ORDER BY RAND()
                            LIMIT 4";
            $related_result = mysqli_query($conn, $related_query);
            if (mysqli_num_rows($related_result) > 0) {
                while ($row = mysqli_fetch_assoc($related_result)) {
                    $row['image'] = !empty($row['image']) ? $row['image'] : 'assets/images/products/placeholder.webp';
                    $related_products[] = $row;
                }
            }
        }
        
        // Get Featured Products
        $featured_products = [];
        $featured_query = "SELECT * FROM products WHERE featured = 1 AND status = 1 AND id != {$product['id']} ORDER BY created_at DESC LIMIT 3";
        $featured_result = mysqli_query($conn, $featured_query);
        while ($row = mysqli_fetch_assoc($featured_result)) {
            $row['image'] = !empty($row['image']) ? $row['image'] : 'assets/images/products/placeholder.webp';
            $featured_products[] = $row;
        }
        
        // Get Best Selling Products
        $best_selling_products = [];
        $best_selling_query = "SELECT * FROM products WHERE best_selling = 1 AND status = 1 AND id != {$product['id']} ORDER BY created_at DESC LIMIT 3";
        $best_selling_result = mysqli_query($conn, $best_selling_query);
        while ($row = mysqli_fetch_assoc($best_selling_result)) {
            $row['image'] = !empty($row['image']) ? $row['image'] : 'assets/images/products/placeholder.webp';
            $best_selling_products[] = $row;
        }
        
        
        // Get Top Rated Products
        $top_rated_products = [];
        $top_rated_query = "SELECT * FROM products WHERE top_rated = 1 AND status = 1 AND id != {$product['id']} ORDER BY created_at DESC LIMIT 3";
        $top_rated_result = mysqli_query($conn, $top_rated_query);
        while ($row = mysqli_fetch_assoc($top_rated_result)) {
            $row['image'] = !empty($row['image']) ? $row['image'] : 'assets/images/products/placeholder.webp';
            $top_rated_products[] = $row;
        }
    }
}

// If product not found, redirect to shop
if (!$product || !isset($product['name']) || empty($product['name'])) {
    header("Location: shop");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Base URL for SEO-friendly URLs - Fixes CSS/JS path issues -->
    <?php 
    // Include site settings for base URL helper
    if (!function_exists('getBaseUrl')) {
        require_once __DIR__ . '/includes/site-settings.php';
    }
    ?>
    <base href="<?php echo getBaseUrl(); ?>">

    <title><?php echo htmlspecialchars($product['name']); ?> - MySmartSCart</title>

    <meta name="keywords" content="<?php echo htmlspecialchars($product['name']); ?>, <?php echo htmlspecialchars($product['category_name'] ?? ''); ?>" />
    <meta name="description" content="<?php echo htmlspecialchars(!empty($product['short_description']) ? $product['short_description'] : $product['name']); ?>">
    <meta name="author" content="MySmartSCart.in">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/icons/favicon.png">


    <script>
        WebFontConfig = {
            google: {
                families: ['Open+Sans:300,400,600,700', 'Poppins:300,400,500,600,700,800', 'Playfair+Display:900', 'Shadows+Into+Light:400']
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
    <link rel="stylesheet" type="text/css" href="assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/css/optimizations.css">
</head>

<body>
    <div class="page-wrapper">
        <?php include "common/top-notice.php"; ?>

        <?php include "common/header.php"; ?>

        <main class="main">
            <nav aria-label="breadcrumb" class="breadcrumb-nav mb-3">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="shop.php">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
                    </ol>
                </div>
            </nav>

            <div class="container">
                <div class="product-single-container product-single-default">
                    <div class="cart-message d-none">
                        <strong class="single-cart-notice">"<?php echo htmlspecialchars($product['name']); ?>"</strong>
                        <span>has been added to your cart.</span>
                    </div>

                    <div class="row">
                        <div class="col-lg-5 col-md-6 product-single-gallery">
                            <div class="product-slider-container">
                                <div class="label-group">
                                    <?php if ((isset($product['featured']) && $product['featured'] == 1) || (isset($product['best_selling']) && $product['best_selling'] == 1) || (isset($product['top_rated']) && $product['top_rated'] == 1)) { ?>
                                    <div class="product-label label-hot">HOT</div>
                                    <?php } ?>
                                    <?php if ($discount > 0) { ?>
                                    <div class="product-label label-sale">
                                        -<?php echo $discount; ?>%
                                    </div>
                                    <?php } ?>
                                </div>

                                <div class="product-single-carousel owl-carousel owl-theme show-nav-hover">
                                    <?php foreach ($gallery_images as $gallery_img) { ?>
                                    <div class="product-item">
                                        <img class="product-single-image"
                                            src="<?php echo htmlspecialchars($gallery_img); ?>"
                                            data-zoom-image="<?php echo htmlspecialchars($gallery_img); ?>" width="468"
                                            height="468" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                    </div>
                                    <?php } ?>
                                </div>
                                <!-- End .product-single-carousel -->
                                <span class="prod-full-screen">
                                    <i class="icon-plus"></i>
                                </span>
                            </div>

                            <div class="prod-thumbnail owl-dots">
                                <?php foreach ($gallery_images as $gallery_img) { ?>
                                <div class="owl-dot">
                                    <img src="<?php echo htmlspecialchars($gallery_img); ?>" width="110" height="110"
                                        alt="product-thumbnail" />
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- End .product-single-gallery -->

                        <div class="col-lg-7 col-md-6 product-single-details">
                            <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                            <?php if ($prev_product || $next_product) { ?>
                            <div class="product-nav">
                                <?php if ($prev_product && isset($prev_product['slug']) && isset($prev_product['name'])) { 
                                    $prev_image = !empty($prev_product['image']) ? $prev_product['image'] : 'assets/images/products/placeholder.webp';
                                ?>
                                <div class="product-prev">
                                    <a href="<?php echo getProductUrl($prev_product['slug']); ?>">
                                        <span class="product-link"></span>
                                        <span class="product-popup">
                                            <span class="box-content">
                                                <img alt="product" width="150" height="150"
                                                    src="<?php echo htmlspecialchars($prev_image); ?>"
                                                    style="padding-top: 0px;">
                                                <span><?php echo htmlspecialchars($prev_product['name']); ?></span>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                                <?php } ?>

                                <?php if ($next_product && isset($next_product['slug']) && isset($next_product['name'])) { 
                                    $next_image = !empty($next_product['image']) ? $next_product['image'] : 'assets/images/products/placeholder.webp';
                                ?>
                                <div class="product-next">
                                    <a href="<?php echo getProductUrl($next_product['slug']); ?>">
                                        <span class="product-link"></span>
                                        <span class="product-popup">
                                            <span class="box-content">
                                                <img alt="product" width="150" height="150"
                                                    src="<?php echo htmlspecialchars($next_image); ?>"
                                                    style="padding-top: 0px;">
                                                <span><?php echo htmlspecialchars($next_product['name']); ?></span>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                                <?php } ?>
                            </div>
                            <?php } ?>

                            <!-- Ratings section removed as we don't have reviews system yet -->

                            <hr class="short-divider">

                            <div class="price-box">
                                <?php if ($sale_price) { ?>
                                <span class="old-price">₹<?php echo $price; ?></span>
                                <span class="product-price">₹<?php echo $sale_price; ?></span>
                                <?php } else { ?>
                                <span class="product-price">₹<?php echo $price; ?></span>
                                <?php } ?>
                            </div>
                            <!-- End .price-box -->

                            <div class="product-desc">
                                <?php if (!empty($product['short_description'])) { ?>
                                <p><?php echo nl2br(htmlspecialchars($product['short_description'])); ?></p>
                                <?php } ?>
                                <?php if (!empty($product['description'])) { ?>
                                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                                <?php } ?>
                            </div>
                            <!-- End .product-desc -->

                            <ul class="single-info-list">
                                <?php if (!empty($product['sku'])) { ?>
                                <li>
                                    SKU: <strong><?php echo htmlspecialchars($product['sku']); ?></strong>
                                </li>
                                <?php } ?>

                                <?php if (!empty($product['category_name'])) { 
                                    $category_link = !empty($product['category_slug']) ? getCategoryUrl($product['category_slug']) : "shop.php";
                                ?>
                                <li>
                                    CATEGORY: <strong><a href="<?php echo $category_link; ?>" class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></a></strong>
                                </li>
                                <?php } ?>

                                <?php if (!empty($product['stock_status'])) { ?>
                                <li>
                                    STOCK: <strong><?php echo strtoupper(str_replace('_', ' ', $product['stock_status'])); ?></strong>
                                    <?php if (isset($product['stock']) && $product['stock'] > 0) { ?>
                                    (<?php echo $product['stock']; ?> available)
                                    <?php } ?>
                                </li>
                                <?php } ?>
                            </ul>

                            <div class="product-filters-container">
                                <div class="product-single-filter select-custom">
                                    <label>COLOR:</label>
                                    <select name="orderby" class="form-control">
                                        <option value="" selected="selected">CHOOSE AN OPTION
                                        </option>
                                        <option value="1">BLACK</option>
                                        <option value="2">BLUE</option>
                                        <option value="3">INDEGO</option>
                                        <option value="4">RIGHT-BLUE</option>
                                        <option value="5">RED</option>
                                    </select>
                                </div>

                                <div class="product-single-filter select-custom">
                                    <label>SIZE:</label>
                                    <select name="orderby" class="form-control">
                                        <option value="" selected="selected">CHOOSE AN OPTION
                                        </option>
                                        <option value="1">EXTRA LARGE</option>
                                        <option value="2">LARGE</option>
                                        <option value="3">MEDIUM</option>
                                        <option value="4">SMALL</option>
                                    </select>
                                </div>

                                <div class="product-single-filter">
                                    <label></label>
                                    <a class="font1 text-uppercase clear-btn" href="#">Clear</a>
                                </div>
                                <!---->
                            </div>

                            <div class="product-action">
                                <div class="price-box product-filtered-price">
                                    <?php if ($sale_price) { ?>
                                    <del class="old-price"><span>₹<?php echo $price; ?></span></del>
                                    <span class="product-price">₹<?php echo $sale_price; ?></span>
                                    <?php } else { ?>
                                    <span class="product-price">₹<?php echo $price; ?></span>
                                    <?php } ?>
                                </div>

                                <div class="product-single-qty">
                                    <input class="horizontal-quantity form-control" type="number" value="1" min="1" max="<?php echo $product['stock'] ?? 999; ?>" data-product-id="<?php echo $product['id'] ?? 0; ?>">
                                </div>
                                <!-- End .product-single-qty -->

                                <a href="javascript:;" class="btn btn-dark add-cart mr-2" title="Add to Cart" data-product-id="<?php echo $product['id'] ?? 0; ?>">Add to
                                    Cart</a>

                                <a href="cart.php" class="btn btn-gray view-cart d-none">View cart</a>
                            </div>
                            <!-- End .product-action -->

                            <hr class="divider mb-0 mt-0">

                            <div class="product-single-share">
                                <label class="sr-only">Share:</label>

                                <div class="social-icons mr-2">
                                    <a href="#" class="social-icon social-facebook icon-facebook" target="_blank"
                                        title="Facebook"></a>
                                    <a href="#" class="social-icon social-twitter icon-twitter" target="_blank"
                                        title="Twitter"></a>
                                    <a href="#" class="social-icon social-linkedin fab fa-linkedin-in" target="_blank"
                                        title="Linkedin"></a>
                                    <a href="#" class="social-icon social-gplus fab fa-google-plus-g" target="_blank"
                                        title="Google +"></a>
                                    <a href="#" class="social-icon social-mail icon-mail-alt" target="_blank"
                                        title="Mail"></a>
                                </div>
                                <!-- End .social-icons -->

                                <?php 
                                $is_in_wishlist = isset($_SESSION['wishlist']) && isset($_SESSION['wishlist'][$product['id']]);
                                $wishlist_class = $is_in_wishlist ? 'added' : '';
                                $wishlist_text = $is_in_wishlist ? 'Remove from Wishlist' : 'Add to Wishlist';
                                $wishlist_url = "wishlist-handler.php?action=" . ($is_in_wishlist ? 'remove' : 'add') . "&id=" . $product['id'] . "&redirect=" . urlencode(getProductUrl($product['slug']));
                                ?>
                                <a href="<?php echo $wishlist_url; ?>" class="btn-icon-wish add-wishlist <?php echo $wishlist_class; ?>" title="<?php echo $wishlist_text; ?>" data-product-id="<?php echo $product['id']; ?>"><i
                                        class="icon-wishlist-2"></i><span><?php echo $wishlist_text; ?></span></a>
                            </div>
                            <!-- End .product single-share -->
                        </div>
                        <!-- End .product-single-details -->
                    </div>
                    <!-- End .row -->
                </div>
                <!-- End .product-single-container -->

                <div class="product-single-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="product-tab-desc" data-toggle="tab"
                                href="#product-desc-content" role="tab" aria-controls="product-desc-content"
                                aria-selected="true">Description</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="product-tab-size" data-toggle="tab" href="#product-size-content"
                                role="tab" aria-controls="product-size-content" aria-selected="true">Size Guide</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="product-tab-tags" data-toggle="tab" href="#product-tags-content"
                                role="tab" aria-controls="product-tags-content" aria-selected="false">Additional
                                Information</a>
                        </li>

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="product-desc-content" role="tabpanel"
                            aria-labelledby="product-tab-desc">
                            <div class="product-desc-content">
                                <?php if (!empty($product['description'])) { ?>
                                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                                <?php } else if (!empty($product['short_description'])) { ?>
                                <p><?php echo nl2br(htmlspecialchars($product['short_description'])); ?></p>
                                <?php } else { ?>
                                <p>No description available for this product.</p>
                                <?php } ?>
                            </div>
                            <!-- End .product-desc-content -->
                        </div>
                        <!-- End .tab-pane -->

                        <div class="tab-pane fade" id="product-size-content" role="tabpanel"
                            aria-labelledby="product-tab-size">
                            <div class="product-size-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"
                                            width="217" height="398" style="object-fit: contain;">
                                    </div>
                                    <!-- End .col-md-4 -->

                                    <div class="col-md-8">
                                        <table class="table table-size">
                                            <thead>
                                                <tr>
                                                    <th>SIZE</th>
                                                    <th>CHEST(in.)</th>
                                                    <th>WAIST(in.)</th>
                                                    <th>HIPS(in.)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>XS</td>
                                                    <td>34-36</td>
                                                    <td>27-29</td>
                                                    <td>34.5-36.5</td>
                                                </tr>
                                                <tr>
                                                    <td>S</td>
                                                    <td>36-38</td>
                                                    <td>29-31</td>
                                                    <td>36.5-38.5</td>
                                                </tr>
                                                <tr>
                                                    <td>M</td>
                                                    <td>38-40</td>
                                                    <td>31-33</td>
                                                    <td>38.5-40.5</td>
                                                </tr>
                                                <tr>
                                                    <td>L</td>
                                                    <td>40-42</td>
                                                    <td>33-36</td>
                                                    <td>40.5-43.5</td>
                                                </tr>
                                                <tr>
                                                    <td>XL</td>
                                                    <td>42-45</td>
                                                    <td>36-40</td>
                                                    <td>43.5-47.5</td>
                                                </tr>
                                                <tr>
                                                    <td>XXL</td>
                                                    <td>45-48</td>
                                                    <td>40-44</td>
                                                    <td>47.5-51.5</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- End .row -->
                            </div>
                            <!-- End .product-size-content -->
                        </div>
                        <!-- End .tab-pane -->

                        <div class="tab-pane fade" id="product-tags-content" role="tabpanel"
                            aria-labelledby="product-tab-tags">
                            <table class="table table-striped mt-2">
                                <tbody>
                                    <?php if (!empty($product['sku'])) { ?>
                                    <tr>
                                        <th>SKU</th>
                                        <td><?php echo htmlspecialchars($product['sku']); ?></td>
                                    </tr>
                                    <?php } ?>

                                    <?php if (!empty($product['category_name'])) { ?>
                                    <tr>
                                        <th>Category</th>
                                        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                    </tr>
                                    <?php } ?>

                                    <tr>
                                        <th>Price</th>
                                        <td>
                                            <?php if ($sale_price) { ?>
                                            <span class="old-price">₹<?php echo $price; ?></span>
                                            <span class="product-price">₹<?php echo $sale_price; ?></span>
                                            <?php } else { ?>
                                            <span class="product-price">₹<?php echo $price; ?></span>
                                            <?php } ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Stock Status</th>
                                        <td><?php echo strtoupper(str_replace('_', ' ', $product['stock_status'] ?? 'in_stock')); ?></td>
                                    </tr>

                                    <?php if (isset($product['stock']) && $product['stock'] > 0) { ?>
                                    <tr>
                                        <th>Stock Quantity</th>
                                        <td><?php echo $product['stock']; ?> units available</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- End .tab-pane -->

                    </div>
                    <!-- End .tab-content -->
                </div>
                <!-- End .product-single-tabs -->

                <?php if (!empty($related_products)) { ?>
                <div class="products-section pt-0">
                    <h2 class="section-title pb-3">Related Products</h2>

                    <div class="products-slider owl-carousel owl-theme dots-top dots-small">
                        <?php foreach ($related_products as $related) {
                            $related_price = number_format($related['price'], 2);
                            $related_sale_price = !empty($related['sale_price']) ? number_format($related['sale_price'], 2) : null;
                            $related_discount = 0;
                            if ($related_sale_price && $related['sale_price'] < $related['price']) {
                                $related_discount = round((($related['price'] - $related['sale_price']) / $related['price']) * 100);
                            }
                            $related_image = !empty($related['image']) ? $related['image'] : 'assets/images/products/placeholder.webp';
                            $related_categories = !empty($related['category_names']) ? explode(',', $related['category_names']) : [];
                        ?>
                        <div class="product-default left-details">
                            <figure>
                                <a href="<?php echo getProductUrl($related['slug']); ?>">
                                    <img src="<?php echo htmlspecialchars($related_image); ?>" alt="<?php echo htmlspecialchars($related['name']); ?>"
                                        width="300" height="300">
                                    <img src="<?php echo htmlspecialchars($related_image); ?>" alt="<?php echo htmlspecialchars($related['name']); ?>"
                                        width="300" height="300">
                                </a>
                                <div class="label-group">
                                    <?php if ($related['featured'] == 1 || $related['best_selling'] == 1 || $related['top_rated'] == 1) { ?>
                                    <span class="product-label label-hot">HOT</span>
                                    <?php } ?>
                                    <?php if ($related_discount > 0) { ?>
                                    <span class="product-label label-sale">-<?php echo $related_discount; ?>%</span>
                                    <?php } ?>
                                </div>
                            </figure>
                            <div class="product-details">
                                <?php if (!empty($related_categories)) { ?>
                                <div class="category-list">
                                    <?php 
                                    $cat_links = [];
                                    foreach ($related_categories as $cat) {
                                        $cat_links[] = '<a href="shop.php" class="product-category">' . htmlspecialchars(trim($cat)) . '</a>';
                                    }
                                    echo implode(', ', $cat_links);
                                    ?>
                                </div>
                                <?php } ?>
                                <h3 class="product-title">
                                    <a href="<?php echo getProductUrl($related['slug']); ?>"><?php echo htmlspecialchars($related['name']); ?></a>
                                </h3>
                                <div class="price-box">
                                    <?php if ($related_sale_price) { ?>
                                    <span class="old-price">₹<?php echo $related_price; ?></span>
                                    <span class="product-price">₹<?php echo $related_sale_price; ?></span>
                                    <?php } else { ?>
                                    <span class="product-price">₹<?php echo $related_price; ?></span>
                                    <?php } ?>
                                </div>
                                <!-- End .price-box -->
                                <div class="product-action">
                                    <a href="product.php?slug=<?php echo htmlspecialchars($related['slug']); ?>" class="btn-icon btn-add-cart product-type-simple"><i
                                            class="icon-shopping-cart"></i><span>VIEW PRODUCT</span></a>
                                    <?php 
                                    $related_in_wishlist = isset($_SESSION['wishlist']) && isset($_SESSION['wishlist'][$related['id']]);
                                    $related_wishlist_url = "wishlist-handler.php?action=" . ($related_in_wishlist ? 'remove' : 'add') . "&id=" . $related['id'] . "&redirect=" . urlencode(getProductUrl($related['slug']));
                                    ?>
                                    <a href="<?php echo $related_wishlist_url; ?>" class="btn-icon-wish <?php echo $related_in_wishlist ? 'added' : ''; ?>" title="<?php echo $related_in_wishlist ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>"><i
                                            class="icon-heart"></i></a>
                                </div>
                            </div>
                            <!-- End .product-details -->
                        </div>
                        <?php } ?>
                    </div>
                    <!-- End .products-slider -->
                </div>
                <!-- End .products-section -->
                <?php } ?>

                <hr class="mt-0 m-b-5" />

                <div class="product-widgets-container row pb-2">
                    <?php 
                    // Helper function to render product widget
                    function renderProductWidget($products, $title) {
                        if (empty($products)) return;
                        echo '<div class="col-lg-3 col-sm-6 pb-5 pb-md-0">';
                        echo '<h4 class="section-sub-title">' . htmlspecialchars($title) . '</h4>';
                        foreach ($products as $p) {
                            $p_price = number_format($p['price'], 2);
                            $p_sale_price = !empty($p['sale_price']) ? number_format($p['sale_price'], 2) : null;
                            $p_image = !empty($p['image']) ? $p['image'] : 'assets/images/products/placeholder.webp';
                            ?>
                        <div class="product-default left-details product-widget">
                            <figure>
                                    <a href="<?php echo getProductUrl($p['slug']); ?>">
                                        <img src="<?php echo htmlspecialchars($p_image); ?>" width="74" height="74" alt="<?php echo htmlspecialchars($p['name']); ?>">
                                        <img src="<?php echo htmlspecialchars($p_image); ?>" width="74" height="74" alt="<?php echo htmlspecialchars($p['name']); ?>">
                                </a>
                            </figure>
                            <div class="product-details">
                                    <h3 class="product-title">
                                        <a href="<?php echo getProductUrl($p['slug']); ?>"><?php echo htmlspecialchars($p['name']); ?></a>
                                </h3>
                                <div class="price-box">
                                        <?php if ($p_sale_price) { ?>
                                        <span class="old-price">₹<?php echo $p_price; ?></span>
                                        <span class="product-price">₹<?php echo $p_sale_price; ?></span>
                                        <?php } else { ?>
                                        <span class="product-price">₹<?php echo $p_price; ?></span>
                                        <?php } ?>
                                </div>
                            </div>
                        </div>
                            <?php
                        }
                        echo '</div>';
                    }
                    
                    renderProductWidget($featured_products, 'Featured Products');
                    renderProductWidget($best_selling_products, 'Best Selling Products');
                    renderProductWidget($top_rated_products, 'Top Rated Products');
                    ?>
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
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/plugins.min.js"></script>
    <script src="assets/js/nouislider.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.min.js"></script>
    
    <!-- Add to Cart Handler -->
    <script>
    $(document).ready(function() {
        // Handle Add to Cart button click
        $('.add-cart').on('click', function(e) {
            e.preventDefault();
            var productId = $(this).data('product-id');
            var quantityInput = $('.product-single-qty input[data-product-id="' + productId + '"]');
            var quantity = quantityInput.length > 0 ? quantityInput.val() : 1;
            
            if (!productId) {
                alert('Product ID not found!');
                return;
            }
            
            // Disable button to prevent multiple clicks
            var $btn = $(this);
            $btn.prop('disabled', true).text('Adding...');
            
            // Add to cart via URL
            var currentUrl = window.location.href;
            window.location.href = 'cart.php?action=add&id=' + productId + '&qty=' + quantity + '&redirect=' + encodeURIComponent(currentUrl);
        });
    });
    </script>
    
    <script>(function () { function c() { var b = a.contentDocument || a.contentWindow.document; if (b) { var d = b.createElement('script'); d.innerHTML = "window.__CF$cv$params={r:'9a48e186ad7bd893',t:'MTc2NDE1NDgxNA=='};var a=document.createElement('script');a.src='../../cdn-cgi/challenge-platform/h/b/scripts/jsd/13c98df4ef2d/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);"; b.getElementsByTagName('head')[0].appendChild(d) } } if (document.body) { var a = document.createElement('iframe'); a.height = 1; a.width = 1; a.style.position = 'absolute'; a.style.top = 0; a.style.left = 0; a.style.border = 'none'; a.style.visibility = 'hidden'; document.body.appendChild(a); if ('loading' !== document.readyState) c(); else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c); else { var e = document.onreadystatechange || function () { }; document.onreadystatechange = function (b) { e(b); 'loading' !== document.readyState && (document.onreadystatechange = e, c()) } } } })();</script>
    <script defer
        src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}'
        crossorigin="anonymous"></script>
</body>
</html>