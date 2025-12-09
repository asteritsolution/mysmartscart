<?php 
session_start();
include "config.php"; 

// Initialize wishlist if not exists
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Get category filter from URL
$category_slug = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$category_filter = '';
$category_name = 'All Products';

if (!empty($category_slug)) {
    $cat_query = "SELECT * FROM categories WHERE slug = '$category_slug' AND status = 1 LIMIT 1";
    $cat_result = mysqli_query($conn, $cat_query);
    if (mysqli_num_rows($cat_result) > 0) {
        $category_data = mysqli_fetch_assoc($cat_result);
        $category_id = $category_data['id'];
        $category_name = $category_data['name'];
        $category_filter = "AND pc.category_id = $category_id";
    }
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 12;
$offset = ($page - 1) * $per_page;

// Get total products count
$count_query = "SELECT COUNT(DISTINCT p.id) as total 
                FROM products p 
                LEFT JOIN product_categories pc ON p.id = pc.product_id
                WHERE p.status = 1 $category_filter";
$count_result = mysqli_query($conn, $count_query);
$total_products = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_products / $per_page);

// Get products
$products_query = "SELECT p.*, GROUP_CONCAT(c.name) AS category_names, GROUP_CONCAT(c.slug) AS category_slugs
                   FROM products p 
                   LEFT JOIN product_categories pc ON p.id = pc.product_id
                   LEFT JOIN categories c ON pc.category_id = c.id
                   WHERE p.status = 1 $category_filter
                   GROUP BY p.id
                   ORDER BY p.created_at DESC
                   LIMIT $per_page OFFSET $offset";
