<?php 
session_start();
include "config.php"; 
require_once "includes/site-settings.php";

// Initialize wishlist if not exists
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Get dynamic settings
$site_name = getSetting('site_name', 'MySmartSCart');
$site_tagline = getSetting('site_tagline', 'Shop Smart, Live Smart!');
$site_description = getSetting('site_description', 'Your one-stop smart shopping destination.');
$site_keywords = getSetting('site_keywords', 'online shopping, best deals, electronics, fashion');
$site_favicon = getSetting('site_favicon', 'assets/images/icons/favicon.png');
$header_top_text = getSetting('header_top_text', '🔥 <b>MEGA SALE</b> - Up to 70% OFF!');
$header_top_small_text = getSetting('header_top_small_text', '* Free Shipping on Orders ₹499+');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo htmlspecialchars($site_name); ?> - <?php echo htmlspecialchars($site_tagline); ?></title>

    <meta name="keywords" content="<?php echo htmlspecialchars($site_keywords); ?>" />
    <meta name="description" content="<?php echo htmlspecialchars($site_description); ?>">
    <meta name="author" content="<?php echo htmlspecialchars($site_name); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo htmlspecialchars($site_favicon); ?>">


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
    
    <!-- Performance Optimizations -->
    <link rel="stylesheet" href="assets/css/optimizations.css">
</head>

