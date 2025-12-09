<?php
/**
 * MySmartSCart - Products API
 * Returns JSON for AJAX requests (infinite scroll, filters, etc.)
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Include required files
require_once '../config.php';
require_once '../includes/products-helper.php';

// Get request parameters
$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        // Get products list with filters
        $options = [
            'page' => isset($_GET['page']) ? intval($_GET['page']) : 1,
            'per_page' => isset($_GET['per_page']) ? intval($_GET['per_page']) : 12,
            'category_slug' => $_GET['category'] ?? null,
            'search' => $_GET['q'] ?? null,
            'min_price' => isset($_GET['min_price']) ? floatval($_GET['min_price']) : null,
            'max_price' => isset($_GET['max_price']) ? floatval($_GET['max_price']) : null,
            'sort' => $_GET['sort'] ?? 'newest',
            'featured' => isset($_GET['featured']) ? intval($_GET['featured']) : null,
            'best_selling' => isset($_GET['best_selling']) ? intval($_GET['best_selling']) : null,
            'top_rated' => isset($_GET['top_rated']) ? intval($_GET['top_rated']) : null,
        ];
        
        $result = getProducts($options);
        
        // Add HTML for each product (for AJAX loading)
        $result['html'] = '';
        foreach ($result['products'] as $product) {
            $result['html'] .= getProductCardHTML($product);
        }
        
        echo json_encode([
            'success' => true,
            'data' => $result
        ]);
        break;
        
    case 'single':
        // Get single product
        $slug = $_GET['slug'] ?? '';
        if (empty($slug)) {
            echo json_encode(['success' => false, 'error' => 'Slug required']);
            exit;
        }
        
        $product = getProductBySlug($slug);
        if ($product) {
            echo json_encode(['success' => true, 'data' => $product]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Product not found']);
        }
        break;
        
    case 'search':
        // Quick search
        $q = $_GET['q'] ?? '';
        if (strlen($q) < 2) {
            echo json_encode(['success' => true, 'data' => []]);
            exit;
        }
        
        $result = getProducts([
            'search' => $q,
            'per_page' => 10,
            'page' => 1
        ]);
        
        echo json_encode([
            'success' => true,
            'data' => $result['products']
        ]);
        break;
        
    case 'categories':
        // Get categories
        $categories = getCategories(0, true);
        echo json_encode([
            'success' => true,
            'data' => $categories
        ]);
        break;
        
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

/**
 * Generate product card HTML for AJAX
 */
function getProductCardHTML($product) {
    // Use SEO-friendly URL
    if (function_exists('getProductUrl')) {
        $product_link = getProductUrl($product['slug']);
    } else {
        $product_link = "product.php?slug=" . htmlspecialchars($product['slug']);
    }
    $image = htmlspecialchars($product['thumbnail'] ?: $product['image']);
    $name = htmlspecialchars($product['name']);
    $price = number_format($product['price'], 2);
    $sale_price = $product['sale_price'] ? number_format($product['sale_price'], 2) : null;
    $discount = $product['discount'];
    $featured = $product['featured'] || $product['best_selling'] || $product['top_rated'];
    
    $html = '<div class="col-6 col-sm-4 product-item">';
    $html .= '<div class="product-default left-details">';
    $html .= '<figure>';
    $html .= '<a href="' . $product_link . '">';
    $html .= '<img src="assets/images/lazy.png" data-src="' . $image . '" alt="' . $name . '" class="lazy" width="300" height="300" loading="lazy">';
    $html .= '</a>';
    $html .= '<div class="label-group">';
    if ($featured) {
        $html .= '<span class="product-label label-hot">HOT</span>';
    }
    if ($discount > 0) {
        $html .= '<span class="product-label label-sale">-' . $discount . '%</span>';
    }
    $html .= '</div></figure>';
    $html .= '<div class="product-details">';
    $html .= '<h3 class="product-title"><a href="' . $product_link . '">' . $name . '</a></h3>';
    $html .= '<div class="price-box">';
    if ($sale_price) {
        $html .= '<span class="product-price">₹' . $sale_price . '</span>';
        $html .= '<span class="old-price">₹' . $price . '</span>';
    } else {
        $html .= '<span class="product-price">₹' . $price . '</span>';
    }
    $html .= '</div>';
    $html .= '<div class="product-action">';
    $html .= '<a href="' . $product_link . '" class="btn-icon btn-add-cart"><i class="icon-shopping-cart"></i><span>VIEW</span></a>';
    $html .= '</div></div></div></div>';
    
    return $html;
}
?>

