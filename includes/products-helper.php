<?php
/**
 * MySmartSCart - Optimized Product Helper Functions
 * For handling 10,000+ products efficiently
 */

require_once __DIR__ . '/cache.php';

/**
 * Get products with optimized queries (for 10,000+ products)
 * Uses caching and efficient pagination
 */
function getProducts($options = []) {
    global $conn;
    
    // Default options
    $defaults = [
        'category_id' => null,
        'category_slug' => null,
        'featured' => null,
        'best_selling' => null,
        'top_rated' => null,
        'search' => null,
        'min_price' => null,
        'max_price' => null,
        'sort' => 'newest',
        'page' => 1,
        'per_page' => 12,
        'ids' => null,  // Get specific product IDs
    ];
    
    $opts = array_merge($defaults, $options);
    
    // Build cache key
    $cache_key = 'products_' . md5(serialize($opts));
    
    // Try cache first (cache for 5 minutes)
    $cached = cache_get($cache_key);
    if ($cached !== null) {
        return $cached;
    }
    
    // Build WHERE clause
    $where = ['p.status = 1'];
    $joins = [];
    
    // Category filter
    if ($opts['category_id']) {
        $cat_id = intval($opts['category_id']);
        $joins[] = "INNER JOIN product_categories pc ON p.id = pc.product_id";
        $where[] = "pc.category_id = $cat_id";
    } elseif ($opts['category_slug']) {
        $cat_slug = mysqli_real_escape_string($conn, $opts['category_slug']);
        $joins[] = "INNER JOIN product_categories pc ON p.id = pc.product_id";
        $joins[] = "INNER JOIN categories cat ON pc.category_id = cat.id";
        $where[] = "cat.slug = '$cat_slug'";
    }
    
    // Feature filters
    if ($opts['featured'] !== null) {
        $where[] = "p.featured = " . intval($opts['featured']);
    }
    if ($opts['best_selling'] !== null) {
        $where[] = "p.best_selling = " . intval($opts['best_selling']);
    }
    if ($opts['top_rated'] !== null) {
        $where[] = "p.top_rated = " . intval($opts['top_rated']);
    }
    
    // Price range
    if ($opts['min_price'] !== null) {
        $where[] = "(COALESCE(p.sale_price, p.price)) >= " . floatval($opts['min_price']);
    }
    if ($opts['max_price'] !== null) {
        $where[] = "(COALESCE(p.sale_price, p.price)) <= " . floatval($opts['max_price']);
    }
    
    // Search
    if ($opts['search']) {
        $search = mysqli_real_escape_string($conn, $opts['search']);
        $where[] = "(p.name LIKE '%$search%' OR p.short_description LIKE '%$search%' OR p.sku LIKE '%$search%')";
    }
    
    // Specific IDs
    if ($opts['ids'] && is_array($opts['ids'])) {
        $ids = implode(',', array_map('intval', $opts['ids']));
        $where[] = "p.id IN ($ids)";
    }
    
    // Build ORDER BY
    $order = 'p.created_at DESC';
    switch ($opts['sort']) {
        case 'oldest':
            $order = 'p.created_at ASC';
            break;
        case 'price_low':
            $order = 'COALESCE(p.sale_price, p.price) ASC';
            break;
        case 'price_high':
            $order = 'COALESCE(p.sale_price, p.price) DESC';
            break;
        case 'name_asc':
            $order = 'p.name ASC';
            break;
        case 'name_desc':
            $order = 'p.name DESC';
            break;
        case 'popularity':
            $order = 'p.best_selling DESC, p.featured DESC, p.created_at DESC';
            break;
        default:
            $order = 'p.created_at DESC';
    }
    
    // Build query
    $join_sql = implode(' ', array_unique($joins));
    $where_sql = implode(' AND ', $where);
    
    // Get total count (optimized - only count IDs)
    $count_query = "SELECT COUNT(DISTINCT p.id) as total FROM products p $join_sql WHERE $where_sql";
    $count_result = mysqli_query($conn, $count_query);
    $total = mysqli_fetch_assoc($count_result)['total'];
    
    // Pagination
    $page = max(1, intval($opts['page']));
    $per_page = max(1, min(100, intval($opts['per_page']))); // Max 100 per page
    $offset = ($page - 1) * $per_page;
    $total_pages = ceil($total / $per_page);
    
    // Get products (only necessary columns)
    $products_query = "SELECT DISTINCT 
        p.id, p.name, p.slug, p.sku, p.short_description,
        p.price, p.sale_price, p.stock, p.stock_status,
        p.image, p.thumbnail, p.featured, p.best_selling, p.top_rated
        FROM products p 
        $join_sql 
        WHERE $where_sql 
        ORDER BY $order 
        LIMIT $per_page OFFSET $offset";
    
    $products_result = mysqli_query($conn, $products_query);
    $products = [];
    
    while ($row = mysqli_fetch_assoc($products_result)) {
        // Calculate discount
        $row['discount'] = 0;
        if ($row['sale_price'] && $row['sale_price'] < $row['price']) {
            $row['discount'] = round((($row['price'] - $row['sale_price']) / $row['price']) * 100);
        }
        $row['final_price'] = $row['sale_price'] ?: $row['price'];
        
        // Use thumbnail if available
        if (empty($row['thumbnail'])) {
            $row['thumbnail'] = $row['image'] ?: 'assets/images/products/placeholder.webp';
        }
        if (empty($row['image'])) {
            $row['image'] = 'assets/images/products/placeholder.webp';
        }
        
        $products[] = $row;
    }
    
    $result = [
        'products' => $products,
        'total' => $total,
        'page' => $page,
        'per_page' => $per_page,
        'total_pages' => $total_pages,
        'has_more' => $page < $total_pages
    ];
    
    // Cache result for 5 minutes
    cache_set($cache_key, $result, 300);
    
    return $result;
}