<body>
    <div class="page-wrapper">
        <?php include "common/top-notice.php"; ?>
        <!-- Start .header -->

        <?php include "common/header.php"; ?>

        <!-- End .header -->

        <main class="main">
            <div class="home-top-container appear-animate" data-animation-name="fadeIn" data-animation-delay="100">
                <div class="container">
                    <div class="owl-carousel owl-theme home-slider" data-owl-options="{
                        'dots': true,
                        'nav': true,
                        'loop': true,
                        'autoplay': true,
                        'autoplayTimeout': 5000,
                        'autoplayHoverPause': true,
                        'smartSpeed': 1000,
                        'navText': [ '<i class=icon-left-open-big>', '<i class=icon-right-open-big>' ],
                        'responsive': {
                            '0': {
                                'items': 1
                            },
                            '768': {
                                'items': 1
                            },
                            '991': {
                                'items': 1
                            }
                        }
                    }">
                        <?php
                        // Fetch active banners from database
                        $banner_query = "SELECT * FROM banners WHERE status = 1 ORDER BY sort_order ASC";
                        $banner_result = mysqli_query($conn, $banner_query);
                        
                        if (mysqli_num_rows($banner_result) > 0) {
                            while ($banner = mysqli_fetch_assoc($banner_result)) {
                                $banner_link = !empty($banner['link']) ? $banner['link'] : '#';
                                $banner_title = !empty($banner['title']) ? $banner['title'] : 'Banner';
                        ?>
                        <div class="home-slide">
                            <figure class="w-100">
                                <a href="<?php echo htmlspecialchars($banner_link); ?>">
                                    <img src="<?php echo htmlspecialchars($banner['image']); ?>" alt="<?php echo htmlspecialchars($banner_title); ?>" class="w-100">
                                </a>
                            </figure>
                        </div>
                        <!-- End .home-slide -->
                        <?php
                            }
                        } else {
                            // Default banner if no banners in database
                        ?>
                        <div class="home-slide">
                            <figure class="w-100">
                                <img src="assets/images/products/placeholder.webp" alt="banner" class="w-100">
                            </figure>
                        </div>
                        <!-- End .home-slide -->
                        <?php
                        }
                        ?>
                    </div>
                    <!-- End .owl-carousel -->
                </div>
                <!-- End .container -->
            </div>
            <!-- End .home-top-container -->

            <div class="container">
                <section class="featured-products-section appear-animate" data-animation-name="fadeInUpShorter"
                    data-animation-delay="100">
                    <h2 class="section-title text-center d-flex align-items-center">🔥 Hot Deals
                    </h2>

                    <div class="owl-carousel owl-theme dots-top dots-small" data-owl-options="{
                            'dots': true,
                            'margin': 20,
                            'nav': false,
                            'loop': false,
                            'responsive': {
                                '0': {
                                    'items': 2
                                },
                                '768': {
                                    'items': 3
                                },
                                '991': {
                                    'items': 4
                                }
                            }
                        }">
                        <?php
                        // Fetch featured products from database
                        $featured_query = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                                          FROM products p 
                                          LEFT JOIN categories c ON p.category_id = c.id 
                                          WHERE p.featured = 1 AND p.status = 1 
                                          ORDER BY p.id DESC 
                                          LIMIT 8";
                        $featured_result = mysqli_query($conn, $featured_query);
                        
                        if (mysqli_num_rows($featured_result) > 0) {
                            while ($product = mysqli_fetch_assoc($featured_result)) {
                                // Placeholder image path
                                $placeholder_image = 'assets/images/products/placeholder.webp';
                                
                                // Get gallery images
                                $gallery_images = [];
                                if (!empty($product['gallery_images'])) {
                                    $gallery_images = json_decode($product['gallery_images'], true);
                                }
                                
                                // Default image if not set
                                $product_image = !empty($product['image']) ? $product['image'] : $placeholder_image;
                                $second_image = (!empty($gallery_images) && isset($gallery_images[1])) ? $gallery_images[1] : $product_image;
                                
                                // Calculate discount percentage
                                $discount = 0;
                                if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']) {
                                    $discount = round((($product['price'] - $product['sale_price']) / $product['price']) * 100);
                                }
                                
                                // Format prices
                                $price = number_format($product['price'], 2);
                                $sale_price = !empty($product['sale_price']) ? number_format($product['sale_price'], 2) : null;
                                
                                // Product link (SEO-friendly)
                                $product_link = getProductUrl($product['slug']);
                                $category_link = !empty($product['category_slug']) ? getCategoryUrl($product['category_slug']) : "shop.php";
                        ?>
                        <div class="product-default left-details">
                            <figure>
                                <a href="<?php echo $product_link; ?>">
                                    <img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"
                                        width="300" height="300">
                                    <?php if ($second_image != $product_image) { ?>
                                    <img src="<?php echo htmlspecialchars($second_image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"
                                        width="300" height="300">
                                    <?php } ?>
                                </a>
                                <div class="label-group">
                                    <span class="product-label label-hot">HOT</span>
                                    <?php if ($discount > 0) { ?>
                                    <span class="product-label label-sale">-<?php echo $discount; ?>%</span>
                                    <?php } ?>
                                </div>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <?php if (!empty($product['category_name'])) { ?>
                                    <a href="<?php echo $category_link; ?>" class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></a>
                                    <?php } ?>
                                </div>
                                <h3 class="product-title"> <a href="<?php echo $product_link; ?>"><?php echo htmlspecialchars($product['name']); ?></a> </h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <?php if ($sale_price) { ?>
                                    <span class="old-price">₹<?php echo $price; ?></span>
                                    <span class="product-price">₹<?php echo $sale_price; ?></span>
                                    <?php } else { ?>
                                    <span class="product-price">₹<?php echo $price; ?></span>
                                    <?php } ?>
                                </div>
                                <!-- End .price-box -->
                                <div class="product-action">
                                    <a href="#" class="btn-icon btn-add-cart product-type-simple" data-product-id="<?php echo $product['id']; ?>"><i
                                            class="icon-shopping-cart"></i><span>ADD TO CART</span></a>
                                    <?php 
                                    $is_in_wishlist = isset($_SESSION['wishlist']) && isset($_SESSION['wishlist'][$product['id']]);
                                    $wishlist_url = "wishlist-handler.php?action=" . ($is_in_wishlist ? 'remove' : 'add') . "&id=" . $product['id'] . "&redirect=" . urlencode($product_link);
                                    ?>
                                    <a href="<?php echo $wishlist_url; ?>" class="btn-icon-wish <?php echo $is_in_wishlist ? 'added' : ''; ?>" title="<?php echo $is_in_wishlist ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>"><i
                                            class="icon-heart"></i></a>
                                    <a href="<?php echo $product_link; ?>" class="btn-quickview" title="View Product"><i
                                            class="fas fa-external-link-alt"></i></a>
                                </div>
                            </div>
                            <!-- End .product-details -->
                        </div>
                        <?php
                            }
                        } else {
                            // Default products if no featured products in database
                            echo '<div class="product-default left-details">
                                <figure>
                                    <a href="shop">
                                        <img src="assets/images/products/placeholder.webp" alt="product" width="300" height="300">
                                    </a>
                                </figure>
                                <div class="product-details">
                                    <h3 class="product-title"><a href="shop">No Featured Products</a></h3>
                                    <div class="price-box">
                                        <span class="product-price">Add products to see them here</span>
                                    </div>
                                </div>
                            </div>';
                        }
                        ?>
                    </div>
                </section>
            </div>

            <div class="categories-section bg-primary">
                <div class="container">
                    <h2 class="section-title border-0 title-decorate text-center text-white d-flex align-items-center appear-animate"
                        data-animation-name="fadeInUpShorter">
                        <span>BROWSE
                            OUR
                            CATEGORIES</span>
                    </h2>
                    <div class="owl-carousel owl-theme appear-animate show-nav-hover"
                        data-animation-name="fadeInUpShorter" data-animation-delay="200" data-owl-options="{
                        'dots': false,
                        'margin': 20,
                        'loop': false,
                        'navText': [ '<i class=icon-left-open-big>', '<i class=icon-right-open-big>' ],
                        'nav': true,
                        'responsive': {
                            '0': {
                                'items': 2
                            },
                            '768': {
                                'items': 3
                            },
                            '991': {
                                'items': 4,
                                'nav': false
                            }
                        }
                    }">
                        <?php
                        // Fetch categories with product count
                        $categories_query = "SELECT c.*, COUNT(pc.product_id) as product_count
                                            FROM categories c
                                            LEFT JOIN product_categories pc ON c.id = pc.category_id
                                            LEFT JOIN products p ON pc.product_id = p.id AND p.status = 1
                                            WHERE c.status = 1 AND c.parent_id = 0
                                            GROUP BY c.id
                                            HAVING product_count > 0
                                            ORDER BY c.sort_order ASC, c.name ASC
                                            LIMIT 10";
                        $categories_result = mysqli_query($conn, $categories_query);
                        
                        if (mysqli_num_rows($categories_result) > 0) {
                            while ($category = mysqli_fetch_assoc($categories_result)) {
                                // Category image - use category image if available, otherwise use default
                                $category_image = !empty($category['image']) ? htmlspecialchars($category['image']) : 'assets/images/products/placeholder.webp';
                                $category_link = getCategoryUrl($category['slug']);
                                $product_count = (int)$category['product_count'];
                                $product_text = $product_count == 1 ? 'PRODUCT' : 'PRODUCTS';
                        ?>
                        <div class="banner banner-image">
                            <a href="<?php echo $category_link; ?>">
                                <img src="<?php echo $category_image; ?>" width="272" height="231"
                                    alt="<?php echo htmlspecialchars($category['name']); ?>">
                            </a>
                            <div class="banner-layer banner-layer-middle">
                                <h3><?php echo strtoupper(htmlspecialchars($category['name'])); ?></h3>
                                <span><?php echo $product_count; ?> <?php echo $product_text; ?></span>
                            </div>
                        </div>
                        <!-- End .banner -->
                        <?php
                            }
                        } else {
                            // Default categories if no categories in database
                            $default_categories = [
                                ['name' => 'Electronics', 'image' => 'assets/images/products/placeholder.webp', 'slug' => 'electronics'],
                                ['name' => 'Fashion', 'image' => 'assets/images/products/placeholder.webp', 'slug' => 'fashion'],
                                ['name' => 'Home & Living', 'image' => 'assets/images/products/placeholder.webp', 'slug' => 'home-living'],
                                ['name' => 'Accessories', 'image' => 'assets/images/products/placeholder.webp', 'slug' => 'accessories']
                            ];
                            foreach ($default_categories as $cat) {
                        ?>
                        <div class="banner banner-image">
                            <a href="<?php echo getCategoryUrl($cat['slug']); ?>">
                                <img src="<?php echo $cat['image']; ?>" width="272" height="231" alt="<?php echo $cat['name']; ?>">
                            </a>
                            <div class="banner-layer banner-layer-middle">
                                <h3><?php echo $cat['name']; ?></h3>
                                <span>0 PRODUCTS</span>
                            </div>
                        </div>
                        <!-- End .banner -->
                        <?php
                            }
                        }
                        ?>
                    </div>
                    <!-- End .cat-carousel -->
                </div>
                <!-- End .container -->
            </div>
            <!-- End .banners-section -->

            <div class="arrival-products-section appear-animate" data-animation-name="fadeIn"
                data-animation-delay="100">
                <div class="container">
                    <h2 class="section-title text-center d-flex align-items-center">JUST ARRIVED
                    </h2>

                    <div class="row">
                        <?php
                        // Fetch latest products (Just Arrived) from database
                        $just_arrived_query = "SELECT p.*, GROUP_CONCAT(c.name) AS category_names, GROUP_CONCAT(c.slug) AS category_slugs
                                               FROM products p 
                                               LEFT JOIN product_categories pc ON p.id = pc.product_id
                                               LEFT JOIN categories c ON pc.category_id = c.id
                                               WHERE p.status = 1 
                                               GROUP BY p.id
                                               ORDER BY p.created_at DESC 
                                               LIMIT 10";
                        $just_arrived_result = mysqli_query($conn, $just_arrived_query);
                        
                        if (mysqli_num_rows($just_arrived_result) > 0) {
                            while ($product = mysqli_fetch_assoc($just_arrived_result)) {
                                // Placeholder image path
                                $placeholder_image = 'assets/images/products/placeholder.webp';
                                
                                // Get gallery images
                                $gallery_images = [];
                                if (!empty($product['gallery_images'])) {
                                    $gallery_images = json_decode($product['gallery_images'], true);
                                }
                                
                                // Default image if not set
                                $product_image = !empty($product['image']) ? $product['image'] : $placeholder_image;
                                $second_image = (!empty($gallery_images) && isset($gallery_images[0])) ? $gallery_images[0] : $product_image;
                                
                                // Calculate discount percentage
                                $discount = 0;
                                if (!empty($product['sale_price']) && $product['sale_price'] < $product['price']) {
                                    $discount = round((($product['price'] - $product['sale_price']) / $product['price']) * 100);
                                }
                                
                                // Format prices
                                $price = number_format($product['price'], 2);
                                $sale_price = !empty($product['sale_price']) ? number_format($product['sale_price'], 2) : null;
                                
                                // Product link (SEO-friendly)
                                $product_link = getProductUrl($product['slug']);
                                
                                // Category links
                                $category_links = '';
                                if (!empty($product['category_names'])) {
                                    $names = explode(',', $product['category_names']);
                                    $slugs = explode(',', $product['category_slugs']);
                                    foreach ($names as $key => $name) {
                                        $category_links .= '<a href="' . getCategoryUrl($slugs[$key]) . '" class="product-category">' . htmlspecialchars($name) . '</a>';
                                        if ($key < count($names) - 1) {
                                            $category_links .= ', ';
                                        }
                                    }
                                }
                                
                                // Price display
                                $price_html = '';
                                if ($sale_price) {
                                    $price_html = '<span class="product-price">₹' . $price . ' – ₹' . $sale_price . '</span>';
                                } else {
                                    $price_html = '<span class="product-price">₹' . $price . '</span>';
                                }
                        ?>
                        <div class="col-6 col-lg-3 col-md-4 col-xl-5col">
                            <div class="product-default left-details">
                                <figure>
                                    <a href="<?php echo $product_link; ?>">
                                        <img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"
                                            width="300" height="300">
                                        <?php if ($second_image != $product_image) { ?>
                                        <img src="<?php echo htmlspecialchars($second_image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"
                                            width="300" height="300">
                                        <?php } ?>
                                    </a>
                                    <div class="label-group">
                                        <?php if ($product['featured']) { ?><span class="product-label label-hot">HOT</span><?php } ?>
                                        <?php if ($discount > 0) { ?><span class="product-label label-sale">-<?php echo $discount; ?>%</span><?php } ?>
                                    </div>
                                </figure>
                                <div class="product-details">
                                    <div class="category-list">
                                        <?php echo $category_links; ?>
                                    </div>
                                    <h3 class="product-title"> <a href="<?php echo $product_link; ?>"><?php echo htmlspecialchars($product['name']); ?></a> </h3>
                                    <div class="ratings-container">
                                        <div class="product-ratings">
                                            <span class="ratings" style="width:0%"></span>
                                            <!-- End .ratings -->
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div>
                                        <!-- End .product-ratings -->
                                    </div>
                                    <!-- End .product-container -->
                                    <div class="price-box">
                                        <?php echo $price_html; ?>
                                    </div>
                                    <!-- End .price-box -->
                                </div>
                                <!-- End .product-details -->
                            </div>
                        </div>
                        <?php
                            }
                        } else {
                            echo '<div class="col-12"><p class="text-center">No products found.</p></div>';
                        }
                        ?>
                    </div>

                    <hr class="mt-1 mb-4">
                </div>
            </div>

            <div class="container">
                <div class="product-widgets row pb-2 appear-animate" data-animation-name="fadeIn"
                    data-animation-delay="100">
                    <div class="col-md-4 col-sm-6 pb-5">
                        <h4 class="section-title border-0 text-left text-uppercase">Best Selling Products</h4>
                        <div class="heading-spacer"></div>
                        <?php
                        // Fetch Best Selling Products
                        $best_selling_query = "SELECT p.*, GROUP_CONCAT(c.name) AS category_names, GROUP_CONCAT(c.slug) AS category_slugs
                                               FROM products p 
                                               LEFT JOIN product_categories pc ON p.id = pc.product_id
                                               LEFT JOIN categories c ON pc.category_id = c.id
                                               WHERE p.best_selling = 1 AND p.status = 1 
                                               GROUP BY p.id
                                               ORDER BY p.created_at DESC 
                                               LIMIT 3";
                        $best_selling_result = mysqli_query($conn, $best_selling_query);
                        
                        if (mysqli_num_rows($best_selling_result) > 0) {
                            while ($product = mysqli_fetch_assoc($best_selling_result)) {
                                // Placeholder image path
                                $placeholder_image = 'assets/images/products/placeholder.webp';
                                
                                // Get gallery images
                                $gallery_images = [];
                                if (!empty($product['gallery_images'])) {
                                    $gallery_images = json_decode($product['gallery_images'], true);
                                }
                                
                                // Default image if not set
                                $product_image = !empty($product['image']) ? $product['image'] : $placeholder_image;
                                $second_image = (!empty($gallery_images) && isset($gallery_images[0])) ? $gallery_images[0] : $product_image;
                                
                                // Format prices
                                $price = number_format($product['price'], 2);
                                $sale_price = !empty($product['sale_price']) ? number_format($product['sale_price'], 2) : null;
                                
                                // Product link (SEO-friendly)
                                $product_link = getProductUrl($product['slug']);
                                
                                // Category links
                                $category_links = '';
                                if (!empty($product['category_names'])) {
                                    $names = explode(',', $product['category_names']);
                                    $slugs = explode(',', $product['category_slugs']);
                                    foreach ($names as $key => $name) {
                                        $category_links .= '<a href="' . getCategoryUrl($slugs[$key]) . '" class="product-category">' . htmlspecialchars($name) . '</a>';
                                        if ($key < count($names) - 1) {
                                            $category_links .= ', ';
                                        }
                                    }
                                }
                                
                                // Price display
                                $price_html = '';
                                if ($sale_price) {
                                    $price_html = '<span class="product-price">₹' . $price . ' – ₹' . $sale_price . '</span>';
                                } else {
                                    $price_html = '<span class="product-price">₹' . $price . '</span>';
                                }
                        ?>
                        <div class="product-default left-details product-widget mb-2">
                            <figure>
                                <a href="<?php echo $product_link; ?>">
                                    <img src="<?php echo htmlspecialchars($product_image); ?>" width="175"
                                        height="175" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                    <?php if ($second_image != $product_image) { ?>
                                    <img src="<?php echo htmlspecialchars($second_image); ?>" width="175"
                                        height="175" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                    <?php } ?>
                                </a>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <?php echo $category_links; ?>
                                </div>
                                <h3 class="product-title"> <a href="<?php echo $product_link; ?>"><?php echo htmlspecialchars($product['name']); ?></a> </h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <?php echo $price_html; ?>
                                </div>
                                <!-- End .price-box -->
                            </div>
                            <!-- End .product-details -->
                        </div>
                        <?php
                            }
                        } else {
                            echo '<p class="text-muted">No best selling products found.</p>';
                        }
                        ?>
                    </div>
                    <div class="col-md-4 col-sm-6 pb-5">
                        <h4 class="section-title border-0 text-left text-uppercase">Top Rated Products</h4>
                        <div class="heading-spacer"></div>
                        <?php
                        // Fetch Top Rated Products
                        $top_rated_query = "SELECT p.*, GROUP_CONCAT(c.name) AS category_names, GROUP_CONCAT(c.slug) AS category_slugs
                                            FROM products p 
                                            LEFT JOIN product_categories pc ON p.id = pc.product_id
                                            LEFT JOIN categories c ON pc.category_id = c.id
                                            WHERE p.top_rated = 1 AND p.status = 1 
                                            GROUP BY p.id
                                            ORDER BY p.created_at DESC 
                                            LIMIT 3";
                        $top_rated_result = mysqli_query($conn, $top_rated_query);
                        
                        if (mysqli_num_rows($top_rated_result) > 0) {
                            while ($product = mysqli_fetch_assoc($top_rated_result)) {
                                // Placeholder image path
                                $placeholder_image = 'assets/images/products/placeholder.webp';
                                
                                // Get gallery images
                                $gallery_images = [];
                                if (!empty($product['gallery_images'])) {
                                    $gallery_images = json_decode($product['gallery_images'], true);
                                }
                                
                                // Default image if not set
                                $product_image = !empty($product['image']) ? $product['image'] : $placeholder_image;
                                $second_image = (!empty($gallery_images) && isset($gallery_images[0])) ? $gallery_images[0] : $product_image;
                                
                                // Format prices
                                $price = number_format($product['price'], 2);
                                $sale_price = !empty($product['sale_price']) ? number_format($product['sale_price'], 2) : null;
                                
                                // Product link (SEO-friendly)
                                $product_link = getProductUrl($product['slug']);
                                
                                // Category links
                                $category_links = '';
                                if (!empty($product['category_names'])) {
                                    $names = explode(',', $product['category_names']);
                                    $slugs = explode(',', $product['category_slugs']);
                                    foreach ($names as $key => $name) {
                                        $category_links .= '<a href="' . getCategoryUrl($slugs[$key]) . '" class="product-category">' . htmlspecialchars($name) . '</a>';
                                        if ($key < count($names) - 1) {
                                            $category_links .= ', ';
                                        }
                                    }
                                }
                                
                                // Price display
                                $price_html = '';
                                if ($sale_price) {
                                    $price_html = '<span class="product-price">₹' . $price . ' – ₹' . $sale_price . '</span>';
                                } else {
                                    $price_html = '<span class="product-price">₹' . $price . '</span>';
                                }
                        ?>
                        <div class="product-default left-details product-widget mb-2">
                            <figure>
                                <a href="<?php echo $product_link; ?>">
                                    <img src="<?php echo htmlspecialchars($product_image); ?>" width="175"
                                        height="175" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                    <?php if ($second_image != $product_image) { ?>
                                    <img src="<?php echo htmlspecialchars($second_image); ?>" width="175"
                                        height="175" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                    <?php } ?>
                                </a>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <?php echo $category_links; ?>
                                </div>
                                <h3 class="product-title"> <a href="<?php echo $product_link; ?>"><?php echo htmlspecialchars($product['name']); ?></a> </h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <?php echo $price_html; ?>
                                </div>
                                <!-- End .price-box -->
                            </div>
                            <!-- End .product-details -->
                        </div>
                        <?php
                            }
                        } else {
                            echo '<p class="text-muted">No top rated products found.</p>';
                        }
                        ?>
                    </div>
                    <div class="col-md-4 col-sm-6 pb-5">
                        <h4 class="section-title border-0 text-left text-uppercase">Featured Products</h4>
                        <div class="heading-spacer"></div>
                        <?php
                        // Fetch Featured Products (for widget section)
                        $featured_widget_query = "SELECT p.*, GROUP_CONCAT(c.name) AS category_names, GROUP_CONCAT(c.slug) AS category_slugs
                                                  FROM products p 
                                                  LEFT JOIN product_categories pc ON p.id = pc.product_id
                                                  LEFT JOIN categories c ON pc.category_id = c.id
                                                  WHERE p.featured = 1 AND p.status = 1 
                                                  GROUP BY p.id
                                                  ORDER BY p.created_at DESC 
                                                  LIMIT 3";
                        $featured_widget_result = mysqli_query($conn, $featured_widget_query);
                        
                        if (mysqli_num_rows($featured_widget_result) > 0) {
                            while ($product = mysqli_fetch_assoc($featured_widget_result)) {
                                // Placeholder image path
                                $placeholder_image = 'assets/images/products/placeholder.webp';
                                
                                // Get gallery images
                                $gallery_images = [];
                                if (!empty($product['gallery_images'])) {
                                    $gallery_images = json_decode($product['gallery_images'], true);
                                }
                                
                                // Default image if not set
                                $product_image = !empty($product['image']) ? $product['image'] : $placeholder_image;
                                $second_image = (!empty($gallery_images) && isset($gallery_images[0])) ? $gallery_images[0] : $product_image;
                                
                                // Format prices
                                $price = number_format($product['price'], 2);
                                $sale_price = !empty($product['sale_price']) ? number_format($product['sale_price'], 2) : null;
                                
                                // Product link (SEO-friendly)
                                $product_link = getProductUrl($product['slug']);
                                
                                // Category links
                                $category_links = '';
                                if (!empty($product['category_names'])) {
                                    $names = explode(',', $product['category_names']);
                                    $slugs = explode(',', $product['category_slugs']);
                                    foreach ($names as $key => $name) {
                                        $category_links .= '<a href="' . getCategoryUrl($slugs[$key]) . '" class="product-category">' . htmlspecialchars($name) . '</a>';
                                        if ($key < count($names) - 1) {
                                            $category_links .= ', ';
                                        }
                                    }
                                }
                                
                                // Price display
                                $price_html = '';
                                if ($sale_price) {
                                    $price_html = '<span class="product-price">₹' . $price . ' – ₹' . $sale_price . '</span>';
                                } else {
                                    $price_html = '<span class="product-price">₹' . $price . '</span>';
                                }
                        ?>
                        <div class="product-default left-details product-widget mb-2">
                            <figure>
                                <a href="<?php echo $product_link; ?>">
                                    <img src="<?php echo htmlspecialchars($product_image); ?>" width="175"
                                        height="175" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                    <?php if ($second_image != $product_image) { ?>
                                    <img src="<?php echo htmlspecialchars($second_image); ?>" width="175"
                                        height="175" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                    <?php } ?>
                                </a>
                            </figure>
                            <div class="product-details">
                                <div class="category-list">
                                    <?php echo $category_links; ?>
                                </div>
                                <h3 class="product-title"> <a href="<?php echo $product_link; ?>"><?php echo htmlspecialchars($product['name']); ?></a> </h3>
                                <div class="ratings-container">
                                    <div class="product-ratings">
                                        <span class="ratings" style="width:0%"></span>
                                        <!-- End .ratings -->
                                        <span class="tooltiptext tooltip-top"></span>
                                    </div>
                                    <!-- End .product-ratings -->
                                </div>
                                <!-- End .product-container -->
                                <div class="price-box">
                                    <?php echo $price_html; ?>
                                </div>
                                <!-- End .price-box -->
                            </div>
                            <!-- End .product-details -->
                        </div>
                        <?php
                            }
                        } else {
                            echo '<p class="text-muted">No featured products found.</p>';
                        }
                        ?>
                    </div>
                </div>
                <!-- End .product-widgets -->

            </div>
        </main>
        <!-- Start .footer -->
        <?php include("common/footer.php") ?>

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

    <!-- <div class="newsletter-popup mfp-hide bg-img" id="newsletter-popup-form"
        style="background: #f1f1f1 no-repeat center/cover url(assets/images/newsletter_popup_bg.jpg)">
        <div class="newsletter-popup-content">
            <img src="assets/images/logo.png" alt="Logo" class="logo-newsletter" width="111" height="44">
            <h2>Subscribe to newsletter</h2>

            <p>
                Subscribe to our mailing list to receive updates on new arrivals, special offers and our
                promotions.
            </p>

            <form action="#">
                <div class="input-group">
                    <input type="email" class="form-control" id="newsletter-email" name="newsletter-email"
                        placeholder="Your email address" required />
                    <input type="submit" class="btn btn-primary" value="Submit" />
                </div>
            </form>
            <div class="newsletter-subscribe">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" value="0" id="show-again" />
                    <label for="show-again" class="custom-control-label">
                        Don't show this popup again
                    </label>
                </div>
            </div>
        </div>
         End .newsletter-popup-content

        <button title="Close (Esc)" type="button" class="mfp-close">
            ×
        </button>
    </div> -->
    <!-- End .newsletter-popup -->

    <a id="scroll-top" href="#top" title="Top" role="button"><i class="icon-angle-up"></i></a>

    <!-- Plugins JS File -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/plugins.min.js"></script>
    <script src="assets/js/jquery.appear.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.min.js"></script>
    
    <!-- Performance Optimizations -->
    <script src="assets/js/optimizations.js"></script>

    <!-- Home Slider Initialization -->
    <script>
        $(document).ready(function () {
            if ($('.home-slider').length) {
                $('.home-slider').owlCarousel({
                    dots: true,
                    nav: true,
                    loop: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    smartSpeed: 1000,
                    navText: ['<i class="icon-left-open-big"></i>', '<i class="icon-right-open-big"></i>'],
                    responsive: {
                        0: {
                            items: 1
                        },
                        768: {
                            items: 1
                        },
                        991: {
                            items: 1
                        }
                    }
                });
            }
        });
    </script>

    <script>(function () { function c() { var b = a.contentDocument || a.contentWindow.document; if (b) { var d = b.createElement('script'); d.innerHTML = "window.__CF$cv$params={r:'9a48e152b845e1dd',t:'MTc2NDE1NDgwNg=='};var a=document.createElement('script');a.src='../../cdn-cgi/challenge-platform/h/b/scripts/jsd/13c98df4ef2d/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);"; b.getElementsByTagName('head')[0].appendChild(d) } } if (document.body) { var a = document.createElement('iframe'); a.height = 1; a.width = 1; a.style.position = 'absolute'; a.style.top = 0; a.style.left = 0; a.style.border = 'none'; a.style.visibility = 'hidden'; document.body.appendChild(a); if ('loading' !== document.readyState) c(); else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c); else { var e = document.onreadystatechange || function () { }; document.onreadystatechange = function (b) { e(b); 'loading' !== document.readyState && (document.onreadystatechange = e, c()) } } } })();</script>
    <script defer
        src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}'
        crossorigin="anonymous"></script>
</body>

</html>