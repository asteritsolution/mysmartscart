<?php
/**
 * MySmartSCart - Automatic Sitemap Generator
 * Generates XML sitemap for all pages, products, and categories
 * Access: http://localhost/mysmartscart/sitemap.xml
 */

// Start output buffering to prevent any output before XML
ob_start();

include "config.php";
require_once "includes/site-settings.php";

// Clear any output that might have been generated
ob_clean();

// Get base URL
$base_url = getBaseUrl();
// Remove trailing slash
$base_url = rtrim($base_url, '/');

// Set content type to XML (must be before any output)
header('Content-Type: application/xml; charset=utf-8');

// Start XML output
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Function to output URL entry
function outputUrl($loc, $lastmod = null, $changefreq = 'monthly', $priority = '0.8') {
    global $base_url;
    $loc = htmlspecialchars($base_url . '/' . ltrim($loc, '/'));
    $lastmod = $lastmod ? date('Y-m-d', strtotime($lastmod)) : date('Y-m-d');
    
    echo "  <url>\n";
    echo "    <loc>{$loc}</loc>\n";
    echo "    <lastmod>{$lastmod}</lastmod>\n";
    echo "    <changefreq>{$changefreq}</changefreq>\n";
    echo "    <priority>{$priority}</priority>\n";
    echo "  </url>\n";
}

// =====================================================
// STATIC PAGES (High Priority)
// =====================================================

// Homepage (Highest Priority)
outputUrl('', date('Y-m-d'), 'daily', '1.0');

// Main Pages
outputUrl('shop', date('Y-m-d'), 'daily', '0.9');
outputUrl('about', date('Y-m-d'), 'weekly', '0.8');
outputUrl('contact', date('Y-m-d'), 'monthly', '0.8');

// Policy Pages
outputUrl('terms-and-conditions', date('Y-m-d'), 'monthly', '0.6');
outputUrl('privacy-policy', date('Y-m-d'), 'monthly', '0.6');
outputUrl('refund-policy', date('Y-m-d'), 'monthly', '0.6');
outputUrl('return-policy', date('Y-m-d'), 'monthly', '0.6');
outputUrl('shipping-policy', date('Y-m-d'), 'monthly', '0.6');

// User Pages
outputUrl('login', date('Y-m-d'), 'monthly', '0.5');
outputUrl('forgot-password', date('Y-m-d'), 'monthly', '0.4');

// =====================================================
// CATEGORIES (High Priority)
// =====================================================
$categories_query = "SELECT slug, name, updated_at, created_at FROM categories WHERE status = 1 ORDER BY name ASC";
$categories_result = mysqli_query($conn, $categories_query);

if ($categories_result && mysqli_num_rows($categories_result) > 0) {
    while ($category = mysqli_fetch_assoc($categories_result)) {
        $category_url = 'category/' . htmlspecialchars($category['slug']);
        // Use updated_at if available, otherwise use created_at
        $lastmod = !empty($category['updated_at']) ? $category['updated_at'] : (!empty($category['created_at']) ? $category['created_at'] : date('Y-m-d'));
        outputUrl($category_url, $lastmod, 'weekly', '0.8');
    }
}

// =====================================================
// PRODUCTS (High Priority)
// =====================================================
$products_query = "SELECT slug, name, updated_at, created_at FROM products WHERE status = 1 ORDER BY created_at DESC";
$products_result = mysqli_query($conn, $products_query);

if ($products_result && mysqli_num_rows($products_result) > 0) {
    while ($product = mysqli_fetch_assoc($products_result)) {
        $product_url = 'product/' . htmlspecialchars($product['slug']);
        // Use updated_at if available, otherwise use created_at
        $lastmod = !empty($product['updated_at']) ? $product['updated_at'] : $product['created_at'];
        outputUrl($product_url, $lastmod, 'weekly', '0.9');
    }
}

// Close XML
echo '</urlset>';

// Close database connection
mysqli_close($conn);

// End output buffering and send output
ob_end_flush();
exit;