/**
 * Get categories with product counts (cached)
 */
function getCategories($parent_id = 0, $with_count = true) {
    global $conn;
    
    $cache_key = "categories_{$parent_id}_{$with_count}";
    $cached = cache_get($cache_key);
    if ($cached !== null) {
        return $cached;
    }
    
    if ($with_count) {
        $query = "SELECT c.*, COUNT(DISTINCT pc.product_id) as product_count
                  FROM categories c
                  LEFT JOIN product_categories pc ON c.id = pc.category_id
                  LEFT JOIN products p ON pc.product_id = p.id AND p.status = 1
                  WHERE c.status = 1 AND c.parent_id = $parent_id
                  GROUP BY c.id
                  HAVING product_count > 0
                  ORDER BY c.sort_order ASC, c.name ASC";
    } else {
        $query = "SELECT * FROM categories 
                  WHERE status = 1 AND parent_id = $parent_id 
                  ORDER BY sort_order ASC, name ASC";
    }
    
    $result = mysqli_query($conn, $query);
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    
    // Cache for 10 minutes
    cache_set($cache_key, $categories, 600);
    
    return $categories;
}

/**
 * Get featured/special products (cached)
 */
function getFeaturedProducts($type = 'featured', $limit = 8) {
    $options = [
        'per_page' => $limit,
        'page' => 1
    ];
    
    switch ($type) {
        case 'featured':
            $options['featured'] = 1;
            break;
        case 'best_selling':
            $options['best_selling'] = 1;
            break;
        case 'top_rated':
            $options['top_rated'] = 1;
            break;
        case 'newest':
            $options['sort'] = 'newest';
            break;
    }
    
    $result = getProducts($options);
    return $result['products'];
}

/**
 * Get single product by slug (cached)
 */
function getProductBySlug($slug) {
    global $conn;
    
    $cache_key = "product_" . md5($slug);
    $cached = cache_get($cache_key);
    if ($cached !== null) {
        return $cached;
    }
    
    $slug_escaped = mysqli_real_escape_string($conn, $slug);
    $query = "SELECT p.*, c.name as category_name, c.slug as category_slug
              FROM products p
              LEFT JOIN categories c ON p.category_id = c.id
              WHERE p.slug = '$slug_escaped' AND p.status = 1
              LIMIT 1";
    
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    
    if ($product) {
        // Get categories
        $cat_query = "SELECT c.id, c.name, c.slug FROM categories c
                      INNER JOIN product_categories pc ON c.id = pc.category_id
                      WHERE pc.product_id = {$product['id']}";
        $cat_result = mysqli_query($conn, $cat_query);
        $product['categories'] = [];
        while ($cat = mysqli_fetch_assoc($cat_result)) {
            $product['categories'][] = $cat;
        }
        
        // Parse gallery images
        $product['gallery'] = [];
        if (!empty($product['gallery_images'])) {
            $product['gallery'] = json_decode($product['gallery_images'], true) ?: [];
        }
        
        // Add main image to gallery
        if (!empty($product['image']) && !in_array($product['image'], $product['gallery'])) {
            array_unshift($product['gallery'], $product['image']);
        }
        
        // Cache for 10 minutes
        cache_set($cache_key, $product, 600);
    }
    
    return $product;
}

/**
 * Clear product cache (call after adding/editing products)
 */
function clearProductCache() {
    global $conn, $_cache;
    if (!isset($_cache)) {
        $_cache = new Cache($conn);
    }
    $_cache->clear();
}
?>

