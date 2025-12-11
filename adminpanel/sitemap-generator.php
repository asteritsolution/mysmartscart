<?php
/**
 * MySmartSCart - Sitemap Generator (Admin Access)
 * Manually trigger sitemap generation from admin panel
 */

session_start();
include "config.php";

// Check admin login
checkAdminLogin();
$admin = getAdminUser();

$page_title = "Sitemap Generator";
include "includes/header.php";
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="content-card">
                    <h2><i class="fas fa-sitemap"></i> Sitemap Generator</h2>
                    <p class="text-muted">Generate and view your website's XML sitemap automatically.</p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Automatic Generation:</strong> Your sitemap is automatically generated when accessed at <code>/sitemap.xml</code>. No manual action needed!
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-link"></i> Sitemap URLs</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Public Sitemap:</strong></p>
                                    <p>
                                        <a href="../sitemap.xml" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-external-link-alt"></i> View Sitemap
                                        </a>
                                        <code>http://localhost/mysmartscart/sitemap.xml</code>
                                    </p>
                                    
                                    <hr>
                                    
                                    <p><strong>For Production:</strong></p>
                                    <p>Update your <code>robots.txt</code> file with your production domain:</p>
                                    <pre class="bg-light p-2"><code>Sitemap: https://yourdomain.com/sitemap.xml</code></pre>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> What's Included</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success"></i> Homepage</li>
                                        <li><i class="fas fa-check text-success"></i> All Static Pages</li>
                                        <li><i class="fas fa-check text-success"></i> All Categories</li>
                                        <li><i class="fas fa-check text-success"></i> All Products</li>
                                        <li><i class="fas fa-check text-success"></i> Policy Pages</li>
                                    </ul>
                                    
                                    <hr>
                                    
                                    <p><strong>Statistics:</strong></p>
                                    <?php
                                    // Count products
                                    $products_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE status = 1");
                                    $products_total = mysqli_fetch_assoc($products_count)['total'];
                                    
                                    // Count categories
                                    $categories_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM categories WHERE status = 1");
                                    $categories_total = mysqli_fetch_assoc($categories_count)['total'];
                                    
                                    // Static pages count
                                    $static_pages = 10; // homepage, shop, about, contact, 5 policy pages, login, forgot-password
                                    $total_urls = $static_pages + $products_total + $categories_total;
                                    ?>
                                    <ul class="list-unstyled">
                                        <li>Static Pages: <strong><?php echo $static_pages; ?></strong></li>
                                        <li>Categories: <strong><?php echo $categories_total; ?></strong></li>
                                        <li>Products: <strong><?php echo $products_total; ?></strong></li>
                                        <li class="mt-2"><strong>Total URLs: <?php echo $total_urls; ?></strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-cog"></i> SEO Tips</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li><strong>Submit to Google Search Console:</strong> Add your sitemap URL to Google Search Console for better indexing.</li>
                                <li><strong>Update robots.txt:</strong> Make sure your <code>robots.txt</code> includes the sitemap URL.</li>
                                <li><strong>Regular Updates:</strong> The sitemap automatically updates when you add/update products or categories.</li>
                                <li><strong>Priority & Frequency:</strong> Homepage has highest priority (1.0), products have 0.9, categories have 0.8.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>