$products_result = mysqli_query($conn, $products_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Shop - MySmartSCart | Best Deals on Trending Products</title>

    <meta name="keywords" content="MySmartSCart, Shop, Online Shopping, Best Deals, Electronics, Fashion, Gadgets" />
    <meta name="description" content="Shop the latest trending products at MySmartSCart. Discover amazing deals on electronics, fashion, home essentials & more with fast delivery across India!">
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
</head>

<body>
    <div class="page-wrapper">
        <div class="top-notice text-white">
            <div class="container text-center">
                <h5 class="d-inline-block mb-0">🔥 <b>MEGA SALE</b> - Up to 70% OFF!</h5>
                <a href="about.php" class="category">ABOUT US</a>
                <a href="shop.php" class="category ml-2 mr-3">SHOP NOW</a>
                <small>* Free Shipping on Orders ₹499+</small>
                <button title="Close (Esc)" type="button" class="mfp-close">×</button>
            </div>
            <!-- End .container -->
        </div>
        <!-- End .top-notice -->

        <?php include "common/header.php"; ?>
        <!-- End .header -->

        <main class="main">
            <div class="category-banner"
                style="background-image: url(assets/images/demoes/demo7/banners/banner-top-2.jpg)">
                <div class="container">
                    <div class="promo-content d-sm-flex align-items-center">
                        <div>
                            <h2 class="m-b-1">MySmartSCart</h2>
                            <h3 class="mb-0 ml-0">Shop Smart, Save Big!</h3>
                        </div>
                        <hr class="divider-short-thick">
                        <a href="shop.php" class="btn btn-light">Shop Now <i
                                class="fas fa-long-arrow-alt-right ml-2 pl-1"></i></a>
                    </div>
                    <!-- End .category-banner-content -->
                </div>
            </div>
            <!-- End .category-banner -->

            <nav aria-label="breadcrumb" class="breadcrumb-nav mb-3">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($category_name); ?></li>
                    </ol>
                </div>
            </nav>

            <div class="container">
                <div class="row">
                    <div class="col-lg-9 main-content">
                        <nav class="toolbox sticky-header" data-sticky-options="{'mobile': true}">
                            <div class="toolbox-left">
                                <a href="#" class="sidebar-toggle"><svg data-name="Layer 3" id="Layer_3"
                                        viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                        <line x1="15" x2="26" y1="9" y2="9" class="cls-1"></line>
                                        <line x1="6" x2="9" y1="9" y2="9" class="cls-1"></line>
                                        <line x1="23" x2="26" y1="16" y2="16" class="cls-1"></line>
                                        <line x1="6" x2="17" y1="16" y2="16" class="cls-1"></line>
                                        <line x1="17" x2="26" y1="23" y2="23" class="cls-1"></line>
                                        <line x1="6" x2="11" y1="23" y2="23" class="cls-1"></line>
                                        <path
                                            d="M14.5,8.92A2.6,2.6,0,0,1,12,11.5,2.6,2.6,0,0,1,9.5,8.92a2.5,2.5,0,0,1,5,0Z"
                                            class="cls-2"></path>
                                        <path d="M22.5,15.92a2.5,2.5,0,1,1-5,0,2.5,2.5,0,0,1,5,0Z" class="cls-2"></path>
                                        <path d="M21,16a1,1,0,1,1-2,0,1,1,0,0,1,2,0Z" class="cls-3"></path>
                                        <path
                                            d="M16.5,22.92A2.6,2.6,0,0,1,14,25.5a2.6,2.6,0,0,1-2.5-2.58,2.5,2.5,0,0,1,5,0Z"
                                            class="cls-2"></path>
                                    </svg>
                                    <span>Filter</span>
                                </a>

                                <div class="toolbox-item toolbox-sort">
                                    <label>Sort By:</label>

                                    <div class="select-custom">
                                        <select name="orderby" class="form-control">
                                            <option value="menu_order" selected="selected">Default sorting</option>
                                            <option value="popularity">Sort by popularity</option>
                                            <option value="rating">Sort by average rating</option>
                                            <option value="date">Sort by newness</option>
                                            <option value="price">Sort by price: low to high</option>
                                            <option value="price-desc">Sort by price: high to low</option>
                                        </select>
                                    </div>
                                    <!-- End .select-custom -->


                                </div>
                                <!-- End .toolbox-item -->
                            </div>
                            <!-- End .toolbox-left -->

                            <div class="toolbox-right">
                                <div class="toolbox-item toolbox-show">
                                    <label>Show:</label>

                                    <div class="select-custom">
                                        <select name="count" class="form-control" onchange="window.location.href='?per_page='+this.value+'<?php echo $category_param; ?>'">
                                            <option value="12" <?php echo ($per_page == 12) ? 'selected' : ''; ?>>12</option>
                                            <option value="24" <?php echo ($per_page == 24) ? 'selected' : ''; ?>>24</option>
                                            <option value="36" <?php echo ($per_page == 36) ? 'selected' : ''; ?>>36</option>
                                        </select>
                                    </div>
                                    <!-- End .select-custom -->
                                </div>
                                <!-- End .toolbox-item -->

                                <div class="toolbox-item layout-modes">
                                    <a href="category.html" class="layout-btn btn-grid active" title="Grid">
                                        <i class="icon-mode-grid"></i>
                                    </a>
                                    <a href="category-list.html" class="layout-btn btn-list" title="List">
                                        <i class="icon-mode-list"></i>
                                    </a>
                                </div>
                                <!-- End .layout-modes -->
                            </div>
                            <!-- End .toolbox-right -->
                        </nav>

                        <div class="row products-group">
                            <?php
                            if (mysqli_num_rows($products_result) > 0) {
                                while ($product = mysqli_fetch_assoc($products_result)) {
                                    // Get gallery images
                                    $gallery_images = [];
                                    if (!empty($product['gallery_images'])) {
                                        $gallery_images = json_decode($product['gallery_images'], true);
                                    }
                                    // Placeholder image path
                                    $placeholder_image = 'assets/images/products/placeholder.webp';
                                    
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
                                    
                                    // Product link
                                    $product_link = "product.php?slug=" . htmlspecialchars($product['slug']);
                                    
                                    // Category links
                                    $category_links = '';
                                    if (!empty($product['category_names'])) {
                                        $category_names = explode(',', $product['category_names']);
                                        $category_slugs = explode(',', $product['category_slugs']);
                                        $cat_links_array = [];
                                        foreach ($category_names as $idx => $cat_name) {
                                            $cat_slug = isset($category_slugs[$idx]) ? trim($category_slugs[$idx]) : '';
                                            if (!empty($cat_slug)) {
                                                $cat_links_array[] = '<a href="shop.php?category=' . htmlspecialchars($cat_slug) . '" class="product-category">' . htmlspecialchars(trim($cat_name)) . '</a>';
                                            }
                                        }
                                        $category_links = implode(', ', $cat_links_array);
                                    }
                            ?>
                            <div class="col-6 col-sm-4">
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
                                        <?php if (!empty($category_links)) { ?>
                                        <div class="category-list">
                                            <?php echo $category_links; ?>
                                        </div>
                                        <?php } ?>
                                        <h3 class="product-title">
                                            <a href="<?php echo $product_link; ?>"><?php echo htmlspecialchars($product['name']); ?></a>
                                        </h3>
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
                                            <span class="product-price">₹<?php echo $sale_price; ?></span>
                                            <span class="old-price">₹<?php echo $price; ?></span>
                                            <?php } else { ?>
                                            <span class="product-price">₹<?php echo $price; ?></span>
                                            <?php } ?>
                                        </div>
                                        <!-- End .price-box -->
                                        <div class="product-action">
                                            <a href="<?php echo $product_link; ?>" class="btn-icon btn-add-cart product-type-simple"><i
                                                    class="icon-shopping-cart"></i><span>ADD TO CART</span></a>
                                            <?php 
                                            $is_in_wishlist = isset($_SESSION['wishlist']) && isset($_SESSION['wishlist'][$product['id']]);
                                            $wishlist_url = "wishlist-handler.php?action=" . ($is_in_wishlist ? 'remove' : 'add') . "&id=" . $product['id'] . "&redirect=" . urlencode($product_link);
                                            ?>
                                            <a href="<?php echo $wishlist_url; ?>" class="btn-icon-wish <?php echo $is_in_wishlist ? 'added' : ''; ?>" title="<?php echo $is_in_wishlist ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>"><i
                                                    class="icon-heart"></i></a>
                                            <a href="<?php echo $product_link; ?>" class="btn-quickview"
                                                title="Quick View"><i class="fas fa-external-link-alt"></i></a>
                                        </div>
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

                        <nav class="toolbox toolbox-pagination">
                            <div class="toolbox-item toolbox-show">
                                <label>Show:</label>

                                <div class="select-custom">
                                    <select name="count" class="form-control">
                                        <option value="12">12</option>
                                        <option value="24">24</option>
                                        <option value="36">36</option>
                                    </select>
                                </div>
                                <!-- End .select-custom -->
                            </div>
                            <!-- End .toolbox-item -->

                            <ul class="pagination toolbox-item">
                                <?php
                                $category_param = !empty($category_slug) ? "&category=" . urlencode($category_slug) : "";
                                $per_page_param = "&per_page=" . $per_page;
                                
                                // Previous button
                                if ($page > 1) {
                                    echo '<li class="page-item"><a class="page-link page-link-btn" href="?page=' . ($page - 1) . $category_param . $per_page_param . '"><i class="icon-angle-left"></i></a></li>';
                                } else {
                                    echo '<li class="page-item disabled"><a class="page-link page-link-btn" href="#"><i class="icon-angle-left"></i></a></li>';
                                }
                                
                                // Page numbers
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);
                                
                                if ($start_page > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?page=1' . $category_param . $per_page_param . '">1</a></li>';
                                    if ($start_page > 2) {
                                        echo '<li class="page-item"><span class="page-link">...</span></li>';
                                    }
                                }
                                
                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    $active = ($i == $page) ? 'active' : '';
                                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . $category_param . $per_page_param . '">' . $i;
                                    if ($i == $page) {
                                        echo ' <span class="sr-only">(current)</span>';
                                    }
                                    echo '</a></li>';
                                }
                                
                                if ($end_page < $total_pages) {
                                    if ($end_page < $total_pages - 1) {
                                        echo '<li class="page-item"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . $category_param . $per_page_param . '">' . $total_pages . '</a></li>';
                                }
                                
                                // Next button
                                if ($page < $total_pages) {
                                    echo '<li class="page-item"><a class="page-link page-link-btn" href="?page=' . ($page + 1) . $category_param . $per_page_param . '"><i class="icon-angle-right"></i></a></li>';
                                } else {
                                    echo '<li class="page-item disabled"><a class="page-link page-link-btn" href="#"><i class="icon-angle-right"></i></a></li>';
                                }
                                ?>
                            </ul>
                        </nav>
                    </div>
                    <!-- End .col-lg-9 -->

                    <div class="sidebar-overlay"></div>
                    <aside class="sidebar-shop col-lg-3 order-lg-first mobile-sidebar">
                        <div class="sidebar-wrapper">
                            <div class="widget">
                                <h3 class="widget-title">
                                    <a data-toggle="collapse" href="#widget-body-2" role="button" aria-expanded="true"
                                        aria-controls="widget-body-2">Categories</a>
                                </h3>

                                <div class="collapse show" id="widget-body-2">
                                    <div class="widget-body">
                                        <ul class="cat-list">
                                            <li>
                                                <a href="shop.php" class="<?php echo empty($category_slug) ? 'active' : ''; ?>">
                                                    All Products<span class="products-count">(<?php echo $total_products; ?>)</span>
                                                </a>
                                            </li>
                                            <?php
                                            // Fetch categories with product count
                                            $sidebar_cats_query = "SELECT c.*, COUNT(DISTINCT pc.product_id) as product_count
                                                                  FROM categories c
                                                                  LEFT JOIN product_categories pc ON c.id = pc.category_id
                                                                  LEFT JOIN products p ON pc.product_id = p.id AND p.status = 1
                                                                  WHERE c.status = 1 AND c.parent_id = 0
                                                                  GROUP BY c.id
                                                                  HAVING product_count > 0
                                                                  ORDER BY c.sort_order ASC, c.name ASC";
                                            $sidebar_cats_result = mysqli_query($conn, $sidebar_cats_query);
                                            
                                            if (mysqli_num_rows($sidebar_cats_result) > 0) {
                                                while ($cat = mysqli_fetch_assoc($sidebar_cats_result)) {
                                                    $is_active = ($category_slug == $cat['slug']) ? 'active' : '';
                                                    $cat_link = "shop.php?category=" . htmlspecialchars($cat['slug']);
                                            ?>
                                            <li>
                                                <a href="<?php echo $cat_link; ?>" class="<?php echo $is_active; ?>">
                                                    <?php echo htmlspecialchars($cat['name']); ?><span class="products-count">(<?php echo $cat['product_count']; ?>)</span>
                                                </a>
                                            </li>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <!-- End .widget-body -->
                                </div>
                                <!-- End .collapse -->
                            </div>
                            <!-- End .widget -->

                            <?php
                            // Get min and max prices from database
                            $price_query = "SELECT MIN(price) as min_price, MAX(price) as max_price FROM products WHERE status = 1";
                            $price_result = mysqli_query($conn, $price_query);
                            $price_data = mysqli_fetch_assoc($price_result);
                            $min_price = floor($price_data['min_price'] ?? 0);
                            $max_price = ceil($price_data['max_price'] ?? 1000);
                            ?>
                            <div class="widget widget-price">
                                <h3 class="widget-title">
                                    <a data-toggle="collapse" href="#widget-body-3" role="button" aria-expanded="true"
                                        aria-controls="widget-body-3">Price</a>
                                </h3>

                                <div class="collapse show" id="widget-body-3">
                                    <div class="widget-body">
                                        <form action="#" method="GET">
                                            <div class="price-slider-wrapper">
                                                <div id="price-slider" data-min="<?php echo $min_price; ?>" data-max="<?php echo $max_price; ?>"></div>
                                                <!-- End #price-slider -->
                                            </div>
                                            <!-- End .price-slider-wrapper -->

                                            <div
                                                class="filter-price-action d-flex align-items-center justify-content-between flex-wrap">
                                                <div class="filter-price-text">
                                                    Price: ₹<span id="filter-price-min"><?php echo $min_price; ?></span> - ₹<span id="filter-price-max"><?php echo $max_price; ?></span>
                                                </div>
                                                <!-- End .filter-price-text -->

                                                <button type="submit" class="btn btn-primary">Filter</button>
                                            </div>
                                            <!-- End .filter-price-action -->
                                        </form>
                                    </div>
                                    <!-- End .widget-body -->
                                </div>
                                <!-- End .collapse -->
                            </div>
                            <!-- End .widget -->

                            <!-- Color, Sizes, and Brands filters removed as they are not applicable for KRC Woollens products -->
                        </div>
                        <!-- End .sidebar-wrapper -->
                    </aside>
                    <!-- End .col-lg-3 -->
                </div>
                <!-- End .row -->
            </div>
            <!-- End .container -->

            <div class="mb-3"></div>
            <!-- margin -->
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
    <script>(function () { function c() { var b = a.contentDocument || a.contentWindow.document; if (b) { var d = b.createElement('script'); d.innerHTML = "window.__CF$cv$params={r:'9a48e1630f05e1dd',t:'MTc2NDE1NDgwOA=='};var a=document.createElement('script');a.src='../../cdn-cgi/challenge-platform/h/b/scripts/jsd/13c98df4ef2d/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);"; b.getElementsByTagName('head')[0].appendChild(d) } } if (document.body) { var a = document.createElement('iframe'); a.height = 1; a.width = 1; a.style.position = 'absolute'; a.style.top = 0; a.style.left = 0; a.style.border = 'none'; a.style.visibility = 'hidden'; document.body.appendChild(a); if ('loading' !== document.readyState) c(); else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c); else { var e = document.onreadystatechange || function () { }; document.onreadystatechange = function (b) { e(b); 'loading' !== document.readyState && (document.onreadystatechange = e, c()) } } } })();</script>
    <script defer
        src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon='{"version":"2024.11.0","token":"ecd4920e43e14654b78e65dbf8311922","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}'
        crossorigin="anonymous"></script>
</body>
</html>